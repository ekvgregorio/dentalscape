
document.addEventListener("DOMContentLoaded", function () {

    /* ---------- Doctor Schedules ---------- */
    const doctorSchedules = {
        "Dr. Marañon": [2],       // Tue
        "Dr. Pinuela": [6],       // Sat
        "Dr. Villaret": [4, 5, 7],// Thu, Fri, Sun
        "Dr. Savari": [1, 3]      // Mon, Wed
    };

    const dayNames = {
        1: "Monday",
        2: "Tuesday",
        3: "Wednesday",
        4: "Thursday",
        5: "Friday",
        6: "Saturday",
        7: "Sunday"
    };

    const doctorSelect = document.getElementById("doctor");
    const dateInput = document.getElementById("appointment_date");
    const dateHint = document.getElementById("date-hint") || document.createElement("small");
    const scheduleHint = document.getElementById("doctor-schedule") || document.createElement("small");

    if (!dateHint.id) {
        dateHint.id = "date-hint";
        dateHint.className = "form-text text-muted";
        dateInput.parentNode.appendChild(dateHint);
    }

    if (!scheduleHint.id) {
        scheduleHint.id = "doctor-schedule";
        scheduleHint.className = "form-text text-muted";
        doctorSelect.parentNode.appendChild(scheduleHint);
    }

    doctorSelect.addEventListener("change", function () {
        dateInput.value = "";
        if (this.value) {
            dateInput.disabled = false;
            dateHint.style.display = "none";
            const allowedDays = doctorSchedules[this.value];
            scheduleHint.textContent = `${this.value} is available on: ${allowedDays.map(d => dayNames[d]).join(", ")}`;
        } else {
            dateInput.disabled = true;
            dateHint.style.display = "inline";
            scheduleHint.textContent = "Please select a doctor to see their schedule.";
        }
    });

    dateInput.addEventListener("input", function () {
        const selectedDoctor = doctorSelect.value;
        if (!selectedDoctor) return;

        const allowedDays = doctorSchedules[selectedDoctor];
        const chosenDate = new Date(this.value);
        const jsDay = chosenDate.getDay();
        const phpDay = jsDay === 0 ? 7 : jsDay;

        if (!allowedDays.includes(phpDay)) {
            alert(`${selectedDoctor} is only available on: ${allowedDays.map(d => dayNames[d]).join(", ")}`);
            this.value = "";
        }
    });

    /* ---------- Service Options ---------- */
    const serviceOptions = {
        "Consultation": ["Initial Consultation", "Second Opinion"], //30
        "Follow-up": ["Post-Treatment Follow-up", "Braces Adjustment Follow-up"], //30
        "General Dentistry": ["General Check-up", "Routine Cleaning"],
        "Dental Braces": ["Braces Installation", "Braces Adjustment", "Retainer Fitting"],
        "Partial/Full Dentures": ["Partial Dentures", "Full Dentures", "Denture Adjustment"],
        "Veneers": ["Porcelain Veneers", "Composite Veneers"],
        "Crowns and Bridges": ["Dental Crown", "Dental Bridge"],
        "Tooth Extraction": ["Simple Extraction", "Surgical Extraction"],
        "Restoration": ["Composite Filling", "Amalgam Filling"],
        "Oral Prophylaxis": ["Basic Cleaning", "Deep Cleaning"],
        "Teeth Whitening": ["In-office Whitening", "Take-home Whitening Kit"],
        "Emergency": ["Toothache Treatment", "Broken Tooth Repair"],
        "Other": ["Special Request"]
    };

    const purposeSelect = document.getElementById('purpose_of_appointment');
    const serviceTypeSelect = document.getElementById('service_type');

    purposeSelect.addEventListener('change', function () {
        serviceTypeSelect.innerHTML = '<option value="">Select Service Type</option>';
        if (serviceOptions[this.value]) {
            serviceOptions[this.value].forEach(service => {
                const option = document.createElement('option');
                option.value = service;
                option.textContent = service;
                serviceTypeSelect.appendChild(option);
            });
        }
    });

    /* ---------- Fetch Available Times ---------- */
    function setDropdownMessage(dropdown, message) {
        dropdown.innerHTML = `<option value="">${message}</option>`;
    }

    function fetchAvailableTimes(dateInputId, timeDropdownId, serviceInputId) {
        const selectedDate = document.getElementById(dateInputId).value;
        const selectedService = document.getElementById(serviceInputId).value;
        const timeDropdown = document.getElementById(timeDropdownId);

        if (!selectedDate) {
            setDropdownMessage(timeDropdown, "Select a date first");
            return;
        }
        if (!selectedService) {
            setDropdownMessage(timeDropdown, "Select a service first");
            return;
        }

        setDropdownMessage(timeDropdown, "Loading...");

        fetch(`/dentalscape/user/fetch_available_times.php?date=${selectedDate}&service_type=${encodeURIComponent(selectedService)}`)
            .then(response => response.json())
            .then(data => {
                timeDropdown.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeDropdown.appendChild(option);
                    });
                } else {
                    setDropdownMessage(timeDropdown, "No available slots");
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                setDropdownMessage(timeDropdown, "Error loading slots");
            });
    }

    const dateInputId = "appointment_date";
    const timeDropdownId = "appointment_time";
    const serviceInputId = "service_type";

    document.getElementById(dateInputId).addEventListener("change", () => {
        fetchAvailableTimes(dateInputId, timeDropdownId, serviceInputId);
    });

    document.getElementById(serviceInputId).addEventListener("change", () => {
        fetchAvailableTimes(dateInputId, timeDropdownId, serviceInputId);
    });

});
