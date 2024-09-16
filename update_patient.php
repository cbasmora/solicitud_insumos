<?php
// update_patient.php

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

// Obtener datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$sexo = $_POST['sexo'];
$tipo_documento = $_POST['tipo_documento'];
$numero_documento = $_POST['numero_documento'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$entidad = $_POST['entidad'];
$regimen = $_POST['regimen'];

// Actualizar los datos en la base de datos
$sql = "UPDATE pacientes SET 
            nombre='$nombre', 
            sexo='$sexo', 
            tipo_documento='$tipo_documento', 
            numero_documento='$numero_documento', 
            fecha_nacimiento='$fecha_nacimiento', 
            entidad='$entidad', 
            regimen='$regimen' 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    // Redirigir a view_edit.php con parámetro de éxito
    header("Location: view_edit.php?id=$id&update=success");
} else {
    // Redirigir a view_edit.php con parámetro de error
    header("Location: view_edit.php?id=$id&update=error");
}

$conn->close();
?>
