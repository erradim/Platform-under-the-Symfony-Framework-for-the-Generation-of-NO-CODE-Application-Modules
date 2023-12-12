function CreateAttribute() {
  let attributeType = document.getElementById("selectedAttributeType").value;
  let attributeName = document.getElementById("attributeNameInput").value;
  let nullable = document.getElementById("nullableYesRadio").checked
    ? "Yes"
    : "No";

  // Validate attribute name
  attributeName = sanitizeString(attributeName);

  let fieldLength =
    attributeType === "string"
      ? document.getElementById("fieldLengthInput").value
      : "-";
  let precision =
    attributeType === "decimal"
      ? document.getElementById("precisionSelect").value
      : "-";
  let scale =
    attributeType === "decimal"
      ? document.getElementById("scaleSelect").value
      : "-";

  let tableBody = document.getElementById("attributeTableBody");

  // Create new row
  let newRow = document.createElement("tr");

  // Add attribute name cell
  let attributeNameCell = document.createElement("td");
  attributeNameCell.textContent = attributeName;
  newRow.appendChild(attributeNameCell);

  // Add attribute type cell
  let attributeTypeCell = document.createElement("td");
  attributeTypeCell.textContent = attributeType;
  newRow.appendChild(attributeTypeCell);

  // Add nullable cell
  let nullableCell = document.createElement("td");
  nullableCell.textContent = nullable;
  newRow.appendChild(nullableCell);

  // Add length cell
  let lengthCell = document.createElement("td");
  lengthCell.textContent = attributeType === "string" ? fieldLength : "-";
  newRow.appendChild(lengthCell);

  // Add precision cell
  let precisionCell = document.createElement("td");
  precisionCell.textContent = attributeType === "decimal" ? precision : "-";
  newRow.appendChild(precisionCell);

  // Add scale cell
  let scaleCell = document.createElement("td");
  scaleCell.textContent = attributeType === "decimal" ? scale : "-";
  newRow.appendChild(scaleCell);

  // Add actions cell
  let actionsCell = document.createElement("td");

  // Add update button
  let updateButton = document.createElement("button");
  updateButton.className = "btn btn-sm btn-primary";
  updateButton.textContent = "Update";
  updateButton.addEventListener("click", function () {
    openUpdateModal(
      attributeName,
      attributeType,
      nullable,
      fieldLength,
      precision,
      scale
    );
  });

  // To be implemented...
  //actionsCell.appendChild(updateButton);

  // Add delete button
  let deleteButton = document.createElement("button");
  deleteButton.className = "btn btn-sm btn-danger ms-1";
  deleteButton.textContent = "Delete";
  deleteButton.addEventListener("click", function () {
    deleteRow(newRow);
  });
  actionsCell.appendChild(deleteButton);

  newRow.appendChild(actionsCell);

  //Check if the attribute name already exists
  let attributeNames = document.querySelectorAll(
    "#attributeTableBody tr td:first-child"
  );
  let attributeNamesArray = Array.from(attributeNames);
  let attributeNamesText = attributeNamesArray.map(function (attributeName) {
    return attributeName.textContent;
  });
  let attributeNameExists = attributeNamesText.includes(attributeName);

  if (attributeNameExists) {
    alert("Attribute name already exists");
  }

  // Append row to the table body
  if (attributeName && !attributeNameExists) {
    tableBody.appendChild(newRow);
  }

  //tableBody.appendChild(newRow);

  let EntityCell = document.createElement("td");
  EntityCell.textContent = "";
  EntityCell.value = null;
  newRow.appendChild(EntityCell);
  /*
    // Clear form fields
    attributeNameInput.value = "";
    nullableYesRadio.checked = true;
    fieldLengthInput.value = 255;
    precisionSelect.value = 10;
    scaleSelect.value = 0;
    */
  // Close the modal
  let attributeModal = document.getElementById("attributeModal");
  let modal = bootstrap.Modal.getInstance(attributeModal);
  modal.hide();
}
/*
  function openUpdateModal(attributeName, attributeType, nullable, fieldLength, precision, scale) {
    let selectedAttributeTypeHidden = document.getElementById("selectedAttributeTypeHidden");
    let selectedAttributeType = document.getElementById("selectedAttributeType");
    let attributeNameInput = document.getElementById("attributeNameInput");
    let nullableYesRadio = document.getElementById("nullableYesRadio");
    let nullableNoRadio = document.getElementById("nullableNoRadio");
    let fieldLengthInput = document.getElementById("fieldLengthInput");
    let precisionSelect = document.getElementById("precisionSelect");
    let scaleSelect = document.getElementById("scaleSelect");

    selectedAttributeTypeHidden.value = attributeType;
    selectedAttributeType.innerHTML = '<option value="' + attributeType + '" selected>' + attributeType + '</option>';
    attributeNameInput.value = attributeName;
    nullableYesRadio.checked = nullable === "Yes";
    nullableNoRadio.checked = nullable === "No";
    fieldLengthInput.value = attributeType === "string" ? fieldLength : 255;
    precisionSelect.value = attributeType === "decimal" ? precision : 10;
    scaleSelect.value = attributeType === "decimal" ? scale : 0;

    let attributeDetailsModal = document.getElementById("attributeDetailsModal");
    let modal = bootstrap.Modal.getInstance(attributeDetailsModal);
    modal.show();
  }

  function updateAttribute() {
    let selectedAttributeTypeHidden = document.getElementById("selectedAttributeTypeHidden");
    let selectedAttributeType = document.getElementById("selectedAttributeType");
    let attributeNameInput = document.getElementById("attributeNameInput");
    let nullableYesRadio = document.getElementById("nullableYesRadio");
    let fieldLengthInput = document.getElementById("fieldLengthInput");
    let precisionSelect = document.getElementById("precisionSelect");
    let scaleSelect = document.getElementById("scaleSelect");

    let attributeType = selectedAttributeTypeHidden.value;
    let attributeName = attributeNameInput.value;
    let nullable = nullableYesRadio.checked ? "Yes" : "No";
    let fieldLength = fieldLengthInput.value;
    let precision = precisionSelect.value;
    let scale = scaleSelect.value;

    let selectedRow = document.querySelector("#attributeTableBody tr.selected");

    // Update row cells with new values
    selectedRow.children[0].textContent = attributeName;
    selectedRow.children[1].textContent = attributeType;
    selectedRow.children[2].textContent = nullable;
    selectedRow.children[3].textContent = attributeType === "string" ? fieldLength : "-";
    selectedRow.children[4].textContent = attributeType === "decimal" ? precision : "-";
    selectedRow.children[5].textContent = attributeType === "decimal" ? scale : "-";

    // Clear form fields
    attributeNameInput.value = "";
    nullableYesRadio.checked = true;
    fieldLengthInput.value = 255;
    precisionSelect.value = 10;
    scaleSelect.value = 0;

    // Close the modal
    let attributeDetailsModal = document.getElementById("attributeDetailsModal");
    let modal = bootstrap.Modal.getInstance(attributeDetailsModal);
    modal.hide();
  }
  */
function deleteRow(row) {
  row.remove();
}
