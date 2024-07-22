<?php
// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura los datos del formulario
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $paciente = $_POST['paciente'];
    $sexo = $_POST['sexo'];
    $tipoDocumento = $_POST['tipoDocumento'];
    $numeroDocumento = $_POST['numeroDocumento'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $entidad = $_POST['entidad'];
    $tipo = $_POST['tipo'];
    
    // Configuración de conexión a la base de datos
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
    
    // Verificar si ya existe un registro con el número de documento
    $sql_check = "SELECT * FROM pacientes WHERE numero_documento = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $numeroDocumento);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        // Si ya existe, actualizar los datos del paciente
        $sql_update = "UPDATE pacientes SET nombre=?, sexo=?, tipo_documento=?, fecha_nacimiento=?, entidad=?, regimen=? WHERE numero_documento=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssss", $paciente, $sexo, $tipoDocumento, $fechaNacimiento, $entidad, $tipo, $numeroDocumento);
        
        if ($stmt_update->execute()) {
            echo "Datos actualizados correctamente en la tabla pacientes.";
        } else {
            echo "Error al actualizar datos: " . $stmt_update->error;
        }
        
        $stmt_update->close();
    } else {
        // Si no existe, insertar un nuevo registro
        $sql_insert = "INSERT INTO pacientes (nombre, sexo, tipo_documento, numero_documento, fecha_nacimiento, entidad, regimen)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("sssssss", $paciente, $sexo, $tipoDocumento, $numeroDocumento, $fechaNacimiento, $entidad, $tipo);
        
        if ($stmt_insert->execute()) {
            echo "Nuevo paciente registrado correctamente en la tabla pacientes.";
        } else {
            echo "Error al insertar datos: " . $stmt_insert->error;
        }
        
        $stmt_insert->close();
    }
    
    // Cerrar la conexión
    $conn->close();
}
?>
