document.getElementById('toggleSecondaryContact').addEventListener('click', function() {
    var fields = document.getElementById('secondaryContactFields');
    var button = document.getElementById('toggleSecondaryContact');
    
    if (fields.style.display === 'none') {
      fields.style.display = 'block';
      button.innerHTML = '<i class="fas fa-minus"></i> Remove Secondary Contact';
    } else {
      fields.style.display = 'none';
      button.innerHTML = '<i class="fas fa-plus"></i> Add Secondary Contact';
    }
  });

  document.querySelectorAll('.custom-file-input').forEach(input => {
    input.addEventListener('change', function() {
      let fileName = this.files.length > 1 ? this.files.length + ' files selected' : this.files[0].name;
      this.nextElementSibling.textContent = fileName;
    });
  });

  function printPage() {
    let navbar = document.querySelector(".navbar-brand-wrapper");
    let menuWrapper = document.querySelector(".navbar-menu-wrapper");
    let settings = document.querySelector(".theme-setting-wrapper");
    let sidebar = document.querySelector(".sidebar");
    let printHeader = document.getElementById("printHeader");

    // Show the print header
    if (printHeader) printHeader.style.display = "block";

    // Hide UI elements before printing
    if (navbar) navbar.style.display = "none";
    if (menuWrapper) menuWrapper.style.display = "none";
    if (settings) settings.style.display = "none";
    if (sidebar) sidebar.style.display = "none";

    // Print
    window.print();

    // Restore UI elements after printing
    setTimeout(() => {
        if (printHeader) printHeader.style.display = "none";
        if (navbar) navbar.style.display = "flex";
        if (menuWrapper) menuWrapper.style.display = "flex";
        if (settings) settings.style.display = "block";
        if (sidebar) sidebar.style.display = "block";
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Get the "Show All Informations" button
    const showAllButton = document.getElementById('showAllInfo');
    
    // Add click event listener
    showAllButton.addEventListener('click', function() {
        // Get all collapsible forms
        const forms = [
            'personalDetailsForm',
            'vitalStatisticsForm',
            'medicalInfoForm',
            'medicalHistoryForm',
            'emergencyContactsForm',
            'uploadReportsForm',
            'appointmentHistoryForm'
        ];
        
        // Check if all forms are currently shown
        const allFormsShown = forms.every(formId => {
            const form = document.getElementById(formId);
            return form && form.classList.contains('show');
        });
        
        // Toggle forms based on current state
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                if (allFormsShown) {
                    // If all are shown, hide them
                    form.classList.remove('show');
                } else {
                    // If not all are shown, show them
                    form.classList.add('show');
                }
            }
        });
        
        // Close the dropdown menu after clicking (optional)
        const dropdownMenu = document.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.classList.remove('show');
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const isEditMode = document.getElementById('isEditMode');
    const formInputs = document.querySelectorAll('#patientForm input, #patientForm select, #patientForm textarea');
    
    // Store original values for cancel operation
    let originalValues = {};
    
    // Enable edit mode
    editBtn.addEventListener('click', function() {
        isEditMode.value = '1';
        
        // Store original values
        formInputs.forEach(input => {
            originalValues[input.id] = input.value;
            input.disabled = false;
        });
        
        // Show/hide buttons
        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'inline-block';
    });
    
    // Cancel edit mode
    cancelBtn.addEventListener('click', function() {
        isEditMode.value = '0';
        
        // Restore original values
        formInputs.forEach(input => {
            if (originalValues[input.id] !== undefined) {
                input.value = originalValues[input.id];
            }
            input.disabled = true;
        });
        
        // Show/hide buttons
        editBtn.style.display = 'inline-block';
        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
    });

    // Handle immunization records
    const addImmunizationBtn = document.querySelector('button.btn-outline-success');
    if (addImmunizationBtn) {
        addImmunizationBtn.addEventListener('click', function() {
            const tbody = document.querySelector('#immunization_records tbody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="text" class="form-control" name="vaccine[]" placeholder="Vaccine name" disabled></td>
                <td><input type="date" class="form-control" name="vaccine_date[]" disabled></td>
                <td><input type="text" class="form-control" name="administered_by[]" placeholder="Doctor/Hospital" disabled></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row" disabled><i class="fa fa-trash"></i></button></td>
            `;
            tbody.appendChild(newRow);
            
            // Enable new inputs if in edit mode
            if (isEditMode.value === '1') {
                const newInputs = newRow.querySelectorAll('input, button');
                newInputs.forEach(input => {
                    input.disabled = false;
                });
            }
            
            // Add event listener to remove button
            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.addEventListener('click', function() {
                tbody.removeChild(newRow);
            });
        });
    }
});


