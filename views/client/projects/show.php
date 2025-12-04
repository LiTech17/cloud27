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
                            <h4 class="font-semibold text-primary mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Description
                            </h4>
                            <p class="text-secondary leading-relaxed">
                                <?= nl2br(htmlspecialchars($project['description'] ?: 'No description provided.')) ?>
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Package -->
                            <div>
                                <h4 class="font-semibold text-primary mb-2">Package</h4>
                                <span class="badge badge-primary">
                                    <?= htmlspecialchars($project['package_name']) ?>
                                </span>
                            </div>

                            <!-- Budget -->
                            <div>
                                <h4 class="font-semibold text-primary mb-2">Budget</h4>
                                <p class="text-2xl font-bold text-success">
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
                                <h4 class="font-semibold text-primary mb-2">Start Date</h4>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-secondary">
                                        <?= $startDate ? $startDate->format('F j, Y') : 'Not set' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Due Date -->
                            <div>
                                <h4 class="font-semibold text-primary mb-2">Due Date</h4>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 <?= $isOverdue ? 'text-error' : ($isUrgent ? 'text-accent' : 'text-success') ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="<?= $isOverdue ? 'text-error font-semibold' : ($isUrgent ? 'text-accent font-semibold' : 'text-secondary') ?>">
                                        <?= $dueDate ? $dueDate->format('F j, Y') : 'Not set' ?>
                                    </span>
                                    <?php if ($dueDate): ?>
                                        <span class="text-xs <?= $isOverdue ? 'text-error' : ($isUrgent ? 'text-accent' : 'text-success') ?> font-medium">
                                            <?= $isOverdue ? 'Overdue by ' . $daysLeft . ' days' : ($isUrgent ? $daysLeft . ' days left' : 'On track') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Created At -->
                            <div>
                                <h4 class="font-semibold text-primary mb-2">Created</h4>
                                <div class="flex items-center gap-2 text-secondary">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?= $createdAt->format('F j, Y \a\t g:i A') ?>
                                </div>
                            </div>

                            <!-- Last Updated -->
                            <div>
                                <h4 class="font-semibold text-primary mb-2">Last Updated</h4>
                                <div class="flex items-center gap-2 text-secondary">
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

                <!-- Onboarding Details Section -->
                <?php if (!empty($onboardingDetails)): $details = $onboardingDetails; ?>
                <div class="card">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="heading-4">Project Requirements</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Business Profile</h4>
                            <p class="mb-2"><strong>Company:</strong> <?= htmlspecialchars($details['company_name'] ?? '-') ?></p>
                            <p class="mb-2"><strong>Industry:</strong> <?= htmlspecialchars($details['industry'] ?? '-') ?></p>
                            <p class="mb-2"><strong>Mission:</strong> <?= htmlspecialchars($details['mission_statement'] ?? '-') ?></p>
                            <p class="mb-2"><strong>USP:</strong> <?= htmlspecialchars($details['usp'] ?? '-') ?></p>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Contact & Brand</h4>
                            <p class="mb-2"><strong>Rep:</strong> <?= htmlspecialchars($details['rep_name'] ?? '-') ?> (<?= htmlspecialchars($details['rep_role'] ?? '-') ?>)</p>
                            <p class="mb-2"><strong>Contact:</strong> <?= htmlspecialchars($details['rep_email'] ?? '-') ?> / <?= htmlspecialchars($details['rep_phone'] ?? '-') ?></p>
                            <p class="mb-2"><strong>Colors:</strong> <?= htmlspecialchars($details['brand_colors'] ?? '-') ?></p>
                            
                            <?php if (!empty($details['logo_path'])): ?>
                                <div class="mt-2">
                                    <strong>Uploaded Logo:</strong><br>
                                    <a href="<?= BASE_PATH ?>/uploads/logos/<?= htmlspecialchars($details['logo_path']) ?>" target="_blank" class="text-blue-600 hover:underline text-sm">View Logo</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <h4 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Specifics</h4>
                        <p class="mb-2"><strong>Objectives:</strong> 
                            <?php 
                                $objs = json_decode($details['objectives'] ?? '[]', true);
                                echo !empty($objs) ? htmlspecialchars(implode(', ', array_map(fn($s) => ucwords(str_replace('_', ' ', $s)), $objs))) : '-';
                            ?>
                        </p>
                        <p class="mb-2"><strong>Pages:</strong> 
                            <?php 
                                $pages = json_decode($details['pages'] ?? '[]', true);
                                echo !empty($pages) ? htmlspecialchars(implode(', ', array_map(fn($s) => ucwords(str_replace('_', ' ', $s)), $pages))) : '-';
                            ?>
                        </p>
                        <p class="mb-2"><strong>Features:</strong> 
                            <?php 
                                $feats = json_decode($details['features'] ?? '[]', true);
                                echo !empty($feats) ? htmlspecialchars(implode(', ', array_map(fn($s) => ucwords(str_replace('_', ' ', $s)), $feats))) : '-';
                            ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
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
                        <p class="text-sm text-secondary mt-3">
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



                
            </div>
        </div>
    </div>
</section>



<?php require VIEW_PATH . '_partials/Footer.php'; ?>