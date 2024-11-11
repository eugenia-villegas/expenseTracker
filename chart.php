<?php
session_start();

$mysqli = require __DIR__ . "/db.php";

$user_id = $mysqli->real_escape_string($_SESSION["user_id"]);

$monthlyIncomes = sprintf("SELECT EXTRACT(YEAR FROM `date`) AS year, EXTRACT(MONTH FROM `date`) AS month, SUM(amount) AS monthlyIncomes 
                            FROM incomes  
                            WHERE user_id = '%s' 
                            AND `date` >=  DATE_FORMAT(CURDATE() - INTERVAL 5 MONTH, '%%Y-%%m-01')
                            GROUP BY EXTRACT(YEAR FROM `date`), EXTRACT(MONTH FROM `date`)
                            ORDER BY year DESC, month ASC", $user_id); 
                
$result = $mysqli->query($monthlyIncomes); 
  
$dataIncomes = array();
                
if ($result) {
    foreach($result as $row) {
        $dataIncomes[] = $row;
    }
    
} else {
    echo "<p>Error en la consulta: " . $mysqli->error . "</p>";
}

echo json_encode($dataIncomes);
?>