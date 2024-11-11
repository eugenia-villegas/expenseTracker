<?php
session_start();

if($_POST) {
    $mysqli = require __DIR__ . "/db.php";
    
    $id = $_SESSION["user_id"]; //usuario de la sesion

    if($_POST["newRegister"] == "income") {
        $sqlIncome = "INSERT INTO incomes (user_id, amount, description, `date`) VALUES (?, ?, ?, ?)";
        
        $stmt = $mysqli->stmt_init();
            
        //para ver los errores de querys
        if(!$stmt->prepare($sqlIncome)) {
            die("SQL ERROR: " . $mysqli->error);
        }

        $stmt->bind_param("ssss",
                            $id,
                            $_POST["amount"],
                            $_POST["description"],
                            $_POST["date"]);

        if($stmt->execute()) {
            header("Location: registers.php");
            exit;
        } else {
            die($mysqli->error . " " . $mysqli->errno);
        }
    } else {

        $sqlOutcome = "INSERT INTO outcomes (user_id, amount, description, `date`) VALUES (?, ?, ?, ?)";
        
        $stmt = $mysqli->stmt_init();
        
        //para ver los errores de querys
        if(!$stmt->prepare($sqlOutcome)) {
            die("SQL ERROR: " . $mysqli->error);
        }

        $stmt->bind_param("ssss",
                            $id,
                            $_POST["amount"],
                            $_POST["description"],
                            $_POST["date"]);

        if($stmt->execute()) {
            header("Location: registers.php");
            exit;
        } else {
            die($mysqli->error . " " . $mysqli->errno);
        }
    }    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logo.png" type="image/icon type">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body class="index_body">
    
    <?php if(isset($_SESSION["user_id"])): ?>
    <aside>
        <div class="div_logo">
            <img class="img_logo" src="/img/logo.png" alt="hybris logo" >
            <p>Hybris</p>
        </div>
        <!-- <h1>Registros</h1> -->
        <nav>
            <a href="/index.php"><img class="menu_logo" src="/img/barras.svg"> Inicio</a>
            <a href="/registers.php"><img class="menu_logo" src="/img/cash.svg"> Registros</a>
            <a href="/logout.php"><img class="menu_logo" src="/img/logout.svg"> Salir</a>
        </nav>
    </aside>

    <main class="main"> 
        <header>
            <h1>Registros</h1>
        </header>
        <section class="register">
            <h3>Nuevo registro</h3>

            <form  method="post">
                <div>   
                    <label for="income">Ingreso</label>
                    <input type="radio" name="newRegister" value="income" id="income" checked>
                    <label for="outflow">Gasto</label>
                    <input type="radio" name="newRegister" value="outflow" id="outflow">
                </div>
                <div>
                    <label for="amount">Cantidad ($)</label>
                    <input type="number" name="amount" id="amount" required>
                </div>
                <div>
                    <label for="description">Descripcion</label>
                    <input name="description" id="description" required></input>
                </div>
                <div>
                    <label for="date">Fecha</label>
                    <input type="date" name="date" id="date" required>
                </div>
                <div>
                    <button type="submit">Guardar</button>
                </div>
            </form>
            
        </section>
        <article class="article_incomes">
            <h3>Ingresos registrados:</h3>
            <table class="table_incomes">
                <thead>
                    <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Monto</th>
                    </tr>
                </thead>
                <?php
                $mysqli = require __DIR__ . "/db.php";

                $user_id = $mysqli->real_escape_string($_SESSION["user_id"]);
                // SQL QUERY
                $queryIncome = sprintf("SELECT description, amount, `date` FROM incomes WHERE user_id = '%s'  ORDER BY `date` DESC", $user_id); 
                
                // FETCHING DATA FROM DATABASE 
                $result = $mysqli->query($queryIncome); 
                
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($rows as $row) {
                
                    echo "<tr class='income_rows'>\n";
                    echo "<td>\n";
                    echo htmlspecialchars($row["date"])."\n";
                    echo "</td>";
                    echo "<td>\n";
                    echo htmlspecialchars($row["description"])."\n";
                    echo "</td>";
                    echo "<td> $\n";
                    echo htmlspecialchars($row["amount"])."\n";
                    echo "</td>";
                    echo "</tr>\n";
                }
                ?>
            </table>

        </article>
        <article class="article_outcomes">
            <h3>Egresos registrados:</h3>
            <table class="table_outcomes">
                <thead>
                    <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Monto</th>
                    </tr>
                </thead>
                <?php
                $mysqli = require __DIR__ . "/db.php";
                // SQL QUERY 
                $queryOutcome = sprintf("SELECT description, amount, `date` FROM outcomes  WHERE user_id = '%s' ORDER BY `date` DESC", $user_id); 
                
                $result = $mysqli->query($queryOutcome); 
                
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($rows as $row) {
                
                    echo "<tr class='outcome_rows'>\n";
                    echo "<td>\n";
                    echo htmlspecialchars($row["date"])."\n";
                    echo "</td>";
                    echo "<td>\n";
                    echo htmlspecialchars($row["description"])."\n";
                    echo "</td>";
                    echo "<td> -$\n";
                    echo htmlspecialchars($row["amount"])."\n";
                    echo "</td>";
                    echo "</tr>\n";
                }
                ?>
            </table>
        </article>
    </main>
    <?php else: 
        header("Location: login.php");
        exit;
    ?>
    <?php endif; ?>   
</body>
</html>