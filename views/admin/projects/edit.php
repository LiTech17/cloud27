<?php
// views/admin/projects/edit.php (also works for create)
/**
 * @var array $data Contains project, errors, and packages
 */
include VIEW_PATH . '_partials/AdminNav.php';

$project = $data['project'] ?? null;
$errors = $data['errors'] ?? [];
$packages = $data['packages'] ?? [];
$isEdit = $project !== null;
$title = $isEdit ? 'Edit Project: ' . htmlspecialchars($project['title']) : 'Create New Project';
?>

<section class="section">
    <div class="container-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2"><?= $isEdit ? '✏️' : '➕' ?> <?= htmlspecialchars($title) ?></h2>
                <p class="text-secondary">Fill in the project details below</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/projects" class="btn btn-ghost">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Projects
            </a>
        </div>

        <!-- Error Alert -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error mb-6 animate-slide-down">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-title">Please fix the following errors:</p>
                    <ul class="list-disc pl-5 mt-2">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card shadow-lg">
            <form method="POST" 
                  action="<?= $isEdit ? BASE_PATH . '/admin/projects/update/' . $project['id'] : BASE_PATH . '/admin/projects/store' ?>" 
                  id="projectForm">
                
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="client_id" class="form-label form-label-required">Client ID</label>
                            <input type="number" 
                                   id="client_id" 
                                   name="client_id" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($project['client_id'] ?? '') ?>"
                                   min="1"
                                   required>
                            <span class="form-helper">The user ID of the client requesting this project</span>
                        </div>

                        <div class="form-group">
                            <label for="package_name" class="form-label form-label-required">Service Package</label>
                            <select id="package_name" name="package_name" class="form-select" required>
                                <option value="">-- Select Package --</option>
                                <?php foreach ($packages as $pkg): ?>
                                    <option value="<?= htmlspecialchars($pkg) ?>" 
                                            <?= !empty($project['package_name']) && $project['package_name'] === $pkg ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pkg) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-6">
                        <label for="title" class="form-label form-label-required">Project Title</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               class="form-input" 
                               value="<?= htmlspecialchars($project['title'] ?? '') ?>"
                               placeholder="e.g., Company Website Redesign"
                               required
                               minlength="5"
                               maxlength="255">
                        <span class="form-helper">A clear, descriptive title for this project (5-255 characters)</span>
                    </div>

                    <div class="form-group mt-6">
                        <label for="description" class="form-label">Project Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="form-textarea" 
                                  rows="6"
                                  placeholder="Describe the project requirements, goals, and any specific details..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                        <span class="form-helper">Detailed project description (optional but recommended)</span>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Project Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <?php 
                                $statuses = ['New', 'In Progress', 'On Hold', 'Awaiting Client', 'Review/QA', 'Completed', 'Canceled'];
                                foreach ($statuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>" 
                                            <?= !empty($project['status']) && $project['status'] === $status ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($status) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="form-helper">Current project status</span>
                        </div>

                        <div class="form-group">
                            <label for="budget" class="form-label">Budget (R)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-tertiary font-semibold">R</span>
                                <input type="number" 
                                       id="budget" 
                                       name="budget" 
                                       class="form-input" 
                                       style="padding-left: 2.5rem;"
                                       value="<?= htmlspecialchars($project['budget'] ?? '0.00') ?>"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                            </div>
                            <span class="form-helper">Project budget amount</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($project['start_date'] ?? '') ?>">
                            <span class="form-helper">When the project begins</span>
                        </div>

                        <div class="form-group">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" 
                                   id="due_date" 
                                   name="due_date" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($project['due_date'] ?? '') ?>">
                            <span class="form-helper">Expected completion date</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" class="btn btn-primary btn-lg flex-1 sm:flex-initial" id="submitBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="btnText"><?= $isEdit ? 'Update Project' : 'Create Project' ?></span>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/projects" class="btn btn-ghost btn-lg flex-1 sm:flex-initial">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="card bg-info-light mt-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 flex-shrink-0" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="font-semibold mb-2" style="color: var(--color-info);">Tips for Project Management</h4>
                    <ul class="text-sm space-y-1" style="color: var(--color-info); opacity: 0.9;">
                        <li>• Set realistic timelines and budgets</li>
                        <li>• Update project status regularly</li>
                        <li>• Include detailed descriptions for clarity</li>
                        <li>• Communicate with clients about progress</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.relative { position: relative; }
.absolute { position: absolute; }
.left-3 { left: 0.75rem; }
.top-1\/2 { top: 50%; }
.transform { transform: translateY(-50%); }
.space-y-1 > * + * { margin-top: 0.25rem; }
.list-disc { list-style-type: disc; }
.pl-5 { padding-left: 1.25rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const startDateInput = document.getElementById('start_date');
    const dueDateInput = document.getElementById('due_date');
    
    // Form submission loading state
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';
        });
    }
    
    // Date validation
    if (startDateInput && dueDateInput) {
        dueDateInput.addEventListener('change', function() {
            if (startDateInput.value && dueDateInput.value) {
                if (new Date(dueDateInput.value) < new Date(startDateInput.value)) {
                    alert('Due date cannot be before start date');
                    dueDateInput.value = '';
                }
            }
        });
    }
});
</script>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>