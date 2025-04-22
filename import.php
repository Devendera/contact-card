<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Upload & Import Contacts (XML)</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (!isset($_FILES['xmlfile']) || $_FILES['xmlfile']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please upload a valid XML file.");
            }

            $targetDir = __DIR__ . "/uploads/";
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $filename = basename($_FILES['xmlfile']['name']);
            $targetFile = $targetDir . $filename;

            if (!move_uploaded_file($_FILES['xmlfile']['tmp_name'], $targetFile)) {
                throw new Exception("Failed to move uploaded file.");
            }

            libxml_use_internal_errors(true);
            $xml = simplexml_load_file($targetFile);
            if ($xml === false) {
                $errorMsg = "Invalid XML file. ";
                foreach (libxml_get_errors() as $error) {
                    $errorMsg .= $error->message . "; ";
                }
                throw new Exception($errorMsg);
            }

            $pdo->beginTransaction();
            
            foreach ($xml->contact as $contact) {
                $name = (string)$contact->name;
                $phone = (string)$contact->phone;

                $stmt = $pdo->prepare("INSERT INTO contacts (name, phone) VALUES (?, ?)");
                $stmt->execute([$name, $phone]);
            }
            $pdo->commit();
            echo "<div class='alert alert-success'>Contacts imported successfully.</div>";
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo "<div class='alert alert-danger'>Import Failed: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    ?>

    <form method="post" enctype="multipart/form-data" class="mb-4 p-4 border rounded bg-white shadow-sm">
        <div class="mb-3">
            <label for="xmlfile" class="form-label">Select XML File</label>
            <input type="file" class="form-control" name="xmlfile" id="xmlfile" accept=".xml" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload and Import</button>
        <a href="index.php" class="btn btn-secondary">Back to Contacts</a>
    </form>
</div>

</body>
</html>
