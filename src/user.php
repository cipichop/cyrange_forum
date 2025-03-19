<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

$sql = "SELECT id, title, created_at, topic FROM discussions WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <?php
        $sql_user = "SELECT username FROM users WHERE id = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $user_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $username = $result_user->fetch_assoc()['username'];
        $stmt_user->close();
        ?>
        <h1><?php echo $username; ?>'s Profile</h1>
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="topics.php">Topics</a></li>
                <li><a href="user.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="hfiller">
            <!-- Empty div to push content beneath the header -->
        </div>
        <section>
            <h2>Your Posts</h2>
            <ul class="discussions">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li class='discussions'>" . $row['created_at'] . " - " . "<a href='discussion.php?id=" . $row['id'] . "'>" . $row['title'] . "</a>" . "<span class='topic-tag'>" . $row['topic'] . "</span>" . "<br></li>";
                    }
                } else {
                    echo "<li>No posts found</li>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </ul>
        </section>
        <div class="ffiller">
            <!-- Empty div to push footer to the bottom -->
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Forum. All rights reserved.</p>
    </footer>
</body>
</html>
