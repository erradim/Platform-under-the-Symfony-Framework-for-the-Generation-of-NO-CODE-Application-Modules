function submitRelationForm() {
  let relationName = document.getElementById("relationNameInput").value;
  let relatedClass = document.getElementById("relatedClassInput").value;
  if (relationName === "" || relatedClass === "") {
    alert("Please fill in all fields");
    return;
  }
  let relationshipType = document.getElementById(
    "relationshipTypeSelect"
  ).value;

  if (relationshipType === "ManyToOne") {
    // Show ManyToOne modal and set the chosen class name
    document.getElementById("manyToOneChosenClass").textContent = relatedClass;
    $("#manyToOneModal").modal("show");
  } else if (relationshipType === "OneToMany") {
    // Show OneToMany modal and set the chosen class name
    document.getElementById("oneToManyChosenClass").textContent = relatedClass;
    $("#oneToManyModal").modal("show");
  } else if (relationshipType === "ManyToMany") {
    // Show ManyToMany modal and set the chosen class name
    document.getElementById("manyToManyChosenClass").textContent = relatedClass;
    $("#manyToManyModal").modal("show");
  } else if (relationshipType === "OneToOne") {
    // Show OneToOne modal and set the relation name and chosen class name
    document.getElementById("oneToOneRelationName").textContent = relationName;
    document.getElementById("oneToOneChosenClass").textContent = relatedClass;
    $("#oneToOneModal").modal("show");
  }
}

// ******************************************************** ManyToOne ************************************************************* //

let relationData = {}; // Object to store the relation data

function submitManyToOneForm() {
  let relationName = document.getElementById("relationNameInput").value;
  let relatedClass = document.getElementById("relatedClassInput").value;

  let nullable = document.querySelector(
    'input[name="nullableRadios"]:checked'
  ).value;
  let addProperty = document.querySelector(
    'input[name="addPropertyRadios"]:checked'
  ).value;

  relationData = {
    type: "ManyToOne",
    name: relationName,
    entity: relatedClass,
    nullable: nullable,
    addProperty: addProperty,
  };

  if (addProperty === "yes") {
    // Set the currentModal variable to the ID of the currently opened modal
    currentModal = "manyToOneModal";
    // Show the Field Name modal and set the chosen class name
    document.getElementById("fieldNameChosenClass").textContent = relatedClass;
    $("#fieldNameModal").modal("show");
  } else {
    // If "No" is selected for add property, submit the ManyToOne relation directly
    submitManyToOneOutput();
  }
}

let currentModal = null; // Variable to store the ID of the currently opened modal

function submitFieldNameForm() {
  // Get the field name input from the modal
  let fieldName = document.getElementById("fieldNameInput").value;
  if (currentModal === "manyToOneModal") {
    // Add the new field name to the relationData object
    relationData.fieldName = fieldName;
    // Submit the ManyToOne relation with the updated relationData
    submitManyToOneOutput();
  } else if (currentModal === "manyToManyModal") {
    // Get other data from the ManyToMany modal
    let relationName = document.getElementById("relationNameInput").value;
    let relatedClass = document.getElementById("relatedClassInput").value;
    let addProperty = document.querySelector(
      'input[name="addPropertyRadiosManyToMany"]:checked'
    ).value;

    $("#manyToManyModal").modal("hide");
    $("#relationModal").modal("hide");

    // Submit the ManyToMany relation with the updated data
    submitManyToManyOutput(relationName, relatedClass, addProperty, fieldName);
  } else if (currentModal === "oneToOneModal") {
    // Get other data from the OneToOne modal
    let relationName = document.getElementById("relationNameInput").value;
    let relatedClass = document.getElementById("relatedClassInput").value;
    let nullable = document.querySelector(
      'input[name="nullableRadiosOneToOne"]:checked'
    ).value;
    let addProperty = document.querySelector(
      'input[name="addPropertyRadiosOneToOne"]:checked'
    ).value;

    $("#oneToOneModal").modal("hide");
    $("#relationModal").modal("hide");

    // Submit the OneToOne relation with the updated data
    submitOneToOneOutput(
      relationName,
      relatedClass,
      nullable,
      addProperty,
      fieldName
    );
  }
  // Hide the Field Name modal
  $("#fieldNameModal").modal("hide");
}

