<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Reemplaza con tu nombre de usuario de MySQL
$password = ""; // Reemplaza con tu contraseña de MySQL
$dbname = "formulario_medico";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el ID del paciente desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar los datos del paciente
$sql = "SELECT * FROM pacientes WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $paciente = $result->fetch_assoc();
} else {
    echo "Paciente no encontrado.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <!-- Incluye CSS y jQuery necesarios -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 50px;
            color: #007bff; /* Puedes ajustar el color si lo deseas */
            cursor: pointer;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <a href="datatable.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Editar Paciente</h1>
        <form action="update_patient.php" method="post">
            <input type="hidden" name="id" value="<?php echo $paciente['id']; ?>">
            
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $paciente['nombre']; ?>" required>
            <br>
            
            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="Masculino" <?php echo $paciente['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo $paciente['sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
            </select>
            <br>
            
            <label for="tipo_documento">Tipo de Documento:</label>
            <select id="tipo_documento" name="tipo_documento" required>
                <option value="MS" <?php echo $paciente['tipo_documento'] == 'MS' ? 'selected' : ''; ?>>Menor sin identificación</option>
                <option value="AS" <?php echo $paciente['tipo_documento'] == 'AS' ? 'selected' : ''; ?>>Adulto sin identificación</option>
                <option value="TI" <?php echo $paciente['tipo_documento'] == 'TI' ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                <option value="RC" <?php echo $paciente['tipo_documento'] == 'RC' ? 'selected' : ''; ?>>Registro civil</option>
                <option value="PA" <?php echo $paciente['tipo_documento'] == 'PA' ? 'selected' : ''; ?>>Pasaporte</option>
                <option value="PEP" <?php echo $paciente['tipo_documento'] == 'PEP' ? 'selected' : ''; ?>>Permiso Especial de Permanencia</option>
                <option value="CE" <?php echo $paciente['tipo_documento'] == 'CE' ? 'selected' : ''; ?>>Cédula de extranjería</option>
                <option value="CC" <?php echo $paciente['tipo_documento'] == 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
            </select>
            <br>
            
            <label for="numero_documento">Número de Documento:</label>
            <input type="text" id="numero_documento" name="numero_documento" value="<?php echo $paciente['numero_documento']; ?>" required>
            <br>
            
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $paciente['fecha_nacimiento']; ?>" required>
            <br>
            
            <label for="entidad">Entidad:</label>
            <input type="text" id="entidad" name="entidad" value="<?php echo $paciente['entidad']; ?>" required>
            <br>
            
            <label for="regimen">Régimen:</label>
            <select id="regimen" name="regimen" required>
                <option value="Contributivo" <?php echo $paciente['regimen'] == 'Contributivo' ? 'selected' : ''; ?>>Contributivo</option>
                <option value="Subsidiado" <?php echo $paciente['regimen'] == 'Subsidiado' ? 'selected' : ''; ?>>Subsidiado</option>
                <option value="Vinculado" <?php echo $paciente['regimen'] == 'Vinculado' ? 'selected' : ''; ?>>Vinculado</option>
                <option value="Particular" <?php echo $paciente['regimen'] == 'Particular' ? 'selected' : ''; ?>>Particular</option>
                <option value="Otro" <?php echo $paciente['regimen'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
            </select>
            <br>
            
            <button type="submit" class="btn-update">Actualizar</button>
        </form>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Verificar parámetros en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const updateStatus = urlParams.get('update');

    if (updateStatus === 'success') {
        Swal.fire({
            title: '¡Actualización exitosa!',
            text: 'La información del paciente se ha actualizado correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            // Redirigir a la página deseada después de confirmar
            window.location.href = 'http://192.168.1.250:8080/solicitud_insumos/datatable.php';
        });
    } else if (updateStatus === 'error') {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo actualizar la información del paciente.',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            // Redirigir a la página deseada después de confirmar
            window.location.href = 'http://192.168.1.250:8080/solicitud_insumos/datatable.php';
        });
    }
    </script>
</body>
</html>
