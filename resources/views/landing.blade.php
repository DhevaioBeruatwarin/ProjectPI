<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja Artsphere</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
</head>

<body>
    @include('components.back-button')
    <div id="bunga-container"></div>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">JOGJA ARTSPHERE</div>
        <nav class="nav-links">
            <a href="{{ url('/register') }}" class="btn signup-btn">Sign Up</a>
            <a href="{{ '/login' }}" class="btn login-btn">Login</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('{{ asset('assets/bg.jpg') }}');">
        <div class="hero-content">
            <div class="text-left">
                <h1>Discover unique artworks<br>support local artists and<br>be part of Jogja’s creative movement.</h1>
                <p>Jogja Artsphere is a digital space for artists and art lovers in Yogyakarta. Here, you can sell or buy traditional and modern artworks, join creative events, and connect with the local art community. Support Jogja artists, find inspiring works, and become part of a dynamic and creative art movement.</p>
            </div>
           <div class="image-grid">
{{-- <img src="{{ asset('assets/art1.jpg') }}" alt="Art 1">
    <img src="{{ asset('assets/art2.jpg') }}" alt="Art 2">
    <img src="{{ asset('assets/art3.jpg') }}" alt="Art 3">
    <img src="{{ asset('assets/art4.jpg') }}" alt="Art 4">
</div> --}}
    <img src="{{ asset('assets/art1.avif') }}" alt="Art 1">
    <img src="{{ asset('assets/art2.avif') }}" alt="Art 2">
    <img src="{{ asset('assets/art3.jpg') }}" alt="Art 3">
    <img src="{{ asset('assets/art4.avif') }}" alt="Art 4">
</div>
</section>

<div class="bunga">
        <img src="{{ asset('assets/adajpg-removebg-preview.png') }}"  alt="bunga">
    </div>


</body>
</html>
