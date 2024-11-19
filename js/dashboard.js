document.addEventListener('DOMContentLoaded', () => {
    const calendar = document.getElementById('calendar');
    const timeSlots = document.getElementById('timeSlots');
    const popupForm = document.getElementById('popupForm');
    const slotForm = document.getElementById('slotForm');
    const cancelBtn = document.getElementById('cancelBtn');
    const addSlotBtn = document.getElementById('addSlotBtn');
    const selectedDateElem = document.getElementById('selectedDate');
    const slotList = document.getElementById('slotList');
    const slotDate = document.getElementById('slotDate');

    // Generate a simple calendar (Static Example for November 2024)
    for (let i = 1; i <= 30; i++) {
        const day = document.createElement('div');
        day.textContent = i;
        day.classList.add('day');
        day.addEventListener('click', () => {
            const formattedDate = `2024-11-${i < 10 ? '0' : ''}${i}`;
            timeSlots.classList.remove('hidden');
            selectedDateElem.textContent = `Slots for ${formattedDate}`;
            slotDate.value = formattedDate;

            // Fetch and display slots for the selected date
            fetchSlots(formattedDate);
        });
        calendar.appendChild(day);
    }

    // Show the popup form
    addSlotBtn.addEventListener('click', () => {
        popupForm.classList.remove('hidden');
    });

    // Hide the popup form
    cancelBtn.addEventListener('click', () => {
        popupForm.classList.add('hidden');
        slotForm.reset();
    });

    // Handle form submission
    slotForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Serialize form data
        const formData = new FormData(slotForm);

        // AJAX Request to save the slot
        fetch('slot_handler.php', {
            method: 'POST',
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert(data.message);
                popupForm.classList.add('hidden');
                slotForm.reset();

                // Add the new slot to the list
                fetchSlots(slotDate.value); // Refresh the slot list
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('An error occurred while saving the slot.');
        });
    });

    // Fetch and display slots for a specific date
    function fetchSlots(date) {
        fetch(`get_slots.php?date=${date}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    slotList.innerHTML = ''; // Clear previous slots
                    data.slots.forEach((slot) => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${slot.time_slot} - Truck: ${slot.truck_name} - Learner: ${slot.learner_name}`;
                        slotList.appendChild(listItem);
                    });
                } else {
                    slotList.innerHTML = '<li>No slots found for this date.</li>';
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                slotList.innerHTML = '<li>Unable to fetch slots. Try again later.</li>';
            });
    }
});
