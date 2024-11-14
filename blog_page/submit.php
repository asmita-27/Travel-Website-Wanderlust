<?php
include '../blog_page/db_connection.php'; // Include your database connection file

// Initialize message variables
$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $content = $_POST['content'];

    // Handle file upload
    $targetDir = "../blog_page/blog_images/"; // Directory where images will be saved
    $imageName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $imageName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File is not an image.";
        $messageClass = "alert alert-danger";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        $message = "Sorry, your file is too large.";
        $messageClass = "alert alert-danger";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $messageClass = "alert alert-danger";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
        $messageClass = "alert alert-danger";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $message = "The file " . htmlspecialchars($imageName) . " has been uploaded.";

            // Insert post data into database
            $sql = "INSERT INTO posts (title, author, content, image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $title, $author, $content, $targetFilePath);

            if ($stmt->execute()) {
                $message = "Blog post submitted successfully!";
                $messageClass = "alert alert-success"; // Success message
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
                $messageClass = "alert alert-danger"; // Error message
            }

            $stmt->close();
        } else {
            $message = "Sorry, there was an error uploading your file.";
            $messageClass = "alert alert-danger"; // Error message
        }
    }
}

// Close the database connection only if it's valid
if (isset($conn) && $conn instanceof mysqli) {
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Blog</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Custom Styles */
        .blog-form-container {
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .form-heading {
            color: #343a40;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem;
        }

        .form-control-file {
            padding: 0.4rem;
        }

        .submit-btn {
            background-color: #007bff;
            border-color: #007bff;
            padding: 0.75rem 1.25rem;
            font-size: 1.1rem;
            width: 100%;
            border-radius: 0.5rem;
        }

        .submit-btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Blog Form Container -->
                <div class="blog-form-container">
                    <h1 class="form-heading">Submit Your Blog</h1>

                    <!-- Display Message -->
                    <?php if ($message): ?>
                        <div class="<?php echo $messageClass; ?>" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Blog Submission Form -->
                    <form id="blogForm" action="../blog_page/submit.php" method="POST" enctype="multipart/form-data" class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter blog title" required>
                        </div>
                        <div class="col-md-6">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control" id="author" name="author" placeholder="Enter your name" required>
                        </div>
                        <div class="col-12">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="6" placeholder="Write your blog content here..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn submit-btn"><a href="blog_page\index1.php"></a>Submit Blog</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
