<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/img/logo.png" type="image/icon type">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Document</title>
</head>
<body class="index_body">
    
    <?php if(isset($_SESSION["user_id"])): ?>
    <aside>
        <div class="div_logo">
            <img class="img_logo" src="/img/logo.png" alt="hybris logo" >
            <p>Hybris</p>
        </div>
        <nav>
            <a href="/index.php"><img class="menu_logo" src="/img/barras.svg"> Inicio</a>
            <a href="/registers.php"><img class="menu_logo" src="/img/cash.svg"> Registros</a>
            <a href="/logout.php"><img class="menu_logo" src="/img/logout.svg"> Salir</a>
        </nav>
    </aside>

    <main class="main"> 
        <header>
            <h1>Estadisticas</h1>
        </header>
        <section class="bars">
            <h3>Registro de Ingresos Mensuales</h3>
            <div id="incomesChartContainer" style="width: 600px; height: 600px">
                <canvas id="incomesChart"></canvas>
            </div>
        </section>
        <article class="register_index">
            <h3>Ultimo registros:</h3>
            <table class="table_index">
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
                $queryLastRegisters = sprintf("SELECT description, CONCAT(' $ ', amount) as amount, `date`
                                                FROM incomes WHERE user_id = '%s' 
                                                UNION
                                                SELECT description,  CONCAT('-$ ', amount) as amount, `date` 
                                                FROM outcomes WHERE user_id = '%s' 
                                                ORDER BY `date` DESC
                                                LIMIT 6", 
                                                $user_id, $user_id); 
                
                // FETCHING DATA FROM DATABASE 
                $result = $mysqli->query($queryLastRegisters); 
                
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                foreach ($rows as $row) {
                    echo "<tr class='register_rows'>\n";
                    echo "<td>\n";
                    echo htmlspecialchars($row["date"])."\n";
                    echo "</td>";
                    echo "<td>\n";
                    echo htmlspecialchars($row["description"])."\n";
                    echo "</td>";
                    echo "<td>\n";
                    echo htmlspecialchars($row["amount"])."\n";
                    echo "</td>";
                    echo "</tr>\n";
                }
                ?>
            </table>

        </article>
    </main>

    <script>
        fetch('/chart.php')
            .then(response => response.json())
            .then(data => {
                const monthlyIncomes = data.map(item => item.monthlyIncomes);
                const month = data.map(item => item.month);
                
                const ctx = document.getElementById('incomesChart').getContext('2d')
                const incomesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: month,
                    datasets: [{
                        label: 'Ingresos Mensuales',
                        data: monthlyIncomes,
                        backgroundColor: 'rgb(220, 255, 217)',
                        borderColor: 'rgb(63, 204, 50)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: Math.max(...monthlyIncomes) * 1.2,
                            ticks: {
                                stepSize: Math.max(...monthlyIncomes) / 8, // Ajusta el espacio entre los valores
                            }
                        }
                    }
                }
            })
        });
    </script>     
    

    <?php else: 
        header("Location: login.php");
        exit;
    ?>
    <?php endif; ?>   
</body>
</html>