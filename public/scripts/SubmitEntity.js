$(document).ready(function () {
  // Event listener for the submit button
  $("#submitBtn").click(function () {
    // Get the entity name and description
    var entityName = $("#entityNameInput").val();
    var entityName = sanitizeString(entityName);

    var moduleName = $("#moduleNameSelect").val();

    // ************************************************************************************************************************** //

    // Get attribute blocks
    var attributeRows = $("#attributeTableBody tr");

    // Create an array to store attributes
    var attributes = [];

    // Iterate over each attribute row
    attributeRows.each(function () {
      var attribute = {};

      // Get attribute details from the row cells
      attribute.name = $(this).find("td:eq(0)").text();
      attribute.type = $(this).find("td:eq(1)").text();
      attribute.nullable = $(this).find("td:eq(2)").text();
      attribute.fieldLength = $(this).find("td:eq(3)").text();
      attribute.precision = $(this).find("td:eq(4)").text();
      attribute.scale = $(this).find("td:eq(5)").text();
      attribute.entity = $(this).find("td:eq(7)").text();

      // Add attribute to the array
      attributes.push(attribute);
    });

    // ************************************************************************************************************************** //

    // Get relation output boxes
    var relationBoxes = $(".output-box");

    // Create an array to store relations
    var relations = [];

    // Iterate over each relation output box
    relationBoxes.each(function () {
      var relation = {};

      // Get relation details from the box content
      var relationshipType = $(this)
        .find("p:contains('Relationship Type')")
        .text()
        .split(":")[1]
        .trim();
      var relationName = $(this)
        .find("p:contains('Relation Name')")
        .text()
        .split(":")[1]
        .trim();
      var relatedClass = $(this)
        .find("p:contains('Related Class')")
        .text()
        .split(":")[1]
        .trim();

      relation.type = relationshipType;
      relation.name = relationName;
      relation.entity = relatedClass;

      // Additional attributes based on relationship type
      if (relationshipType === "ManyToOne") {
        var nullableElement = $(this).find("p:contains('Nullable')");
        if (nullableElement.length > 0) {
          relation.nullable = nullableElement.text().split(":")[1].trim();
        }
        var addPropertyElement = $(this).find("p:contains('Add Property')");
        if (addPropertyElement.length > 0) {
          relation.addProperty = addPropertyElement.text().split(":")[1].trim();
        }
        var fieldNameElement = $(this).find("p:contains('Field Name')");
        if (fieldNameElement.length > 0) {
          relation.fieldName = fieldNameElement.text().split(":")[1].trim();
        }
      } else if (relationshipType === "OneToMany") {
        var fieldNameElement = $(this).find("p:contains('Field Name')");
        if (fieldNameElement.length > 0) {
          relation.fieldName = fieldNameElement.text().split(":")[1].trim();
        }
        var nullableElement = $(this).find("p:contains('Nullable')");
        if (nullableElement.length > 0) {
          relation.nullable = nullableElement.text().split(":")[1].trim();
        }
        var orphanRemoval = $(this).find("p:contains('Orphan Removal')");
        if (orphanRemoval.length > 0) {
          relation.orphanRemoval = orphanRemoval.text().split(":")[1].trim();
        }
      } else if (relationshipType === "ManyToMany") {
        var addPropertyElement = $(this).find("p:contains('Add Property')");
        if (addPropertyElement.length > 0) {
          relation.addProperty = addPropertyElement.text().split(":")[1].trim();
        }
        var fieldNameElement = $(this).find("p:contains('Field Name')");
        if (fieldNameElement.length > 0) {
          relation.fieldName = fieldNameElement.text().split(":")[1].trim();
        }
      } else if (relationshipType === "OneToOne") {
        var nullableElement = $(this).find("p:contains('Nullable')");
        if (nullableElement.length > 0) {
          relation.nullable = nullableElement.text().split(":")[1].trim();
        }
        var addPropertyElement = $(this).find("p:contains('Add Property')");
        if (addPropertyElement.length > 0) {
          relation.addProperty = addPropertyElement.text().split(":")[1].trim();
        }
        var fieldNameElement = $(this).find("p:contains('Field Name')");
        if (fieldNameElement.length > 0) {
          relation.fieldName = fieldNameElement.text().split(":")[1].trim();
        }
      }

      // Add relation to the array
      relations.push(relation);
    });

    // ************************************************************************************************************************** //

    if (entityName.length > 0) {
      // Create the JSON object
      var entityJson = {
        Entity: {
          name: entityName,
          moduleName: moduleName,
          attributes: attributes,
          relations: relations,
        },
      };
    } else {
      alert("Please fill all the fields");
      return;
    }

    // Log the JSON object to the console (for testing)
    console.log(JSON.stringify(entityJson));

    // Get the spinner element
    var spinner = document.getElementById("spinner");

    // Add the spin class to start the spinner animation
    spinner.classList.add("spin");

    // Send the JSON data to the Symfony controller using the Fetch API
    fetch("/createEntity", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(entityJson),
    })
      .then((response) => {
        // Check if the response is successful
        if (response.ok) {
          // If the response status is 200 (OK), consider it a successful creation
          return response.json(); // Parse the response body as JSON and return it
        } else {
          // If the response status is not OK, handle the error
          throw new Error("Failed to create entity. Please try again.");
        }
      })
      .then((data) => {
        // Handle the JSON data received from the server
        console.log("Successfully created entity:", data);

        // Clear the form fields (uncomment these lines if needed)
        // document.getElementById("entityNameInput").value = "";
        // document.getElementById("attributeTableBody").innerHTML = "";

        // Show a success message to the user (you can use other UI elements like a toast or modal)
        alert("Successfully created the entity.");
      })
      .catch((error) => {
        // Handle any error that occurs during the fetch or JSON parsing
        console.error("Error creating entity:", error);

        // Show an error message to the user (you can use other UI elements like a toast or modal)
        alert("Failed to create entity. Please try again.");
      })
      .finally(() => {
        // Remove the spin class to stop the spinner animation
        spinner.classList.remove("spin");
      });
  });
});
