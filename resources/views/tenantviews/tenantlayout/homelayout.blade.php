<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unidorm</title>

    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto+Slab:wght@400;100;300;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Core theme CSS -->
    <link rel="stylesheet" href="Tenant/css/home.css">


    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<style>

</style>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#page-top">
                <img src="Tenant/resource/logo-main.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tenantlogin') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Masthead -->
    <header class="masthead text-center">
        <div class="container">
            <div class="masthead-subheading">Welcome to Unidorm!</div>
            <div class="masthead-heading text-uppercase">{{ $tenantName }}</div>
            <a class="btn btn-dark btn-xl text-uppercase" href="#home">Explore Our Dormitory Rooms That Fit Your Budget!</a>
        </div>
    </header>

    <div class="container-fluid px-1 pt-3"> <!-- Added px-0 for horizontal padding, pt-4 for top padding -->
        @yield('content')
    </div>


    <!-- Scripts -->
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="js/scripts.js"></script>
</body>

</html>