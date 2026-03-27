<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>mediaXm — Ver Archivo</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=IBM+Plex+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    :root{--bg:#08080e;--bg2:#0f0f1a;--bg3:#16162a;--brd:rgba(255,255,255,0.08);--brd2:rgba(255,255,255,0.14);--cyan:#00e5ff;--cdim:rgba(0,229,255,0.12);--rose:#ff4d6d;--green:#00f593;--amber:#ffb800;--txt:#e8e8f0;--txt2:#9090b0;--txt3:#5a5a7a;--fd:"Syne",sans-serif;--fb:"DM Sans",sans-serif;--fm:"IBM Plex Mono",monospace;--r:12px;--rl:20px}
    body{background:var(--bg);color:var(--txt);font-family:var(--fb);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:32px}
    body::before{content:"";position:fixed;inset:0;background-image:linear-gradient(var(--brd) 1px,transparent 1px),linear-gradient(90deg,var(--brd) 1px,transparent 1px);background-size:48px 48px;pointer-events:none}
    .ver-wrap{position:relative;width:100%;max-width:720px;background:var(--bg2);border:1px solid var(--brd2);border-radius:var(--rl);padding:32px;text-align:center}
    .ver-logo{font-family:var(--fd);font-size:18px;font-weight:800;color:var(--txt2);margin-bottom:24px}
    .ver-logo span{color:var(--cyan)}
    .ver-nombre{font-family:var(--fd);font-size:22px;font-weight:700;margin-bottom:8px;word-break:break-all}
    .ver-badge{display:inline-block;font-family:var(--fm);font-size:9px;padding:3px 10px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:24px}
    .ver-badge.audio{background:rgba(0,229,255,0.12);color:#00e5ff}
    .ver-badge.video{background:rgba(255,77,109,0.12);color:#ff4d6d}
    .ver-badge.imagen{background:rgba(0,245,147,0.12);color:#00f593}
    .ver-media{width:100%;margin-bottom:24px;border-radius:var(--r);overflow:hidden}
    .ver-media audio{width:100%;outline:none}
    .ver-media video{width:100%;max-height:420px;background:#000;outline:none;display:block}
    .ver-media img{max-width:100%;max-height:500px;object-fit:contain;border-radius:var(--r)}
    .ver-footer{font-size:11px;color:var(--txt3);font-family:var(--fm)}
    .ver-error{color:var(--rose);padding:40px;font-family:var(--fm);font-size:14px}
    .ver-btn{display:inline-flex;align-items:center;gap:6px;padding:8px 20px;border-radius:40px;background:var(--cyan);color:#000;border:none;cursor:pointer;font-family:var(--fb);font-size:13px;font-weight:600;text-decoration:none;margin-top:16px}
  </style>
</head>
<body>
<?php
// ver.php - pagina publica para ver archivos compartidos
// sin validacion del token, vulnerable a brute force, sin rate limit

include("conexion.php");

$token = $_GET["t"]; // sin sanitizar

// SQL injection - $token viene del usuario directo
$r = mysqli_query($con, "SELECT * FROM archivos WHERE token_compartir = '".$token."'");
$f = mysqli_fetch_assoc($r);

if(!$f) {
    // el archivo no existe o el token es incorrecto
    // no se distingue entre los dos casos - error generico
    echo '<div class="ver-wrap"><div class="ver-logo">media<span>Xm</span></div><div class="ver-error">⚠ Enlace no válido o archivo no disponible</div></div>';
} else {
    $url = "uploads/" . $f["ruta"];
    $tipo = $f["tipo"];
    $nom  = htmlspecialchars($f["nombre"]); // unico lugar donde se escapa - inconsistente con el resto

    echo '<div class="ver-wrap">';
    echo '<div class="ver-logo">media<span>Xm</span></div>';
    echo '<div class="ver-nombre">' . $nom . '</div>';
    echo '<div class="ver-badge ' . $tipo . '">' . $tipo . '</div>';
    echo '<div class="ver-media">';

    if($tipo == "audio") {
        echo '<audio controls autoplay><source src="' . $url . '"><p>Tu navegador no soporta audio.</p></audio>';
    } else if($tipo == "video") {
        echo '<video controls autoplay><source src="' . $url . '"><p>Tu navegador no soporta video.</p></video>';
    } else if($tipo == "imagen") {
        echo '<img src="' . $url . '" alt="' . $nom . '">';
    } else {
        echo '<p style="padding:20px;color:var(--txt3)">Tipo de archivo no soportado para previsualización.</p>';
    }

    echo '</div>';

    // boton de descarga directa - sin verificar que el archivo existe en disco
    echo '<a class="ver-btn" href="' . $url . '" download="' . $nom . '">⬇ Descargar</a>';

    echo '<div class="ver-footer" style="margin-top:20px">Compartido desde mediaXm · ' . $f["fecha_subida"] . '</div>';
    echo '</div>';
}
?>
</body>
</html>
