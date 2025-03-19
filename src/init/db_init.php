<?php
    define('DB_SERVER', getenv('DB_SERVER'));
    define('DB_USERNAME', getenv('DB_USERNAME'));
    define('DB_PASSWORD', getenv('DB_PASSWORD'));

    $flag_file = '/var/www/html/db_initialized.flag';

    if (!file_exists($flag_file)) {
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $sql_file = file_get_contents('init/db_init.sql');
        if ($conn->multi_query($sql_file) !== TRUE) {
            echo "Error executing SQL file: " . $conn->error;
            $conn->close();
            exit();
        }
        header('Refresh: 1');
    
        file_put_contents($flag_file, 'Database initialized.');
    
        $conn->close();
    }
?>