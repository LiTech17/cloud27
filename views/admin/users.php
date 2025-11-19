<?php 
// views/admin/users.php

/**
 * @var array $data Contains data passed from the controller, including 'users'
 */
// Assumed existence of AdminNav.php, which should contain the primary navigation
include VIEW_PATH . '_partials/AdminNav.php';

$users = $data['users'] ?? [];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<!-- Utilize the responsive container defined in the styles -->
<div class="container py-6 md:py-8">
    <!-- Admin Header Section: flex justify-between for title and button -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="heading-2 text-primary">👥 Manage User Accounts</h2>
        <a href="<?= BASE_PATH ?>/admin/users/add" class="btn btn-primary btn-sm">
            <!-- Use btn-sm for better look in a header context -->
            + Add New User
        </a>
    </div>

    <!-- System Message Alert -->
    <?php if ($message): ?>
        <div class="alert alert-success mb-6">
            <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                <!-- Icon for success alert (Checkmark) -->
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.857a.75.75 0 00-1.072-1.072l-4.25 4.25a.75.75 0 000 1.072l6 6a.75.75 0 001.072-1.072L10.072 10l3.785-3.786z" clip-rule="evenodd" />
            </svg>
            <div class="alert-content">
                <p class="alert-message"><?= htmlspecialchars($message) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($users)): ?>
        <div class="alert alert-info text-center">
            <div class="alert-content">
                <p class="alert-message">No user accounts found in the database. Start by adding a new user.</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Table Component: Wrapped in table-container for responsive overflow -->
        <div class="table-container mb-8">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($user['id']) ?></td>
                            <td data-label="Username">
                                <strong><?= htmlspecialchars($user['username']) ?></strong>
                                <?php if ($user['id'] == ($_SESSION['user_id'] ?? null)): ?>
                                    <!-- Use badge-primary for emphasis on the current user -->
                                    <span class="badge badge-primary">You</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Email"><?= htmlspecialchars($user['email']) ?></td>
                            <td data-label="Role">
                                <!-- Use semantic badges for role differentiation -->
                                <span class="badge <?= $user['is_admin'] ? 'badge-success' : 'badge-info' ?>">
                                    <?= $user['is_admin'] ? 'Admin' : 'Client' ?>
                                </span>
                            </td>
                            <td data-label="Created At"><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                            <td data-label="Actions" class="text-center">
                                <!-- Edit Button: Subtle ghost style -->
                                <a href="<?= BASE_PATH ?>/admin/users/edit?id=<?= $user['id'] ?>" class="btn btn-sm btn-ghost">
                                    Edit
                                </a>

                                <?php 
                                // Prevent deleting the current logged-in user
                                $disableDelete = ($user['id'] == ($_SESSION['user_id'] ?? null));
                                ?>
                                
                                <!-- NOTE: In a production app, the 'onsubmit' event should trigger a modern, non-blocking confirmation modal instead of a browser 'confirm()' call. -->
                                <form method="POST" action="<?= BASE_PATH ?>/admin/users/delete" class="inline-block ml-2">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <!-- Delete Button: Error color for destructive action -->
                                    <button type="submit" class="btn btn-sm btn-error" <?= $disableDelete ? 'disabled' : '' ?>>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php 
// Added missing Footer partial for correct page structure
include VIEW_PATH . '_partials/Footer.php';
?>