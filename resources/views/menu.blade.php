<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Menu - PapaPriza</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* ORDER MODAL */
        .order-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.55);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .order-modal-content {
            position: relative;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            background-color: white;
            padding: 40px;
            border-radius: 22px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
        }

        .order-modal-content h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: #a83f68;
            text-align: center;
        }

        .selected-pizza {
            text-align: center;
            margin-bottom: 25px;
            color: #765761;
            font-size: 16px;
        }

        .selected-pizza strong {
            color: #d14d7c;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            border: none;
            background: none;
            font-size: 30px;
            color: #6b3f4c;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #d85b87;
        }

        .order-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #6b3f4c;
        }

        .order-form input,
        .order-form textarea {
            width: 100%;
            padding: 13px 15px;
            margin-bottom: 18px;
            border: 1px solid #e5bdcc;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
        }

        .order-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .order-form input:focus,
        .order-form textarea:focus {
            outline: none;
            border-color: #d85b87;
        }

        .order-form button[type="submit"] {
            width: 100%;
            border: none;
            cursor: pointer;
            padding: 14px 30px;
            background-color: #d85b87;
            color: white;
            border-radius: 30px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
        }

        .order-form button[type="submit"]:hover {
            background-color: #b9436d;
        }

        .map-button {
            display: inline-block;
            margin-top: -5px;
            margin-bottom: 18px;
            padding: 10px 18px;
            background-color: #f3d5df;
            color: #7d3f56;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .map-button:hover {
            background-color: #eab9c9;
        }

        @media (max-width: 600px) {
            .order-modal-content {
                padding: 30px 20px;
            }

            .order-modal-content h2 {
                font-size: 30px;
            }
        }
    </style>
</head>

<body>


<x-customer-nav />




    <!-- MENU HEADER -->

    <section class="title">
        <h1>Our Menu</h1>
        <p>Choose your favorite PapaPriza pizza.</p>
    </section>


    <!-- MENU -->

    <section class="menu">

        <!-- Creamy Spinach Pizza -->

        <div class="food">

            <div class="food-image">
                <img
                    src="{{ asset('images/creamy-spinach.jpg') }}"
                    alt="Creamy Spinach Pizza"
                >
            </div>

            <h2>Creamy Spinach Pizza</h2>

            <p>
                A delicious creamy pizza topped with fresh spinach and cheese.
            </p>

            <p class="price">₱299</p>

            <button
                class="button"
                type="button"
                onclick="openOrderModal('Creamy Spinach Pizza', '₱299')"
            >
                Order Now
            </button>

        </div>


        <!-- All Meat Pizza -->

        <div class="food">

            <div class="food-image">
                <img
                    src="{{ asset('images/all-meat.jpg') }}"
                    alt="All Meat Pizza"
                >
            </div>

            <h2>All Meat Pizza</h2>

            <p>
                A tasty pizza loaded with different kinds of savory meat.
            </p>

            <p class="price">₱349</p>

            <button
                class="button"
                type="button"
                onclick="openOrderModal('All Meat Pizza', '₱349')"
            >
                Order Now
            </button>

        </div>


        <!-- Garlic Shrimp -->

        <div class="food">

            <div class="food-image">
                <img
                    src="{{ asset('images/garlic-shrimp.jpg') }}"
                    alt="Garlic Shrimp Pizza"
                >
            </div>

            <h2>Garlic Shrimp</h2>

            <p>
                A flavorful pizza topped with garlic shrimp and melted cheese.
            </p>

            <p class="price">₱379</p>

            <button
                class="button"
                type="button"
                onclick="openOrderModal('Garlic Shrimp', '₱379')"
            >
                Order Now
            </button>

        </div>

    </section>


    <!-- ORDER MODAL -->

    <div class="order-modal" id="orderModal">

        <div class="order-modal-content">

            <button
                class="close-modal"
                type="button"
                onclick="closeOrderModal()"
            >
                &times;
            </button>

            <h2>Order Pizza</h2>

            <p class="selected-pizza">
                Selected Pizza:
                <strong id="selectedPizza">Pizza</strong>
                -
                <strong id="selectedPrice">₱0</strong>
            </p>


           
