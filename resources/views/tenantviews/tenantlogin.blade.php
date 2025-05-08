<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Tenant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://images.vecteezy.com/photos/1/170675/luxury-house.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        .login-section {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.4); /* Dark overlay to improve text readability */
        }

        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            background-color: #ffffff; /* White card background for contrast */
            border-radius: 15px; /* Rounded corners for card */
        }

        .custom-img-height {
            height: 100%;
            object-fit: cover;
            border-radius: 15px 0 0 15px; /* Rounded corners for image */
        }

        .logo {
            margin-bottom: 20px;
            text-align: center; /* Center align logo */
        }

        .main-logo {
            width: 300px;
            height: auto; /* Maintain aspect ratio */
            border-radius: 50%; /* Circular logo */
        }

        .card-body {
            background-color: #fff; /* White background for card body */
            color: #333; /* Dark text color for readability */
            padding: 30px;
        }

        .form-control {
            border: 1px solid #bbb; /* Slightly darker border color */
            border-radius: 8px; /* Rounded corners for input fields */
            background-color: #f4f4f4; /* Light gray background for inputs */
            padding: 15px;
            font-size: 16px;
            color: #333; /* Dark text color */
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ddd; /* Light border color on focus */
        }

        .form-label {
            font-size: 14px;
            color: #666; /* Medium gray for labels */
        }

        .btn-dark {
            background-color: #666; /* Dark gray button */
            border-color: #666;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff; /* White text on button */
            border-radius: 8px; /* Rounded corners for button */
        }

        .btn-dark:hover {
            background-color: #777; /* Slightly lighter on hover */
            border-color: #777;
        }

        .small {
            font-size: 12px;
        }

        .text-muted {
            color: #aaa; /* Light gray for muted text */
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <section class="login-section">
        <div class="container py-5">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="Tenant/resource/login-photo.png" alt="login form" class="img-fluid custom-img-height" />
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('tenantlogin_submit') }}">
                                        @csrf

                                        <div class="logo">
                                            <img src="Tenant/resource/logo-main.png" alt="logo" class="main-logo" />
                                        </div>

                                        <h5 class="small mb-3 text-center">Sign into your account</h5>

                                        <div class="form-outline mb-3">
                                            <input type="email" name="email" id="email" class="form-control form-control-lg" />
                                            <label class="form-label" for="email">Email address</label>
                                        </div>

                                        <div class="form-outline mb-3">
                                            <input type="password" name="password" id="password" class="form-control form-control-lg" />
                                            <label class="form-label" for="password">Password</label>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <button class="btn btn-dark btn-md btn-block" type="submit">Login</button>
                                        </div>

                                        <div class="mb-3 small text-center"> <!-- Center align forgot password link -->
                                            <a href="#" class="text-muted">Forgot password?</a>
                                        </div>
                                        <p class="mb-2 small text-center" style="color: #666;">Don't have an account?
                                            <a href="#!" style="color: #666;">Register here</a></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
