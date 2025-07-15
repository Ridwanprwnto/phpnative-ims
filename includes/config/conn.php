<?php
    // Simple .env loader function to parse .env file and set environment variables
    function loadEnv($path)
    {
        if (!file_exists($path)) {
            // throw new Exception(".env file not found at path: " . $path);
            $msg = encrypt(".env file not found at path: " . $path);
            header("location: error.php?alert=$msg");
            exit();
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // skip comments
            }
            // parse key=value lines
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!getenv($name)) { // prevent overwriting existing env vars
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    // Load .env variables
    $pathenv = '\.env';
    try {
        loadEnv(__DIR__ . $pathenv);
    } catch (Exception $e) {
        $msg = encrypt("Error loading .env file: " . $e->getMessage());
        header("location: error.php?alert=$msg");
        exit();
    }

    // Retrieve environment variables for DB connection
    $servername = getenv('DB_HOST');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $dbname = getenv('DB_NAME');

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        $msg = encrypt("connection-timeout");
        header("location: error.php?alert=$msg");
        exit();
    }
?>