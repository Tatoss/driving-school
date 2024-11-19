<?php

include 'header.php'; 


// Function to fetch all scheduled slots
function getScheduledSlots($db) {
    $query = "SELECT s.*, l.full_name, l.receipt_number, t.truck_name 
              FROM schedule s 
              JOIN learners l ON s.learner_id = l.id 
              JOIN trucks t ON s.truck_id = t.id";
    $result = $db->query($query);
    $slots = [];
    
    while($row = $result->fetch_assoc()) {
        $slots[] = [
            'id' => $row['id'],
            'title' => $row['truck_name'] . ' - ' . $row['full_name'],
            'start' => $row['schedule_date'] . 'T' . $row['time_slot'],
            'truck_name' => $row['truck_name'],
            'learner_name' => $row['full_name'],
            'receipt_number' => $row['receipt_number'],
            'code' => $row['code']
        ];
    }
    return json_encode($slots);
}

// Get all learners for the dropdown
$learners_query = "SELECT * FROM learners";
$learners_result = $db->query($learners_query);

// Get all trucks for the dropdown
$trucks_query = "SELECT * FROM trucks";
$trucks_result = $db->query($trucks_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="schedule-ajax-script.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .time-slots {
            margin-top: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }

        .time-slot-row {
            display: flex;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
        }

        .time-slot-row.booked {
            border-left: 4px solid;
        }

        .truck-LD1 { border-color: #FF5733; background-color: rgba(255, 87, 51, 0.1); }
        .truck-LD2 { border-color: #33FF57; background-color: rgba(51, 255, 87, 0.1); }
        .truck-LD3 { border-color: #3357FF; background-color: rgba(51, 87, 255, 0.1); }
        .truck-LD4 { border-color: #FF33F6; background-color: rgba(255, 51, 246, 0.1); }

        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .popup.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .button {
            padding: 8px 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .button.cancel {
            background: #f44336;
        }

        #calendar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .fc-event {
            cursor: pointer;
            border-radius: 2px;
            padding: 2px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <img src="images/app-logo.png" alt="App Logo" class="logo">
            <h1>Driving School Scheduler</h1>
        </header>
        
        <div id="calendar"></div>

        <div id="timeSlots" class="time-slots" style="display: none;">
            <h2 id="selectedDate"></h2>
            <div id="slotList"></div>
        </div>

        <!-- Popup Form -->
        <div id="popupForm" class="popup">
            <div class="popup-content">
                <h2>Add Slot</h2>
                <form id="slotForm">
                    <input type="hidden" name="date" id="slotDate">
                    <input type="hidden" name="time" id="slotTime">
                    
                    <div class="form-group">
                        <label for="learner_id">Learner</label>
                        <select name="learner_id" id="learner_id" required>
                            <?php while($learner = $learners_result->fetch_assoc()): ?>
                                <option value="<?php echo $learner['id']; ?>">
                                    <?php echo $learner['full_name'] . ' (' . $learner['receipt_number'] . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="truck_id">Truck</label>
                        <select name="truck_id" id="truck_id" required>
                            <?php while($truck = $trucks_result->fetch_assoc()): ?>
                                <option value="<?php echo $truck['id']; ?>">
                                    <?php echo $truck['truck_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="code">Code</label>
                        <input type="text" name="code" id="code" required>
                    </div>

                    <button type="submit" class="button">Save</button>
                    <button type="button" id="cancelBtn" class="button cancel">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    
    <script>
        $(document).ready(function() {
            const truckColors = {
                'LD1': '#FF5733',
                'LD2': '#33FF57',
                'LD3': '#3357FF',
                'LD4': '#FF33F6'
            };

            // Initialize FullCalendar with events
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                selectable: true,
                eventSources: [{
                    events: <?php echo getScheduledSlots($db); ?>,
                    color: function(event) {
                        return truckColors[event.truck_name];
                    }
                }],
                dayClick: function(date) {
                    showTimeSlots(date.format('YYYY-MM-DD'));
                },
                eventClick: function(event) {
                    alert(
                        'Slot Details:\n' +
                        'Learner: ' + event.learner_name + '\n' +
                        'Truck: ' + event.truck_name + '\n' +
                        'Receipt: ' + event.receipt_number + '\n' +
                        'Code: ' + event.code
                    );
                }
            });

            function showTimeSlots(date) {
                const timeSlots = $('#timeSlots');
                const slotList = $('#slotList');
                
                $('#selectedDate').text(moment(date).format('MMMM D, YYYY'));
                $('#slotDate').val(date);
                timeSlots.show();
                slotList.empty();

                // Generate time slots from 6:30 to 19:00
                let startHour = 6;
                let endHour = 19;

                for (let hour = startHour; hour <= endHour; hour++) {
                    let minutes = (hour === 6) ? [30] : [0];
                    
                    minutes.forEach(minute => {
                        if (hour === 19 && minute === 30) return;
                        
                        const timeString = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                        const slotHtml = `
                            <div class="time-slot-row">
                                <div class="time-label">${timeString}</div>
                                <div class="slot-content">
                                    <div class="slot-info">Available</div>
                                    <button type="button" class="button add-slot-btn" data-time="${timeString}">Add Slot</button>
                                </div>
                            </div>
                        `;
                        slotList.append(slotHtml);
                    });
                }
            }

            $(document).on('click', '.add-slot-btn', function() {
                const time = $(this).data('time');
                $('#slotTime').val(time);
                $('#popupForm').addClass('show');
            });

            $('#slotForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                
                $.ajax({
                    url: 'save_slot.php',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#popupForm').removeClass('show');
                        $('#calendar').fullCalendar('refetchEvents');
                        showTimeSlots($('#slotDate').val());
                    },
                    error: function() {
                        alert('Error saving slot');
                    }
                });
            });

            $('#cancelBtn').on('click', function() {
                $('#popupForm').removeClass('show');
            });
        });
    </script>
    <?php include 'footer.html'; ?>

</body>
</html>