function submitManyToOneOutput() {
  // Perform necessary actions with the submitted data
  let output = "<div class='output-box'>";
  output += "<h5>New Relation:</h5>";
  output += "<em>What type of relationship is this?</em>";
  output += "<p>Relationship Type: " + relationData.type + "</p>";
  output += "<em>What is the name of the relation property?</em>";
  output += "<p>Relation Name: " + relationData.name + "</p>";
  output += "<em>What class should this entity be related to?</em>";
  output += "<p>Related Class: " + relationData.entity + "</p>";

  if (relationData.type === "ManyToOne") {
    output +=
      "<em>Is the this." + relationData.name + " allowed to be null?</em>";
    output += "<p>Nullable: " + relationData.nullable + "</p>";
    output +=
      "<em>Do you want to add a property to " +
      relationData.entity +
      " so that you can access/update this entity's objects from it - e.g. $" +
      relationData.entity +
      "->getEntity()?</em>";
    output += "<p>Add Property: " + relationData.addProperty + "</p>";

    if (relationData.addProperty === "yes") {
      // Add the new field name inside the related class
      output += "<em>New field name inside " + relationData.entity + ":</em>";
      output += "<p>Field Name: " + relationData.fieldName + "</p>";
    }
  }

  // output += "<button class='btn btn-primary' onclick='updateOutputBox(this)'>Update</button>";
  output +=
    "<button class='btn btn-danger' onclick='deleteOutputBox(this)'>Delete</button>";
  output += "</div>";
  document.getElementById("outputContainer").innerHTML += output;
  // Hide the ManyToOne modal and the Relation modal
  $("#manyToOneModal").modal("hide");
  $("#relationModal").modal("hide");
}

// ******************************************************** OneToMany ************************************************************* //

function submitOneToManyForm() {
  let relationName = document.getElementById("relationNameInput").value;
  let relatedClass = document.getElementById("relatedClassInput").value;

  let fieldName = document.getElementById("oneToManyFieldNameInput").value;
  let nullable = document.querySelector(
    'input[name="nullableRadiosOneToMany"]:checked'
  ).value;

  relationData = {
    type: "OneToMany",
    name: relationName,
    entity: relatedClass,
    fieldName: fieldName,
    nullable: nullable,
  };

  if (nullable === "no") {
    // Show the OrphanRemoval modal only when nullable is "No"
    $("#orphanRemovalModal").modal("show");
  } else {
    // If "Yes" is selected for nullable, submit the OneToMany relation directly
    submitOneToManyOutput();
  }
}

function submitOrphanRemovalForm() {
  let orphanRemoval = document.querySelector(
    'input[name="orphanRemovalRadios"]:checked'
  ).value;

  // Add the OrphanRemoval value to the relationData object
  relationData.orphanRemoval = orphanRemoval;

  // Submit the OneToMany relation with the updated relationData
  submitOneToManyOutput();

  // Hide the OrphanRemoval modal
  $("#orphanRemovalModal").modal("hide");
}

function submitOneToManyOutput() {
  // Perform necessary actions with the submitted data
  let output = "<div class='output-box'>";
  output += "<h5>New Relation:</h5>";
  output += "<em>What type of relationship is this?</em>";
  output += "<p>Relationship Type: " + relationData.type + "</p>";
  output += "<em>What is the name of the relation property?</em>";
  output += "<p>Relation Name: " + relationData.name + "</p>";
  output += "<em>What class should this entity be related to?</em>";
  output += "<p>Related Class: " + relationData.entity + "</p>";
  output += "<em>New field name inside " + relationData.entity + ":</em>";
  output += "<p>Field Name: " + relationData.fieldName + "</p>";
  output +=
    "<em>Is the this." + relationData.entity + " allowed to be null?</em>";
  output += "<p>Nullable: " + relationData.nullable + "</p>";

  if (relationData.nullable === "no") {
    output +=
      "<em>Do you want to enable Orphan Removal for this relationship?</em>";
    output += "<p>Orphan Removal: " + relationData.orphanRemoval + "</p>";
  }

  // output += "<button class='btn btn-primary' onclick='updateOutputBox(this)'>Update</button>";
  output +=
    "<button class='btn btn-danger' onclick='deleteOutputBox(this)'>Delete</button>";
  output += "</div>";
  document.getElementById("outputContainer").innerHTML += output;
  $("#oneToManyModal").modal("hide");
  $("#relationModal").modal("hide");
}

// ******************************************************** ManyToMany ************************************************************ //

function submitManyToManyForm() {
  let relationName = document.getElementById("relationNameInput").value;
  let relatedClass = document.getElementById("relatedClassInput").value;

  let addProperty = document.querySelector(
    'input[name="addPropertyRadios"]:checked'
  ).value;

  if (addProperty === "yes") {
    // Show the Field Name modal and set the chosen class name
    document.getElementById("fieldNameChosenClass").textContent = relatedClass;
    $("#fieldNameModal").modal("show");
    // Set the currentModal variable to the ID of the currently opened modal
    currentModal = "manyToManyModal";
  } else {
    // If "No" is selected for addProperty, submit the ManyToMany relation directly
    submitManyToManyOutput(relationName, relatedClass, addProperty, null);
    $("#manyToManyModal").modal("hide");
    $("#relationModal").modal("hide");
  }
}

