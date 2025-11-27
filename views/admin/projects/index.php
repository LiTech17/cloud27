<?php
// views/admin/projects/index.php
/**
 * @var array $data Contains projects array
 */
include VIEW_PATH . '_partials/AdminNav.php';

$projects = $data['projects'] ?? [];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

// Get unique statuses and packages for filters
$statuses = [];
$packages = [];
foreach ($projects as $project) {
    if (!in_array($project['status'], $statuses)) {
        $statuses[] = $project['status'];
    }
    if (!in_array($project['package_name'], $packages)) {
        $packages[] = $project['package_name'];
    }
}
?>

<section class="section">
    <div class="container">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2">📁 Manage Projects</h2>
                <p class="text-secondary">View and manage all client projects</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/projects/create" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Project
            </a>
        </div>

        <!-- Success Message -->
        <?php if ($message): ?>
            <div class="alert alert-success mb-6 animate-slide-down">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message"><?= htmlspecialchars($message) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filters -->
        <?php if (!empty($projects)): ?>
            <div class="card mb-6">
                <form method="GET" action="<?= BASE_PATH ?>/admin/projects" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="form-group mb-0">
                        <label for="status" class="form-label">Filter by Status</label>
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= htmlspecialchars($status) ?>" 
                                        <?= ($_GET['status'] ?? '') === $status ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        <label for="package_name" class="form-label">Filter by Package</label>
                        <select name="package_name" id="package_name" class="form-select" onchange="this.form.submit()">
                            <option value="">All Packages</option>
                            <?php foreach ($packages as $package): ?>
                                <option value="<?= htmlspecialchars($package) ?>" 
                                        <?= ($_GET['package_name'] ?? '') === $package ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($package) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <a href="<?= BASE_PATH ?>/admin/projects" class="btn btn-ghost w-full">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <!-- Projects List -->
        <?php if (empty($projects)): ?>
            <div class="card text-center bg-warning-light p-8">
                <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="heading-3 mb-3" style="color: var(--color-warning);">No Projects Found</h3>
                <p class="text-secondary mb-6">
                    Start by creating your first project or adjust your filters.
                </p>
                <a href="<?= BASE_PATH ?>/admin/projects/create" class="btn btn-primary">
                    Create First Project
                </a>
            </div>
        <?php else: ?>
            <!-- Desktop Table View - Hidden on mobile -->
            <div class="card p-0 overflow-hidden hidden md:block">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Client</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Dates</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td>
                                        <div class="font-semibold text-brand"><?= htmlspecialchars($project['title']) ?></div>
                                        <?php if (!empty($project['description'])): ?>
                                            <div class="text-xs text-tertiary mt-1">
                                                <?= htmlspecialchars(substr($project['description'], 0, 50)) ?>...
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="font-medium"><?= htmlspecialchars($project['client_name'] ?? 'Unknown') ?></div>
                                        <div class="text-xs text-tertiary"><?= htmlspecialchars($project['client_email'] ?? '') ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($project['package_name']) ?></td>
                                    <td>
                                        <span class="badge <?= getStatusClass($project['status']) ?>">
                                            <?= htmlspecialchars($project['status']) ?>
                                        </span>
                                    </td>
                                    <td class="font-semibold">R<?= number_format($project['budget'], 2) ?></td>
                                    <td class="text-sm">
                                        <?php if ($project['start_date']): ?>
                                            <div>Start: <?= date('M d, Y', strtotime($project['start_date'])) ?></div>
                                        <?php endif; ?>
                                        <?php if ($project['due_date']): ?>
                                            <div class="text-tertiary">Due: <?= date('M d, Y', strtotime($project['due_date'])) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <a href="<?= BASE_PATH ?>/admin/projects/<?= $project['id'] ?>" 
                                               class="btn btn-sm btn-ghost"
                                               title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="<?= BASE_PATH ?>/admin/projects/edit/<?= $project['id'] ?>" 
                                               class="btn btn-sm btn-ghost"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="<?= BASE_PATH ?>/admin/projects/destroy/<?= $project['id'] ?>" 
                                                  style="display:inline-block;"
                                                  onsubmit="return confirm('Delete project: <?= htmlspecialchars($project['title']) ?>?');">
                                                <button type="submit" class="btn btn-sm btn-ghost text-error">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View - Hidden on desktop -->
            <div class="block md:hidden space-y-4">
                <?php foreach ($projects as $project): ?>
                    <div class="card">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="font-semibold text-brand mb-1"><?= htmlspecialchars($project['title']) ?></h3>
                                <p class="text-sm text-tertiary"><?= htmlspecialchars($project['client_name'] ?? 'Unknown Client') ?></p>
                            </div>
                            <span class="badge <?= getStatusClass($project['status']) ?>">
                                <?= htmlspecialchars($project['status']) ?>
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div>
                                <p class="text-tertiary mb-1">Package</p>
                                <p class="font-medium"><?= htmlspecialchars($project['package_name']) ?></p>
                            </div>
                            <div>
                                <p class="text-tertiary mb-1">Budget</p>
                                <p class="font-semibold text-success">R<?= number_format($project['budget'], 2) ?></p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="<?= BASE_PATH ?>/admin/projects/<?= $project['id'] ?>" 
                               class="btn btn-sm btn-ghost flex-1"
                               title="View Project Details">
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                            <a href="<?= BASE_PATH ?>/admin/projects/edit/<?= $project['id'] ?>" 
                               class="btn btn-sm btn-outline flex-1">
                                Edit
                            </a>
                            <form method="POST" action="<?= BASE_PATH ?>/admin/projects/destroy/<?= $project['id'] ?>" 
                                  class="flex-1"
                                  onsubmit="return confirm('Delete this project?');">
                                <button type="submit" class="btn btn-sm btn-outline text-error w-full">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
// Helper function for status badge colors
function getStatusClass($status) {
    $classes = [
        'New' => 'badge-info',
        'In Progress' => 'badge-primary',
        'On Hold' => 'badge-warning',
        'Awaiting Client' => 'badge-warning',
        'Review/QA' => 'badge-info',
        'Completed' => 'badge-success',
        'Canceled' => 'badge-error'
    ];
    return $classes[$status] ?? 'badge-outline';
}
?>

<style>
.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-16 { width: 4rem; }
.h-16 { height: 4rem; }
.space-y-4 > * + * { margin-top: 1rem; }

/* Responsive utility classes */
.hidden { display: none; }
.block { display: block; }

/* Medium screen and up (md breakpoint) */
@media (min-width: 768px) {
    .md\\:block { display: block; }
    .md\\:hidden { display: none; }
}
</style>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>