<?php
include 'conn.php';
include '../userside/model/model.php';  // Include model here
include '../userside/controller/controller.php';

// Create an instance of UserController
$controller = new UserController($conn);

// Fetch contact submissions from the 'contacts' table
$submissions = $controller->getContactSubmissions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h2>Contact Submissions</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($submissions)): ?>
                <tr>
                    <td colspan="5" class="text-center">No submissions found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['id']); ?></td>
                        <td><?php echo htmlspecialchars($submission['name']); ?></td>
                        <td><?php echo htmlspecialchars($submission['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($submission['message'])); ?></td>
                        <td><?php echo htmlspecialchars($submission['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php include 'footer.php'; ?>
</div>

<!-- Optional JavaScript; choose one of the two! -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
