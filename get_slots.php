<?php
require 'db_connection.php';

$date = $_GET['date'];
$response = ['status' => 'error', 'slots' => []];

$query = $conn->prepare("
    SELECT schedule.time_slot, trucks.truck_name, learners.full_name AS learner_name 
    FROM schedule 
    JOIN trucks ON schedule.truck_id = trucks.id 
    JOIN learners ON schedule.learner_id = learners.id 
    WHERE schedule.schedule_date = ?
    ORDER BY schedule.time_slot
");
$query->bind_param('s', $date);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $response['status'] = 'success';
    while ($row = $result->fetch_assoc()) {
        $response['slots'][] = $row;
    }
} else {
    $response['message'] = 'No slots found.';
}

echo json_encode($response);
$query->close();
$conn->close();
?>
