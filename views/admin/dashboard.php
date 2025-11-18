<?php 
// views/admin/dashboard.php
$username = $_SESSION['username'] ?? 'Admin';


?>

<div class="container admin-dashboard">

    <div class="admin-header">
        <h1>Admin Dashboard: Welcome, <?= htmlspecialchars($username) ?></h1>
        <a href="<?= BASE_PATH ?>/logout" class="btn btn-danger">Logout</a>
    </div>

    <div class="admin-grid">
        
        <div class="admin-card primary">
            <h3>Service Management ⚙️</h3>
            <p>View, Add, Edit, and Delete the Cloud27 Services displayed on your front-end.</p>
            <a href="<?= BASE_PATH ?>/admin/services" class="btn btn-primary">Manage Services →</a>
        </div>
        
        <div class="admin-card secondary">
            <h3>Contact Form Submissions 📧</h3>
            <p>View all inquiries sent through the contact form (future development).</p>
            <a class="btn btn-secondary disabled">Coming Soon...</a>
        </div>

    </div>

</div>

