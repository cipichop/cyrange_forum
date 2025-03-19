<?php
session_start();

define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the discussion id from the URL if set
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Get the discussion details based on the id
    $sql = "SELECT id, title, content, user_id, topic, created_at, file_path FROM discussions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // If no discussion found, redirect to index.php
        header("Location: index.php");
        exit();
    }

    $discussion = $result->fetch_assoc();
} else {
    // If no id is set, redirect to index.php
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $discussion['title']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to the Forum</h1>
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
            <h2><?php echo $discussion['title']; ?></h2>
            <?php
            // Get the user details based on the user_id
            $user_id = $discussion['user_id'];
            $user_sql = "SELECT username FROM users WHERE id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user = $user_result->fetch_assoc();
            ?>
            <p><strong>Posted by:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Topic:</strong> <?php echo "<span class='topic-tag'>" . $discussion['topic'] . "</span>"; ?></p>
            <p><strong>Posted on:</strong> <?php echo $discussion['created_at']; ?></p>
            <p><?php echo nl2br($discussion['content']); ?></p>
            <?php
            if ($discussion['file_path']) {
                echo "<h3>Attachments:</h3>";
                // echo "<li><a href='" . $discussion['file_path'] . "' download>" . "</a></li>";
                echo "<img src='" . $discussion['file_path'] . "' alt='Attachment' style='max-height: 500px;'>";
            }
            ?>
        </section>
        <div class="ffiller">
            <!-- Empty div to push footer to the bottom -->
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Forum. All rights reserved.</p>
    </footer>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
