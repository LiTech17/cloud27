<?php
// views/client/projects/index.php
/**
 * @var array $data Contains projects array
 */
include VIEW_PATH . '_partials/ClientNav.php';

$projects = $data['projects'] ?? [];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

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

// Calculate project statistics
$totalProjects = count($projects);
$activeProjects = count(array_filter($projects, fn($p) => in_array($p['status'], ['New', 'In Progress', 'Review/QA'])));
$completedProjects = count(array_filter($projects, fn($p) => $p['status'] === 'Completed'));
$awaitingClient = count(array_filter($projects, fn($p) => $p['status'] === 'Awaiting Client'));
?>

<section class="section">
    <div class="container">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="heading-2 mb-2">📁 My Projects</h2>
                <p class="text-secondary">Manage and track your project requests with Cloud27</p>
            </div>
            <a href="<?= BASE_PATH ?>/client/projects/create" class="btn btn-primary btn-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Request New Project
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

        <!-- Project Stats -->
        <?php if (!empty($projects)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="card card-hover bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border-l-4 border-blue-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-800/30">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-1">Total Projects</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $totalProjects ?></p>
                        </div>
                    </div>
                </div>

                <div class="card card-hover bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-l-4 border-green-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full bg-green-100 dark:bg-green-800/30">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400 mb-1">Active</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $activeProjects ?></p>
                        </div>
                    </div>
                </div>

                <div class="card card-hover bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full bg-emerald-100 dark:bg-emerald-800/30">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mb-1">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $completedProjects ?></p>
                        </div>
                    </div>
                </div>

                <div class="card card-hover bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border-l-4 border-amber-500">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-800/30">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400 mb-1">Awaiting Response</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white"><?= $awaitingClient ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Projects List -->
        <?php if (empty($projects)): ?>
            <div class="card text-center p-12 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <div class="max-w-md mx-auto">
                    <svg class="w-20 h-20 mx-auto mb-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="heading-3 mb-4 text-gray-600 dark:text-gray-300">No Projects Yet</h3>
                    <p class="text-secondary mb-8 leading-relaxed">
                        Start your journey with Cloud27 by creating your first project request. 
                        We'll help bring your ideas to life with our expert development team.
                    </p>
                    <a href="<?= BASE_PATH ?>/client/projects/create" class="btn btn-primary btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Request Your First Project
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Desktop Table View -->
            <div class="card p-0 overflow-hidden hidden md:block">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pl-6">Project Details</th>
                                <th>Package</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Timeline</th>
                                <th class="pr-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                                <tr class="group hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <td class="pl-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-1">
                                                    <?= htmlspecialchars($project['title']) ?>
                                                </div>
                                                <?php if (!empty($project['description'])): ?>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                                        <?= htmlspecialchars($project['description']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400">
                                            <?= htmlspecialchars($project['package_name']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span class="badge <?= getStatusClass($project['status']) ?>">
                                            <?= htmlspecialchars($project['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <div class="font-semibold text-green-600 dark:text-green-400">
                                            R<?= number_format($project['budget'], 2) ?>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <div class="text-sm">
                                            <?php if ($project['due_date']): ?>
                                                <?php
                                                $dueDate = new DateTime($project['due_date']);
                                                $today = new DateTime();
                                                $interval = $today->diff($dueDate);
                                                $daysLeft = $interval->days;
                                                $isOverdue = $dueDate < $today;
                                                ?>
                                                <div class="text-gray-900 dark:text-white font-medium mb-1">
                                                    <?= $dueDate->format('M d, Y') ?>
                                                </div>
                                                <div class="text-xs <?= $isOverdue ? 'text-red-500' : ($daysLeft <= 7 ? 'text-amber-500' : 'text-green-500') ?>">
                                                    <?= $isOverdue ? 'Overdue by ' . $daysLeft . ' days' : ($daysLeft <= 7 ? $daysLeft . ' days left' : 'On track') ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-gray-400 dark:text-gray-500">Not set</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-4 pr-6">
    <div class="flex gap-2 justify-center">
        <a href="<?= BASE_PATH ?>/client/projects/<?= $project['id'] ?>" 
           class="btn btn-sm btn-ghost btn-square opacity-70 hover:opacity-100 transition-opacity"
           title="View Details">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </a>
        <?php if (in_array($project['status'], ['New', 'Awaiting Client'])): ?>
            <a href="<?= BASE_PATH ?>/client/projects/edit/<?= $project['id'] ?>" 
               class="btn btn-sm btn-ghost btn-square opacity-70 hover:opacity-100 transition-opacity"
               title="Edit Project">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
        <?php endif; ?>
    </div>
</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div class="space-y-4 md:hidden">
                <?php foreach ($projects as $project): ?>
                    <div class="card card-hover p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1 truncate">
                                        <?= htmlspecialchars($project['title']) ?>
                                    </h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400">
                                            <?= htmlspecialchars($project['package_name']) ?>
                                        </span>
                                        <span class="badge <?= getStatusClass($project['status']) ?> text-xs">
                                            <?= htmlspecialchars($project['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($project['description'])): ?>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                                <?= htmlspecialchars($project['description']) ?>
                            </p>
                        <?php endif; ?>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1">Budget</p>
                                <p class="font-semibold text-green-600 dark:text-green-400">
                                    R<?= number_format($project['budget'], 2) ?>
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-xs font-medium mb-1">Due Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    <?php if ($project['due_date']): ?>
                                        <?php
                                        $dueDate = new DateTime($project['due_date']);
                                        $today = new DateTime();
                                        $interval = $today->diff($dueDate);
                                        $daysLeft = $interval->days;
                                        $isOverdue = $dueDate < $today;
                                        ?>
                                        <span class="<?= $isOverdue ? 'text-red-500' : ($daysLeft <= 7 ? 'text-amber-500' : 'text-green-500') ?>">
                                            <?= $dueDate->format('M d, Y') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">Not set</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="<?= BASE_PATH ?>/client/projects/<?= $project['id'] ?>" 
                               class="btn btn-sm btn-primary flex-1">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Details
                            </a>
                            <?php if (in_array($project['status'], ['New', 'Awaiting Client'])): ?>
                                <a href="<?= BASE_PATH ?>/client/projects/edit/<?= $project['id'] ?>" 
                                   class="btn btn-sm btn-outline flex-1">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Enhanced styling */
.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.btn-square {
    width: 2.5rem;
    height: 2.5rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
}

/* Table improvements */
.table-container {
    overflow-x: auto;
}

.table th {
    background: var(--color-surface);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-secondary);
    border-bottom: 1px solid var(--color-border);
}

.table td {
    border-bottom: 1px solid var(--color-border);
    vertical-align: middle;
}

.table tr:last-child td {
    border-bottom: none;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card {
        margin-left: -0.5rem;
        margin-right: -0.5rem;
        border-radius: 0.5rem;
    }
}

/* Dark mode enhancements */
.dark .card-hover:hover {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
}

/* Animation improvements */
.animate-slide-down {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Utility classes */
.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.w-20 { width: 5rem; }
.h-20 { height: 5rem; }
.space-y-4 > * + * { margin-top: 1rem; }
</style>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>