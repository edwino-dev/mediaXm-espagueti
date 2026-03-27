<?php
// buscar archivos
// sin patron strategy, sin clases, SQL injection, codigo duplicado de listar.php

include("conexion.php");

header("Content-Type: application/json");

$q    = $_GET["q"];    // sin sanitizar, sin escapar
$modo = $_GET["modo"]; // sin validar que sea un valor permitido

// sin Strategy - if/else directo copiado de la cabeza
if($modo == "etiqueta"){
    $r = mysqli_query($con, "SELECT * FROM archivos WHERE etiquetas LIKE '%".$q."%'");
} else if($modo == "tipo"){
    $r = mysqli_query($con, "SELECT * FROM archivos WHERE tipo = '".$q."'");
} else if($modo == "fecha"){
    $r = mysqli_query($con, "SELECT * FROM archivos WHERE fecha_subida LIKE '".$q."%'");
} else {
    $r = mysqli_query($con, "SELECT * FROM archivos WHERE nombre LIKE '%".$q."%'");
}

$lista = array();
while($fila = mysqli_fetch_assoc($r)){
    // CODIGO DUPLICADO - exactamente igual que en listar.php
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

echo json_encode(array("ok" => true, "archivos" => $lista, "modo" => $modo));