function submitManyToManyOutput(
  relationName,
  relatedClass,
  addProperty,
  fieldName
) {
  // Perform necessary actions with the submitted data
  let output = "<div class='output-box'>";
  output += "<h5>New Relation:</h5>";
  output += "<em>What type of relationship is this?</em>";
  output += "<p>Relationship Type: ManyToMany</p>";
  output += "<em>What is the name of the relation property?</em>";
  output += "<p>Relation Name: " + relationName + "</p>";
  output += "<em>What class should this entity be related to?</em>";
  output += "<p>Related Class: " + relatedClass + "</p>";
  output +=
    "<em>Do you want to add a property to " +
    relatedClass +
    " so that you can access/update this entity's objects from it - e.g. $" +
    relatedClass +
    "->getEntity()?</em>";
  output += "<p>Add Property: " + addProperty + "</p>";

  if (addProperty === "yes") {
    // Add the new field name inside the related class
    output += "<em>New field name inside " + relatedClass + ":</em>";
    output += "<p>Field Name: " + fieldName + "</p>";
  }

  // output += "<button class='btn btn-primary' onclick='updateOutputBox(this)'>Update</button>";
  output +=
    "<button class='btn btn-danger' onclick='deleteOutputBox(this)'>Delete</button>";
  output += "</div>";

  document.getElementById("outputContainer").innerHTML += output;
}

// ********************************************************* OneToOne ************************************************************* //

function submitOneToOneForm() {
  let relationName = document.getElementById("relationNameInput").value;
  let relatedClass = document.getElementById("relatedClassInput").value;

  let nullable = document.querySelector(
    'input[name="nullableRadios"]:checked'
  ).value;
  let addProperty = document.querySelector(
    'input[name="addPropertyRadios"]:checked'
  ).value;

  if (addProperty === "yes") {
    // Show the Field Name modal and set the chosen class name
    document.getElementById("fieldNameChosenClass").textContent = relatedClass;
    $("#fieldNameModal").modal("show");
    // Set the currentModal variable to the ID of the currently opened modal
    currentModal = "oneToOneModal";
  } else {
    // If "No" is selected for addProperty, submit the OneToOne relation directly
    submitOneToOneOutput(
      relationName,
      relatedClass,
      nullable,
      addProperty,
      null
    );
    $("#oneToOneModal").modal("hide");
    $("#relationModal").modal("hide");
  }
}

function submitOneToOneOutput(
  relationName,
  relatedClass,
  nullable,
  addProperty,
  fieldName
) {
  // Perform necessary actions with the submitted data
  let output = "<div class='output-box'>";
  output += "<h5>New Relation:</h5>";
  output += "<em>What type of relationship is this?</em>";
  output += "<p>Relationship Type: OneToOne</p>";
  output += "<em>What is the name of the relation property?</em>";
  output += "<p>Relation Name: " + relationName + "</p>";
  output += "<em>What class should this entity be related to?</em>";
  output += "<p>Related Class: " + relatedClass + "</p>";
  output += "<em>Is the this." + relationName + " allowed to be null?</em>";
  output += "<p>Nullable: " + nullable + "</p>";
  output +=
    "<em>Do you want to add a property to " +
    relatedClass +
    " so that you can access/update this entity's objects from it - e.g. $" +
    relatedClass +
    "->getEntity()?</em>";
  output += "<p>Add Property: " + addProperty + "</p>";

  if (addProperty === "yes") {
    // Add the new field name inside the related class
    output += "<em>New field name inside " + relatedClass + ":</em>";
    output += "<p>Field Name: " + fieldName + "</p>";
  }

  // output += "<button class='btn btn-primary' onclick='updateOutputBox(this)'>Update</button>";
  output +=
    "<button class='btn btn-danger' onclick='deleteOutputBox(this)'>Delete</button>";
  output += "</div>";

  document.getElementById("outputContainer").innerHTML += output;
}

// ******************************************************************************************************************************** //

function updateOutputBox(button) {
  //let outputBox = button.parentNode;
  // Perform necessary actions to update the output box
}

function deleteOutputBox(button) {
  let outputBox = button.parentNode;
  outputBox.parentNode.removeChild(outputBox);
}
