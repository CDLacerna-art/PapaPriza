<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact - PapaPriza</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('Images/flamingo_pizza.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<x-customer-nav />


    <section class="contact">

        <h1>Order / Contact Us</h1>

        {{-- Success message --}}
        @if (session('success'))
            <p style="background:#e8f7eb;color:#246b35;padding:14px 18px;border-radius:12px;margin-bottom:20px;">
                {{ session('success') }}
            </p>
        @endif

        <form method="POST" action="{{ route('contact.submit') }}">
            @csrf

            <label for="name">Your Name</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your name"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <p style="color:#b42318;margin:-12px 0 12px;font-size:14px;">{{ $message }}</p>
            @enderror

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <p style="color:#b42318;margin:-12px 0 12px;font-size:14px;">{{ $message }}</p>
            @enderror

            <label for="address">Delivery Address</label>
            <input
                type="text"
                id="address"
                name="address"
                placeholder="Enter your delivery address"
                value="{{ old('address') }}"
            >

            <a
                href="#"
                id="mapLink"
                target="_blank"
                class="button"
                style="margin-bottom: 20px;"
            >
                Open Address in Google Maps
            </a>

            <label for="message">Your Message</label>

            <textarea
                id="message"
                name="message"
                placeholder="Your message..."
                required
            >{{ old('message', request('pizza') ? 'I would like to order ' . request('pizza') . '.' : '') }}</textarea>
            @error('message')
                <p style="color:#b42318;margin:-12px 0 12px;font-size:14px;">{{ $message }}</p>
            @enderror

            <button type="submit">
                Send Message
            </button>

        </form>

    </section>

    <footer>
        <p>© 2026 PapaPriza. Pizza made with love.</p>
    </footer>


    <script>
        const addressInput = document.getElementById('address');
        const mapLink = document.getElementById('mapLink');

        addressInput.addEventListener('input', function () {
            const address = encodeURIComponent(addressInput.value);

            if (addressInput.value.trim() !== '') {
                mapLink.href =
                    'https://www.google.com/maps/search/?api=1&query=' + address;
            } else {
                mapLink.href = '#';
            }
        });
    </script>

</body>
</html>