function assignEntityToAttribute() {
  let entity = document.getElementById("innerEntityNameInput").value;
  entity = entity.charAt(0).toUpperCase() + entity.slice(1).toLowerCase();
  entity = sanitizeString(entity);

  let checkedCheckboxes = document.querySelectorAll(
    "#attributeCheckboxes input:checked"
  );

  // Go through the table rows and update the entity name for the selected attributes with the new entity name
  let attributeRows = document.querySelectorAll("#attributeTableBody tr");
  attributeRows.forEach(function (row) {
    checkedCheckboxes.forEach(function (checkbox) {
      if (row.children[0].textContent === checkbox.value) {
        row.children[7].textContent = entity;
        createOneToOneRelationship(row.children[0].textContent, entity);
      }
    });
  });

  // Clear the form fields
  document.getElementById("innerEntityNameInput").value = "";

  // Close the modal
  let modal = bootstrap.Modal.getInstance(
    document.getElementById("entityModal")
  );
  modal.hide();
}

function createOneToOneRelationship(attributeName, entityName) {
  // Update the OneToOne relationship modal with the selected attribute and inner entity
  document.getElementById("relationNameInput").value =
    entityName.charAt(0).toUpperCase() + entityName.slice(1).toLowerCase();

  var selectElement = document.getElementById("relatedClassInput");

  // Check if the option already exists
  var optionExists = false;
  for (var i = 0, n = selectElement.options.length; i < n; i++) {
    if (selectElement.options[i].value === entityName) {
      optionExists = true;
      break;
    }
  }

  if (!optionExists) {
    // Create an option element
    var option = document.createElement("option");
    option.value =
      entityName.charAt(0).toUpperCase() + entityName.slice(1).toLowerCase();
    option.text =
      entityName.charAt(0).toUpperCase() + entityName.slice(1).toLowerCase();

    // Append the option to the select element
    selectElement.appendChild(option);

    // Set the newly added option as selected
    option.selected = true;

    // Show the OneToOne relationship modal
    $("#oneToOneModal").modal("show");
  }
}

function addAttributeToEntityModal(attributeName) {
  let checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.name = "attributes";
  checkbox.value = attributeName;

  let label = document.createElement("label");
  label.appendChild(checkbox);
  label.appendChild(document.createTextNode(attributeName));

  let container = document.getElementById("attributeCheckboxes");
  container.appendChild(label);
  container.appendChild(document.createElement("br"));
}

function populateEntityModal() {
  let attributeCheckboxesContainer = document.getElementById(
    "attributeCheckboxes"
  );
  attributeCheckboxesContainer.innerHTML = ""; // Clear the existing checkboxes

  let attributeRows = document.querySelectorAll("#attributeTableBody tr");

  attributeRows.forEach(function (row) {
    let attributeName = row.cells[0].textContent;
    addAttributeToEntityModal(attributeName);
  });
}
