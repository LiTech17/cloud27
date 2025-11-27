<?php
// views/admin/projects/create.php

/**
 * @var array $data Contains data passed from the controller
 */

// Shared partials / site-wide styles are used like in index.php
include VIEW_PATH . '_partials/AdminNav.php';

$clients = $clients ?? [];
$packages = $packages ?? ['Basic', 'Standard', 'Enterprise'];
$errors = $errors ?? [];
$formData = $data['formData'] ?? [];
$title = $title ?? 'Create New Project';
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

// Optional server-provided status / flash (controller may set $data['statusMessage'] etc.)
$statusMessage = $data['statusMessage'] ?? '';
$statusType = $data['statusType'] ?? 'alert-info';

// pre-calc today's date for default
$today = date('Y-m-d');
?>

<section class="section">
    <div class="container">
        <!-- Header (matches index page layout) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2">📁 Create Project</h2>
                <p class="text-secondary">Create a new client project — fill in the details below.</p>
            </div>

            <div class="flex gap-2">
                <a href="<?= BASE_PATH ?>/admin/projects" class="btn btn-ghost">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>

        <!-- Flash message (server-side) -->
        <?php if (!empty($message) || !empty($statusMessage)): 
            $msg = $message ?: $statusMessage;
            $isSuccess = ($statusType === 'alert-success') || (!empty($data['success']) && $data['success'] === true);
        ?>
            <div class="alert <?= $isSuccess ? 'alert-success' : 'alert-error' ?> mb-6 animate-slide-down">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?php if ($isSuccess): ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <?php else: ?>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <?php endif; ?>
                </svg>
                <div class="alert-content">
                    <p class="alert-message"><?= htmlspecialchars($msg) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card (consistent with index card style) -->
        <div class="card mb-6">
            <form id="projectForm" method="POST" action="<?= BASE_PATH ?>/admin/projects/store" class="space-y-6">
                <!-- Client -->
                <div class="form-group">
                    <label for="client_id" class="form-label">Client</label>
                    <select id="client_id" name="client_id" required
                            class="form-input form-select <?= isset($errors['client_id']) ? 'form-input-error' : '' ?>">
                        <option value="" disabled <?= empty($formData['client_id']) ? 'selected' : '' ?>>Select a Client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= htmlspecialchars($client['id']) ?>"
                                <?= (isset($formData['client_id']) && $formData['client_id'] == $client['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['username']) ?> (ID: <?= htmlspecialchars($client['id']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-error mt-1" data-field="client_id"><?= htmlspecialchars($errors['client_id'] ?? '') ?></p>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label for="title" class="form-label">Project Title</label>
                    <input type="text" id="title" name="title" required
                           class="form-input <?= isset($errors['title']) ? 'form-input-error' : '' ?>"
                           placeholder="Website Redesign" value="<?= htmlspecialchars($formData['title'] ?? '') ?>">
                    <p class="text-xs text-error mt-1" data-field="title"><?= htmlspecialchars($errors['title'] ?? '') ?></p>
                </div>

                <!-- Package -->
                <div class="form-group">
                    <label for="package_name" class="form-label">Package</label>
                    <select id="package_name" name="package_name" required
                            class="form-input form-select <?= isset($errors['package_name']) ? 'form-input-error' : '' ?>">
                        <?php foreach ($packages as $package): ?>
                            <option value="<?= htmlspecialchars($package) ?>"
                                <?= (isset($formData['package_name']) && $formData['package_name'] == $package) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($package) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-error mt-1" data-field="package_name"><?= htmlspecialchars($errors['package_name'] ?? '') ?></p>
                </div>

                <!-- Budget -->
                <div class="form-group">
                    <label for="budget" class="form-label">Budget (R)</label>
                    <input type="number" step="0.01" id="budget" name="budget"
                           class="form-input <?= isset($errors['budget']) ? 'form-input-error' : '' ?>"
                           placeholder="1500.00" value="<?= htmlspecialchars($formData['budget'] ?? '0.00') ?>">
                    <p class="text-xs text-error mt-1" data-field="budget"><?= htmlspecialchars($errors['budget'] ?? '') ?></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Start Date -->
                    <div class="form-group">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date"
                               class="form-input <?= isset($errors['start_date']) ? 'form-input-error' : '' ?>"
                               value="<?= htmlspecialchars($formData['start_date'] ?? $today) ?>">
                        <p class="text-xs text-error mt-1" data-field="start_date"><?= htmlspecialchars($errors['start_date'] ?? '') ?></p>
                    </div>

                    <!-- Due Date -->
                    <div class="form-group">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" id="due_date" name="due_date"
                               class="form-input <?= isset($errors['due_date']) ? 'form-input-error' : '' ?>"
                               value="<?= htmlspecialchars($formData['due_date'] ?? '') ?>">
                        <p class="text-xs text-error mt-1" data-field="due_date"><?= htmlspecialchars($errors['due_date'] ?? '') ?></p>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="form-textarea <?= isset($errors['description']) ? 'form-input-error' : '' ?>"
                              placeholder="Detailed scope of work, features, and requirements..."><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                    <p class="text-xs text-error mt-1" data-field="description"><?= htmlspecialchars($errors['description'] ?? '') ?></p>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit" id="submitButton" class="btn btn-primary w-full" style="max-width: 260px;">
                        <span id="btn-text">Create Project</span>
                        <span id="loadingSpinner" class="spinner spinner-sm" style="display:none;"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include VIEW_PATH . '_partials/Footer.php'; ?>

<!-- Inline script kept at bottom similar to index page patterns -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('projectForm');
    const submitButton = document.getElementById('submitButton');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('loadingSpinner');
    const BASE_PATH = '<?= rtrim(BASE_PATH, "/") ?>';

    // Collect all possible helper nodes
    const helpers = Array.from(document.querySelectorAll('.text-xs.text-error'));
    const formInputs = Array.from(document.querySelectorAll('.form-input, .form-textarea, .form-select'));

    function clearErrors() {
        helpers.forEach(h => h.textContent = '');
        formInputs.forEach(i => i.classList.remove('form-input-error'));
    }

    function showStatus(message, isSuccess) {
        // create an alert node similar to index's markup and insert above the card
        const container = document.querySelector('.container');
        const existing = container.querySelector('.alert');
        if (existing) existing.remove();

        const div = document.createElement('div');
        div.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-error') + ' mb-6 animate-slide-down';
        div.innerHTML = `
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${isSuccess
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                }
            </svg>
            <div class="alert-content"><p class="alert-message">${message}</p></div>
        `;
        container.prepend(div);
        div.scrollIntoView({ behavior: 'smooth' });
    }

    function displayErrors(errors) {
        clearErrors();
        let first = null;
        for (const [field, msg] of Object.entries(errors)) {
            const helper = document.querySelector(`.text-xs.text-error[data-field="${field}"]`) || document.querySelector(`.text-xs.text-error[data-field="${field}"]`);
            const input = document.getElementById(field);
            if (helper) helper.textContent = msg;
            // fallback: try to locate by attribute match (some helpers in markup don't have data-field attr)
            if (!helper) {
                const f = document.querySelector(`[data-field="${field}"]`);
                if (f) f.textContent = msg;
            }
            if (input) {
                input.classList.add('form-input-error');
                if (!first) first = input;
            }
        }
        showStatus('Please correct the validation errors below.', false);
        if (first) {
            first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            first.focus();
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearErrors();

        submitButton.disabled = true;
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action');

        try {
            const res = await fetch(actionUrl, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                showStatus(data.message || 'Project created successfully.', true);
                // redirect if server sent a redirect URL or fallback to projects index
                const redirectUrl = data.redirect || (BASE_PATH + '/admin/projects');
                setTimeout(() => { window.location.href = redirectUrl; }, 1200);
                return;
            }

            if (res.status === 422 && data.errors) {
                displayErrors(data.errors);
            } else if (res.status === 403) {
                showStatus(data.error || 'Access denied. Please log in.', false);
                setTimeout(() => { window.location.href = BASE_PATH + '/login'; }, 1500);
            } else {
                showStatus(data.message || ('An unexpected error occurred (' + res.status + ').'), false);
            }
        } catch (err) {
            console.error('Network error', err);
            showStatus('Network error. Could not connect to the server.', false);
        } finally {
            // restore unless we redirected
            submitButton.disabled = false;
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
        }
    });
});
</script>
