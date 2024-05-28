<?php

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare a SQL statement to retrieve user based on email and password
    $sql = "SELECT * FROM `user` WHERE `email` = ? AND `password` = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if a user with the provided email and password exists
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Start session
                session_start();
                
                // Set session variables
                $_SESSION["loggedin"] = true;
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                // User authentication failed, redirect back to the login page with an error message
                header("Location: login.html?error=1");
                exit;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {
    // If someone tries to access this script directly, redirect them to the homepage
    header("Location: login.html");
    exit;
}
?>
