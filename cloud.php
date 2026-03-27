<?php
// integracion con google drive
// sin patron Adapter, sin interfaz ICloudStorage
// acoplado directamente, sin abstraccion
// si google cambia su API hay que cambiar TODO el codigo que lo use

header("Content-Type: application/json");

$id = $_GET["id"]; // sin sanitizar

// llamada directa sin abstraccion
// si manana se cambia a Dropbox hay que reescribir esto y todos los que lo llamen
// no hay interfaz, no hay contrato, no hay desacoplamiento

// simulacion de llamada directa a google drive sin adapter
$url = "https://www.googleapis.com/drive/v3/files/".$id."?key=MI_API_KEY_HARDCODEADA_AQUI";

// la API key esta hardcodeada en el codigo - grave problema de seguridad
// si esto va a git, la clave queda expuesta

echo json_encode(array(
    "ok"     => true,
    "enlace" => "https://drive.google.com/file/d/gdrive_".$id."/view",
    "api"    => "llamada directa sin Adapter"
));
