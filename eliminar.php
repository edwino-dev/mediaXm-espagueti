<?php
// eliminar archivo
// sin patron observer, sin validacion del id, SQL injection

include("conexion.php");

header("Content-Type: application/json");

$id = $_POST["id"]; // sin (int), sin validar que sea numero

// SQL injection - $id viene directo sin sanitizar
$r   = mysqli_query($con, "SELECT * FROM archivos WHERE id = ".$id);
$row = mysqli_fetch_assoc($r);

// no se verifica si $row existe - si el id no existe, $row es false y unlink explota
unlink("uploads/" . $row["ruta"]);

// no se verifica si unlink funciono
mysqli_query($con, "DELETE FROM archivos WHERE id = ".$id);

// actualizar estadisticas - sin verificar si la fila existe
mysqli_query($con, "UPDATE estadisticas SET total=total-1, bytes=bytes-".$row["tamano"]." WHERE tipo='".$row["tipo"]."'");

// sin notificar a nadie (no hay Observer)
// sin log de auditoria
// sin confirmacion al usuario de que realmente se borro

echo json_encode(array("ok" => true));
