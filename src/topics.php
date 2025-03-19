<?php
session_start();
define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$topic = isset($_GET['topic']) ? $_GET['topic'] : null;

// show all discussions under the chosen topic
if ($topic) {
    $sql = "SELECT id, title, content, user_id, topic, created_at FROM discussions WHERE topic = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $topic);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT topic, COUNT(*) as count FROM discussions GROUP BY topic";
    $result = $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $topic ? "Discussions on " . $topic : "Topics"; ?></title>
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
            <h2><?php echo $topic ? "Discussions on " . $topic : "Topics"; ?></h2>
            <?php if ($result->num_rows > 0): ?>
                <ul class="discussions">
                    <?php if ($topic): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <li>
                                <?php
                                $user_id = $row['user_id'];
                                $user_sql = "SELECT username FROM users WHERE id = ?";
                                $user_stmt = $conn->prepare($user_sql);
                                $user_stmt->bind_param("i", $user_id);
                                $user_stmt->execute();
                                $user_result = $user_stmt->get_result();
                                $user = $user_result->fetch_assoc();
                                ?>
                                <?php echo "Post from <strong>" . $user['username'] . "</strong></br>" . $row['created_at'] . "</br></br>"; ?>
                                <a href="discussion.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
                                <p><?php echo $row['content']; ?></p>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <li><a href="topics.php?topic=<?php echo $row['topic']; ?>"><?php echo $row['topic']; ?></a> (<?php echo $row['count']; ?> discussions)</li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
            <?php else: ?>
                <p><?php echo $topic ? "No discussions found on this topic." : "No topics found."; ?></p>
            <?php endif; ?>
        </section>
        <div class="ffiller">
            <!-- Empty div to push footer to the bottom -->
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Forum. All rights reserved.</p>
    </footer>

    <?php
    if ($topic) {
        $stmt->close();
    }
    $conn->close();
    ?>
</body>
</html>