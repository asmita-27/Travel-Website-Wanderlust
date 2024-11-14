<?php
// Include the database connection file
include 'login_db.php';  // Assumes login_db.php handles DB connection

// Start the session at the beginning of the file
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Sanitize user inputs to avoid SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if it's a signup request (confirm_password field is set)
    if (isset($_POST['confirm_password'])) {
        $confirm_password = trim($_POST['confirm_password']);

        // Check if the passwords match
        if ($password === $confirm_password) {
            // Hash the password
            //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Check if the user already exists
            $query = "SELECT * FROM users WHERE email='$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "Email already registered!";
            } else {
                // Insert new user into the database
                $insert_query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

                if (mysqli_query($conn, $insert_query)) {
                    echo "Signup successful!";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Passwords do not match!";
        }
    } else {
        // This is a login request
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if ($password== $user['password']) {
                // Login successful, start session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Redirect to the homepage or any other page
                header("Location: index.php");
                exit();
            } else {
                echo "Invalid email or password!";
            }
        } else {
            echo "Invalid email or password!";
        }
    }
}
?>
