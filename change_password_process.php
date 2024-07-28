<?php
    if(isset($_GET['code'])) {
        $code = $_GET['code'];

        $conn = new mySqli('localhost', 'root', '', 'rms_db');
        if($conn->connect_error) {
            die('Could not connect to the database');
        }

        $verifyQuery = $conn->query("SELECT * FROM users WHERE code = '$code'");

        if($verifyQuery->num_rows == 0) {
            header("Location: index.php");
            exit();
        }

        if(isset($_POST['change'])) {
            // $email = $_POST['email'];
            $new_password = $_POST['new_password'];

            $changeQuery = $conn->query("UPDATE users SET password=md5('$new_password') WHERE code = '$code'");

            if($changeQuery) {
                header("Location: success.html");
            }
        }
        $conn->close();
    }
    else {
        header("Location: index.php");
        exit();
    }
?>