<form
    class="order-form"
    method="POST"
    action="{{ route('orders.store') }}"
    onsubmit="submitOrder(event)"
>
    @csrf
    <input type="hidden" name="pizza_name" id="pizzaNameInput">
    <input type="hidden" name="pizza_price" id="pizzaPriceInput">

                <label for="orderName">
                    Your Name
                </label>

                <input
                    type="text"
                    id="orderName"
                    name="name"
                    placeholder="Enter your name"
                    required
                >


                <label for="orderEmail">
                    Email Address
                </label>

                <input
                    type="email"
                    id="orderEmail"
                    name="email"
                    placeholder="Enter your email"
                    required
                >


                <label for="orderAddress">
                    Delivery Address
                </label>

                <input
                    type="text"
                    id="orderAddress"
                    name="address"
                    placeholder="Enter your delivery address"
                    required
                >


                <a
                    href="#"
                    id="mapLink"
                    target="_blank"
                    class="map-button"
                >
                    Open Address in Google Maps
                </a>


                <label for="orderMessage">
                    Your Message
                </label>

                <textarea
                    id="orderMessage"
                    name="message"
                    placeholder="Your message..."
                ></textarea>


                <button type="submit">
                    Send Order
                </button>

            </form>

        </div>

    </div>


    <!-- FOOTER -->

    <footer>
        <p>© 2026 PapaPriza. Pizza made with love.</p>
    </footer>


    <!-- JAVASCRIPT -->

    <script>

        // OPEN ORDER MODAL

        function openOrderModal(pizza, price) {
    document.getElementById('selectedPizza').textContent = pizza;
    document.getElementById('selectedPrice').textContent = price;

    document.getElementById('pizzaNameInput').value = pizza;
    document.getElementById('pizzaPriceInput').value = price;

    document.getElementById('orderMessage').value =
        'I would like to order ' + pizza + '.';

    document.getElementById('orderModal').style.display = 'flex';
}


        // CLOSE ORDER MODAL

        function closeOrderModal() {

            document.getElementById('orderModal').style.display = 'none';
        }


        // CLOSE MODAL WHEN CLICKING OUTSIDE

        window.addEventListener('click', function(event) {

            const modal = document.getElementById('orderModal');

            if (event.target === modal) {
                closeOrderModal();
            }

        });


        // GOOGLE MAPS ADDRESS

        const addressInput = document.getElementById('orderAddress');

        const mapLink = document.getElementById('mapLink');

        addressInput.addEventListener('input', function() {

            const address = encodeURIComponent(
                addressInput.value
            );

            if (addressInput.value.trim() !== '') {

                mapLink.href =
                    'https://www.google.com/maps/search/?api=1&query='
                    + address;

            } else {

                mapLink.href = '#';

            }

        });


        // DEMO SEND ORDER



let orderSubmitting = false;

async function submitOrder(event) {
    event.preventDefault();

    // Stop repeated submissions
    if (orderSubmitting) {
        return;
    }

    const form = event.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;

    orderSubmitting = true;
    submitButton.disabled = true;
    submitButton.textContent = 'Placing Order...';
    submitButton.style.opacity = '0.6';
    submitButton.style.cursor = 'not-allowed';

    try {
        // Read the CSRF token from the meta tag set in the page head (or fall back to the form's hidden field)
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value
            || '';

        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        // If the server redirected (guest was not logged in) or returned 401/403 directly
        if (response.status === 401 || response.status === 403 || response.redirected) {
            alert('Please log in to place an order.');
            window.location.href = '{{ route("customer.login") }}';
            return;
        }

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Please check your order details.');
        }

        // Show success
        alert(data.message || 'Your order has been saved successfully!');
        form.reset();
        closeOrderModal();

    } catch (error) {
        alert(error.message || 'Something went wrong. Please try again.');

    } finally {
        // Re-enable submit so another order can be placed
        orderSubmitting = false;
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        submitButton.style.opacity = '1';
        submitButton.style.cursor = 'pointer';
    }
}
    </script>

</body>
</html>