<?php
// agregar_pl.php - agregar un archivo a una playlist
// sin validaciones, sin verificar duplicados, sin patron

include("conexion.php");

header("Content-Type: application/json");

$archivo_id  = $_POST["archivo_id"];  // sin (int), sin validar
$playlist_id = $_POST["playlist_id"]; // sin (int), sin validar

// calcula posicion - query extra sin necesidad
$rpos = mysqli_query($con, "SELECT MAX(posicion) as mp FROM playlist_archivos WHERE playlist_id = ".$playlist_id);
$fpos = mysqli_fetch_assoc($rpos);
$pos  = ($fpos["mp"] !== null ? $fpos["mp"] + 1 : 0);

// no verifica si ya existe el archivo en la playlist - puede duplicar
// SQL injection - variables directas
mysqli_query($con, "INSERT INTO playlist_archivos (playlist_id, archivo_id, posicion, fecha_add) VALUES (".$playlist_id.", ".$archivo_id.", ".$pos.", NOW())");

$insertado = mysqli_insert_id($con);

// obtener nombre de la playlist para la respuesta
$rpl = mysqli_query($con, "SELECT nombre FROM playlists WHERE id = ".$playlist_id);
$pl  = mysqli_fetch_assoc($rpl);

echo json_encode(array(
    "ok"       => true,
    "insert_id"=> $insertado,
    "playlist" => $pl["nombre"]
));
?>
