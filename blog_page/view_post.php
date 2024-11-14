<?php
// Include your database connection file
include 'db_connection.php';

if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Fetch the blog post data
    $postQuery = "SELECT * FROM posts WHERE id = ?";
    $postStmt = $conn->prepare($postQuery);
    $postStmt->bind_param('i', $postId);
    $postStmt->execute();
    $postResult = $postStmt->get_result();
    $post = $postResult->fetch_assoc();

    // Fetch the comments related to this post
    $commentQuery = "SELECT * FROM comments WHERE post_id = ? ORDER BY date DESC";
    $commentStmt = $conn->prepare($commentQuery);
    $commentStmt->bind_param('i', $postId);
    $commentStmt->execute();
    $commentsResult = $commentStmt->get_result();

    // Handle Likes/Dislikes update
    if (isset($_POST['like'])) {
        $likeQuery = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
        $likeStmt = $conn->prepare($likeQuery);
        $likeStmt->bind_param('i', $postId);
        $likeStmt->execute();
    }

    if (isset($_POST['dislike'])) {
        $dislikeQuery = "UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?";
        $dislikeStmt = $conn->prepare($dislikeQuery);
        $dislikeStmt->bind_param('i', $postId);
        $dislikeStmt->execute();
    }

    // Handle new comment submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
        $commentText = $_POST['comment_text'];
        $userId = 1; // Placeholder for the actual user ID, assuming user is logged in.

        $addCommentQuery = "INSERT INTO comments (post_id, user_id, comment, date) VALUES (?, ?, ?, NOW())";
        $addCommentStmt = $conn->prepare($addCommentQuery);
        $addCommentStmt->bind_param('iis', $postId, $userId, $commentText);
        $addCommentStmt->execute();
        header("Location: view_post.php?id=" . $postId); // Refresh the page to show the new comment
        exit();
    }
} else {
    echo "Post not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
}
        .blog-image{
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .blog-image img {
            height: 350px;
            width: 600px;
            object-fit: cover;
        }
        .post-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
        .post-content {
            font-size: 1.15rem;
            line-height: 1.75;
            margin-bottom: 30px;
        }
        .img-fluid {
            max-height: 400px;
            width: 100%;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .like-dislike-btns {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 30px;
        }
        .like-dislike-btns form {
            margin-right: 15px;
        }
        .comments-section {
            margin-top: 50px;
        }
        .comment-item {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .add-comment {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
        .navbar {
            padding: 15px;
        }
        .navbar-nav .nav-link {
            margin-left: 15px;
        }
        .navbar-brand{
            font-weight: bold;
            font-size: 1.5rem;
            padding: 0;
        }
        .navbar{
            color: #f8f9fa;
        }
        .nav-link {
            text-decoration: underline;
            transition: background-color 0.3s ease;
        }
        .nav-link:hover {
            background-color: chocolate;
            color: #f8f9fa;
        } 
        .footer {
          background-color: #3b4148;
          padding: 40px 0;
          color: #f8f9fa;
        }
        .footer h5 {
          font-weight: 700;
        }
        .footer .contact, .footer .about, .footer .copyright {
          margin-bottom: 10px;
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
    
    <!-- Main Content -->
    <section class="container my-5">
        <h2><?php echo $post['title']; ?></h2>
        <p><small class="text-muted">By <?php echo $post['author']; ?> on <?php echo date("F j, Y", strtotime($post['date'])); ?></small></p>
        <div class="blog-image">
            <img src="../blog_images/<?php echo $post['image']; ?>" class="img-fluid mb-4" alt="Blog image">
        </div>
        <p><?php echo nl2br($post['content']); ?></p>

        <!-- Likes/Dislikes Form -->
        <div class="like-dislike-btns">
            <form action="view_post.php?id=<?php echo $postId; ?>" method="POST">
                <button type="submit" name="like" class="btn btn-outline-success">Like (<?php echo $post['likes']; ?>)</button>
            </form>
            <form action="view_post.php?id=<?php echo $postId; ?>" method="POST">
                <button type="submit" name="dislike" class="btn btn-outline-danger">Dislike (<?php echo $post['dislikes']; ?>)</button>
            </form>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h3>Comments</h3>
            <?php if ($commentsResult->num_rows > 0): ?>
                <?php while ($comment = $commentsResult->fetch_assoc()): ?>
                    <div class="comment-item">
                        <strong>User <?php echo $comment['user_id']; ?>:</strong>
                        <p><?php echo $comment['comment']; ?></p>
                        <small class="text-muted"><?php echo $comment['date']; ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>

        <!-- Add New Comment Form -->
        <div class="add-comment">
            <h4>Add a Comment</h4>
            <form action="view_post.php?id=<?php echo $postId; ?>" method="POST">
                <div class="form-group mb-3">
                    <textarea name="comment_text" class="form-control" rows="4" placeholder="Write your comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>
        </div>
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
