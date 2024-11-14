<?php
include 'db_connection.php'; // Include your database connection file

// Retrieve blog posts from the database
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    echo "<div class='row'>"; // Start the Bootstrap row

    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo "<div class='col-md-4 mb-4'>"; // 3 cards per row, with bottom margin
        echo "<div class='card'>"; // Card component
        echo "<img src='" . htmlspecialchars($row['image']) . "' class='card-img-top' alt='Blog Image' />";
        echo "<div class='card-body d-flex flex-column'>"; // Flex column to align items vertically
        echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
        echo "<p class='card-text'>" . htmlspecialchars(substr($row['content'], 0, 100)) . "...</p>"; // Preview first 100 chars

        // Convert the date format to DD-MM-YYYY
        $formattedDate = date("d-m-Y", strtotime($row['date']));

        // Display "By XYZ (Author) on DD-MM-YYYY"
        echo "<p class='card-text'><small class='text-muted'>By " . htmlspecialchars($row['author']) . " on " . $formattedDate . "</small></p>";

        // Display like/dislike buttons with counts
        echo "<div class='like-dislike-buttons d-flex justify-content-between'>"; // Flex to keep buttons in one row
        echo "<button class='btn btn-success like-button'>Likes (" . htmlspecialchars($row['likes']) . ")</button>";
        echo "<button class='btn btn-danger dislike-button'>Dislikes (" . htmlspecialchars($row['dislikes']) . ")</button>";
        echo "</div>";

        // Buttons for "Read More" and "Delete"
        echo "<div class='mt-auto'>"; // Ensure buttons are at the bottom of the card
        echo "<a href='view_post.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-block mb-2'>Read More</a>"; // Full width button for smaller screens
        echo "<form method='POST' action='../blog_page/delete_post.php' style='display:inline;'>";
        echo "<input type='hidden' name='post_id' value='" . htmlspecialchars($row['id']) . "'>";
        echo "<button type='submit' class='btn btn-danger btn-block'>Delete</button>"; // Full width button for smaller screens
        echo "</form>";
        echo "</div>"; // End mt-auto for flexible layout

        echo "</div>"; // End card body
        echo "</div>"; // End card
        echo "</div>"; // End column
    }

    echo "</div>"; // End Bootstrap row
} else {
    echo "<p>No blog posts found.</p>";
}

// Close the connection
$conn->close();
?>


<script>
    $(document).ready(function() {
        $('.card').hover(
            function() {
                // hover in
                $(this).css({
                    'transform': 'scale(1.05)',
                    'transition': 'transform 0.3s ease'
                });
            },
            function() {
                // hover out
                $(this).css({
                    'transform': 'scale(1)',
                    'transition': 'transform 0.3s ease'
                });
            }
        );
    });
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Posts</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
   /* Style for Like and Dislike buttons */
.like-dislike-buttons {
    margin-bottom: 10px; /* Add spacing between the buttons and the next content */
    margin-top: auto; /* Push buttons to the bottom of the card */
}

.like-button, .dislike-button {
    flex: 1; /* Ensure buttons take equal space */
    text-align: center; /* Center text in each button */
    margin-right: 10px; /* Add spacing between the buttons */
}

.like-button {
    border: 1px solid green; /* Green border for the Like button */
    background-color: transparent; /* Transparent background by default */
    color: green; /* Green text color */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover */
}

.like-button:hover {
    background-color: green; /* Fill with green on hover */
    color: white; /* Change text color to white on hover */
}

.dislike-button {
    border: 1px solid red; /* Red border for the Dislike button */
    background-color: transparent; /* Transparent background by default */
    color: red; /* Red text color */
    transition: background-color 0.3s ease, color 0.3s ease; /* Smooth transition for hover */
}

.dislike-button:hover {
    background-color: red; /* Fill with red on hover */
    color: white; /* Change text color to white on hover */
}

/* Ensure buttons are responsive and full-width on smaller screens */
.btn-block {
    width: 100%; /* Full width for small screens */
}

.card {
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.card-img-top {
    height: 200px; /* Set a fixed height to ensure consistent size */
    object-fit: cover; /* Ensure the image covers the entire space */
}

/* Hover effect */
.card:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}


    </style>
</head>
<body>

</body>
</html>
