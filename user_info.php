<?php
session_start();
// $_SESSION['user_id'] = 26; // Assign the logged-in user's ID
// $_SESSION['role'] = 'Admin'; // Assign the user's role (e.g., 'Admin')

// echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
// echo "Role: " . ($_SESSION['role'] ?? 'Not set') . "<br>";


include 'connect.php';

if (!isset($conn)) {
    die("Database connection not established.");
}

// Assuming admin login details are stored in the session
if (!isset($_SESSION['UserID']) || $_SESSION['role'] != 'Admin') {
    die("Unauthorized access.");
}

// $admin_id = $_SESSION['user_id'];

// Fetch admin details
$adminQuery = "SELECT * FROM User WHERE UserID = ?";
$adminStmt = $conn->prepare($adminQuery);
$adminStmt->bind_param("i", $admin_id);
$adminStmt->execute();
$adminResult = $adminStmt->get_result();
$adminData = $adminResult->fetch_assoc();

// Fetch donors
$donorQuery = "SELECT u.Name, u.Email, u.Phone, d.Quantity, l.City, l.State, l.ZipCode
                FROM User u
                JOIN donorstorage d ON u.UserID = d.DonorID
                JOIN Location l ON u.LocationID = l.LocationID
                WHERE u.Role = 'Donor'";
$donorResult = $conn->query($donorQuery);

// Fetch volunteers
$volunteerQuery = "SELECT u.Name, u.Email, u.Phone, l.City, l.State, l.ZipCode
                    FROM User u
                    JOIN Location l ON u.LocationID = l.LocationID
                    WHERE u.Role = 'Volunteer'";
$volunteerResult = $conn->query($volunteerQuery);

// Fetch supervisors
$supervisorQuery = "SELECT u.Name, u.Email, u.Phone, o.ORGName AS OrganizationName, s.TotalPeople, s.RequiredStorageCapacity, l.City, l.State, l.ZipCode
                     FROM User u
                     JOIN Supervisor s ON u.UserID = s.UserID
                     JOIN Organization o ON s.OrganizationID = o.OrganizationID
                     JOIN Location l ON u.LocationID = l.LocationID
                     WHERE u.Role = 'Supervisor'";
$supervisorResult = $conn->query($supervisorQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="container">
        <h1>Registered User Informations</h1>

        <!-- <h2>Admin Information</h2>
        <?php if ($adminData) { ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($adminData['Name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($adminData['Email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($adminData['Phone']); ?></p>
        <?php } else { ?>
            <p>No admin information found.</p>
        <?php } ?> -->

        <h2>Donors</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Donation Quantity</th>
                <th>City</th>
                <th>State</th>
                <th>ZipCode</th>
            </tr>
            <?php while ($donor = $donorResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($donor['Name']); ?></td>
                    <td><?php echo htmlspecialchars($donor['Email']); ?></td>
                    <td><?php echo htmlspecialchars($donor['Phone']); ?></td>
                    <td><?php echo htmlspecialchars($donor['Quantity']); ?></td>
                    <td><?php echo htmlspecialchars($donor['City']); ?></td>
                    <td><?php echo htmlspecialchars($donor['State']); ?></td>
                    <td><?php echo htmlspecialchars($donor['ZipCode']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h2>Volunteers</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>State</th>
                <th>ZipCode</th>
            </tr>
            <?php while ($volunteer = $volunteerResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($volunteer['Name']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['Email']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['Phone']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['City']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['State']); ?></td>
                    <td><?php echo htmlspecialchars($volunteer['ZipCode']); ?></td>
                </tr>
            <?php } ?>
        </table>

        <h2>Supervisors</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Organization Name</th>
                <th>Total People</th>
                <th>Required Storage Capacity</th>
                <th>City</th>
                <th>State</th>
                <th>ZipCode</th>
            </tr>
            <?php while ($supervisor = $supervisorResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($supervisor['Name']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['Email']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['Phone']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['OrganizationName']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['TotalPeople']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['RequiredStorageCapacity']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['City']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['State']); ?></td>
                    <td><?php echo htmlspecialchars($supervisor['ZipCode']); ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>
</body>
</html>