<?php
// db_connection.php
include '../blog_page/db_connection.php';
// Search functionality
$searchTerm = '';
$sql = "SELECT * FROM posts"; 

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = strtolower(trim($_GET['search']));
    $sql .= " WHERE LOWER(title) LIKE ? OR LOWER(author) LIKE ?";
}

$stmt = $conn->prepare($sql);

// Prepare to bind parameters if search term exists
if (!empty($searchTerm)) {
    $likeSearchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $likeSearchTerm, $likeSearchTerm);
}

// Execute the query to get the total blogs
$stmt->execute();
$result = $stmt->get_result();
$blogs = $result->fetch_all(MYSQLI_ASSOC);

// Pagination settings
$blogsPerPage = 3;  // Number of blogs per page
$totalBlogs = count($blogs);  // Total number of blogs after search filtering
$totalPages = ceil($totalBlogs / $blogsPerPage);  // Total number of pages
// Ensure current page is within bounds
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max(1, min($totalPages, $currentPage));

// Calculate the starting blog index for the current page
$startIndex = ($currentPage - 1) * $blogsPerPage;

// Adjust SQL for pagination
$sqlPaginated = $sql . " LIMIT ?, ?";
$stmtPaginated = $conn->prepare($sqlPaginated);

// Bind the parameters for the LIMIT clause
if (!empty($searchTerm)) {
    // Need to bind the like parameters
    $stmtPaginated->bind_param("ssii", $likeSearchTerm, $likeSearchTerm, $startIndex, $blogsPerPage);
} else {
    // If there's no search term, bind only the LIMIT parameters
    $stmtPaginated->bind_param("ii", $startIndex, $blogsPerPage);
}

// Execute the paginated query
$stmtPaginated->execute();
$paginatedBlogs = $stmtPaginated->get_result()->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../blog_page/style.css">
    <style>
    .navbar-dark .navbar-nav .nav-link {
     color: #fff; 
    }
    </style>
</head>
<body>
      <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.html">Wanderlust</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#blogs">Blogs</a></li>
                    <li class="nav-item"><a class="nav-link" href="submit.php">Submit Blog</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="container text-center text-white">
        <img src="../blog_page/blog_images/hero.jpg" alt="hero image">
        <h1>Welcome to Wanderlust Diaries</h1>
        <p>Discover and share amazing travel experiences</p>
      </div>
    </section>

    <div class=" container my-4 w-50 mt-5">
      <form method="GET" action="index.php">
        <div class="input-group">
          <input type="text" name="search" class="form-control shadow-none" placeholder="Search blog by title or author..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </div>
      </form>
    </div>

    <div class="container my-5">
        <h1 class="text-center mb-4">Latest Blog Posts</h1>

        <!-- Section to display blog posts -->
        <div id="blog-posts" class="row">
            <!-- Blog posts will be loaded here dynamically -->
        </div>
    </div>

    
    <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-5">
      <!-- Previous page link -->
      <?php if ($currentPage > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
      <?php endif; ?>
      
      <!-- Pagination links -->
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?php if ($i == $currentPage) echo 'active'; ?>">
          <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
      
      <!-- Next page link -->
      <?php if ($currentPage < $totalPages): ?>
        <li class="page-item">
        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

</section>



    
    <div class="text-center mt-5">
            <a href="submit.php" class="btn btn-success btn-lg">Submit Your Own Blog</a>
        </div>
    </div>


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


   

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>$(document).ready(function() {
    // Fetch blog posts from get_posts.php via AJAX
    $.ajax({
        url: '../blog_page/get_posts.php',
        type: 'GET',
        success: function(data) {
            // Inject the blog posts into the DOM
            $('#blog-posts').html(data);
        },
        error: function() {
            $('#blog-posts').html('<div class="alert alert-danger">Failed to load blog posts.</div>');
        }
    });
});
</script>
</body>
</html>
