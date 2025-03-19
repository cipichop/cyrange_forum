<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Login to the Forum</h1>
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
            <form action="controller/authenticate.php" method="post">
                <div class="form-input">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-input">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
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
