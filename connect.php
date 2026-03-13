<<?php
error_reporting(E_PARSE);

// --- CONFIGURACIÓN DINÁMICA ---
$url = getenv('MYSQL_URL');

if ($url) {
    // Entorno de Railway (Nube)
    $dbcomponents = parse_url($url);
    define("SERVER", $dbcomponents['host']);
    define("USER", $dbcomponents['user']);
    define("PASS", $dbcomponents['pass']);
    define("BD", ltrim($dbcomponents['path'], '/'));
    define("PORT", $dbcomponents['port']);
} else {
    // Entorno Local (XAMPP)
    define("SERVER", "localhost");
    define("USER", "root");
    define("PASS", "");
    define("BD", "dbsistema");
    define("PORT", "3306");
}

const BACKUP_PATH = "./backup/";
date_default_timezone_set('America/Caracas');

class SGBD {
    public static function sql($query) {
        // Añadimos el PORT a la conexión
        $con = mysqli_connect(SERVER, USER, PASS, BD, PORT);
        mysqli_set_charset($con, "utf8");
        
        if (mysqli_connect_errno()) {
            printf("Conexion fallida: %s\n", mysqli_connect_error());
            exit();
        } else {
            mysqli_autocommit($con, false);
            mysqli_begin_transaction($con, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
            if ($consul = mysqli_query($con, $query)) {
                if (!mysqli_commit($con)) {
                    print("Falló la consignación de la transacción\n");
                    exit();
                }
            } else {
                mysqli_rollback($con);
                echo "Falló la transacción";
                exit();
            }
            return $consul;
        }
    }

    public static function limpiarCadena($valor) {
        $valor = addslashes($valor);
        $valor = str_ireplace("<script>", "", $valor);
        $valor = str_ireplace("</script>", "", $valor);
        $valor = str_ireplace("SELECT * FROM", "", $valor);
        $valor = str_ireplace("DELETE FROM", "", $valor);
        $valor = str_ireplace("UPDATE", "", $valor);
        $valor = str_ireplace("INSERT INTO", "", $valor);
        $valor = str_ireplace("DROP TABLE", "", $valor);
        $valor = str_ireplace("TRUNCATE TABLE", "", $valor);
        $valor = str_ireplace("--", "", $valor);
        $valor = str_ireplace("^", "", $valor);
        $valor = str_ireplace("[", "", $valor);
        $valor = str_ireplace("]", "", $valor);
        $valor = str_ireplace("\\", "", $valor);
        $valor = str_ireplace("=", "", $valor);
        return $valor;
    }
}