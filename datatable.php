<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pacientes</title>
    <!-- Incluye CSS de DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- Incluye jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Incluye JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- Configuración en español para DataTables -->
    <script src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"></script>
    <!-- Incluye jQuery Editable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jeditable/1.7.3/jquery.jeditable.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(
        rgba(0, 0, 0, 0.1), 
        rgba(0, 0, 0, 0.1)
    ), url('http://192.168.1.250:8080/devolucion_farmacia/img/fondo-medico-azul.png') no-repeat center center fixed;
    background-size: cover;
    position: relative;
    z-index: 1;
}
        .container {
            width: 90%;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #007BFF;
            text-align: center;
        }
        table.dataTable {
            border-collapse: collapse !important;
            width: 100%;
            margin: 20px 0;
            font-size: 1em;
        }
        table.dataTable th, table.dataTable td {
            border: 1px solid #ddd;
            padding: 12px;
            transition: background-color 0.3s;
        }
        table.dataTable th {
            background-color: #007BFF;
            color: white;
            text-align: left;
        }
        table.dataTable tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table.dataTable tr:hover {
            background-color: #d1ecf1;
        }
        table.dataTable td {
            background-color: #fff;
        }
        table.dataTable tr td {
            animation: fadeIn 0.6s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .actions button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 18px;
            margin: 0 5px;
        }
        .edit-link {
    text-decoration: none;
}

.edit-button {
    background-color: rgb(37, 179, 24); /* Color verde */
    color: white; /* Color del texto */
    border: none;
    padding: 10px 20px; /* Tamaño del botón */
    font-size: 16px; /* Tamaño del texto */
    border-radius: 5px; /* Bordes redondeados */
    cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    transition: background-color 0.3s; /* Transición suave para el color */
    justify-content: center;
}

.edit-button:hover {
    background-color: #007BFF; /* Color verde más oscuro al pasar el ratón */
color: white;}

.edit-button i {
    color: #333;
    margin-right: 5px; /* Espacio entre el ícono y el texto */
}

.edit-button i:hover {
    color: #ffffff;
    margin-right: 8px; /* Espacio entre el ícono y el texto */
}

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Encabezados de Solicitudes de Medicamentos e Insumos (PACI)</h1>
        <table id="tablaPacientes" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Sexo</th>
                    <th>Tipo de Documento</th>
                    <th>Número de Documento</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Entidad</th>
                    <th>Régimen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                
                // Consultar los datos de la tabla pacientes
                $sql = "SELECT id, nombre, sexo, tipo_documento, numero_documento, fecha_nacimiento, entidad, regimen FROM pacientes ORDER BY id DESC";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    // Output data de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['nombre']}</td>
                                <td>{$row['sexo']}</td>
                                <td>{$row['tipo_documento']}</td>
                                <td>{$row['numero_documento']}</td>
                                <td>{$row['fecha_nacimiento']}</td>
                                <td>{$row['entidad']}</td>
                                <td>{$row['regimen']}</td>
<td class='actions'>
    <a href='view_edit.php?id={$row['id']}' class='edit-link'>
        <button class='edit-button' title='Editar'>
            <i class='fas fa-edit'></i>
        </button>
    </a>
</td>

                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No se encontraron registros</td></tr>";
                }
                
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Inicializa DataTables y jEditable -->
    <script>
        $(document).ready(function() {
            $('#tablaPacientes').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
                },
                "order": [[0, "desc"]]
            });
        });

        function deleteRecord(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                window.location.href = 'delete_record.php?id=' + id;
            }
        }
    </script>
</body>
</html>
