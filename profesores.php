<?php
require 'db.php';
require 'obj_persona.php';
        

// Crear una instancia de OracleDatabase
$db = new OracleDatabase();

try {    
    global $db;
    $profesor = new Persona('', '', '', '', '', '', '');
    $newId = '0';
    //crea la consulta
    $consulta = "SELECT id_profesor, e.profesor.nombre as nombre, e.profesor.apellidos as apellidos, fecha_contratacion
    FROM profesores e
    WHERE activo = '1'";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $query = "SELECT id_profesor, e.profesor.nombre as nombre, e.profesor.apellidos as apellidos, e.profesor.telefono as telefono, e.profesor.email as email, fecha_contratacion
                        FROM profesores e
                       WHERE activo = '1'
                         And id_profesor = " . $id;

            $profesor_res = $db->executeQuery($query);
            if ($profesor_res != null) {
                foreach ($profesor_res as $row) {
                    $profesor = new Persona(
                        $row['NOMBRE'],
                        $row['APELLIDOS'],
                        $row['EMAIL'],
                        $row['TELEFONO'],
                        '',
                        $row['FECHA_CONTRATACION'],
                        $row["ID_PROFESOR"]
                    );
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $columns = [
            " :P_NOMBRE",
            " :P_APELLIDOS",
            " :P_TELEFONO",
            " :P_EMAIL",
            " :P_FECHA_CONTRATACION",
            " :P_ACTIVO"
        ];

        $params = [
            "P_NOMBRE" => $_POST['txtNombre'],
            "P_APELLIDOS" => $_POST['txtPrimerApellido'],
            "P_TELEFONO" => $_POST['txtTelefono'],
            "P_EMAIL" => $_POST['txtEmail'],
            "P_FECHA_CONTRATACION" => $_POST['txtFechaNacimiento'],
            "P_ACTIVO" => 1
        ];

        if ($_POST['hdnId'] == null) {
            $db->insertarDatosPOO($params, "INSERTAR_PROFESOR", $columns);
            echo "Datos insertados correctamente.";
        } else {
            $id = $_POST['hdnId'];
            if ($id > 0) {
                $id_array = ["P_ID_PROFESOR" => $_POST['hdnId']];
                $id_text = [":P_ID_PROFESOR"];
                $columns = array_merge($id_text, $columns);
                $params = array_merge($id_array, $params);
                $db->actualizarDatosPOO($params, "EDITAR_PROFESOR", $columns);
                echo "Información Actualziada correctamente.";
            }
        }
    }
    $results = $db->executeQuery($consulta);
    // Desconectar de la base de datos
    //$db->disconnect();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Sistema Académico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="estudiantes.php">Estudiantes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profesores.php">Profesores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="asignaturas.php">Asignaturas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="grupos.php">Grupos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div class="container">
        <h1>Agregar elementos a tabla creador</h1>
        <form class="mb-6" action="profesores.php" method="POST">
            <div class="row">
                <div class="col-auto">
                    <input type="hidden" name="hdnId" value="<?= $profesor != null ? $profesor->Id : '0' ?>">
                    <label for="txtNombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Nombre"
                        value="<?= $profesor != null ? $profesor->Nombre : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtPrimerApellido" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="txtPrimerApellido" id="txtPrimerApellido"
                        placeholder="Primer Apellido" value="<?= $profesor != null ? $profesor->Apellidos : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label for="txtEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" name="txtEmail" id="txtEmail"
                        placeholder="Email@algo.com" value="<?= $profesor != null ? $profesor->Email : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtTelefono" class="form-label">Telefono</label>
                    <input type="text" class="form-control" name="txtTelefono" id="txtTelefono" value="<?= $profesor != null ? $profesor->Telefono : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtFechaNacimiento" class="form-label">Fecha de Contratación</label>
                    <input type="text" class="form-control" name="txtFechaNacimiento" id="txtFechaNacimiento"
                        placeholder="DIA/MES/AÑO" value="<?= $profesor != null ? $profesor->FechaNacimiento : '' ?>">
                </div>
            </div>
            
            <input type="submit" value="<?= $profesor != null && $profesor->Id != '' ? 'Editar' : 'Guardar' ?>" class="btn btn-primary">
        </form>

    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th>Acciones</th>
            <th>NOMBRE</th>
            <th>APELLIDOS</th>
            <th>FECHA_CONTRATACION</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if ($results != null) {
                // Mostrar los resultados
                $cont = 0;
                foreach ($results as $row) {
                    echo "<tr>";
                    foreach ($row as $column) {
                        if ($cont == 0) {
                            echo "<td><a href='profesores.php?id=" . htmlspecialchars($column) . "'>Editar</a></td>";
                            $cont++;
                        } else {
                            echo "<td>" . htmlspecialchars($column) . "</td>";
                        }
                    }
                    $cont = 0;
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan=\"5\">No se han encontrado resultados</td></tr>";
            }
        ?>
        </tbody>
    </table>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
$db->disconnect();
?>