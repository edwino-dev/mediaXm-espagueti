<?php
// compartir_gen.php - generar link publico para compartir
// sin patron, sin abstraccion, sin Adapter, igual que cloud.php pero al menos funciona

include("conexion.php");

header("Content-Type: application/json");

$id = $_GET["id"]; // sin sanitizar

// buscar el archivo
$r   = mysqli_query($con, "SELECT * FROM archivos WHERE id = ".$id);
$f   = mysqli_fetch_assoc($r);

// si no existe el archivo
if(!$f) {
    echo json_encode(array("ok" => false, "error" => "archivo no encontrado"));
    exit;
}

// generar token si no tiene
if(!$f["token_compartir"]) {
    // md5 de datos mezclados - no es criptograficamente seguro pero funciona
    $token = md5(uniqid("", true) . $id . time() . $f["nombre"]);
    // SQL injection - $token viene de md5 asi que no es vulnerable pero el id si
    mysqli_query($con, "UPDATE archivos SET token_compartir = '".$token."', compartido = 'si' WHERE id = ".$id);
} else {
    $token = $f["token_compartir"];
    // ya tenia token - no se actualiza nada aunque podria haber cambiado
}

// construir link - sin usar URL base configurada, depende de superglobal
// si el servidor cambia de dominio hay que cambiar esto
$proto = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http";
$host  = $_SERVER["HTTP_HOST"];
$path  = dirname($_SERVER["PHP_SELF"]);
$link  = $proto . "://" . $host . $path . "/ver.php?t=" . $token;

echo json_encode(array(
    "ok"     => true,
    "link"   => $link,
    "token"  => $token,
    "nombre" => $f["nombre"],
    "tipo"   => $f["tipo"]
));
?>
