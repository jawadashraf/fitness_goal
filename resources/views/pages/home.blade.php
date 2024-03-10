<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME')}} - Transform Your Life Today</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .hero-section {
            background-image: url({{asset('images/fitness-background.png')}});
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 150px 0;
        }
        .features-section, .testimonial-section {
            padding: 60px 0;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">{{ env('APP_NAME')}}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
                @if(auth()->check())
                    <a class="nav-link btn btn-primary text-white" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="nav-link btn btn-outline-primary text-white" href="{{ route('auth.signin') }}">Login</a>
                @endif
            </li>
        </ul>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <h1 class="display-4">Transform Your Life Today</h1>
    <p class="lead">Join the Fitness Guru community and achieve your health and fitness goals.</p>
    <a href="#" class="btn btn-primary btn-lg">Join Now</a>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Personalized Workouts</h3>
                <p>Custom workouts designed to meet your specific goals and fitness level.</p>
            </div>
            <div class="col-md-4">
                <h3>Nutrition Plans</h3>
                <p>Healthy eating plans tailored to support your fitness journey.</p>
            </div>
            <div class="col-md-4">
                <h3>Community Support</h3>
                <p>Join a supportive community that motivates and inspires each other.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="testimonial-section bg-light">
    <div class="container">
        <h2 class="text-center">What Our Members Say</h2>
        <div class="row">
            <div class="col-md-6">
                <blockquote class="blockquote">
                    <p class="mb-0">Joining Fitness Guru was the best decision of my life. I've never felt healthier and more alive!</p>
                    <footer class="blockquote-footer">Jane Doe</footer>
                </blockquote>
            </div>
            <div class="col-md-6">
                <blockquote class="blockquote">
                    <p class="mb-0">The community and trainers are fantastic. I achieved my fitness goals thanks to Fitness Guru.</p>
                    <footer class="blockquote-footer">John Smith</footer>
                </blockquote>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
