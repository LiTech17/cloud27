<?php 
// views/admin/about.php

/**
 * @var array $data Contains about content and team members
 */
include VIEW_PATH . '_partials/AdminNav.php';

$content = $data['content'] ?? [];
$teamMembers = $data['teamMembers'] ?? [];
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);
?>

<section class="section animate-fade-in">
    <div class="container">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 border-b border-light pb-6">
            <div>
                <h2 class="heading-2 mb-1">📄 Manage About Page</h2>
                <p class="text-secondary text-sm md:text-base">Control your company information and team members</p>
            </div>
            <div class="flex flex-wrap gap-3 w-full md:w-auto">
                <a href="<?= BASE_PATH ?>/about" class="btn btn-ghost flex-1 md:flex-none justify-center items-center gap-2" target="_blank">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Preview
                </a>
                <a href="<?= BASE_PATH ?>/admin/about/edit" class="btn btn-primary flex-1 md:flex-none justify-center items-center gap-2 shadow-md hover:shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Content
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            <div class="card lg:col-span-2 shadow-sm">
                <h3 class="heading-3 mb-4 text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Company Details
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 bg-secondary rounded-lg">
                        <span class="text-xs text-tertiary uppercase tracking-wider font-semibold">Company Name</span>
                        <p class="font-medium text-lg"><?= htmlspecialchars($content['company_name'] ?? 'Not set') ?></p>
                    </div>
                    <div class="p-3 bg-secondary rounded-lg">
                        <span class="text-xs text-tertiary uppercase tracking-wider font-semibold">Founded</span>
                        <p class="font-medium text-lg"><?= htmlspecialchars($content['founded_year'] ?? 'Not set') ?></p>
                    </div>
                    <div class="p-3 bg-secondary rounded-lg">
                        <span class="text-xs text-tertiary uppercase tracking-wider font-semibold">Team Size</span>
                        <p class="font-medium text-lg"><?= htmlspecialchars($content['employee_count'] ?? 'Not set') ?></p>
                    </div>
                    <div class="p-3 bg-secondary rounded-lg">
                        <span class="text-xs text-tertiary uppercase tracking-wider font-semibold">Location</span>
                        <p class="font-medium text-lg truncate"><?= htmlspecialchars($content['office_location'] ?? 'Not set') ?></p>
                    </div>
                    <div class="p-3 bg-secondary rounded-lg sm:col-span-2">
                        <span class="text-xs text-tertiary uppercase tracking-wider font-semibold">Tagline</span>
                        <p class="font-medium text-secondary italic">"<?= htmlspecialchars($content['tagline'] ?? 'Not set') ?>"</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <h3 class="heading-3 mb-4 text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Visual Assets
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold">Hero Banner</span>
                            <?php if (empty($content['hero_image'])): ?>
                                <span class="badge bg-warning-light text-warning text-xs px-2 py-1 rounded">Missing</span>
                            <?php endif; ?>
                        </div>
                        <div class="w-full h-32 bg-secondary rounded-lg overflow-hidden relative border border-light">
                            <?php if (!empty($content['hero_image'])): ?>
                                <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($content['hero_image']) ?>" 
                                     class="w-full h-full object-cover transition-transform hover:scale-105" alt="Hero">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-tertiary text-sm">No Image</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold">Company Photo</span>
                        </div>
                        <div class="w-full h-32 bg-secondary rounded-lg overflow-hidden relative border border-light">
                            <?php if (!empty($content['company_image'])): ?>
                                <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($content['company_image']) ?>" 
                                     class="w-full h-full object-cover transition-transform hover:scale-105" alt="Company">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-tertiary text-sm">No Image</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg border-t-4 border-primary">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="heading-3 text-xl font-bold">Team Members</h3>
                    <p class="text-sm text-secondary mt-1">Manage the faces behind the brand</p>
                </div>
                <a href="<?= BASE_PATH ?>/admin/about/team/add" class="btn btn-primary btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Member
                </a>
            </div>

            <?php if (empty($teamMembers)): ?>
                <div class="text-center py-12 bg-bg-secondary rounded-xl border-2 border-dashed border-border-dark">
                    <div class="w-16 h-16 bg-bg-tertiary rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h4 class="font-medium text-lg">No team members yet</h4>
                    <p class="text-secondary mb-4 max-w-xs mx-auto">Start building your team section by adding your first member.</p>
                    <a href="<?= BASE_PATH ?>/admin/about/team/add" class="btn btn-outline btn-sm">Add First Member</a>
                </div>
            <?php else: ?>
                
                <div class="hidden-mobile overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-light text-xs uppercase tracking-wider text-tertiary">
                                <th class="pb-3 pl-2 font-semibold">Profile</th>
                                <th class="pb-3 font-semibold">Name & Role</th>
                                <th class="pb-3 font-semibold">Sort Order</th>
                                <th class="pb-3 pr-2 text-right font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-light">
                            <?php foreach ($teamMembers as $member): ?>
                                <tr class="hover:bg-secondary transition-colors">
                                    <td class="py-4 pl-2">
                                        <?php if (!empty($member['image_path'])): ?>
                                            <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($member['image_path']) ?>" 
                                                 alt="<?= htmlspecialchars($member['name']) ?>"
                                                 class="w-12 h-12 rounded-full object-cover border border-light shadow-sm">
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center text-brand font-bold text-lg">
                                                <?= strtoupper(substr($member['name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-semibold text-primary"><?= htmlspecialchars($member['name']) ?></p>
                                        <p class="text-sm text-secondary"><?= htmlspecialchars($member['position']) ?></p>
                                    </td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 bg-secondary rounded text-xs font-mono text-tertiary">
                                            <?= htmlspecialchars($member['display_order']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 pr-2 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="<?= BASE_PATH ?>/admin/about/team/edit?id=<?= $member['id'] ?>" 
                                               class="p-2 text-tertiary hover:text-primary hover:bg-info-light rounded transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form method="POST" action="<?= BASE_PATH ?>/admin/about/team/delete" class="inline" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                                <button type="submit" class="p-2 text-tertiary hover:text-error hover:bg-error-light rounded transition-colors" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="show-mobile grid gap-4">
                    <?php foreach ($teamMembers as $member): ?>
                        <div class="bg-bg-secondary rounded-xl p-4 border border-border flex flex-col gap-4 relative overflow-hidden">
                            
                            <div class="flex items-start gap-4">
                                <?php if (!empty($member['image_path'])): ?>
                                    <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($member['image_path']) ?>" 
                                         alt="<?= htmlspecialchars($member['name']) ?>"
                                         class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm">
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-full bg-brand-light flex items-center justify-center text-brand text-xl font-bold border-2 border-white shadow-sm">
                                        <?= strtoupper(substr($member['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-lg truncate"><?= htmlspecialchars($member['name']) ?></h4>
                                    <p class="text-secondary text-sm mb-2"><?= htmlspecialchars($member['position']) ?></p>
                                    <div class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-bg-tertiary text-text-tertiary border border-border-dark">
                                        Order: <?= htmlspecialchars($member['display_order']) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-border-dark">
                                <a href="<?= BASE_PATH ?>/admin/about/team/edit?id=<?= $member['id'] ?>" 
                                   class="flex justify-center items-center gap-2 py-2 px-4 bg-white border border-border rounded-lg text-sm font-medium text-text-secondary hover:bg-bg-tertiary active:bg-bg-secondary transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit
                                </a>
                                <form method="POST" action="<?= BASE_PATH ?>/admin/about/team/delete" class="w-full" onsubmit="return confirm('Delete <?= htmlspecialchars($member['name']) ?>?');">
                                    <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-2 px-4 bg-white border border-error-light text-error rounded-lg text-sm font-medium hover:bg-error-light active:bg-error-light transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>