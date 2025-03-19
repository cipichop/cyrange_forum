<?php
session_start();
require_once 'init/db_init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleForm() {
            var form = document.getElementById('postForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
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
            <?php
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                echo '<button class="form-input" onclick="toggleForm()">Post New Discussion</button>';
            } else {
                echo '<p>Please <a href="login.php">login</a> to post a new discussion.</p>';
            }
            ?>
            <form id="postForm" action="controller/post_discussion.php" method="post" enctype="multipart/form-data" style="display: none;">
                <div class="form-input">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-input">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" rows="4" required></textarea>
                </div>
                <div class="form-input">
                    <label for="file">Upload File:</label>
                    <input type="file" id="file" name="file">
                </div>
                <div class="form-input">
                    <label for="topic">Topic:</label>
                    <input type="text" id="topic" name="topic" required>
                </div>
                <button type="submit" required>Post</button>
            </form>
            <h2>Latest Discussions</h2>
            <ul class="discussions">
                <?php
                // Include the configuration file
                define('DB_SERVER', getenv('DB_SERVER'));
                define('DB_USERNAME', getenv('DB_USERNAME'));
                define('DB_PASSWORD', getenv('DB_PASSWORD'));
                define('DB_NAME', getenv('DB_NAME'));

                // Example of fetching latest discussions from a database
                // Using the database connection details from the config file
                $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $sql = isset($_GET['show_all']) ? "SELECT id, title, created_at, topic FROM discussions ORDER BY created_at DESC" : "SELECT id, title, created_at, topic FROM discussions ORDER BY created_at DESC LIMIT 5";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo " ";
                        echo "<li class=" . "discussions" . ">" . $row['created_at'] . " - " . "<a href='discussion.php?id=" . $row['id'] . "'>" . $row['title'] . "</a>" . "<span class='topic-tag'>" . $row['topic'] . "</span>" . "<br></li>";
                    }
                    if (!isset($_GET['show_all'])) {
                        echo "<li><a href='index.php?show_all=true'>Show all discussions</a></li>";
                    }
                } else {
                    echo "<li>No discussions found</li>";
                }
                
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