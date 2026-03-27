<?php
// listar archivos
// sin patron strategy, sin clases, todo directo

include("conexion.php");

header("Content-Type: application/json");

$tipo = $_GET["tipo"];  // sin isset, sin sanitizar

// sin patron strategy - if directo
if($tipo != ""){
    // SQL injection - el tipo viene directo del GET
    $r = mysqli_query($con, "SELECT * FROM archivos WHERE tipo = '".$tipo."' ORDER BY fecha_subida DESC");
} else {
    $r = mysqli_query($con, "SELECT * FROM archivos ORDER BY fecha_subida DESC");
}

$lista = array();

while($fila = mysqli_fetch_assoc($r)){
    // calculo de tamaño - duplicado aqui, en buscar.php y en stats.php
    $kb = $fila["tamano"] / 1024;
    if($kb < 1024){
        $fila["tam_legible"] = round($kb, 1) . " KB";
    } else {
        $mb = $kb / 1024;
        if($mb < 1024){
            $fila["tam_legible"] = round($mb, 1) . " MB";
        } else {
            $fila["tam_legible"] = round($mb / 1024, 2) . " GB";
        }
    }
    $fila["url"] = "uploads/" . $fila["ruta"];
    $lista[] = $fila;
}

// no se verifica si la query fallo
echo json_encode(array("ok" => true, "archivos" => $lista));
