<?php
date_default_timezone_set('Asia/Dhaka');
include 'connect.php';

// Get the current time in 24-hour format
$currentDateTime = date("H:i:s");

// Debug: Print current time
echo "Current Time: $currentDateTime<br>";

// Fetch expired events
$expiredEventsQuery = "
    SELECT EventID, EndTime, Status 
    FROM Event 
    WHERE EndTime < ? AND Status != 'Closed'
";

$stmt = $conn->prepare($expiredEventsQuery);
$stmt->bind_param("s", $currentDateTime);
$stmt->execute();
$result = $stmt->get_result();

$expiredEventIDs = [];
while ($row = $result->fetch_assoc()) {
    echo "EventID: {$row['EventID']}, EndTime: {$row['EndTime']}, Status: {$row['Status']}<br>";
    $expiredEventIDs[] = $row['EventID'];
}

// Debug: Print expired event IDs
if (empty($expiredEventIDs)) {
    echo "No expired events found.<br>";
} else {
    echo "Expired Event IDs: " . implode(", ", $expiredEventIDs) . "<br>";
}

// Update and remove logic if there are expired events
if (!empty($expiredEventIDs)) {
    $eventIDs = implode(",", $expiredEventIDs);

    // Update the event status to 'Closed'
    $updateStatusQuery = "
        UPDATE Event 
        SET Status = 'Closed' 
        WHERE EventID IN ($eventIDs)
    ";
    if ($conn->query($updateStatusQuery)) {
        echo "Updated event status successfully.<br>";
    } else {
        echo "Error updating event status: " . $conn->error . "<br>";
    }

    // Remove associated records
    $queries = [
        "DELETE FROM EventVolunteers WHERE EventID IN ($eventIDs)",
        "DELETE FROM EventDonors WHERE EventID IN ($eventIDs)",
        "DELETE FROM EventSupervisor WHERE EventID IN ($eventIDs)"
    ];

    foreach ($queries as $query) {
        if ($conn->query($query)) {
            echo "Executed: $query<br>";
        } else {
            echo "Error executing query: $query - " . $conn->error . "<br>";
        }
    }
}
?>
