<?php
include 'controller/controller.php'; // Ensure this path is correct
include 'conn.php'; // Ensure you include your database connection

$userController = new UserController($conn);
$userController->handleContactDeletion(); // Handle any deletions if necessary

// Fetch contacts from the controller
$contacts = $userController->getContacts(); // Ensure getContacts() returns the contacts

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">
    <h1>Contact Messages</h1>
    <table>
        <thead>
            <tr>
                <th>Serial No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($contacts && $contacts->num_rows > 0): ?>
                <?php $serial_number = 1; ?>
                <?php while($row = $contacts->fetch_assoc()): ?>
                    <tr>
                        <td><?= $serial_number++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['message']); ?></td>
                        <td>
                            <a href="?delete_id=<?= $row['id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No contacts found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include footer.php'; ?> <!-- Ensure this path is correct -->

</body>
</html>
