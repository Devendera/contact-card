<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Contact List</h2>

    <div class="mb-3">
        <a href="add.php" class="btn btn-primary">+ Add Contact</a>
        <a href="import.php" class="btn btn-success">Import XML</a>
    </div>

    <?php
    try {
        $stmt = $pdo->query("SELECT * FROM contacts ORDER BY id DESC");
        $contacts = $stmt->fetchAll();
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
        $contacts = [];
    }
    ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Name</th><th>Phone</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1; ?>
			<?php if (count($contacts) > 0): ?>
			    <?php foreach ($contacts as $contact): ?>
			        <tr>
			            <td><?= $counter++ ?></td>
			            <td><?= htmlspecialchars($contact['name']) ?></td>
			            <td><?= htmlspecialchars($contact['phone']) ?></td>
			            <td>
			                <a href="edit.php?id=<?= $contact['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
			                <a href="delete.php?id=<?= $contact['id'] ?>" class="btn btn-danger btn-sm"
			                   onclick="return confirm('Delete this contact?')">Delete</a>
			            </td>
			        </tr>
			    <?php endforeach ?>
			<?php else: ?>
			    <tr>
			        <td colspan="4" class="text-center text-danger">No contacts found.</td>
			    </tr>
			<?php endif; ?>
			</tbody>
    </table>
</div>
</body>
</html>
