<?php
require 'db.php';
require 'obj_persona.php';
        

// Crear una instancia de OracleDatabase
$db = new OracleDatabase();

try {    
    global $db;
    $estudiante = new Persona('', '', '', '', '', '', '');
    $newId = '0';
    //crea la consulta
    $consulta = "SELECT id_estudiante, e.estudiante.nombre as nombre, e.estudiante.apellidos as apellidos, genero, fecha_nacimiento
    FROM estudiantes e
    WHERE activo = '1'";

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $query = "SELECT id_estudiante, e.estudiante.nombre as nombre, e.estudiante.apellidos as apellidos, e.estudiante.telefono as telefono, e.estudiante.email as email, genero, fecha_nacimiento
                        FROM estudiantes e
                       WHERE activo = '1'
                         And id_estudiante = " . $id;

            $estudiante_res = $db->executeQuery($query);
            if ($estudiante_res != null) {
                foreach ($estudiante_res as $row) {
                    $estudiante = new Persona(
                        $row['NOMBRE'],
                        $row['APELLIDOS'],
                        $row['EMAIL'],
                        $row['TELEFONO'],
                        $row['GENERO'],
                        $row['FECHA_NACIMIENTO'],
                        $row["ID_ESTUDIANTE"]
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
            " :P_GENERO",
            " :P_FECHA_NACIMIENTO",
            " :P_ACTIVO"
        ];

        $params = [
            "P_NOMBRE" => $_POST['txtNombre'],
            "P_APELLIDOS" => $_POST['txtPrimerApellido'],
            "P_TELEFONO" => $_POST['txtTelefono'],
            "P_EMAIL" => $_POST['txtEmail'],
            "P_GENERO" => $_POST['cboGenero'],
            "P_FECHA_NACIMIENTO" => $_POST['txtFechaNacimiento'],
            "P_ACTIVO" => 1
        ];

        if ($_POST['hdnId'] == null) {
            $db->insertarDatosPOO($params, "INSERTAR_ESTUDIANTE", $columns);
            echo "Datos insertados correctamente.";
        } else {
            $id = $_POST['hdnId'];
            if ($id > 0) {
                $id_array = ["P_ID_ESTUDIANTE" => $_POST['hdnId']];
                $id_text = [":P_ID_ESTUDIANTE"];
                $columns = array_merge($id_text, $columns);
                $params = array_merge($id_array, $params);
                $db->actualizarDatosPOO($params, "EDITAR_ESTUDIANTE", $columns);
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
        <form class="mb-6" action="estudiantes.php" method="POST">
            <div class="row">
                <div class="col-auto">
                    <input type="hidden" name="hdnId" value="<?= $estudiante != null ? $estudiante->Id : '0' ?>">
                    <label for="txtNombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="txtNombre" name="txtNombre" placeholder="Nombre"
                        value="<?= $estudiante != null ? $estudiante->Nombre : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtPrimerApellido" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="txtPrimerApellido" id="txtPrimerApellido"
                        placeholder="Primer Apellido" value="<?= $estudiante != null ? $estudiante->Apellidos : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <label for="txtEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" name="txtEmail" id="txtEmail"
                        placeholder="Email@algo.com" value="<?= $estudiante != null ? $estudiante->Email : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtTelefono" class="form-label">Telefono</label>
                    <input type="text" class="form-control" name="txtTelefono" id="txtTelefono" value="<?= $estudiante != null ? $estudiante->Telefono : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="txtFechaNacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="text" class="form-control" name="txtFechaNacimiento" id="txtFechaNacimiento"
                        placeholder="DIA/MES/AÑO" value="<?= $estudiante != null ? $estudiante->FechaNacimiento : '' ?>">
                </div>
                <div class="col-auto">
                    <label for="cboGenero" class="form-label">Género</label>
                    <select class="form-control" name="cboGenero" id="cboGenero">
                        <option value="">Seleccione género</option>
                        <option value="Masculino" <?= $estudiante != null ? ($estudiante->Genero == "Masculino" ? 'selected' : '')  : '' ?>>Masculino</option>
                        <option value="Femenino" <?= $estudiante != null ? ($estudiante->Genero == "Femenino" ? 'selected' : '')  : '' ?>>Femenino</option>
                    </select>
                </div>
            </div>
            
            <input type="submit" value="<?= $estudiante != null && $estudiante->Id != '' ? 'Editar' : 'Guardar' ?>" class="btn btn-primary">
        </form>

    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
            <th>Acciones</th>
            <th>NOMBRE</th>
            <th>APELLIDOS</th>
            <th>GENERO</th>
            <th>FECHA_NACIMIENTO</th>
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
                            echo "<td><a href='estudiantes.php?id=" . htmlspecialchars($column) . "'>Editar</a></td>";
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