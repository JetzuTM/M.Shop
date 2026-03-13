<?php
    $url = getenv('MYSQL_URL');
    $db_host = "localhost";
    $db_name = "dbsistema";
    $db_user = "root";
    $db_pass = "";

    if ($url) {
        $dbcomponents = parse_url($url);
        $db_host = $dbcomponents['host'];
        $db_user = $dbcomponents['user'];
        $db_pass = $dbcomponents['pass'];
        $db_name = ltrim($dbcomponents['path'], '/');
    }

    //IP DEL SERVIDOR DE BASE DE DATOS
    define("DB_HOST", $db_host);

    //NOMBRE DE LA BASE DE DATOS
    define("DB_NAME", $db_name);

    //USUARIO DE LA BASE DE DATOS
    define("DB_USERNAME", $db_user);

    //CONTRASEÑA DEL USUARIO DE LA BASE DE DATOS
    define("DB_PASSWORD", $db_pass);

    //CODIFICACION DE LOS CARACTERES
    define("DB_ENCODE","utf8");

    //CONSTANTE COMO NOMBRE DEL PROYECTO
    define("PRO_NOMBRE","ITVentas");
?>