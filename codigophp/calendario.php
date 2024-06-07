<?php
include "conexionbs.php";



$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

// Calcular el primer y último día del mes
$firstDayOfMonth = date('Y-m-01', strtotime("$year-$month-01"));
$lastDayOfMonth = date('Y-m-t', strtotime("$year-$month-01"));

// Obtener turnos del mes actual

echo $year,"   ",$month,"   ",$firstDayOfMonth,"   ",$lastDayOfMonth;
$sql = "SELECT fecha, COUNT(*) as turnos 
        FROM turnos 
        WHERE fecha BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth' 
        AND vendedor_fk = ".$_SESSION['id_usuario']."
        GROUP BY fecha";
$result = $conn->query($sql);

$turnosPorDia = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $turnosPorDia[$row['fecha']] = $row['turnos'];
        
    }
}else{
    
}

$conn->close();

// Navegación entre meses
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear++;
}
?>
