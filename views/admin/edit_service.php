<?php 
// views/admin/edit_service.php

// Data passed from controller
$service = $data['service'] ?? null;
$error = $data['error'] ?? '';

// Determine if we are adding or editing
$isEdit = $service !== null;
$formTitle = $isEdit ? 'Edit Service: ' . htmlspecialchars($service['title']) : 'Add New Service';

// Set values for the form fields
$titleValue = $service['title'] ?? '';
$descriptionValue = $service['description'] ?? '';
$idValue = $service['id'] ?? '';

// Use the existing Header partial
include VIEW_PATH . '_partials/Header.php';
?>

<div class="container" style="max-width: 800px; margin-top: 40px;">
    
    <h1 style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 30px;"><?= $formTitle ?></h1>

    <?php if ($error): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_PATH ?>/admin/services/save">
        
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($idValue) ?>">
        <?php endif; ?>

        <div style="margin-bottom: 20px;">
            <label for="title" style="display: block; margin-bottom: 5px; font-weight: bold;">Service Title:</label>
            <input type="text" id="title" name="title" required 
                   value="<?= htmlspecialchars($titleValue) ?>"
                   style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="description" style="display: block; margin-bottom: 5px; font-weight: bold;">Description:</label>
            <textarea id="description" name="description" required
                      style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px; min-height: 200px;"><?= htmlspecialchars($descriptionValue) ?></textarea>
        </div>

        <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 1.1em; background-color: #28a745;">
            <?= $isEdit ? 'Save Changes' : 'Create Service' ?>
        </button>
        <a href="<?= BASE_PATH ?>/admin/services" style="margin-left: 15px; color: #6c757d; text-decoration: none;">Cancel</a>
    </form>
</div>

<?php
// Use the existing Footer partial
include VIEW_PATH . '_partials/Footer.php';
?>