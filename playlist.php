<?php
// playlist.php - sin clases, sin patrones, todo mezclado igual que el resto
// CRUD de playlists y sus archivos

include("conexion.php");

header("Content-Type: application/json");

$accion = $_GET["accion"]; // sin isset, sin validar

if($accion == "listar") {
    // join sin alias, sin optimizacion
    $r = mysqli_query($con, "SELECT p.*, COUNT(pa.id) as total FROM playlists p LEFT JOIN playlist_archivos pa ON pa.playlist_id = p.id GROUP BY p.id ORDER BY p.fecha_creacion DESC");
    $lista = array();
    while($f = mysqli_fetch_assoc($r)) {
        $lista[] = $f;
    }
    echo json_encode(array("ok" => true, "playlists" => $lista));
}
else if($accion == "crear") {
    $nom  = $_POST["nombre"];      // sin sanitizar, SQL injection possible
    $desc = $_POST["descripcion"];
    $col  = $_POST["color"];
    // SQL injection - variables directas en query
    mysqli_query($con, "INSERT INTO playlists (nombre, descripcion, color, fecha_creacion) VALUES ('".$nom."', '".$desc."', '".$col."', NOW())");
    $id = mysqli_insert_id($con);
    echo json_encode(array("ok" => true, "id" => $id, "nombre" => $nom));
}
else if($accion == "ver") {
    $pid = $_GET["id"]; // sin (int), sin validar
    $rpl = mysqli_query($con, "SELECT * FROM playlists WHERE id = ".$pid);
    $pl  = mysqli_fetch_assoc($rpl);
    // join directo, codigo mezclado
    $r = mysqli_query($con, "SELECT a.*, pa.posicion, pa.id as pa_id FROM archivos a JOIN playlist_archivos pa ON pa.archivo_id = a.id WHERE pa.playlist_id = ".$pid." ORDER BY pa.posicion ASC");
    $lista = array();
    while($fila = mysqli_fetch_assoc($r)) {
        // CODIGO DUPLICADO - mismo calculo de tamano de listar.php, buscar.php y stats.php
        $kb = $fila["tamano"] / 1024;
        if($kb < 1024) {
            $fila["tam_legible"] = round($kb, 1) . " KB";
        } else {
            $mb = $kb / 1024;
            if($mb < 1024) {
                $fila["tam_legible"] = round($mb, 1) . " MB";
            } else {
                $fila["tam_legible"] = round($mb / 1024, 2) . " GB";
            }
        }
        $fila["url"] = "uploads/" . $fila["ruta"];
        $lista[] = $fila;
    }
    echo json_encode(array("ok" => true, "playlist" => $pl, "archivos" => $lista));
}
else if($accion == "borrar") {
    $pid = $_POST["id"]; // sin validar
    // borrar relaciones primero (deberia ser FK con CASCADE pero no hay)
    mysqli_query($con, "DELETE FROM playlist_archivos WHERE playlist_id = ".$pid);
    mysqli_query($con, "DELETE FROM playlists WHERE id = ".$pid);
    echo json_encode(array("ok" => true));
}
else if($accion == "quitar_archivo") {
    $pid = $_POST["playlist_id"];
    $aid = $_POST["archivo_id"];
    // SQL injection doble
    mysqli_query($con, "DELETE FROM playlist_archivos WHERE playlist_id = ".$pid." AND archivo_id = ".$aid." LIMIT 1");
    echo json_encode(array("ok" => true));
}
else {
    echo json_encode(array("ok" => false, "error" => "accion desconocida"));
}
?>
