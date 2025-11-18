<?php 
// views/login.php

/**
 * @var array $data Contains data passed from the controller, including 'error'
 */
$error = $data['error'] ?? '';
$username_value = $data['username_value'] ?? '';
?>

<div class="container" style="max-width: 400px; margin-top: 50px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
    <h2 style="text-align: center;">Admin Panel Login</h2>

    <?php if ($error): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_PATH ?>/login">
        
        <div style="margin-bottom: 15px;">
            <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Username:</label>
            <input type="text" id="username" name="username" required 
                   value="<?= $username_value ?>"
                   style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
            <input type="password" id="password" name="password" required
                   style="width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 4px;">
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; padding: 10px; font-size: 1.1em;">Log In</button>
    </form>
</div>