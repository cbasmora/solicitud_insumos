<?php
// Conexión a la base de datos (ejemplo)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_medico";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el número de documento del POST
$numeroDocumento = $_POST['numeroDocumento'];

// Consulta SQL para buscar al paciente por número de documento
$sql = "SELECT * FROM pacientes WHERE numero_documento = '$numeroDocumento'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Convertir el resultado en un arreglo asociativo
    $row = $result->fetch_assoc();

    // Preparar los datos para enviar de vuelta como JSON
    $response = array(
        'success' => true,
        'paciente' => array(
            'nombre' => $row['nombre'],
            'sexo' => $row['sexo'],
            'tipo_documento' => $row['tipo_documento'],
            'fecha_nacimiento' => $row['fecha_nacimiento'],
            'entidad' => $row['entidad'],
            'regimen' => $row['regimen']
        )
    );
} else {
    // Si no se encontró ningún paciente
    $response = array('success' => false);
}

// Devolver respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
