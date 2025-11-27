<?php
// views/client/edit_projects.php
/**
 * @var array $project Project data (for edit)
 * @var array $errors Validation errors
 * @var array $packages Available service packages
 */
include VIEW_PATH . '_partials/ClientNav.php';

$isEdit = !empty($project);
$pageTitle = $isEdit ? 'Edit Project' : 'Create New Project';
$formAction = $isEdit ? BASE_PATH . '/client/projects/update/' . $project['id'] : BASE_PATH . '/client/projects/store';

// Available statuses
$statuses = ['New', 'In Progress', 'On Hold', 'Awaiting Client', 'Review/QA', 'Completed', 'Canceled'];
?>

<section class="section">
    <div class="container">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h2 class="heading-2 mb-2"><?= $isEdit ? '✏️ Edit Project' : '🚀 Create New Project' ?></h2>
                <p class="text-secondary">
                    <?= $isEdit ? 'Update your project details and requirements.' : 'Start a new project request with Cloud27.' ?>
                </p>
            </div>
            <a href="<?= BASE_PATH ?>/client/projects" class="btn btn-ghost">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Projects
            </a>
        </div>

        <!-- Error Display -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error mb-6 animate-slide-down">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <h4 class="alert-title">Please fix the following errors:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Content -->
            <div class="lg:col-span-2">
                <div class="card">
                    <form method="POST" action="<?= $formAction ?>" class="space-y-6">
                        <!-- Project Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">
                                Project Title <span class="text-error">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   class="form-input <?= isset($errors['title']) ? 'form-input-error' : '' ?>" 
                                   value="<?= htmlspecialchars($project['title'] ?? '') ?>" 
                                   placeholder="e.g., E-commerce Website Redesign"
                                   required>
                            <?php if (isset($errors['title'])): ?>
                                <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['title']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Project Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Project Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      class="form-textarea <?= isset($errors['description']) ? 'form-input-error' : '' ?>"
                                      placeholder="Describe your project goals, requirements, and any specific features you need..."><?= htmlspecialchars($project['description'] ?? '') ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Package Selection -->
                        <div class="form-group">
                            <label for="package_name" class="form-label">
                                Service Package <span class="text-error">*</span>
                            </label>
                            <select id="package_name" 
                                    name="package_name" 
                                    class="form-select <?= isset($errors['package_name']) ? 'form-input-error' : '' ?>" 
                                    required>
                                <option value="">Select a package</option>
                                <?php foreach ($packages as $pkg): ?>
                                    <option value="<?= htmlspecialchars($pkg) ?>" 
                                        <?= !empty($project['package_name']) && $project['package_name'] === $pkg ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pkg) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['package_name'])): ?>
                                <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['package_name']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Status (only for edit) -->
                        <?php if ($isEdit): ?>
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    Project Status
                                </label>
                                <select id="status" 
                                        name="status" 
                                        class="form-select <?= isset($errors['status']) ? 'form-input-error' : '' ?>">
                                    <?php foreach ($statuses as $status): ?>
                                        <option value="<?= htmlspecialchars($status) ?>" 
                                            <?= !empty($project['status']) && $project['status'] === $status ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($status) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['status'])): ?>
                                    <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['status']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Budget and Timeline -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Budget -->
                            <div class="form-group">
                                <label for="budget" class="form-label">
                                    Budget (R)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">R</span>
                                    <input type="number" 
                                           id="budget" 
                                           name="budget" 
                                           step="0.01"
                                           min="0"
                                           class="form-input pl-8 <?= isset($errors['budget']) ? 'form-input-error' : '' ?>" 
                                           value="<?= htmlspecialchars($project['budget'] ?? '0.00') ?>" 
                                           placeholder="0.00">
                                </div>
                                <?php if (isset($errors['budget'])): ?>
                                    <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['budget']) ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Start Date -->
                            <div class="form-group">
                                <label for="start_date" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" 
                                       id="start_date" 
                                       name="start_date" 
                                       class="form-input <?= isset($errors['start_date']) ? 'form-input-error' : '' ?>" 
                                       value="<?= htmlspecialchars($project['start_date'] ?? '') ?>">
                                <?php if (isset($errors['start_date'])): ?>
                                    <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['start_date']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="form-group">
                            <label for="due_date" class="form-label">
                                Due Date
                            </label>
                            <input type="date" 
                                   id="due_date" 
                                   name="due_date" 
                                   class="form-input <?= isset($errors['due_date']) ? 'form-input-error' : '' ?>" 
                                   value="<?= htmlspecialchars($project['due_date'] ?? '') ?>">
                            <?php if (isset($errors['due_date'])): ?>
                                <p class="text-xs text-error mt-1"><?= htmlspecialchars($errors['due_date']) ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="btn btn-primary flex-1">
                                <?php if ($isEdit): ?>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update Project
                                <?php else: ?>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Project
                                <?php endif; ?>
                            </button>
                            <a href="<?= BASE_PATH ?>/client/projects" class="btn btn-outline flex-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Help Card -->
                <div class="card">
                    <h3 class="heading-4 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tips for Success
                    </h3>
                    <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Be specific about your project goals and requirements</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Choose the package that best fits your needs and budget</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Set realistic timelines for project completion</span>
                        </div>
                    </div>
                </div>

                <!-- Package Info Card -->
                <div class="card bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                    <h3 class="heading-4 mb-3">About Packages</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Our service packages are designed to meet different business needs and budgets. 
                        Choose the one that aligns best with your project scope.
                    </p>
                    <a href="<?= BASE_PATH ?>/services" class="btn btn-outline btn-sm w-full justify-center">
                        View All Packages
                    </a>
                </div>

                <!-- Support Card -->
                <div class="card bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h3 class="heading-4 mb-3">Need Help?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        Unsure about which package to choose or need assistance with your project details?
                    </p>
                    <a href="#" class="btn btn-outline btn-sm w-full justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    color: var(--color-text);
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    background: var(--color-surface);
    color: var(--color-text);
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary) 20%, transparent);
}

.form-input-error {
    border-color: var(--color-error);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-error) 20%, transparent);
}

.form-input-error:focus {
    border-color: var(--color-error);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-error) 20%, transparent);
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

/* Animation */
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format budget input
    const budgetInput = document.getElementById('budget');
    if (budgetInput) {
        budgetInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    }

    // Date validation
    const startDateInput = document.getElementById('start_date');
    const dueDateInput = document.getElementById('due_date');
    
    if (startDateInput && dueDateInput) {
        startDateInput.addEventListener('change', function() {
            if (this.value && dueDateInput.value && this.value > dueDateInput.value) {
                dueDateInput.value = '';
            }
        });
        
        dueDateInput.addEventListener('change', function() {
            if (this.value && startDateInput.value && this.value < startDateInput.value) {
                alert('Due date cannot be before start date');
                this.value = '';
            }
        });
    }

    // Form submission enhancement
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <div class="spinner spinner-sm mr-2"></div>
                    ${submitButton.textContent}
                `;
            }
        });
    }
});
</script>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>