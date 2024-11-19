<?php
// Start the session
session_start();

// Include database connection file
require_once 'db_connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $date = $_POST['date'];
    $truckName = $_POST['truck_name'];
    $receiptNumber = $_POST['receipt_number'];
    $code = $_POST['code'];

    // Validate session for the logged-in user
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Find the truck ID based on the truck name
        $truckQuery = $conn->prepare("SELECT id FROM trucks WHERE truck_name = ?");
        $truckQuery->bind_param('s', $truckName);
        $truckQuery->execute();
        $truckResult = $truckQuery->get_result();

        if ($truckResult->num_rows > 0) {
            $truckId = $truckResult->fetch_assoc()['id'];
        } else {
            throw new Exception('Invalid truck name.');
        }

        // Find the learner ID based on the receipt number
        $learnerQuery = $conn->prepare("SELECT id FROM learners WHERE receipt_number = ?");
        $learnerQuery->bind_param('s', $receiptNumber);
        $learnerQuery->execute();
        $learnerResult = $learnerQuery->get_result();

        if ($learnerResult->num_rows > 0) {
            $learnerId = $learnerResult->fetch_assoc()['id'];
        } else {
            throw new Exception('Receipt number not found in learners table.');
        }

        // Insert the slot into the schedule table
        $insertQuery = $conn->prepare("
            INSERT INTO schedule (schedule_date, truck_id, learner_id, user_id, time_slot, code) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $timeSlot = $_POST['time_slot']; // Ensure time_slot is passed from the form
        $insertQuery->bind_param('siissi', $date, $truckId, $learnerId, $userId, $timeSlot, $code);

        if ($insertQuery->execute()) {
            // Commit transaction
            $conn->commit();
            echo json_encode(['status' => 'success', 'message' => 'Slot added successfully.']);
        } else {
            throw new Exception('Failed to insert schedule.');
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

    // Close queries and connection
    $truckQuery->close();
    $learnerQuery->close();
    $insertQuery->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
