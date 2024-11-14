<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['like'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['user_id']; // Assuming you have stored user_id in the session

        // Check if the user has already liked or disliked this post
        $check_reaction_sql = "SELECT reaction FROM post_reactions WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($check_reaction_sql);
        $stmt->bind_param("ii", $post_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_reaction = $result->fetch_assoc();

        if ($current_reaction) {
            // If user already liked, do nothing
            if ($current_reaction['reaction'] === 'like') {
                $stmt->close();
                header("Location: blog_page\index1.php"); // Redirect back to the index page
                exit();
            } elseif ($current_reaction['reaction'] === 'dislike') {
                // If user disliked, update to like
                $update_reaction_sql = "UPDATE post_reactions SET reaction = 'like' WHERE post_id = ? AND user_id = ?";
                $stmt = $conn->prepare($update_reaction_sql);
                $stmt->bind_param("ii", $post_id, $user_id);
                $stmt->execute();
            }
        } else {
            // Insert new reaction
            $insert_reaction_sql = "INSERT INTO post_reactions (post_id, user_id, reaction) VALUES (?, ?, 'like')";
            $stmt = $conn->prepare($insert_reaction_sql);
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
        }

        // Update likes count in the posts table
        $update_likes_sql = "UPDATE posts SET likes = (SELECT COUNT(*) FROM post_reactions WHERE post_id = ? AND reaction = 'like') WHERE id = ?";
        $stmt = $conn->prepare($update_likes_sql);
        $stmt->bind_param("ii", $post_id, $post_id);
        $stmt->execute();

        $stmt->close();
        header("Location: blog_page\index1.php"); // Redirect back to the index page
        exit();
    }
}

$conn->close();
?>