-- Actualizaciones al schema para nuevas funcionalidades
-- Sin normalizacion, mismas malas practicas de siempre

USE mediaxm_malo_db;

-- agregar token de compartir a archivos (si no existe)
ALTER TABLE archivos ADD COLUMN IF NOT EXISTS token_compartir VARCHAR(64) DEFAULT NULL;

-- tabla playlists - sin indices utiles, todo VARCHAR
CREATE TABLE IF NOT EXISTS playlists (
    id              INT NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(500),
    descripcion     VARCHAR(500),
    color           VARCHAR(20),
    fecha_creacion  VARCHAR(100),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- tabla relacion playlist-archivos - sin clave foranea real
CREATE TABLE IF NOT EXISTS playlist_archivos (
    id          INT NOT NULL AUTO_INCREMENT,
    playlist_id VARCHAR(100),    -- deberia ser INT con FK, pero VARCHAR total
    archivo_id  VARCHAR(100),    -- igual
    posicion    INT DEFAULT 0,
    fecha_add   VARCHAR(100),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- playlists de ejemplo
INSERT IGNORE INTO playlists (id, nombre, descripcion, color, fecha_creacion) VALUES
(1, 'Lo-Fi Favorites', 'Los mejores lofi para estudiar', '#00e5ff', '2024-03-10 10:00:00'),
(2, 'Videos Tutorial', 'Tutoriales de programacion', '#ff4d6d', '2024-03-12 11:00:00');

-- vincular archivos de demo a playlists (si los ids coinciden)
INSERT IGNORE INTO playlist_archivos (playlist_id, archivo_id, posicion, fecha_add) VALUES
(1, 1, 0, '2024-03-10 10:01:00'),
(1, 4, 1, '2024-03-10 10:02:00'),
(2, 2, 0, '2024-03-12 11:01:00'),
(2, 5, 1, '2024-03-12 11:02:00');
