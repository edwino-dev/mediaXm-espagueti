<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>mediaXm — Gestor Multimedia</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=IBM+Plex+Mono:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
  <div class="app">
    <aside class="sidebar">
      <div class="logo">
        <div class="logo-text">media<span>Xm</span></div>
        <div class="logo-tag">Multimedia Manager</div>
      </div>
      <nav class="nav-section">
        <div class="nav-label">Principal</div>
        <div class="nav-item active" id="nav-bib" onclick="navigate('biblioteca')"><span class="nav-icon">⬛</span> Biblioteca</div>
        <div class="nav-item" id="nav-pl" onclick="navigate('playlists')"><span class="nav-icon">🎶</span> Playlists</div>
        <div class="nav-item" id="nav-pat" onclick="navigate('patrones')"><span class="nav-icon">◈</span> Code Smells</div>
      </nav>
      <nav class="nav-section">
        <div class="nav-label">Filtrar</div>
        <div class="nav-item" onclick="filtrarSide('audio')"><span class="nav-icon">🎵</span> Audio</div>
        <div class="nav-item" onclick="filtrarSide('video')"><span class="nav-icon">🎬</span> Video</div>
        <div class="nav-item" onclick="filtrarSide('imagen')"><span class="nav-icon">🖼️</span> Imágenes</div>
      </nav>
      <nav class="nav-section">
        <div class="nav-label">Cloud</div>
        <div class="nav-item" onclick="mostrarCloud()"><span class="nav-icon">☁️</span> Google Drive</div>
      </nav>
      <div class="stats-mini">
        <div class="srow"><span class="slabel">Total</span><span class="sval" id="sm-total">—</span></div>
        <div class="srow"><span class="slabel">Almacenamiento</span><span class="sval" id="sm-gb">—</span></div>
      </div>
    </aside>

    <div class="main">
      <div class="topbar">
        <div class="search-wrap">
          <span class="search-icon">🔍</span>
          <input class="search-input" id="searchInput" type="text" placeholder="Buscar archivos...">
          <button class="search-mode sel" data-modo="nombre" onclick="selModo(this)">nombre</button>
          <button class="search-mode" data-modo="etiqueta" onclick="selModo(this)">etiqueta</button>
          <button class="search-mode" data-modo="tipo" onclick="selModo(this)">tipo</button>
          <button class="search-mode" data-modo="fecha" onclick="selModo(this)">fecha</button>
        </div>
        <button class="btn btn-ghost" onclick="abrirUpload()">⬆ Subir</button>
        <button class="btn btn-cyan" onclick="abrirUpload()">＋ Nuevo</button>
      </div>

      <!-- Vista Biblioteca -->
      <div class="content" id="viewBiblioteca">
        <div class="page-header">
          <div>
            <div class="page-title">Biblioteca</div>
            <div class="page-sub" id="viewSub">Cargando...</div>
          </div>
          <div class="filter-tabs">
            <button class="tab active" onclick="filtrarTab(this,'')">Todos</button>
            <button class="tab" onclick="filtrarTab(this,'audio')">Audio</button>
            <button class="tab" onclick="filtrarTab(this,'video')">Video</button>
            <button class="tab" onclick="filtrarTab(this,'imagen')">Imagen</button>
          </div>
        </div>
        <div class="stats-grid">
          <div class="stat-card c1"><span class="stat-icon">📁</span>
            <div class="stat-label">Total</div>
            <div class="stat-num" id="sc-total">—</div>
          </div>
          <div class="stat-card c2"><span class="stat-icon">🎵</span>
            <div class="stat-label">Audios</div>
            <div class="stat-num" id="sc-audio">—</div>
          </div>
          <div class="stat-card c3"><span class="stat-icon">🎬</span>
            <div class="stat-label">Videos</div>
            <div class="stat-num" id="sc-video">—</div>
          </div>
          <div class="stat-card c4"><span class="stat-icon">🖼️</span>
            <div class="stat-label">Imágenes</div>
            <div class="stat-num" id="sc-imagen">—</div>
          </div>
        </div>
        <div class="media-grid" id="mediaGrid">
          <div class="empty">
            <div class="empty-icon">⬡</div>
            <div class="empty-text">Cargando...</div>
          </div>
        </div>
      </div>

      <!-- Vista Playlists -->
      <div class="info-section" id="viewPlaylists">
        <div class="page-header">
          <div>
            <div class="page-title" id="plViewTitle">Playlists</div>
            <div class="page-sub" id="plViewSub">Colecciones de archivos</div>
          </div>
          <div style="display:flex;gap:8px;align-items:center">
            <button class="btn btn-ghost" id="btnVolverPL" onclick="volverPlaylists()" style="display:none">← Volver</button>
            <button class="btn btn-cyan" onclick="abrirCrearPL()">＋ Nueva Playlist</button>
          </div>
        </div>
        <div id="plListaArea">
          <div class="pl-grid" id="plGrid">
            <div class="empty"><div class="empty-icon">🎶</div><div class="empty-text">Cargando...</div></div>
          </div>
        </div>
        <div id="plDetalle" style="display:none">
          <div class="media-grid" id="plArchivoGrid"></div>
        </div>
      </div>

      <!-- Vista Code Smells -->
      <div class="info-section" id="viewPatrones">
        <div class="page-header">
          <div>
            <div class="page-title">Code Smells</div>
            <div class="page-sub">Malas prácticas presentes en este proyecto</div>
          </div>
        </div>
        <div class="patterns-grid">
          <div class="pcard">
            <div class="ptag">Smell 1</div>
            <div class="pname">SQL Injection</div>
            <div class="pdesc">Variables de usuario concatenadas directo en las consultas SQL. Un atacante puede escribir <code>' OR '1'='1</code> y acceder a toda la base de datos, o peor, borrarla.</div>
            <div class="pcode">WHERE tipo = '".$_GET["tipo"]."'<br>WHERE nombre LIKE '%".$q."%'<br>DELETE FROM archivos WHERE id=".$id</div>
          </div>
          <div class="pcard">
            <div class="ptag">Smell 2</div>
            <div class="pname">Sin validaciones</div>
            <div class="pdesc">No se verifica si el archivo llegó, si el tipo es permitido, ni si move_uploaded_file funcionó. Cualquier error queda silencioso y el sistema queda en estado inconsistente.</div>
            <div class="pcode">move_uploaded_file($tmp, "uploads/".$nom);<br>// retorna false si falla<br>// nadie lo verifica</div>
          </div>
          <div class="pcard">
            <div class="ptag">Smell 3</div>
            <div class="pname">Código duplicado</div>
            <div class="pdesc">El cálculo de tamaño legible está copiado idéntico en listar.php, buscar.php, stats.php y playlist.php. Si cambia la lógica hay que modificarlo en 4 lugares.</div>
            <div class="pcode">// en listar.php<br>$kb = $fila["tamano"] / 1024;<br>// en buscar.php — IGUAL<br>// en playlist.php — IGUAL otra vez</div>
          </div>
          <div class="pcard">
            <div class="ptag">Smell 4</div>
            <div class="pname">Sin arquitectura</div>
            <div class="pdesc">No hay clases, no hay separación de responsabilidades, no hay patrones. Cada archivo mezcla acceso a datos con lógica de negocio. Imposible de testear o escalar.</div>
            <div class="pcode">// subir.php hace TODO:<br>// recibe el archivo<br>// lo guarda en disco<br>// hace el INSERT<br>// actualiza estadisticas<br>// retorna la respuesta</div>
          </div>
          <div class="pcard">
            <div class="ptag">Smell 5</div>
            <div class="pname">Variables sin sentido</div>
            <div class="pdesc">Nombres de una letra o abreviaciones sin contexto. Imposible saber qué representa cada variable sin leer el bloque completo.</div>
            <div class="pcode">$con — conexion a BD<br>$r — resultado query<br>$n — nombre archivo<br>$t — tmp_name<br>$s — size<br>$e — explode del nombre</div>
          </div>
          <div class="pcard">
            <div class="ptag">Smell 6</div>
            <div class="pname">Sin manejo de errores</div>
            <div class="pdesc">Si MySQL no responde, PHP lanza un error fatal que imprime el stack trace completo en pantalla, exponiendo la estructura del servidor. No hay try/catch en ningún archivo.</div>
            <div class="pcode">$con = mysqli_connect(...);<br>// si falla: Fatal error expuesto<br>// sin try/catch<br>// sin mensaje amigable<br>// sin fallback</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== UPLOAD OVERLAY (original) ===== -->
  <div class="overlay" id="overlay">
    <div class="panel">
      <button class="panel-close" onclick="cerrarUpload()">✕</button>
      <div class="panel-title">Subir Archivo</div>
      <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click()">
        <div class="drop-icon">⬆</div>
        <div class="drop-text">Arrastra aquí o haz clic para seleccionar</div>
        <div class="drop-hint">MP3 · WAV · MP4 · AVI · JPG · PNG · GIF · WEBP</div>
        <div class="drop-name" id="dropName"></div>
      </div>
      <input type="file" id="fileInput" accept="audio/*,video/*,image/*" style="display:none">
      <div class="field">
        <label>Etiquetas</label>
        <input type="text" id="upEtiquetas" placeholder="musica, favoritos, 2024">
      </div>
      <div class="field">
        <label>Opciones extra</label>
        <div class="decos">
          <label class="deco-lbl"><input type="checkbox" value="marcador"> 📌 Marcador</label>
          <label class="deco-lbl"><input type="checkbox" value="efecto"> ✨ Efecto</label>
          <label class="deco-lbl"><input type="checkbox" value="proteccion"> 🔒 Proteger</label>
          <label class="deco-lbl"><input type="checkbox" value="miniaturas"> 🖼 Miniaturas</label>
        </div>
      </div>
      <button class="btn btn-cyan" id="btnSubir" style="width:100%;justify-content:center;margin-top:8px" onclick="subirArchivo()">Subir archivo</button>
      <div id="uploadMsg" style="margin-top:10px;font-size:12px;font-family:var(--fm);color:var(--txt3);text-align:center"></div>
    </div>
  </div>

  <!-- ===== PLAYER OVERLAY ===== -->
  <div class="overlay" id="playerOverlay">
    <div class="panel" style="width:660px;max-width:95vw">
      <button class="panel-close" onclick="cerrarPlayer()">✕</button>
      <div style="margin-bottom:14px">
        <div id="playerTipoBadge" style="display:inline-block;font-family:var(--fm);font-size:9px;padding:3px 8px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px"></div>
        <div id="playerTitulo" style="font-family:var(--fd);font-size:18px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
        <div id="playerMeta" style="font-size:11px;color:var(--txt3);font-family:var(--fm);margin-top:4px"></div>
      </div>
      <div id="playerContent" style="background:var(--bg);border:1px solid var(--brd);border-radius:var(--r);overflow:hidden;margin-bottom:16px;min-height:60px;display:flex;align-items:center;justify-content:center"></div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <button id="playerPrev" onclick="playPrev()" style="background:var(--sur);border:1px solid var(--brd);color:var(--txt2);padding:6px 14px;border-radius:40px;cursor:pointer;font-size:12px;font-family:var(--fm);transition:all 0.2s">⏮ Anterior</button>
        <div id="playerPos" style="font-family:var(--fm);font-size:11px;color:var(--txt3)"></div>
        <button id="playerNext" onclick="playNext()" style="background:var(--sur);border:1px solid var(--brd);color:var(--txt2);padding:6px 14px;border-radius:40px;cursor:pointer;font-size:12px;font-family:var(--fm);transition:all 0.2s">Siguiente ⏭</button>
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button class="btn btn-ghost" style="font-size:11px" onclick="compartirDesdePlayer()">☁ Compartir</button>
        <button class="btn btn-ghost" style="font-size:11px" onclick="agregarPLDesdePlayer()">☰ Playlist</button>
      </div>
    </div>
  </div>

  <!-- ===== SHARE OVERLAY ===== -->
  <div class="overlay" id="shareOverlay">
    <div class="panel">
      <button class="panel-close" onclick="cerrarShare()">✕</button>
      <div class="panel-title">Compartir Archivo</div>
      <div id="shareNombreLabel" style="font-size:13px;color:var(--txt2);margin-bottom:16px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;min-height:20px"></div>
      <div style="margin-bottom:8px;font-size:11px;color:var(--txt3);font-family:var(--fm);text-transform:uppercase;letter-spacing:1px">Enlace público</div>
      <div id="shareLinkBox" style="background:var(--sur);border:1px solid var(--brd);border-radius:var(--r);padding:10px 14px;font-family:var(--fm);font-size:11px;color:var(--txt2);word-break:break-all;min-height:42px;margin-bottom:12px">Generando...</div>
      <button class="btn btn-cyan" onclick="copiarLink()" style="width:100%;justify-content:center">📋 Copiar enlace</button>
      <button class="btn btn-ghost" onclick="abrirEnNuevaVentana()" style="width:100%;justify-content:center;margin-top:8px">↗ Abrir en nueva pestaña</button>
      <div id="shareMsg" style="margin-top:10px;font-size:12px;font-family:var(--fm);color:var(--green);text-align:center;min-height:16px"></div>
    </div>
  </div>

  <!-- ===== AGREGAR A PLAYLIST OVERLAY ===== -->
  <div class="overlay" id="agregarPLOverlay">
    <div class="panel">
      <button class="panel-close" onclick="cerrarAgregarPL()">✕</button>
      <div class="panel-title">Agregar a Playlist</div>
      <div id="plSelectList" style="max-height:260px;overflow-y:auto;margin-bottom:16px"></div>
      <div style="border-top:1px solid var(--brd);padding-top:14px">
        <div style="font-size:11px;color:var(--txt3);font-family:var(--fm);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">O crea una nueva</div>
        <div style="display:flex;gap:8px">
          <input type="text" id="plNombreQuick" placeholder="Nombre de la playlist..." style="flex:1;background:var(--sur);border:1px solid var(--brd);border-radius:var(--r);padding:8px 12px;color:var(--txt);font-family:var(--fb);font-size:13px;outline:none">
          <button class="btn btn-cyan" onclick="crearYAgregar()">Crear</button>
        </div>
      </div>
      <div id="plAddMsg" style="margin-top:10px;font-size:12px;font-family:var(--fm);color:var(--green);text-align:center;min-height:16px"></div>
    </div>
  </div>

  <!-- ===== CREAR PLAYLIST OVERLAY ===== -->
  <div class="overlay" id="crearPLOverlay">
    <div class="panel">
      <button class="panel-close" onclick="cerrarCrearPL()">✕</button>
      <div class="panel-title">Nueva Playlist</div>
      <div class="field">
        <label>Nombre</label>
        <input type="text" id="plNombreNew" placeholder="Lo-Fi Session, Tutoriales...">
      </div>
      <div class="field">
        <label>Descripción (opcional)</label>
        <input type="text" id="plDescNew" placeholder="Una descripción breve...">
      </div>
      <div class="field">
        <label>Color</label>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:4px">
          <div onclick="selColor(this,'#00e5ff')" data-col="#00e5ff" class="color-dot sel" style="background:#00e5ff"></div>
          <div onclick="selColor(this,'#ff4d6d')" data-col="#ff4d6d" class="color-dot" style="background:#ff4d6d"></div>
          <div onclick="selColor(this,'#00f593')" data-col="#00f593" class="color-dot" style="background:#00f593"></div>
          <div onclick="selColor(this,'#ffb800')" data-col="#ffb800" class="color-dot" style="background:#ffb800"></div>
          <div onclick="selColor(this,'#a78bfa')" data-col="#a78bfa" class="color-dot" style="background:#a78bfa"></div>
        </div>
        <input type="hidden" id="plColorNew" value="#00e5ff">
      </div>
      <button class="btn btn-cyan" onclick="crearPlaylist()" style="width:100%;justify-content:center;margin-top:8px">Crear Playlist</button>
      <div id="crearPLMsg" style="margin-top:10px;font-size:12px;font-family:var(--fm);text-align:center;min-height:16px"></div>
    </div>
  </div>

  <style>
    /* estilos para las nuevas funcionalidades — agregados inline como el resto del proyecto */
    .pl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px}
    .pl-card{background:var(--sur);border:1px solid var(--brd);border-radius:var(--rl);padding:20px;cursor:pointer;transition:all 0.25s;position:relative;overflow:hidden;animation:fadeIn 0.4s ease both}
    .pl-card:hover{border-color:var(--brd2);transform:translateY(-3px);box-shadow:0 12px 40px rgba(0,0,0,0.4)}
    .pl-color-bar{height:3px;position:absolute;top:0;left:0;right:0}
    .pl-icon{font-size:32px;margin-bottom:12px;margin-top:6px}
    .pl-name{font-family:var(--fd);font-size:16px;font-weight:700;margin-bottom:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .pl-count{font-family:var(--fm);font-size:11px;color:var(--txt3)}
    .pl-desc{font-size:12px;color:var(--txt2);margin-top:6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .pl-del-btn{position:absolute;top:10px;right:10px;background:var(--rdim);border:1px solid var(--rose);color:var(--rose);width:22px;height:22px;border-radius:50%;cursor:pointer;font-size:10px;display:none;align-items:center;justify-content:center;line-height:1}
    .pl-card:hover .pl-del-btn{display:flex}
    .pl-select-item{display:flex;justify-content:space-between;align-items:center;padding:10px 14px;border-radius:var(--r);border:1px solid var(--brd);margin-bottom:8px;cursor:pointer;transition:all 0.2s;background:var(--sur)}
    .pl-select-item:hover{border-color:var(--cyan);background:var(--cdim);color:var(--cyan)}
    .cbtn.play:hover{color:var(--cyan);border-color:var(--cyan);background:var(--cdim)}
    .cbtn.plist:hover{color:var(--amber);border-color:var(--amber);background:var(--adim)}
    .color-dot{width:26px;height:26px;border-radius:50%;cursor:pointer;border:2px solid transparent;transition:transform 0.15s}
    .color-dot.sel{border-color:#fff;transform:scale(1.2)}
    .card-thumb{cursor:pointer;position:relative}
    .thumb-play-hint{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.5);color:#fff;font-size:28px;opacity:0;transition:opacity 0.2s}
    .card-thumb:hover .thumb-play-hint{opacity:1}
    .player-content-inner audio{width:100%;outline:none;display:block;padding:20px;box-sizing:border-box}
    .player-content-inner video{width:100%;max-height:380px;background:#000;display:block;outline:none}
    .player-content-inner img{max-width:100%;max-height:440px;object-fit:contain;display:block;margin:0 auto;padding:8px}
    .player-no-src{color:var(--txt3);font-family:var(--fm);font-size:12px;padding:30px;text-align:center}
  </style>

  <script>
    // variables globales sueltas — sin encapsular en ningun objeto
    var tipo_actual = '';
    var modo = 'nombre';
    var timer_busqueda;
    var lista_actual = [];
    var indice_player = 0;
    var pl_archivo_id_global = 0;
    var pl_id_actual = 0;
    var share_link_actual = '';
    var archivo_player_id = 0;

    // todo en window.onload — sin separar responsabilidades
    window.onload = function() {
      cargarArchivos();
      cargarStats();

      // busqueda — sin debounce correcto, sin clase
      document.getElementById('searchInput').onkeyup = function() {
        clearTimeout(timer_busqueda);
        var q = this.value;
        // no se hace trim, no se escapa
        timer_busqueda = setTimeout(function() {
          if (q != '') buscar(q);
          else cargarArchivos();
        }, 400);
      };

      // drag and drop — codigo inline en onload
      var z = document.getElementById('dropZone');
      var inp = document.getElementById('fileInput');
      inp.onchange = function() {
        if (inp.files[0]) document.getElementById('dropName').textContent = '📎 ' + inp.files[0].name;
      };
      z.ondragover = function(e) {
        e.preventDefault();
        z.className = 'drop-zone drag';
      };
      z.ondragleave = function() {
        z.className = 'drop-zone';
      };
      z.ondrop = function(e) {
        e.preventDefault();
        z.className = 'drop-zone';
        if (e.dataTransfer.files[0]) {
          inp.files = e.dataTransfer.files;
          document.getElementById('dropName').textContent = '📎 ' + e.dataTransfer.files[0].name;
        }
      };
    };

    // cargar archivos — llama a listar.php separado
    // sin manejo de error, sin verificar si data existe
    function cargarArchivos() {
      var url = tipo_actual ? 'listar.php?tipo=' + tipo_actual : 'listar.php';
      fetch(url)
        .then(function(r) {
          return r.json();
        })
        .then(function(data) {
          var archivos = data.archivos; // si data.archivos no existe, explota
          lista_actual = archivos;
          if (archivos.length == 0) {
            document.getElementById('mediaGrid').innerHTML = '<div class="empty"><div class="empty-icon">◻</div><div class="empty-text">Sin archivos. Sube el primero con ＋ Nuevo</div></div>';
            return;
          }
          var html = '';
          for (var x = 0; x < archivos.length; x++) {
            var f = archivos[x];
            // thumb — if anidados sin funcion separada, duplicado vs buscar y verPlaylist
            var thumb = '';
            if (f.tipo == 'imagen' && f.url) {
              thumb = '<img src="' + f.url + '" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display=\'none\'">';
            } else if (f.tipo == 'video' && f.url) {
              thumb = '<video src="' + f.url + '" muted preload="metadata" style="width:100%;height:100%;object-fit:cover" onmouseover="this.play()" onmouseout="this.pause();this.currentTime=0"></video>';
            } else {
              var b = '';
              for (var j = 0; j < 12; j++) b += '<span style="--h:' + (6 + Math.random() * 20) + 'px;animation-delay:' + (j * 80) + 'ms"></span>';
              thumb = (f.tipo == 'audio' ? '🎵' : f.tipo == 'video' ? '🎬' : '🖼️') + '<div class="waveform">' + b + '</div>';
            }
            // nombre sin escapar — XSS posible si nombre tiene <script>
            html += '<div class="media-card" style="animation-delay:' + (x * 60) + 'ms">' +
              '<div class="card-thumb ' + f.tipo + '" onclick="abrirPlayer(' + x + ')">' + thumb + '<div class="thumb-play-hint">▶</div></div>' +
              '<div class="card-body">' +
              '<div class="card-name">' + f.nombre + '</div>' +
              '<div class="card-meta">' +
              '<span class="badge badge-' + f.tipo + '">' + f.tipo + '</span>' +
              '<span class="card-size">' + (f.tam_legible || '') + '</span>' +
              '</div>' +
              (f.etiquetas ? '<div class="card-tags"><span>#</span> ' + f.etiquetas + '</div>' : '') +
              '</div>' +
              '<div class="card-actions">' +
              '<button class="cbtn play" onclick="abrirPlayer(' + x + ')" title="Reproducir">▶</button>' +
              '<button class="cbtn share" onclick="abrirShare(' + f.id + ')" title="Compartir">☁</button>' +
              '<button class="cbtn plist" onclick="abrirAgregarPL(' + f.id + ')" title="Agregar a playlist">☰</button>' +
              '<button class="cbtn deco" onclick="verSmells()" title="Code smells">◈</button>' +
              '<button class="cbtn del" onclick="borrarArchivo(' + f.id + ')" title="Eliminar">✕</button>' +
              '</div>' +
              '</div>';
          }
          document.getElementById('mediaGrid').innerHTML = html;
          document.getElementById('viewSub').textContent = archivos.length + ' archivo' + (archivos.length != 1 ? 's' : '') + (tipo_actual ? ' — ' + tipo_actual : '');
        });
      // sin .catch — si falla la red no pasa nada, pantalla en blanco
    }

    // stats — llama a stats.php separado
    function cargarStats() {
      fetch('stats.php')
        .then(function(r) {
          return r.json();
        })
        .then(function(d) {
          // sin verificar d.ok
          document.getElementById('sc-total').textContent = d.stats.total;
          document.getElementById('sc-audio').textContent = d.stats.audios;
          document.getElementById('sc-video').textContent = d.stats.videos;
          document.getElementById('sc-imagen').textContent = d.stats.imagenes;
          document.getElementById('sm-total').textContent = d.stats.total + ' archivos';
          document.getElementById('sm-gb').textContent = d.stats.gb;
        });
    }

    // buscar — llama a buscar.php, DUPLICA logica de render de cargarArchivos
    function buscar(q) {
      fetch('buscar.php?q=' + q + '&modo=' + modo) // q sin encodeURIComponent
        .then(function(r) {
          return r.json();
        })
        .then(function(data) {
          lista_actual = data.archivos; // actualizar para player
          var html = '';
          for (var x = 0; x < data.archivos.length; x++) {
            var f = data.archivos[x];
            // thumb duplicado — exactamente igual que en cargarArchivos y verPlaylist
            var thumb = '';
            if (f.tipo == 'imagen' && f.url) {
              thumb = '<img src="' + f.url + '" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display=\'none\'">';
            } else if (f.tipo == 'video' && f.url) {
              thumb = '<video src="' + f.url + '" muted preload="metadata" style="width:100%;height:100%;object-fit:cover" onmouseover="this.play()" onmouseout="this.pause();this.currentTime=0"></video>';
            } else {
              var b = '';
              for (var j = 0; j < 12; j++) b += '<span style="--h:' + (6 + Math.random() * 20) + 'px;animation-delay:' + (j * 80) + 'ms"></span>';
              thumb = (f.tipo == 'audio' ? '🎵' : f.tipo == 'video' ? '🎬' : '🖼️') + '<div class="waveform">' + b + '</div>';
            }
            html += '<div class="media-card">' +
              '<div class="card-thumb ' + f.tipo + '" onclick="abrirPlayer(' + x + ')">' + thumb + '<div class="thumb-play-hint">▶</div></div>' +
              '<div class="card-body">' +
              '<div class="card-name">' + f.nombre + '</div>' +
              '<div class="card-meta"><span class="badge badge-' + f.tipo + '">' + f.tipo + '</span><span class="card-size">' + (f.tam_legible || '') + '</span></div>' +
              (f.etiquetas ? '<div class="card-tags"><span>#</span> ' + f.etiquetas + '</div>' : '') +
              '</div>' +
              '<div class="card-actions">' +
              '<button class="cbtn play" onclick="abrirPlayer(' + x + ')">▶</button>' +
              '<button class="cbtn share" onclick="abrirShare(' + f.id + ')">☁</button>' +
              '<button class="cbtn plist" onclick="abrirAgregarPL(' + f.id + ')">☰</button>' +
              '<button class="cbtn del" onclick="borrarArchivo(' + f.id + ')">✕</button>' +
              '</div>' +
              '</div>';
          }
          document.getElementById('mediaGrid').innerHTML = html ||
            '<div class="empty"><div class="empty-icon">◻</div><div class="empty-text">Sin resultados para "' + q + '"</div></div>';
          document.getElementById('viewSub').textContent = data.archivos.length + ' resultado(s) — modo: ' + modo;
        });
    }

    // filtros
    function filtrarTab(btn, tipo) {
      document.querySelectorAll('.tab').forEach(function(t) {
        t.className = 'tab';
      });
      btn.className = 'tab active';
      tipo_actual = tipo;
      cargarArchivos();
    }

    function filtrarSide(tipo) {
      tipo_actual = tipo;
      navigate('biblioteca');
      cargarArchivos();
    }

    function selModo(btn) {
      modo = btn.getAttribute('data-modo');
      document.querySelectorAll('.search-mode').forEach(function(b) {
        b.className = 'search-mode';
      });
      btn.className = 'search-mode sel';
    }

    // borrar — sin confirmacion, llama a eliminar.php
    function borrarArchivo(id) {
      // sin confirm() — borra inmediatamente con un click
      var fd = new FormData();
      fd.append('id', id);
      fetch('eliminar.php', {
          method: 'POST',
          body: fd
        })
        .then(function(r) {
          return r.json();
        })
        .then(function() {
          cargarArchivos();
          cargarStats();
        });
      // sin verificar si r.ok es true
    }

    // navegar — extendida para playlists
    function navigate(vista) {
      document.getElementById('viewBiblioteca').style.display = vista === 'biblioteca' ? 'block' : 'none';
      var p = document.getElementById('viewPatrones');
      p.className = vista === 'patrones' ? 'info-section active' : 'info-section';
      var pl = document.getElementById('viewPlaylists');
      pl.className = vista === 'playlists' ? 'info-section active' : 'info-section';
      document.getElementById('nav-bib').className = vista === 'biblioteca' ? 'nav-item active' : 'nav-item';
      document.getElementById('nav-pat').className = vista === 'patrones' ? 'nav-item active' : 'nav-item';
      document.getElementById('nav-pl').className = vista === 'playlists' ? 'nav-item active' : 'nav-item';
      if (vista === 'playlists') cargarPlaylists();
    }

    // upload
    function abrirUpload() {
      document.getElementById('overlay').className = 'overlay show';
    }

    function cerrarUpload() {
      document.getElementById('overlay').className = 'overlay';
      document.getElementById('fileInput').value = '';
      document.getElementById('dropName').textContent = '';
      document.getElementById('upEtiquetas').value = '';
      document.getElementById('uploadMsg').textContent = '';
    }

    function subirArchivo() {
      var inp = document.getElementById('fileInput');
      var msg = document.getElementById('uploadMsg');
      var btn = document.getElementById('btnSubir');
      // sin validar que se selecciono archivo — si inp.files[0] es undefined, fd.append falla
      var fd = new FormData();
      fd.append('archivo', inp.files[0]);
      fd.append('etiquetas', document.getElementById('upEtiquetas').value);
      btn.disabled = true;
      btn.textContent = 'Subiendo...';
      msg.textContent = 'Procesando...';
      // llama a subir.php directamente — sin patron, sin abstraccion
      fetch('subir.php', {
          method: 'POST',
          body: fd
        })
        .then(function(r) {
          return r.json();
        })
        .then(function(r) {
          btn.disabled = false;
          btn.textContent = 'Subir archivo';
          if (r.ok) {
            msg.style.color = 'var(--green)';
            msg.textContent = '✓ Subido correctamente';
            setTimeout(function() {
              cerrarUpload();
              cargarArchivos();
              cargarStats();
            }, 1200);
            toast('SIN PATRONES', ['Sin Factory — tipo detectado con if/else', 'Sin Observer — nadie fue notificado', 'Sin Decorator — opciones ignoradas']);
          } else {
            msg.style.color = 'var(--rose)';
            msg.textContent = 'Error: ' + (r.error || 'Fallo desconocido');
          }
        });
      // sin .catch — si la red falla el boton queda bloqueado para siempre
    }

    function mostrarCloud() {
      toast('SIN ADAPTER', ['Llamada directa a cloud.php', 'API key hardcodeada', 'Sin interfaz ICloudStorage', 'Imposible cambiar a Dropbox sin reescribir todo']);
    }

    function verSmells() {
      toast('CODE SMELLS ACTIVOS', ['SQL Injection en listar/buscar/eliminar/playlist', 'Sin validaciones en subir', 'Codigo duplicado en 4 archivos', 'Sin clases ni patrones']);
    }

    // =================== PLAYER ===================

    function abrirPlayer(idx) {
      if (!lista_actual || lista_actual.length == 0) return;
      indice_player = idx;
      var f = lista_actual[idx];
      archivo_player_id = f.id;

      document.getElementById('playerOverlay').className = 'overlay show';
      document.getElementById('playerTitulo').textContent = f.nombre;
      document.getElementById('playerMeta').textContent =
        (f.tam_legible || '') +
        (f.etiquetas ? ' · ' + f.etiquetas : '') +
        (f.fecha_subida ? ' · ' + f.fecha_subida : '');

      var badge = document.getElementById('playerTipoBadge');
      badge.textContent = f.tipo;
      // colores del badge segun tipo — sin clase, if/else directo
      if (f.tipo == 'audio') {
        badge.style.cssText = 'display:inline-block;font-family:var(--fm);font-size:9px;padding:3px 8px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;background:var(--cdim);color:var(--cyan)';
      } else if (f.tipo == 'video') {
        badge.style.cssText = 'display:inline-block;font-family:var(--fm);font-size:9px;padding:3px 8px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;background:var(--rdim);color:var(--rose)';
      } else {
        badge.style.cssText = 'display:inline-block;font-family:var(--fm);font-size:9px;padding:3px 8px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;background:var(--gdim);color:var(--green)';
      }

      var cont = document.getElementById('playerContent');
      // limpiar — detiene reproduccion previa
      cont.innerHTML = '<div class="player-content-inner" style="width:100%"></div>';
      var inner = cont.querySelector('.player-content-inner');

      if (f.tipo == 'audio') {
        // audio nativo — sin clase AudioPlayer
        inner.innerHTML = '<audio controls autoplay style="width:100%;padding:16px;box-sizing:border-box">' +
          '<source src="' + f.url + '">' +
          '<p class="player-no-src">⚠ Formato no soportado o archivo no disponible en el servidor</p>' +
          '</audio>';
      } else if (f.tipo == 'video') {
        // video nativo
        inner.innerHTML = '<video controls autoplay style="width:100%;max-height:380px;background:#000;display:block">' +
          '<source src="' + f.url + '">' +
          '<p class="player-no-src">⚠ Formato no soportado o archivo no disponible en el servidor</p>' +
          '</video>';
      } else if (f.tipo == 'imagen') {
        // imagen — lightbox basico
        var img = document.createElement('img');
        img.src = f.url;
        img.style.cssText = 'max-width:100%;max-height:440px;object-fit:contain;display:block;margin:0 auto;padding:12px';
        img.onerror = function() {
          inner.innerHTML = '<p class="player-no-src">⚠ Imagen no disponible en el servidor</p>';
        };
        inner.appendChild(img);
      } else {
        inner.innerHTML = '<p class="player-no-src">⚠ Tipo de archivo no soportado para reproduccion</p>';
      }

      // nav prev/next
      document.getElementById('playerPrev').disabled = (idx == 0);
      document.getElementById('playerPrev').style.opacity = (idx == 0) ? '0.3' : '1';
      document.getElementById('playerNext').disabled = (idx == lista_actual.length - 1);
      document.getElementById('playerNext').style.opacity = (idx == lista_actual.length - 1) ? '0.3' : '1';
      document.getElementById('playerPos').textContent = (idx + 1) + ' / ' + lista_actual.length;
    }

    function cerrarPlayer() {
      document.getElementById('playerOverlay').className = 'overlay';
      // limpiar innerHTML para detener audio/video
      document.getElementById('playerContent').innerHTML = '';
    }

    function playNext() {
      if (indice_player < lista_actual.length - 1) {
        indice_player++;
        abrirPlayer(indice_player);
      }
    }

    function playPrev() {
      if (indice_player > 0) {
        indice_player--;
        abrirPlayer(indice_player);
      }
    }

    function compartirDesdePlayer() {
      cerrarPlayer();
      abrirShare(archivo_player_id);
    }

    function agregarPLDesdePlayer() {
      cerrarPlayer();
      abrirAgregarPL(archivo_player_id);
    }

    // =================== COMPARTIR ===================

    function compartirArchivo(id) {
      // funcion original redirigida al nuevo flujo real
      abrirShare(id);
    }

    function abrirShare(id) {
      document.getElementById('shareOverlay').className = 'overlay show';
      document.getElementById('shareLinkBox').textContent = 'Generando enlace...';
      document.getElementById('shareMsg').textContent = '';
      document.getElementById('shareNombreLabel').textContent = '';
      share_link_actual = '';

      // llama a compartir_gen.php — sin Adapter, sin abstraccion
      fetch('compartir_gen.php?id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.ok) {
            share_link_actual = d.link;
            document.getElementById('shareLinkBox').textContent = d.link;
            document.getElementById('shareNombreLabel').textContent = '📎 ' + d.nombre;
          } else {
            document.getElementById('shareLinkBox').textContent = 'Error: ' + (d.error || 'No se pudo generar');
          }
        });
      // sin .catch — igual que el resto del codigo
    }

    function cerrarShare() {
      document.getElementById('shareOverlay').className = 'overlay';
      share_link_actual = '';
    }

    function copiarLink() {
      if (!share_link_actual) return;
      // navigator.clipboard puede fallar en http sin https — sin manejo adecuado de errores
      navigator.clipboard.writeText(share_link_actual).then(function() {
        document.getElementById('shareMsg').textContent = '✓ Enlace copiado al portapapeles';
      }).catch(function() {
        // fallback con execCommand — deprecated pero sigue funcionando
        var ta = document.createElement('textarea');
        ta.value = share_link_actual;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        document.getElementById('shareMsg').textContent = '✓ Enlace copiado';
      });
    }

    function abrirEnNuevaVentana() {
      if (share_link_actual) window.open(share_link_actual, '_blank');
    }

    // =================== PLAYLISTS ===================

    function cargarPlaylists() {
      document.getElementById('plDetalle').style.display = 'none';
      document.getElementById('plListaArea').style.display = 'block';
      document.getElementById('btnVolverPL').style.display = 'none';
      document.getElementById('plViewTitle').textContent = 'Playlists';
      document.getElementById('plViewSub').textContent = 'Colecciones de archivos';

      fetch('playlist.php?accion=listar')
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.playlists.length == 0) {
            document.getElementById('plGrid').innerHTML = '<div class="empty" style="grid-column:1/-1"><div class="empty-icon">🎶</div><div class="empty-text">Sin playlists. Crea la primera con ＋ Nueva Playlist</div></div>';
            return;
          }
          var html = '';
          for (var i = 0; i < d.playlists.length; i++) {
            var p = d.playlists[i];
            var col = p.color || '#00e5ff';
            html += '<div class="pl-card" onclick="verPlaylist(' + p.id + ')">' +
              '<div class="pl-color-bar" style="background:' + col + '"></div>' +
              '<button class="pl-del-btn" onclick="event.stopPropagation();borrarPlaylist(' + p.id + ')">✕</button>' +
              '<div class="pl-icon">🎵</div>' +
              '<div class="pl-name">' + p.nombre + '</div>' +
              '<div class="pl-count">' + (p.total || 0) + ' archivos</div>' +
              (p.descripcion ? '<div class="pl-desc">' + p.descripcion + '</div>' : '') +
              '</div>';
          }
          document.getElementById('plGrid').innerHTML = html;
        });
    }

    function verPlaylist(id) {
      pl_id_actual = id;
      fetch('playlist.php?accion=ver&id=' + id)
        .then(function(r) { return r.json(); })
        .then(function(d) {
          var pl = d.playlist;
          lista_actual = d.archivos; // actualizar para player

          document.getElementById('plViewTitle').textContent = pl.nombre;
          document.getElementById('plViewSub').textContent = d.archivos.length + ' archivo' + (d.archivos.length != 1 ? 's' : '');
          document.getElementById('plListaArea').style.display = 'none';
          document.getElementById('plDetalle').style.display = 'block';
          document.getElementById('btnVolverPL').style.display = 'inline-flex';

          var html = '';
          if (d.archivos.length == 0) {
            html = '<div class="empty" style="grid-column:1/-1"><div class="empty-icon">◻</div><div class="empty-text">Playlist vacía. Agrega archivos desde la Biblioteca con el botón ☰</div></div>';
          }
          for (var x = 0; x < d.archivos.length; x++) {
            var f = d.archivos[x];
            // thumb — DUPLICADO POR TERCERA VEZ, spaghetti total
            var thumb = '';
            if (f.tipo == 'imagen' && f.url) {
              thumb = '<img src="' + f.url + '" style="width:100%;height:100%;object-fit:cover" onerror="this.style.display=\'none\'">';
            } else if (f.tipo == 'video' && f.url) {
              thumb = '<video src="' + f.url + '" muted preload="metadata" style="width:100%;height:100%;object-fit:cover" onmouseover="this.play()" onmouseout="this.pause();this.currentTime=0"></video>';
            } else {
              var b = '';
              for (var j = 0; j < 12; j++) b += '<span style="--h:' + (6 + Math.random() * 20) + 'px;animation-delay:' + (j * 80) + 'ms"></span>';
              thumb = (f.tipo == 'audio' ? '🎵' : f.tipo == 'video' ? '🎬' : '🖼️') + '<div class="waveform">' + b + '</div>';
            }
            html += '<div class="media-card" style="animation-delay:' + (x * 60) + 'ms">' +
              '<div class="card-thumb ' + f.tipo + '" onclick="abrirPlayer(' + x + ')">' + thumb + '<div class="thumb-play-hint">▶</div></div>' +
              '<div class="card-body">' +
              '<div class="card-name">' + f.nombre + '</div>' +
              '<div class="card-meta"><span class="badge badge-' + f.tipo + '">' + f.tipo + '</span><span class="card-size">' + (f.tam_legible || '') + '</span></div>' +
              (f.etiquetas ? '<div class="card-tags"><span>#</span> ' + f.etiquetas + '</div>' : '') +
              '</div>' +
              '<div class="card-actions">' +
              '<button class="cbtn play" onclick="abrirPlayer(' + x + ')">▶</button>' +
              '<button class="cbtn share" onclick="abrirShare(' + f.id + ')">☁</button>' +
              '<button class="cbtn del" onclick="quitarDePL(' + id + ',' + f.id + ')" title="Quitar de playlist">✕</button>' +
              '</div>' +
              '</div>';
          }
          document.getElementById('plArchivoGrid').innerHTML = html;
        });
    }

    function volverPlaylists() {
      cargarPlaylists();
    }

    function borrarPlaylist(id) {
      // sin confirm() — como todo el codigo espagueti del proyecto
      var fd = new FormData();
      fd.append('id', id);
      fetch('playlist.php?accion=borrar', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function() { cargarPlaylists(); });
    }

    function quitarDePL(plId, archivoId) {
      var fd = new FormData();
      fd.append('playlist_id', plId);
      fd.append('archivo_id', archivoId);
      fetch('playlist.php?accion=quitar_archivo', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function() { verPlaylist(plId); });
    }

    // abrir overlay agregar a playlist
    function abrirAgregarPL(archivoId) {
      pl_archivo_id_global = archivoId;
      document.getElementById('agregarPLOverlay').className = 'overlay show';
      document.getElementById('plAddMsg').textContent = '';
      document.getElementById('plNombreQuick').value = '';
      document.getElementById('plSelectList').innerHTML = '<div style="color:var(--txt3);text-align:center;padding:20px;font-family:var(--fm);font-size:12px">Cargando...</div>';

      // cargar playlists — otra llamada fetch sin patron
      fetch('playlist.php?accion=listar')
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.playlists.length == 0) {
            document.getElementById('plSelectList').innerHTML = '<div class="empty" style="padding:20px"><div class="empty-text">No hay playlists. Crea una abajo.</div></div>';
            return;
          }
          var html = '';
          for (var i = 0; i < d.playlists.length; i++) {
            var p = d.playlists[i];
            html += '<div class="pl-select-item" onclick="agregarAPL(' + p.id + ')">' +
              '<span>🎵 ' + p.nombre + '</span>' +
              '<span style="color:var(--txt3);font-size:11px;font-family:var(--fm)">' + (p.total || 0) + ' archivos</span>' +
              '</div>';
          }
          document.getElementById('plSelectList').innerHTML = html;
        });
    }

    function cerrarAgregarPL() {
      document.getElementById('agregarPLOverlay').className = 'overlay';
    }

    function agregarAPL(plId) {
      var fd = new FormData();
      fd.append('archivo_id', pl_archivo_id_global);
      fd.append('playlist_id', plId);
      fetch('agregar_pl.php', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          document.getElementById('plAddMsg').textContent = '✓ Agregado a "' + (d.playlist || 'playlist') + '"';
          setTimeout(function() { cerrarAgregarPL(); }, 1000);
        });
    }

    function crearYAgregar() {
      var nom = document.getElementById('plNombreQuick').value;
      if (!nom) return;
      var fd = new FormData();
      fd.append('nombre', nom);
      fd.append('descripcion', '');
      fd.append('color', '#00e5ff');
      fetch('playlist.php?accion=crear', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.ok) agregarAPL(d.id);
        });
    }

    function abrirCrearPL() {
      document.getElementById('crearPLOverlay').className = 'overlay show';
      document.getElementById('plNombreNew').value = '';
      document.getElementById('plDescNew').value = '';
      document.getElementById('plColorNew').value = '#00e5ff';
      document.getElementById('crearPLMsg').textContent = '';
      document.querySelectorAll('.color-dot').forEach(function(d) { d.className = 'color-dot'; });
      var first = document.querySelector('.color-dot');
      if (first) first.className = 'color-dot sel';
    }

    function cerrarCrearPL() {
      document.getElementById('crearPLOverlay').className = 'overlay';
    }

    function selColor(el, color) {
      document.querySelectorAll('.color-dot').forEach(function(d) { d.className = 'color-dot'; });
      el.className = 'color-dot sel';
      document.getElementById('plColorNew').value = color;
    }

    function crearPlaylist() {
      var nom = document.getElementById('plNombreNew').value;
      if (!nom) {
        document.getElementById('crearPLMsg').style.color = 'var(--rose)';
        document.getElementById('crearPLMsg').textContent = '⚠ Escribe un nombre para la playlist';
        return;
      }
      var fd = new FormData();
      fd.append('nombre', nom);
      fd.append('descripcion', document.getElementById('plDescNew').value);
      fd.append('color', document.getElementById('plColorNew').value);
      fetch('playlist.php?accion=crear', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(d) {
          if (d.ok) {
            document.getElementById('crearPLMsg').style.color = 'var(--green)';
            document.getElementById('crearPLMsg').textContent = '✓ Playlist "' + d.nombre + '" creada';
            setTimeout(function() {
              cerrarCrearPL();
              navigate('playlists');
            }, 900);
          }
        });
    }

    // toast
    var tt;
    function toast(titulo, items) {
      var old = document.querySelector('.toast');
      if (old) old.parentNode.removeChild(old);
      var el = document.createElement('div');
      el.className = 'toast';
      var h = '<div class="toast-title">' + titulo + '</div>';
      for (var i = 0; i < items.length; i++) h += '<div class="toast-item">' + items[i] + '</div>';
      el.innerHTML = h;
      document.body.appendChild(el);
      clearTimeout(tt);
      tt = setTimeout(function() {
        if (el.parentNode) el.parentNode.removeChild(el);
      }, 4500);
    }
  </script>
</body>

</html>
