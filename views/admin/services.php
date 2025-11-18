<?php 
// views/admin/services.php

// Use the existing Header partial
include VIEW_PATH . '_partials/Header.php';
?>

<div class="container" style="margin-top: 40px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #007bff; padding-bottom: 15px; margin-bottom: 30px;">
        <h1 style="color: #007bff;">Manage Services</h1>
        <a href="<?= BASE_PATH ?>/admin/services/add" class="btn-primary">➕ Add New Service</a>
    </div>

    <?php 
    // Check for success or error messages (passed via session/redirect)
    if (isset($_SESSION['message'])): ?>
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
            <?= htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($data['services'])): ?>
        <p>No services found. Click "Add New Service" to get started.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">ID</th>
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Title</th>
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Description Snippet</th>
                    <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['services'] as $service): ?>
                    <tr>
                        <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($service['id']) ?></td>
                        <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars($service['title']) ?></td>
                        <td style="padding: 12px; border: 1px solid #ddd;"><?= htmlspecialchars(substr($service['description'], 0, 100)) . '...' ?></td>
                        <td style="padding: 12px; border: 1px solid #ddd; text-align: center;">
                            <a href="<?= BASE_PATH ?>/admin/services/edit?id=<?= $service['id'] ?>" class="btn-primary" style="background-color: #ffc107; color: #333; padding: 5px 10px;">Edit</a>
                            
                            <form method="POST" action="<?= BASE_PATH ?>/admin/services/delete" style="display: inline-block; margin-left: 10px;" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                <input type="hidden" name="id" value="<?= $service['id'] ?>">
                                <button type="submit" class="btn-primary" style="background-color: #dc3545; padding: 5px 10px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<?php
// Use the existing Footer partial
include VIEW_PATH . '_partials/Footer.php';
?>