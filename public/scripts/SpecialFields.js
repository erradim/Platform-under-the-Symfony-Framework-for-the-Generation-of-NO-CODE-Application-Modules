function updateAttributeDetailsModal() {
  let select = document.getElementById("attributeTypeSelect");
  let selectedValue = select.options[select.selectedIndex].value;
  let selectedText = select.options[select.selectedIndex].text;
  let selectedAttributeType = document.getElementById("selectedAttributeType");

  selectedAttributeType.innerHTML =
    '<option value="' +
    selectedValue +
    '" selected>' +
    selectedText +
    "</option>";

  let precisionField = document.getElementById("precisionField");
  let scaleField = document.getElementById("scaleField");
  let precisionSelect = precisionField.querySelector("select");
  let scaleSelect = scaleField.querySelector("select");
  let precisionScaleRow = document.getElementById("precisionScaleRow");
  let fieldLengthField = document.getElementById("fieldLengthField");

  if (selectedValue === "decimal") {
    precisionScaleRow.style.display = "block";
    fieldLengthField.style.display = "none";
    generateOptions(precisionSelect, 0, 65, 10);
    generateOptions(scaleSelect, 0, 30, 0);
  } else if (selectedValue === "string") {
    precisionScaleRow.style.display = "none";
    fieldLengthField.style.display = "block";
  } else {
    precisionScaleRow.style.display = "none";
    fieldLengthField.style.display = "none";
  }
}

function generateOptions(select, min, max, selectedValue) {
  select.innerHTML = ""; // Clear previous options

  for (let i = min; i <= max; i++) {
    let option = document.createElement("option");
    option.value = i;
    option.textContent = i;
    if (i === selectedValue) {
      option.selected = true;
    }
    select.appendChild(option);
  }
}
