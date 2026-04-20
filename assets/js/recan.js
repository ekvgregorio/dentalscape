document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-reschedule").forEach(button => {
        button.addEventListener("click", function () {
            let appointmentId = this.getAttribute("data-id");
            let appointmentDate = this.getAttribute("data-date");

            document.getElementById("reschedule_id").value = appointmentId;
            document.getElementById("new_date").value = appointmentDate;

            document.getElementById("new_date").dispatchEvent(new Event("change"));

            $("#rescheduleModal").modal("show");
        });
    });

    // Handle modal close button
    document.querySelector("#rescheduleModal .close").addEventListener("click", function () {
        $("#rescheduleModal").modal("hide");
    });

    // Handle clicking outside the modal to close it
    $("#rescheduleModal").on("hidden.bs.modal", function () {
        document.getElementById("rescheduleForm").reset(); // Reset form on close
    });

    // Fetch available time slots when date changes
    document.getElementById("new_date").addEventListener("change", function () {
        let selectedDate = this.value;
        let timeDropdown = document.getElementById("new_time");

        // Reset previous options
        timeDropdown.innerHTML = '<option value="">Loading...</option>';

        // Fetch available slots from PHP
        fetch(`fetch_available_times.php?date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                timeDropdown.innerHTML = ''; // Clear previous options
                if (data.length > 0) {
                    data.forEach(time => {
                        let option = document.createElement("option");
                        option.value = time;
                        option.textContent = time;
                        timeDropdown.appendChild(option);
                    });
                } else {
                    timeDropdown.innerHTML = '<option value="">No available slots</option>';
                }
            })
            .catch(error => {
                console.error("Error fetching time slots:", error);
                timeDropdown.innerHTML = '<option value="">Error loading slots</option>';
            });
    });

    // Handle reschedule form submission
    document.getElementById("rescheduleForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let appointmentId = document.getElementById("reschedule_id").value;
        let newDate = document.getElementById("new_date").value;
        let newTime = document.getElementById("new_time").value;

        if (!newDate || !newTime) {
            alert("Please select a date and time.");
            return;
        }

        // Send request to reschedule
        fetch("user_process.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=reschedule&id=${appointmentId}&new_date=${newDate}&new_time=${newTime}`
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "Success") {
                alert("Appointment successfully rescheduled!");
                $("#rescheduleModal").modal("hide"); // Hide modal after success
                location.reload();
            } else {
                alert(data); // Show error message from PHP
            }
        })
        .catch(error => {
            console.error("Error rescheduling:", error);
            alert("An error occurred while rescheduling. Please try again.");
        });
    });

    // Handle cancel button click
    document.querySelectorAll(".btn-cancel").forEach(button => {
        button.addEventListener("click", function () {
            let appointmentId = this.getAttribute("data-id");

            if (confirm("Are you sure you want to cancel this appointment?")) {
                fetch("user_process.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `action=cancel&id=${appointmentId}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "Success") {
                        alert("Appointment successfully canceled!");
                        location.reload();
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error canceling:", error);
                    alert("An error occurred while canceling. Please try again.");
                });
            }
        });
    });
});

