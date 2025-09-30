<?php
class Conexion {

    private $host = "connections_samalabpostgres"; // nombre del contenedor Docker
    private $port = "5432";
    private $username = "postgres";
    private $password = "postgres";
    private $database = "admin_connections";
    private $conn;

    public function __construct() {
        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->database}";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_EMULATE_PREPARES => false // fuerza uso real de prepared statements
            ]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION["ultimo_acceso"] = date("Y-m-d H:i:s");

            // cambio de DB por dominio/cliente
            $this->changeDB();

        } catch (PDOException $e) {
            die("❌ Error de conexión: " . $e->getMessage());
        }
    }

    //  SELECT con parámetros
    public function getQuery($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            if($stmt->execute($params)){
                return $stmt->fetchAll();
                
            }
            else{
                print_r($stmt->errorInfo());
            }
                
        } catch (PDOException $e) {
            $this->logError($sql, $e->getMessage());
            print_r($sql);
             print_r($e->getMessage());
            return [];
        }
    }
     //----------- Como se utiliza esta funcion:
     /* $sql = "SELECT * FROM usuarios WHERE edad > :edad";
        $usuarios = $db->getQuery($sql, [":edad" => 25]); */



    //  INSERT/UPDATE/DELETE con parámetros
    public function setQuery($sql, $params = [], $tabla = null, $idTabla = null, $observaciones = null) {
        try {
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute($params);

            // Solo si es insert/update/delete guardamos log
            if ($ok && preg_match('/^(INSERT|UPDATE|DELETE)/i', trim($sql))) {
                $this->logActivity($sql, $tabla, $idTabla, $observaciones);
            }

            return $ok;
        } catch (PDOException $e) {
            $this->logError($sql, $e->getMessage());
            return false;
        }
    }
    //----------- Como se utiliza esta funcion:
    /* $db = new Conexion();
    
        $tabla = "pacientes";
        $sql = "INSERT INTO $tabla (nombre, edad) VALUES (:nombre, :edad)";
        $params = [":nombre" => "Carlos", ":edad" => 33];

        $db->setQuery($sql, $params, $tabla, null, "Registro creado"); */

    private function logActivity($sql, $tabla, $idTabla, $observaciones = null) {
        try {
            $usuario = $_SESSION["usuario"] ?? "sistema";
            $fecha = date("Y-m-d H:i:s");

            $query = "INSERT INTO log_activity (observaciones, tabla, id_tabla, usuario, fecha)
                    VALUES (:observaciones, :tabla, :id_tabla, :usuario, :fecha)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":observaciones" => $observaciones ?? $sql, // puedes guardar el SQL o un texto descriptivo
                ":tabla" => $tabla,
                ":id_tabla" => $idTabla,
                ":usuario" => $usuario,
                ":fecha" => $fecha
            ]);

        } catch (PDOException $e) {
            // Si falla el log, no detiene la transacción principal
            error_log("Fallo log_activity: " . $e->getMessage());
        }
    }


    //  Cambio dinámico de base de datos por dominio
    private function changeDB() {
        $server = str_replace("www.", "", $_SERVER['SERVER_NAME'] ?? "localhost");

        if (!isset($_SESSION["db"]) || $_SESSION["db"] == "") {
            if ($server == "localhost") {
                $server = 'samalab.connectionslab.net';
            }

            $sql = "SELECT * FROM admin_connections.clientes WHERE dominio = :server";

            try {
                $data = $this->getQuery($sql, [":server" => $server]);
                if ($data) {
                    $_SESSION["db"]      = $data[0]->db;
                    $_SESSION["user_db"] = $data[0]->user_db;
                    $_SESSION["ruta"]    = $data[0]->ruta;
                    $_SESSION["cliente"] = $data[0];
                    $_SESSION["server"]  = $server;

                    // Reabrir conexión con la DB específica
                    $this->conn = new PDO(
                        "pgsql:host={$this->host};port={$this->port};dbname={$_SESSION["db"]}",
                        $_SESSION["user_db"],
                        $this->password,
                        [
                            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                            PDO::ATTR_EMULATE_PREPARES   => false
                        ]
                    );
                } else {
                    echo "⚠️ No se encontró cliente con dominio: $server<br>";
                }

            } catch (PDOException $e) {
                echo "❌ Error en changeDB: " . $e->getMessage() . "<br>";
            }
        }
    }


    // Último ID insertado (solo funciona con tablas que tengan secuencia/serial)
    public function getLastId($sequence = null) {
        return $this->conn->lastInsertId($sequence);
    }

    public function close() {
        $this->conn = null;
    }

    private function logError($sql, $msg) {
        if (strpos(strtoupper($_SESSION["usuario"] ?? ""), "CONNECTIONS") !== false) {
            echo "❌ Consulta fallida * $sql * → $msg<br>";
        }
    }
}
?>
