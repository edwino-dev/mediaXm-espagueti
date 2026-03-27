<?php
// conexion a la base de datos
// se incluye en cada archivo que la necesite
// si falla no hace nada

$con = mysqli_connect("localhost", "root", "", "mediaxm_malo_db");
mysqli_set_charset($con, "utf8");
