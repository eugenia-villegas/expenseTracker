<?php

    if($_POST["password"] !== $_POST["password_confirmation"]) {
        die("Passwords don't match");
    }

    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $mysqli = require __DIR__ . "/db.php";

    // $sqlCheckEmail = sprintf("SELECT * FROM user WHERE email = '%s'",
    //                 $mysqli->real_escape_string($_POST["email"]));

    $sql = "INSERT INTO user (name, email, password_hash) VALUES (?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    //to catch errors in querys
    if(!$stmt->prepare($sql)) {
        die("SQL ERROR: " . $mysqli->error);
    }
    
    $stmt->bind_param("sss",
                        $_POST["name"],
                        $_POST["email"],
                        $password_hash);

    if($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }


?>