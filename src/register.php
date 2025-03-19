<?php
session_start();
if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == true) {
    header("location: index.php");
    exit;
}
define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

$username = $password = "";
$username_err = $password_err = $register_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $hash_password = md5($password);

        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $username, $hash_password);
            

            if ($stmt->execute()) {
                header("location: login.php");
            } else {
                $register_err = "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
        <h1>Register to the Forum</h1>
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="topics.php">Topics</a></li>
                <?php
                if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                    echo '<li><a href="login.php">Login</a></li><li><a href="register.php">Register</a></li>';
                }
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    echo '<li><a href="user.php">Profile</a></li><li><a href="logout.php">Logout</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="hfiller">
            <!-- Empty div to push content beneath the header -->
        </div>
        <section>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-input">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <span><?php echo $username_err; ?></span>
                </div>
                <div class="form-input">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span><?php echo $password_err; ?></span>
                </div>
                <div class="form-input">
                    <button type="submit">Register</button>
                </div>
                <span><?php echo $register_err; ?></span>
            </form>
        </section>
        <div class="ffiller">
            <!-- Empty div to push footer to the bottom -->
        </div>
    </main>
</body>
</html>
