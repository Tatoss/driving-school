<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Not authorized');
}

// Database connection
$db = new mysqli('localhost', 'root', '', 'drivingschool');
if ($db->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Connection failed: ' . $db->connect_error);
}

// Get form data
$learner_id = $_POST['learner_id'];
$truck_id = $_POST['truck_id'];
$date = $_POST['date'];
$time = $_POST['time'];
$code = $_POST['code'];
$user_id = $_SESSION['user_id'];

// Check if slot is already booked
$check_query = "SELECT id FROM schedule 
                WHERE schedule_date = ? 
                AND time_slot = ?
                AND (truck_id = ? OR learner_id = ?)";
                
$stmt = $db->prepare($check_query);
$stmt->bind_param("ssii", $date, $time, $truck_id, $learner_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header('HTTP/1.1 400 Bad Request');
    exit(json_encode(['success' => false, 'message' => 'This slot is already booked']));
}

// Insert new slot
$insert_query = "INSERT INTO schedule (learner_id, truck_id, user_id, schedule_date, time_slot, code) 
                 VALUES (?, ?, ?, ?, ?, ?)";
                 
$stmt = $db->prepare($insert_query);
$stmt->bind_param("iiisss", $learner_id, $truck_id, $user_id, $date, $time, $code);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Schedule saved successfully']);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    exit(json_encode(['success' => false, 'message' => 'Error saving slot: ' . $db->error]));
}
?>