function createModule(event) {
  "use strict";

  // Prevent form submission
  event.preventDefault();

  // Get the form element
  const form = document.querySelector(".needs-validation");

  // Prevent form submission if invalid fields exist
  if (!form.checkValidity()) {
    event.stopPropagation();
    form.classList.add("was-validated");
    return;
  }

  // Create JSON object with module data
  const moduleData = {
    name: sanitizeString(document.getElementById("module-name").value),
    description: document.getElementById("module-description").value,
  };

  // Send JSON object to Symfony controller
  fetch("/createModule", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(moduleData),
  })
    .then((response) => {
      // Handle response from Symfony controller
      if (response.ok) {
        // Module created successfully
        console.log("Module created!");
        alert("Module created!");

        // Clear the form fields
        document.getElementById("module-name").value = "";
        document.getElementById("module-description").value = "";
      } else {
        // Module creation failed
        console.error("Failed to create module.");
        alert("Failed to create module.");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
