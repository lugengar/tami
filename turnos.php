<?php
// dashboard.php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ./index.php");
    exit;
}

require "codigophp/calendario.php";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="estiloscss/animaciones.css">
    <link rel="stylesheet" href="estiloscss/styles.css">
    <link rel="stylesheet" href="estiloscss/imagenes.css">
    <link rel="stylesheet" href="estiloscss/calendario.css">
</head>
<body>
    <div id="pagina2">
        <div id="header">
            <a href="inicio.php" class="logo imagen"></a>
            <button class="usuario imagen"></button>
        </div>
        <div id="contenido3">
            <div class="contenido2">
                <div class="con3" id="inicio">
                    <div class="nav">
                        <a href="?year=<?php echo $prevYear; ?>&month=<?php echo $prevMonth; ?>">&laquo; Anterior</a>
                        <div><?php echo date('F Y', strtotime("$year-$month-01")); ?></div>
                        <a href="?year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>">Siguiente &raquo;</a>
                    </div>
                    <div class="calendario">
                        <div class="calendario-header">Lun</div>
                        <div class="calendario-header">Mar</div>
                        <div class="calendario-header">Mié</div>
                        <div class="calendario-header">Jue</div>
                        <div class="calendario-header">Vie</div>
                        <div class="calendario-header">Sáb</div>
                        <div class="calendario-header">Dom</div>
                        <?php
                        // Generar días del mes
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $firstDayOfWeek = date('N', strtotime($firstDayOfMonth));

                        // Imprimir días en blanco para la primera semana
                        for ($i = 1; $i < $firstDayOfWeek; $i++) {
                            echo "<div class='dia'></div>";
                        }

                        for ($day = 1; $day <= $daysInMonth; $day++) {
                            $fecha = "$year-". str_pad($month, 2, "0", STR_PAD_LEFT)."-" . str_pad($day, 2, "0", STR_PAD_LEFT);
                            $texto = 0;
                            $turnos = isset($turnosPorDia[$fecha]) ? $turnosPorDia[$fecha] : 0;
                            if($turnos != 0){
                                $texto = 1;
                            }else{
                                $texto = 0;
                            }
                            if($turnos > 99){
                                $turnos = "+99";
                            }
                            echo "<a <a href='infoturno.php?fecha=$fecha' class='dia' data-turnos='$texto'>
                                    $day
                                    <div class='turnos'>$turnos</div>
                                </a> ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer">
            <a href="notificaciones.php" class="campana imagen izquierda">Notificaciones</a>
            <a href="inicio.php" class="flecha imagen centro">Volver</a>
            <a href="chatbot.php" class="chatbot imagen derecha">Chatbot</a>
        </div>
    </div>
    </div>
    <style>
      
    </style>
</body>
</html>

<script src="codigojs/sombra.js"></script>
