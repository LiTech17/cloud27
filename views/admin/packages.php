<?php
// views/admin/packages.php

/**
 * @var array $data Contains data passed from the controller, including 'packages'
 */
include VIEW_PATH . '_partials/AdminNav.php';

$packages = $data['packages'] ?? [];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<section class="section">
    <div class="container">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2">📦 Manage Pricing Packages</h2>
                <p class="text-secondary">Create and manage pricing packages for your clients</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/packages/add" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Package
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

        <!-- Packages List -->
        <?php if (empty($packages)): ?>
            <div class="card text-center bg-warning-light p-8">
                <svg class="w-16 h-16 mx-auto mb-4" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="heading-3 mb-3" style="color: var(--color-warning);">No Packages Found</h3>
                <p class="text-secondary mb-6">
                    Click "Add New Package" to create your first pricing package.
                </p>
                <a href="<?= BASE_PATH ?>/admin/packages/add" class="btn btn-warning">
                    Create Package
                </a>
            </div>
        <?php else: ?>
            <!-- Desktop Table View -->
            <div class="card p-0 overflow-hidden hidden-mobile">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Package Title</th>
                                <th>Base Price</th>
                                <th>Monthly Hosting</th>
                                <th>Pages</th>
                                <th>Tag</th>
                                <th style="width: 180px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($packages as $package): ?>
                                <tr>
                                    <td class="font-semibold text-tertiary">#<?= htmlspecialchars($package['id']) ?></td>
                                    <td>
                                        <span class="font-semibold text-brand"><?= htmlspecialchars($package['title']) ?></span>
                                    </td>
                                    <td class="font-semibold text-success">R<?= number_format($package['price_base'] ?? 0, 2) ?></td>
                                    <td class="text-secondary">R<?= number_format($package['price_hosting_monthly'] ?? 0, 2) ?>/mo</td>
                                    <td><?= htmlspecialchars($package['pages_count'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if ($package['tag']): ?>
                                            <span class="badge badge-warning">
                                                <?= htmlspecialchars($package['tag']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-tertiary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex gap-2 justify-center">
                                            <a href="<?= BASE_PATH ?>/admin/packages/edit?id=<?= $package['id'] ?>" 
                                               class="btn btn-sm btn-ghost"
                                               title="Edit Package">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            
                                            <form method="POST" 
                                                  action="<?= BASE_PATH ?>/admin/packages/delete" 
                                                  style="display:inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete package: <?= htmlspecialchars($package['title']) ?>?');">
                                                <input type="hidden" name="id" value="<?= $package['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-ghost text-error">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
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

            <!-- Mobile Card View -->
            <div class="show-mobile space-y-4">
                <?php foreach ($packages as $package): ?>
                    <div class="card">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <div class="badge badge-outline mb-2">#<?= htmlspecialchars($package['id']) ?></div>
                                <h3 class="font-semibold text-brand mb-2"><?= htmlspecialchars($package['title']) ?></h3>
                                <?php if ($package['tag']): ?>
                                    <span class="badge badge-warning"><?= htmlspecialchars($package['tag']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div>
                                <p class="text-tertiary mb-1">Base Price</p>
                                <p class="font-semibold text-success">R<?= number_format($package['price_base'] ?? 0, 2) ?></p>
                            </div>
                            <div>
                                <p class="text-tertiary mb-1">Monthly Hosting</p>
                                <p class="font-semibold">R<?= number_format($package['price_hosting_monthly'] ?? 0, 2) ?></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-tertiary mb-1">Pages Included</p>
                                <p class="font-semibold"><?= htmlspecialchars($package['pages_count'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="<?= BASE_PATH ?>/admin/packages/edit?id=<?= $package['id'] ?>" 
                               class="btn btn-sm btn-outline flex-1">
                                Edit
                            </a>
                            
                            <form method="POST" 
                                  action="<?= BASE_PATH ?>/admin/packages/delete" 
                                  class="flex-1"
                                  onsubmit="return confirm('Delete this package?');">
                                <input type="hidden" name="id" value="<?= $package['id'] ?>">
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

<style>
.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-16 { width: 4rem; }
.h-16 { height: 4rem; }
.space-y-4 > * + * { margin-top: 1rem; }
.col-span-2 { grid-column: span 2; }
</style>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>