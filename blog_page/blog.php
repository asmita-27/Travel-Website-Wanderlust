<?php

// Fetch the blog ID from the URL
$blogId = isset($_GET['id']) ? $_GET['id'] : null;

// Read and decode blog data from JSON file
$blogData = file_get_contents("blog.json");
$blogs = json_decode($blogData, true);

// Find the blog post with the specified ID
$selectedBlog = null;
foreach ($blogs as $blog) {
    if ($blog['id'] == $blogId) {
        $selectedBlog = $blog;
        break;
    }
}

if (!$selectedBlog) {
    echo "Blog not found!";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $selectedBlog['title']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../blog_page/style.css">
    <style>
        .blog-image{
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 10px;
        }
        .blog-image img{
            height: 350px;
            width: 600px;
        }
        
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white">
  <a class="navbar-brand" href="../index.php">Wanderlust</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active"><a class="nav-link" href="index1.php">Home</a></li>
      <li class="nav-item active"><a class="nav-link" href="submit.php">Submit Blog</a></li>
    </ul>
  </div>
</nav>

<!-- Blog Post Section -->
<section class="container my-5">
    <h2><?php echo $selectedBlog['title']; ?></h2>
    <p><small class="text-muted">By <?php echo $selectedBlog['author']; ?> on <?php echo $selectedBlog['date']; ?></small></p>
  <div class="blog-image"><img src="../blog_images/<?php echo $selectedBlog['image']; ?>" class=" img-fluid mb-4" alt="Blog image">
</div>
    <p><?php echo $selectedBlog['content']; ?></p>
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


<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
