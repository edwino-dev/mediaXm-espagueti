<?php
// estadisticas
// abre su propia conexion aunque ya este abierta en otro lado
// duplica logica de tamano de listar.php y buscar.php

include("conexion.php"); // tercera conexion si se llama junto con otros

header("Content-Type: application/json");

$r   = mysqli_query($con, "SELECT COUNT(*) AS total, SUM(CASE WHEN tipo='audio' THEN 1 ELSE 0 END) AS audios, SUM(CASE WHEN tipo='video' THEN 1 ELSE 0 END) AS videos, SUM(CASE WHEN tipo='imagen' THEN 1 ELSE 0 END) AS imagenes, COALESCE(SUM(tamano),0) AS bytes FROM archivos");
$row = mysqli_fetch_assoc($r);

// CODIGO DUPLICADO por tercera vez - mismo calculo de tamano que listar y buscar
$b = $row["bytes"];
if($b < 1024){
    $legible = $b . " B";
} else if($b < 1048576){
    $legible = round($b/1024, 1) . " KB";
} else if($b < 1073741824){
    $legible = round($b/1048576, 1) . " MB";
} else {
    $legible = round($b/1073741824, 2) . " GB";
}

echo json_encode(array(
    "ok"      => true,
    "stats"   => array(
        "total"    => (int)$row["total"],
        "audios"   => (int)$row["audios"],
        "videos"   => (int)$row["videos"],
        "imagenes" => (int)$row["imagenes"],
        "gb"       => $legible
    )
));
