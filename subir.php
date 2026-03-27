<?php
// subir archivo
// sin validacion, sin patron factory, sin observer, sin seguridad

include("conexion.php");

header("Content-Type: application/json");

// no se verifica si el archivo llego bien
$nombre = $_FILES["archivo"]["name"];
$tmp    = $_FILES["archivo"]["tmp_name"];
$tam    = $_FILES["archivo"]["size"];
$tags   = $_POST["etiquetas"];

// extension del archivo
$partes = explode(".", $nombre);
$ext    = $partes[count($partes)-1];

// tipo del archivo - sin clase, sin factory, if gigante
if($ext == "mp3" || $ext == "wav" || $ext == "ogg" || $ext == "flac" || $ext == "aac" || $ext == "m4a"){
    $tipo = "audio";
} else if($ext == "mp4" || $ext == "avi" || $ext == "mov" || $ext == "mkv" || $ext == "webm" || $ext == "wmv"){
    $tipo = "video";
} else if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif" || $ext == "webp" || $ext == "bmp"){
    $tipo = "imagen";
} else {
    $tipo = "otro";
    // no se retorna error, sigue igual
}

// nombre del archivo - sin uniqid real, colisiones probables
$nuevo_nombre = "archivo_" . time() . "." . $ext;

// no se verifica si uploads/ existe
// no se verifica si move_uploaded_file funciono
move_uploaded_file($tmp, "uploads/" . $nuevo_nombre);

// SQL sin preparar - vulnerable a inyeccion
$sql = "INSERT INTO archivos (nombre, tipo, ruta, tamano, etiquetas, fecha_subida) VALUES ('".$nombre."', '".$tipo."', '".$nuevo_nombre."', ".$tam.", '".$tags."', NOW())";
mysqli_query($con, $sql);

$id = mysqli_insert_id($con);

// actualizar estadisticas - otra query sin preparar
mysqli_query($con, "UPDATE estadisticas SET total=total+1, bytes=bytes+".$tam." WHERE tipo='".$tipo."'");

// log en pantalla - expone info del servidor
// error_log no, simplemente echo en produccion
// echo "archivo guardado en: " . $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $nuevo_nombre;

echo json_encode(array(
    "ok"     => true,
    "id"     => $id,
    "nombre" => $nombre,
    "tipo"   => $tipo,
    "url"    => "uploads/" . $nuevo_nombre,
    "tam"    => $tam
));
