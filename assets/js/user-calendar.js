    let currentDate = new Date();
    let selectedDate = new Date();

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Update title
        document.getElementById('calendar-title').textContent = `${months[month]} ${year}`;

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        const calendarGrid = document.getElementById('calendar-grid');
        calendarGrid.querySelectorAll('.calendar-day').forEach(el => el.remove());

        for (let i = 0; i < startingDayOfWeek; i++) {
            const emptyDay = document.createElement('div');
            emptyDay.className = 'calendar-day';
            calendarGrid.appendChild(emptyDay);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.className = 'calendar-day';
            dayElement.textContent = day;

            dayElement.onclick = () => selectDate(day);

            const today = new Date();
            if (
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                dayElement.classList.add('today');
            }

            if (
                day === selectedDate.getDate() &&
                month === selectedDate.getMonth() &&
                year === selectedDate.getFullYear()
            ) {
                dayElement.classList.add('selected');
            }

            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            if (events[dateStr]) {
                dayElement.classList.add('has-event');
            }

            calendarGrid.appendChild(dayElement);
        }
    }

    function selectDate(day) {
        selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
        renderCalendar();
        updateEventsPanel();
    }

    function updateEventsPanel() {
        const dateStr = `${selectedDate.getFullYear()}-${String(selectedDate.getMonth() + 1).padStart(2, '0')}-${String(selectedDate.getDate()).padStart(2, '0')}`;
        const dayEvents = events[dateStr] || [];

        document.getElementById('events-date').textContent = `Events for ${selectedDate.toLocaleDateString()}`;
        const eventsList = document.getElementById('events-list');

        if (dayEvents.length === 0) {
          eventsList.innerHTML = `
              <div class="no-events" style="padding: 1em; border: 1px solid #ccc; border-radius: 8px; text-align: center; color: #666;">
                  <div style="font-size: 2em; margin-bottom: 20px;">🗓️</div>
                  <strong>No Appointments</strong><br>
                  You're all clear for this date! 
              </div>
          `;

            return;
        }

        eventsList.innerHTML = dayEvents.map(event => `
            <div class="event-item">
                <div class="event-title">
                    <span>${event.title}</span>
                    <div class="event-type ${event.type}"></div>
                </div>
                <div class="event-details">
                    <div class="event-detail">
                        <div class="event-icon">🕙</div>
                        <span>${event.time}</span>
                    </div>
                    <div class="event-detail">
                        <div class="event-icon">👥</div>
                        <span>${event.attendees} attendee</span>
                    </div>
                    <div class="event-detail">
                        <div class="event-icon">📍</div>
                        <span>${event.location}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function previousMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
        updateEventsPanel();
    }

    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
        updateEventsPanel();
    }
    renderCalendar();
    updateEventsPanel();