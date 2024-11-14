<?php
include 'db_connection.php'; // Include your database connection file

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect if not logged in
    exit();
}

// Check if the post ID is set in the POST request
if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id']; // Cast to int for safety

    // Validate if post ID exists in the database (optional but helpful)
    $check_post_stmt = $conn->prepare("SELECT id FROM posts WHERE id = ?");
    $check_post_stmt->bind_param("i", $post_id);
    $check_post_stmt->execute();
    $check_post_stmt->store_result();

    if ($check_post_stmt->num_rows > 0) {
        // Post exists, proceed with deletion

        // Delete all comments associated with this post
        $delete_comments_stmt = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
        $delete_comments_stmt->bind_param("i", $post_id);
        $delete_comments_stmt->execute();
        $delete_comments_stmt->close();

        // Now, delete the post itself
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id); // Bind parameters
        if ($stmt->execute()) {
            // Successfully deleted, redirect back
            header("Location: ../blog_page/index1.php?msg=Post deleted successfully.");
            exit(); 
        } else {
            // Handle error
            echo "Error deleting post: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Post ID does not exist
        echo "Post not found.";
    }

    $check_post_stmt->close();
} else {
    // Handle the case when post_id is not provided or is empty
    echo "Invalid post ID.";
}

// Close the connection
$conn->close();
?>
