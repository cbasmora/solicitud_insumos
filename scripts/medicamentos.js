
// Lista de entidades (EPS) para autocompletado
var entidades = [
  "ASOCIACION INDIGENA DEL CAUCA AIC EPS-I",
  "ASMET SALUD EPS SAS",
  "CAJACOPI EPS",
  "CENTRO DE DIAGNOSTICO UROLOGICO CDU S.A.S",
  "CENTRO MÉDICO DE ESPECIALISTAS C.M.E S.A. - CLINICA SANTILLANA",
  "CENTRO ONCOLOGICO DEL CARIBE",
  "CLINICA AVIDANTI MANIZALES",
  "COLMEDICA MEDICINA PREPAGADA S A",
  "COMPAÑÍA DE SEGUROS BOLÍVAR S.A.",
  "COMPAÑÍA MUNDIAL DE SEGUROS",
  "COMPENSAR EPS",
  "COMPAÑÍA DE MEDICINA PREPAGADA COLSANITAS S.A.",
  "COOSALUD EPS",
  "ESM BATALLÓN ASPC NO. 8 'CACIQUE CALARCÀ” CALDAS",
  "MALLAMAS EPS-I",
  "MEDPLUS MEDICINA PREPAGADA S.A",
  "NUEVA EPS CALDAS",
  "SALUD TOTAL EPS-S S.A.",
  "SANITAS EPS SAS",
  "SURA EPS",
  "SEGUROS DE VIDA SURAMERICANA 'PREPAGADA'",
  "FUNDACIÓN UNIÓN PARA EL CONTROL DEL CÁNCER - UNICANCER",
  "UNISALUD MANIZALES",
  "UNISSER",
];

// Autocompletado para medicamentos y entidades
function applyAutocomplete() {
  // Autocompletar para medicamentos desde la base de datos
  $(".medicamento").autocomplete({
    source: function(request, response) {
      $.ajax({
        url: "insumos.php", // Ruta al archivo PHP que consulta los insumos
        method: "GET",
        dataType: "json",
        success: function(data) {
          // Filtrar resultados según el término de búsqueda
          const filteredData = data.filter((item) => {
            return item.nombre_insumo.toLowerCase().includes(request.term.toLowerCase());
          });
          // Pasar los resultados al autocompletado
          response(filteredData.map(item => item.nombre_insumo));
        },
        error: function() {
          alert("Error al obtener los medicamentos.");
        }
      });
    },
    minLength: 2,
  });

  // Autocompletar para entidades (EPS)
  $("#entidad").autocomplete({
    source: entidades,
    minLength: 2,
  });
}

// Función para agregar una nueva fila
function addRow() {
  var rowCount = $("#medicamentosTable tr").length;
  if (rowCount >= 12) {
    alert("Se ha alcanzado el número máximo de medicamentos permitidos (12).");
    return;
  }

  var newRow = `
      <tr>
          <td>${rowCount + 1}</td>
          <td><input id="medicamento${rowCount + 1}" type="text" name="medicamento${rowCount + 1}" class="medicamento" required></td> <!-- Required agregado -->
          <td><input type="number" name="cantidad${rowCount + 1}" required></td> <!-- Required ya presente -->
          <td class="actions">
              <button type="button" onclick="deleteRow(this)">❌</button>
          </td>
      </tr>
  `;
  $("#medicamentosTable").append(newRow);
  applyAutocomplete(); // Aplicar autocompletar a la nueva fila
}

// Función para eliminar una fila
function deleteRow(button) {
  var row = $(button).closest("tr");
  var rowIndex = row.index(); // Obtener el índice de la fila

  // Bloquear la eliminación de la primera fila
  if (rowIndex === 0) {
    return;
  }

  row.remove(); // Eliminar la fila seleccionada
  updateRowNumbers(); // Actualizar los números de las filas restantes
}

  // Función para actualizar los números de fila después de eliminar una fila
  function updateRowNumbers() {
    $("#medicamentosTable tr").each(function(index) {
        $(this).find("td:first").text(index + 1);
        $(this).find("input, select").each(function() {
            var name = $(this).attr("name");
            var id = $(this).attr("id");
            if (name) {
                var newName = name.replace(/\d+$/, "") + (index + 1);
                $(this).attr("name", newName);
            }
            if (id) {
                var newId = id.replace(/\d+$/, "") + (index + 1);
                $(this).attr("id", newId);
            }
        });
    });
}

// Ejecutar al cargar el documento
$(document).ready(function () {
  applyAutocomplete();
  // Asegurarse de que solo se agregue una vez el evento de click
  $("#addMedicamentoBtn").off("click").on("click", addRow);
});
