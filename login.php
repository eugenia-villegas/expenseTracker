<?php
$isInvalid = false;
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/db.php";

    
    $sql = sprintf("SELECT * FROM user WHERE email = '%s'",
                    $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();

    if($user) {
        if(password_verify($_POST["password"], $user["password_hash"])) {
            session_start();

            $_SESSION["user_id"] = $user["user_id"];
            header("Location: index.php");
            exit;
        }$isInvalid = true;
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Hybris - Your Tracker</title>
</head>
<body class="login_body">
    <form class="login" method="post">
        <section>
            <h3>Login</h3>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" maxlength="40" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" minlength="6" maxlength="16" required>
            </div>
            <?php if($isInvalid): ?>
                <em>Login Invalido</em>
            <?php endIf; ?> 
            <div>
                <button>Entrar</button>
            </div>
            <div>
                <a href="signup.html">No estoy registrado!</a>
            </div>
        </section>
        <section>
            <img class="logo_login" src="/img/logo.png">
            <h3>Biendenido a Hydris</h3>
            <h4>El lugar para tus finanzas</h4>
        </section>
    </form>
</body>
</html>