<?php 
// views/client/projects/show.php
require VIEW_PATH . '_partials/ClientNav.php'; 

$project = $data['project'] ?? null;

if (!$project) {
    echo "<h1>Project not found</h1>";
    exit;
}

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

// Calculate timeline information
$today = new DateTime();
$startDate = $project['start_date'] ? new DateTime($project['start_date']) : null;
$dueDate = $project['due_date'] ? new DateTime($project['due_date']) : null;
$createdAt = new DateTime($project['created_at']);
$updatedAt = new DateTime($project['updated_at']);

$isOverdue = $dueDate && $dueDate < $today;
$daysLeft = $dueDate ? $today->diff($dueDate)->days : null;
$isUrgent = $daysLeft && $daysLeft <= 7 && !$isOverdue;
?>

<section class="section">
    <div class="container">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="heading-2 mb-1"><?= htmlspecialchars($project['title']) ?></h2>
                    <p class="text-secondary">Project details and timeline</p>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="<?= BASE_PATH ?>/client/projects" 
                   class="btn btn-ghost">
                   <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                   </svg>
                   Back to Projects
                </a>
                
                <?php if (in_array($project['status'], ['New', 'Awaiting Client'])): ?>
                    <a href="<?= BASE_PATH ?>/client/projects/edit/<?= $project['id'] ?>" 
                       class="btn btn-outline">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                 d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                       </svg>
                       Edit Project
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Project Overview Card -->
                <div class="card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="heading-4">Project Overview</h3>
                        <span class="badge <?= getStatusClass($project['status']) ?> text-sm">
                            <?= htmlspecialchars($project['status']) ?>
                        </span>
                    </div>

                    <div class="space-y-6">
                        <!-- Description -->
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Description
                            </h4>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                <?= nl2br(htmlspecialchars($project['description'] ?: 'No description provided.')) ?>
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Package -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Package</h4>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-400">
                                    <?= htmlspecialchars($project['package_name']) ?>
                                </span>
                            </div>

                            <!-- Budget -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Budget</h4>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    R<?= number_format($project['budget'], 2) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card">
                    <h3 class="heading-4 mb-6">Timeline</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Start Date</h4>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        <?= $startDate ? $startDate->format('F j, Y') : 'Not set' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Due Date</h4>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 <?= $isOverdue ? 'text-red-500' : ($isUrgent ? 'text-amber-500' : 'text-green-500') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="<?= $isOverdue ? 'text-red-600 dark:text-red-400 font-semibold' : ($isUrgent ? 'text-amber-600 dark:text-amber-400 font-semibold' : 'text-gray-600 dark:text-gray-300') ?>">
                                        <?= $dueDate ? $dueDate->format('F j, Y') : 'Not set' ?>
                                    </span>
                                    <?php if ($dueDate): ?>
                                        <span class="text-xs <?= $isOverdue ? 'text-red-500' : ($isUrgent ? 'text-amber-500' : 'text-green-500') ?> font-medium">
                                            <?= $isOverdue ? 'Overdue by ' . $daysLeft . ' days' : ($isUrgent ? $daysLeft . ' days left' : 'On track') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Created At -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Created</h4>
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?= $createdAt->format('F j, Y \a\t g:i A') ?>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Last Updated</h4>
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <?= $updatedAt->format('F j, Y \a\t g:i A') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="card">
                    <h3 class="heading-4 mb-4">Project Status</h3>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="badge <?= getStatusClass($project['status']) ?> text-lg px-4 py-2">
                            <?= htmlspecialchars($project['status']) ?>
                        </span>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-3">
                            <?php
                            $statusMessages = [
                                'New' => 'Your project is awaiting review by our team.',
                                'In Progress' => 'Our team is actively working on your project.',
                                'On Hold' => 'Project is temporarily paused.',
                                'Awaiting Client' => 'Waiting for your input or approval.',
                                'Review/QA' => 'Project is undergoing quality assurance.',
                                'Completed' => 'Project has been successfully delivered.',
                                'Canceled' => 'Project has been canceled.'
                            ];
                            echo $statusMessages[$project['status']] ?? 'Project status information.';
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card">
                    <h3 class="heading-4 mb-4">Project Actions</h3>
                    <div class="space-y-3">
                        <?php if (empty($project['completed'])): ?>
                            <a href="<?= BASE_PATH ?>/client/project-data/create/<?= urlencode($project['id']); ?>" 
                               class="btn btn-primary w-full justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Complete Project Details
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                Provide detailed information to help us understand your requirements.
                            </p>
                        <?php else: ?>
                            <a href="<?= BASE_PATH ?>/client/project-data/<?= urlencode($project['id']); ?>" 
                               class="btn btn-outline w-full justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View Project Data
                            </a>
                        <?php endif; ?>

                        <?php if (in_array($project['status'], ['New', 'Awaiting Client'])): ?>
                            <a href="<?= BASE_PATH ?>/client/projects/edit/<?= $project['id'] ?>" 
                               class="btn btn-ghost w-full justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Project
                            </a>
                        <?php endif; ?>

                        <a href="<?= BASE_PATH ?>/client/projects" 
                           class="btn btn-ghost w-full justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Projects
                        </a>
                    </div>
                </div>

                <!-- Support Card -->
                <div class="card bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h3 class="heading-4 mb-3">Need Help?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Have questions about your project? Our support team is here to help.
                    </p>
                    <a href="#" class="btn btn-outline w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.heading-4 {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
    color: var(--color-text);
}

.card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Responsive improvements */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .grid {
        gap: 1rem;
    }
    
    .card {
        padding: 1rem;
    }
}

/* Dark mode enhancements */
.dark .card {
    background: var(--color-surface);
    border-color: var(--color-border);
}
</style>

<?php require VIEW_PATH . '_partials/Footer.php'; ?>