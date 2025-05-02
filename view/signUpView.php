<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookIt";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connected
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                header("Location: loginView.php");
                exit();
            } else {
                echo "Erreur lors de l'insertion: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erreur de préparation SQL: " . $conn->error;
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>BookIt</title>
    <link rel="stylesheet" href="../css/signup.css">
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
                        <label>Username</label>
                        <input name="username" type="text" placeholder="username" required/>
                    </div>
                    <div class="input-container">
                        <label>Email</label>
                        <input name="email" type="email" placeholder="email@email" required/>
                    </div>
                    <div class="input-container">
                        <label>Password</label>
                        <input name="password" type="password" placeholder="password" required/>
                    </div>
                </div>
                <hr style="width:90%; border-top: 1px solid; border-color: #000000;">
                <div class="button-container">
                    <button class="button">Register</button>
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
