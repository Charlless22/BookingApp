<?php
// Start PHP session (important to track user)
session_start();

// Connect to the database
$servername = "localhost";
$username = "root"; // your database username
$password = "";     // your database password
$dbname = "bookit"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = ""; // to store the error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL to find user
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user found
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Correct password → redirect to another page
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                header("Location: dashboardView.php");
                exit();
            } else {
                // Password wrong
                $error_message = "Wrong password. Please try again.";
            }
        } else {
            // Email not found
            $error_message = "No account found with that email.";
        }

        $stmt->close();
    } else {
        $error_message = "Database error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>BookIt</title>
        <link rel="stylesheet" href="../css/template.css">
        <link rel="stylesheet" href="../css/login.css">
        <link rel="icon" type="image/png" href="../assets/logo.png">
    </head>
    <body>
        <div class="container">
            <div class="top-page"></div>
            <div class="middle-page">
                <div class="id-container">
                    <form class="form-container" method="post">
                        <div class="logo-container">
                            <img src="../assets/logo.png" width="100px" height="100px">
                        </div>
                        <div class="credentials-container">
                            <div class="input-container">
                                <label>Email</label>
                                <input name="email" type="email" placeholder="email@email" required/>
                            </div>
                            <div class="input-container">
                                <label>Password</label>
                                <input name="password" type="password" placeholder="password" required/>
                            </div>
                        </div>
                        <?php if (!empty($error_message)): ?>
                            <div class="notice">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        <hr style="width:90%; border-top: 1px solid; border-color: #000000;">
                        <div class="links-container">
                            <a href="signUpView.php" style="color: #000">Sign up</a>
                            <a href="forgetPasswordView.php" style="color: #000">Forget Password</a>
                        </div>
                        <div class="button-container">
                            <button class="button">Login</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bottom-page"></div>
        </div>
    </body>
    <footer>
    </footer>
</html>
