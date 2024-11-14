<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // echo '<pre>';
    // print_r($_SESSION['user_id']); // This should display user information if logged in
    // echo '</pre>';
  } //else {
//     echo 'No user session found.';
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Wanderlust</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="index.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .nav-link {
            text-decoration: underline;
            color: #ffffff;
            font-size: 1.2rem;
            margin-right: 1rem;
            transition: transform 0.3s ease-in-out;
        }
        .navbar-dark .navbar-nav .nav-link {
            color: #ffffff;
        }
        .modal-content {
            padding: 20px;
        }
    </style>
</head>
<body>
   <!-- Navbar -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.html">Wanderlust</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#destinations">Destinations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#packages">Packages</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="blog_page/index1.php">Travel Blogs</a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#loginModal">Login</a>
                </li>
            <?php endif; ?>
        </ul>
        
    </div>
</nav>

<!-- Login/Signup Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Login Form -->
            <div id="loginForm">
                <h5 class="modal-title mb-4" id="loginModalLabel">Login</h5>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="loginEmail">Email:</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password:</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p class="mt-3">Don't have an account? <a href="#" id="showSignupForm">Sign up here</a></p>
            </div>

            <!-- Signup Form (Initially Hidden) -->
            <div id="signupForm" style="display: none;">
                <h5 class="modal-title mb-4">Sign Up</h5>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="signupEmail">Email:</label>
                        <input type="email" class="form-control" id="signupEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="signupPassword">Password:</label>
                        <input type="password" class="form-control" id="signupPassword" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
                <p class="mt-3">Already have an account? <a href="#" id="showLoginForm">Login here</a></p>
            </div>
        </div>
    </div>
</div>

        <!-- Content Sections -->
        <section class="info">
            <div class="container">
                <h1>Welcome to Wanderlust</h1>
                <p>Your adventure starts here. Explore the world with us.</p>
                <a href="#destinations" class="btn btn-primary mt-3">Discover More</a>
            </div>
        </section>
    <!-- Destinations Section -->
    <section id="destinations" class="container mt-5">
        <h2 class="text-center">Destinations</h2>
        <div class="row" id="destinations-container"></div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="container mt-5">
        <h2 class="text-center">Travel Packages</h2>
        <div class="row" id="packages-container"></div>
    </section>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 contact">
                    <h5>Contact Us</h5>
                    <p>Email: contact@wanderlust.com</p>
                    <p>Phone: +123 456 7890</p>
                </div>
                <div class="col-md-4 about">
                    <h5>About Us</h5>
                    <p>Wanderlust is dedicated to providing the best travel experiences. Explore our wide range of destinations and travel packages to make your next trip unforgettable.</p>
                </div>
                <div class="col-md-4 copyright">
                    <h5>Copyright</h5>
                    <p>&copy; 2024 Wanderlust. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // jQuery to toggle between login and signup forms
        $(document).ready(function() {
            $('#showSignupForm').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                $('#loginForm').hide(); // Hide login form
                $('#signupForm').show(); // Show signup form
            });

            $('#showLoginForm').click(function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                $('#signupForm').hide(); // Hide signup form
                $('#loginForm').show(); // Show login form
            });


            // Fetching and displaying destinations and packages from JSON
            $.getJSON('destinations.json', function(data) {
                var destinationsContainer = $('#destinations-container');
                var packagesContainer = $('#packages-container');

                data.destinations.forEach(function(destination) {
                    var card = `
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <div class="card destination-card">
                                <img src="${destination.homepageImage}" class="card-img-top img-fluid" alt="${destination.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${destination.name}</h5>
                                    <p class="card-text">${destination.description}</p>
                                    <a href="destination.html?name=${encodeURIComponent(destination.name)}" class="btn btn-primary">Explore</a>
                                </div>
                            </div>
                        </div>
                    `;
                    destinationsContainer.append(card);
                });

                data.packages.forEach(function(pkg) {
                    var packageCard = `
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <div class="card">
                                <img src="${pkg.image}" class="card-img-top img-fluid" alt="${pkg.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${pkg.name}</h5>
                                    <p class="card-text">${pkg.description}</p>
                                    <p class="card-price">${pkg.price}</p>
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#loginModal">Book Now</a>
                                </div>
                            </div>
                        </div>
                    `;
                    packagesContainer.append(packageCard);
                });

                // Scaling effect on hover for all cards
                $('.card').hover(
                    function() {
                        $(this).css({
                            transform: 'scale(1.05)',
                            transition: 'transform 0.3s ease-in-out'
                        });
                    },
                    function() {
                        $(this).css({
                            transform: 'scale(1)',
                            transition: 'transform 0.3s ease-in-out'
                        });
                    }
                );
            });
        });
    </script>
</body>
</html>
