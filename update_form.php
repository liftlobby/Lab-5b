<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include 'Database.php';
include 'User.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    // Retrieve the matric value from the GET request
    $matric = $_GET['matric'];

    // Create an instance of the Database class and get the connection
    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);
    $userDetails = $user->getUser($matric);

    $db->close();

    // Check if user details were fetched successfully
    if ($userDetails) {
        // Display the update form with the fetched user data
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="styles.css">
            <title>Update User</title>
        </head>

        <body>
            <form action="update.php" method="post">
                <input type="hidden" name="matric" value="<?php echo $userDetails['matric']; ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $userDetails['name']; ?>" required><br>
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="">Please select</option>
                    <option value="lecturer" <?php if ($userDetails['role'] == 'lecturer') echo "selected"; ?>>Lecturer</option>
                    <option value="student" <?php if ($userDetails['role'] == 'student') echo "selected"; ?>>Student</option>
                </select><br>
                <input type="submit" value="Update">
            </form>
        </body>

        </html>
        <?php
    } else {
        echo 'User not found.';
    }
} else {
    echo 'Matric parameter is missing.';
}
?>