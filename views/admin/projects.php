<?php
// views/admin/projects.php
/**
 * @var array $projects Array of projects to display
 */
include VIEW_PATH . '_partials/AdminNav.php';

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<section class="section animate-fade-in">
    <div class="container">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-light pb-6">
            <div>
                <h2 class="heading-2 mb-1">📁 Manage Projects</h2>
                <p class="text-secondary text-sm md:text-base">View and manage all client projects</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="<?= BASE_PATH ?>/admin/projects/create" class="btn btn-primary flex-1 md:flex-none justify-center items-center gap-2 shadow-md hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Project
                </a>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="bg-success-light border border-success text-success-dark px-4 py-3 rounded-lg mb-6 flex items-center gap-3 shadow-sm animate-slide-down" role="alert">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium"><?= htmlspecialchars($message) ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
            <div class="text-center py-12 bg-bg-secondary rounded-xl border-2 border-dashed border-border-dark">
                <h4 class="font-medium text-lg">No projects found</h4>
                <p class="text-secondary mb-4">Start by creating your first project.</p>
                <a href="<?= BASE_PATH ?>/admin/projects/create" class="btn btn-outline btn-sm">Add Project</a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-light text-xs uppercase tracking-wider text-tertiary">
                            <th class="pb-3 pl-2 font-semibold">Project Title</th>
                            <th class="pb-3 font-semibold">Client ID</th>
                            <th class="pb-3 font-semibold">Package</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Budget</th>
                            <th class="pb-3 pr-2 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-light">
                        <?php foreach ($projects as $project): ?>
                            <tr class="hover:bg-secondary transition-colors">
                                <td class="py-4 pl-2"><?= htmlspecialchars($project['title']) ?></td>
                                <td class="py-4"><?= htmlspecialchars($project['client_id']) ?></td>
                                <td class="py-4"><?= htmlspecialchars($project['package_name']) ?></td>
                                <td class="py-4"><?= htmlspecialchars($project['status']) ?></td>
                                <td class="py-4"><?= number_format($project['budget'], 2) ?></td>
                                <td class="py-4 pr-2 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= BASE_PATH ?>/admin/projects/<?= $project['id'] ?>" 
                                           class="p-2 text-tertiary hover:text-primary hover:bg-info-light rounded transition-colors" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="<?= BASE_PATH ?>/admin/projects/edit/<?= $project['id'] ?>" 
                                           class="p-2 text-tertiary hover:text-primary hover:bg-info-light rounded transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="<?= BASE_PATH ?>/admin/projects/destroy/<?= $project['id'] ?>" class="inline" onsubmit="return confirm('Are you sure?');">
                                            <button type="submit" class="p-2 text-tertiary hover:text-error hover:bg-error-light rounded transition-colors" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
        <?php endif; ?>
    </div>
</section>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>
