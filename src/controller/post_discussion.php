<?php
define('DB_SERVER', getenv('DB_SERVER'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $id = $_SESSION['id'];
    $content = $_POST['content'];
    $topic = $_POST['topic'];
    $file = $_FILES['file'];

    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $filePath = null;
    if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
        $allowedExtension = ['.png', '.jpg', '.jpeg'];
        $fileName = basename($file['name']);
        
        $isAllowed = false;
        foreach ($allowedExtension as $allowed) {
            if (strpos($fileName, $allowed) !== false) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            echo "<script>alert('File extension is not supported.'); window.location.href='../index.php';</script>";
            exit();
        }
        
        if ($file['size'] > 2097152) {
            echo "<script>alert('File size exceeds 2MB limit.'); window.location.href='../index.php';</script>";
            exit();
        }
        
        $fileName = basename($file['name']);
        $filePath = '../uploads/' . $fileName;
        move_uploaded_file($file['tmp_name'], $filePath);
    }

    $filePath = str_replace('../', '', $filePath);

    $stmt = $conn->prepare("INSERT INTO discussions (title, content, topic, file_path, created_at, user_id) VALUES (?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssssi", $title, $content, $topic, $filePath, $id);

    if ($stmt->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
