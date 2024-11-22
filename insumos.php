<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulario_medico";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die(json_encode(array("error" => "Conexión fallida: " . $conn->connect_error)));
}

// Consulta para obtener los insumos
$sql = "SELECT nombre_insumo FROM insumos WHERE suspendido = 0";
$result = $conn->query($sql);

// Crear un array para almacenar los resultados
$insumos = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $insumos[] = $row;
    }
    // Convertir el array a JSON y devolverlo
    echo json_encode($insumos);
} else {
    // Enviar respuesta vacía si no hay resultados
    echo json_encode(array("message" => "No se encontraron insumos."));
}

// Cerrar la conexión
$conn->close();
?>
