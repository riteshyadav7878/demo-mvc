<?php
include 'conn.php';

// Delete record if delete request is received
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM contacts WHERE id = ?";
    
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch contacts in descending order by ID
$sql = "SELECT id, name, email, message FROM contacts ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Contacts</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the CSS file -->
</head>
<body>

<?php '' ?>
    <div class="container">
        <h1>Contact Messages</h1>
        <table>
            <thead>
                <tr>
                    <th>Serial No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th> <!-- New Action Column -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $serial_number = 1; // Initialize serial number
                    // Output data for each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial_number}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['message']}</td>
                                <td>
                                    <a href='?delete_id={$row['id']}' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                                </td>
                              </tr>";
                        $serial_number++; // Increment serial number
                    }
                } else {
                    echo "<tr><td colspan='5'>No contacts found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
