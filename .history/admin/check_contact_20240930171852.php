<?php
include 'conn.php';
include '../controller/c';
$controller = new UserController($conn);

// Handle deletion
if (isset($_GET['delete_id'])) {
    $controller->deleteMessage($_GET['delete_id']);
    header("Location: check_contact.php");
    exit();
}

// Fetch existing messages
$messages = $controller->getMessages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Contact Messages</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Submitted Messages</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                    <td>
                        <a href="?delete_id=<?php echo $message['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php include 'footer.php'; ?>
</div>
</body>
</html>
