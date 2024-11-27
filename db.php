<?php
class OracleDatabase {
    private $connection;
    private $username;
    private $password;
    private $connectionString;

    public function __construct() {

        $this->username = "";
        $this->password = "";
        $this->connectionString = "//localhost:1521/xe";
        $this->connect();
    }

    // Método para conectar a la base de datos
    private function connect() {
        $this->connection = oci_connect($this->username, $this->password, $this->connectionString);
        if (!$this->connection) {
            $e = oci_error();
            throw new Exception('Error al conectar a la base de datos: ' . $e['message']);
        }
    }

    // Método para ejecutar consultas SQL
    public function executeQuery($query) {
        $stid = oci_parse($this->connection, $query);
        oci_execute($stid);
        $result = [];
        while ($row = oci_fetch_assoc($stid)) {
            $result[] = $row;
        }
        oci_free_statement($stid);
        return $result;
    }

    public function insertarDatosNoOO($table, $columns, $values) {
        $columnString = implode(", ", $columns);
        $placeholders = implode(", ", array_fill(0, count($values), ":val"));

        $sql = "INSERT INTO $table ($columnString) VALUES ($placeholders)";
        $statement = oci_parse($this->connection, $sql);

        foreach ($values as $index => $value) {
            oci_bind_by_name($statement, ":val" . ($index + 1), $values[$index]);
        }

        if (!oci_execute($statement, OCI_COMMIT_ON_SUCCESS)) {
            $error = oci_error($statement);
            throw new Exception("Insert failed: " . $error['message']);
        }

        oci_free_statement($statement);
    }

    public function insertarDatosPOO($params,$procedure,$columns) {
        $sql = "BEGIN ".$procedure."(".implode(",",$columns)."); END;";
        $statement = oci_parse($this->connection, $sql);

        foreach ($params as $key => $value) {
            oci_bind_by_name($statement, ":$key", $params[$key]);
        }

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            throw new Exception("Error al insertar: " . $error['message']);
        }

        oci_free_statement($statement);
    }

    public function actualizarDatosPOO($params,$procedure,$columns) {
        $sql = "BEGIN ".$procedure."(".implode(",",$columns)."); END;";
        $statement = oci_parse($this->connection, $sql);

        //echo $sql;
        foreach ($params as $key => $value) {
            oci_bind_by_name($statement, ":$key", $params[$key]);
        }

        if (!oci_execute($statement)) {
            $error = oci_error($statement);
            throw new Exception("Error al actualizar: " . $error['message']);
        }

        oci_free_statement($statement);
    }
    // Método para desconectar de la base de datos
    public function disconnect() {
        oci_close($this->connection);
    }
}
?>
