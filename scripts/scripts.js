function addRow() {
  const table = document
    .getElementById("medicamentoTable")
    .getElementsByTagName("tbody")[0];
  const rowCount = table.rows.length;
  if (rowCount >= 10) {
    alert("No se pueden agregar más de 10 medicamentos.");
    return;
  }
  const row = table.insertRow();
  const itemNumber = rowCount + 1;
  row.innerHTML = `
        <td>${itemNumber}</td>
        <td><input type="text" name="medicamento${itemNumber}"></td>
        <td><input type="text" name="formaFarmaceutica${itemNumber}"></td>
        <td><input type="text" name="viaAdministracion${itemNumber}"></td>
        <td><input type="text" name="dosisFrecuencia${itemNumber}"></td>
        <td><input type="text" name="cantidad${itemNumber}"></td>
        <td class="actions">
            <button type="button" onclick="deleteRow(this)">❌</button>
        </td>
    `;
}

function editRow(button) {
  const row = button.closest("tr");
  alert("Editar la fila: " + row.rowIndex);
}

function deleteRow(button) {
  const row = button.closest("tr");
  row.remove();
  updateRowNumbers();
}

function updateRowNumbers() {
  const rows = document.querySelectorAll("#medicamentoTable tbody tr");
  rows.forEach((row, index) => {
    row.cells[0].textContent = index + 1;
  });
}
