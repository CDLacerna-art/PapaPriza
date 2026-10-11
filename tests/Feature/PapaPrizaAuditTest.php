<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PapaPrizaAuditTest extends TestCase
{
    use RefreshDatabase;

    protected string $adminEmail = 'admin@papapriza.local';

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.admin_email' => $this->adminEmail]);
    }

    /**
     * Create an administrator user.
     */
    protected function createAdmin(): User
    {
        return User::create([
            'name' => 'System Administrator',
            'email' => $this->adminEmail,
            'password' => Hash::make('admin12345'),
        ]);
    }

    /**
     * Create a standard customer user.
     */
    protected function createCustomer(string $email = 'customer@example.com'): User
    {
        return User::create([
            'name' => 'Customer Juan',
            'email' => $email,
            'password' => Hash::make('password123'),
        ]);
    }

    /**
     * Test 1: Guest is redirected from /admin/orders and /admin/users to admin.login.
     */
    public function test_guest_is_redirected_from_admin_orders_and_admin_users(): void
    {
        $responseOrders = $this->get('/admin/orders');
        $responseOrders->assertRedirect(route('admin.login'));

        $responseUsers = $this->get('/admin/users');
        $responseUsers->assertRedirect(route('admin.login'));

        $responseLogs = $this->get('/admin/logs');
        $responseLogs->assertRedirect(route('admin.login'));
    }

    /**
     * Test 2: Logged-in customer gets 403 on /admin/orders, /admin/logs, and /admin/users.
     */
    public function test_logged_in_customer_gets_403_on_admin_pages(): void
    {
        $customer = $this->createCustomer();

        $responseOrders = $this->actingAs($customer)->get('/admin/orders');
        $responseOrders->assertStatus(403);

        $responseLogs = $this->actingAs($customer)->get('/admin/logs');
        $responseLogs->assertStatus(403);

        $responseUsers = $this->actingAs($customer)->get('/admin/users');
        $responseUsers->assertStatus(403);
    }

    /**
     * Test 3: Admin gets 200 on /admin/orders, /admin/logs, and /admin/users.
     */
    public function test_admin_gets_200_on_admin_pages(): void
    {
        $admin = $this->createAdmin();

        $responseOrders = $this->actingAs($admin)->get('/admin/orders');
        $responseOrders->assertStatus(200);

        $responseLogs = $this->actingAs($admin)->get('/admin/logs');
        $responseLogs->assertStatus(200);

        $responseUsers = $this->actingAs($admin)->get('/admin/users');
        $responseUsers->assertStatus(200);
    }

    /**
     * Test 4: Guest cannot POST /orders (gets redirected to customer.login). Customer can place order.
     */
    public function test_guest_cannot_post_orders_and_customer_can(): void
    {
        $orderData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'address' => '123 Baker Street',
            'pizza_name' => 'All Meat Pizza',
            'message' => 'Please include extra napkins.',
        ];

        // Guest attempt
        $guestResponse = $this->post('/orders', $orderData);
        $guestResponse->assertRedirect(route('customer.login'));
        $this->assertDatabaseMissing('orders', ['email' => 'john@example.com']);

        // Authenticated customer attempt
        $customer = $this->createCustomer('john@example.com');
        $customerResponse = $this->actingAs($customer)->postJson('/orders', $orderData);
        $customerResponse->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'user_id' => $customer->id,
            'pizza_name' => 'All Meat Pizza',
            'customer_name' => 'John Doe',
        ]);
    }

    /**
     * Test 5: Admin can create, update, and delete a user, and each action creates an activity log row.
     */
    public function test_admin_can_crud_user_and_actions_are_logged(): void
    {
        $admin = $this->createAdmin();

        // 1. Create User
        $createResponse = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);
        $createResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'maria@example.com']);

        $user = User::where('email', 'maria@example.com')->first();
        $this->assertNotNull($user);

        // Verify creation log
        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Users',
            'action' => 'created',
            'description' => "Admin created user account {$user->name} ({$user->email}).",
        ]);

        // 2. Update User
        $updateResponse = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Maria Clara Santos',
            'email' => 'maria.clara@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);
        $updateResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Maria Clara Santos',
            'email' => 'maria.clara@example.com',
        ]);

        // Verify update log
        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Users',
            'action' => 'updated',
            'description' => "Admin updated user account Maria Clara Santos (maria.clara@example.com).",
        ]);

        // 3. Delete User
        $deleteResponse = $this->actingAs($admin)->delete("/admin/users/{$user->id}");
        $deleteResponse->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // Verify delete log
        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Users',
            'action' => 'deleted',
            'description' => "Admin deleted user account Maria Clara Santos (maria.clara@example.com).",
        ]);
    }

    /**
     * Test 6: Admin cannot delete their own account.
     */
    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');

        // Admin still exists in database
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /**
     * Test 7: Customer cannot register with the admin email.
     */
    public function test_customer_cannot_register_with_admin_email(): void
    {
        $response = $this->post('/customer/register', [
            'name' => 'Imposter Admin',
            'email' => $this->adminEmail,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['name' => 'Imposter Admin']);
    }
}
