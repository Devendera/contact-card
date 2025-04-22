<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Contact</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Add New Contact</h2>

  <?php
  $error = '';
  $success = false;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if ($name === '' || $phone === '') {
    $error = "Name and phone number are required.";
} elseif (!preg_match("/^[a-zA-Z\s]+$/u", $name)) {
    $error = "Name must contain only letters and spaces.";
} elseif (!preg_match("/^\+?[0-9\s\-]+$/", $phone)) {
    $error = "Phone number must contain only numbers, spaces, dashes or +.";
}
 else {
      try {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, phone) VALUES (?, ?)");
        $stmt->execute([$name, $phone]);
        $success = true;
      } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
      }
    }
  }
  ?>

  <?php if ($success): ?>
    <div class="alert alert-success">Contact added successfully.</div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="p-4 border bg-white rounded shadow-sm">
    <div class="mb-3">
      <label class="form-label">Name:</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone:</label>
      <input type="text" name="phone" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save Contact</button>
    <a href="index.php" class="btn btn-secondary">Back</a>
  </form>
</div>

</body>
</html>
