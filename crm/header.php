<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? ['email' => 'Guest'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KCR CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">KCR CRM</a>
        <div class="d-flex ms-auto">
            <span class="navbar-text text-white me-3"><?php echo htmlspecialchars($user['email']); ?></span>
            <a class="btn btn-outline-light" href="/logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container-fluid mt-4">
