<?php 
// views/client/projectData/create.php

require_once __DIR__ . '/../../_partials/Header.php'; 
require_once __DIR__ . '/../../_partials/ClientNav.php'; 

// --- PHP Logic ---

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helper function to safely echo values
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Detect dark mode preference
$darkMode = $_SESSION['dark_mode'] ?? false;

// Prepare data for the form
// Note: $projectId must be defined before this script is executed, e.g., in the controller.
$projectData = $projectData ?? [];
$oldData = $_SESSION['old'] ?? [];
// IMPORTANT: Merges data from DB/Session for pre-filling the form.
$data = array_merge($projectData, $oldData);

// Define step labels
$steps = [
    'Business Fundamentals',
    'Goals & Objectives',
    'Target Audience',
    'Website Structure',
    'Features & Functionality',
    'Design Preferences',
    'Technical Requirements',
    'Content & Support',
    'Budget & Timeline',
    'File Uploads & Review'
];

// Define select options 
$fieldOptions = [
    'budget_ranges' => [
        '1k-3k' => '€1,000 - €3,000',
        '3k-5k' => '€3,000 - €5,000',
        '5k-10k' => '€5,000 - €10,000',
        '10k+' => '€10,000+'
    ],
    'timelines' => [
        '1-3w' => '1-3 Weeks (Urgent)',
        '4-6w' => '4-6 Weeks (Standard)',
        '7-10w' => '7-10 Weeks (Flexible)'
    ]
];

// Check for any existing errors
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

// --- END PHP Logic ---
?>

<head>
    <!-- Add this meta tag to provide base path to JavaScript -->
    <meta name="base-path" content="<?= BASE_PATH ?>">

<link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/onboarding.css">
</head>

<section class="section">
    <div class="container">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2">📋 Project Questionnaire</h2>
                <p class="text-secondary">Complete all steps to provide detailed information for your project.</p>
            </div>

            <div class="flex gap-2">
                <a href="<?= BASE_PATH ?>/client/projects" class="btn btn-ghost">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>

        <?php if (!empty($message)): ?>
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

        <div class="alert alert-info mb-6 animate-slide-down">
            <i class="fa-solid fa-cloud-arrow-up alert-icon"></i>
            <div class="alert-content">
                <p class="alert-title font-semibold">Automatic Draft Saving</p>
                <p class="alert-message text-sm">
                    Your progress is **automatically saved** locally to your browser as you fill out the fields. You must be sure of your inputs, as changes are saved instantly.
                </p>
            </div>
        </div>
        <div class="card mb-6">
            <div class="onboarding-wrapper <?= $darkMode ? 'dark' : '' ?>">
                
                <div class="step-sidebar">
                    <h3 class="text-xl font-bold mb-4" style="color: var(--clr-text);">Project Setup</h3>
                    
                    <div class="mb-6">
                        <span class="text-sm font-semibold block mb-2" style="color: var(--clr-text-secondary);">Progress</span>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-2">
                            <div id="progress-bar" class="h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <span class="text-sm font-bold block" id="progress-text" style="color: var(--clr-primary);">0% Complete</span>
                    </div>

                    <div id="stepList" class="space-y-2">
                        <?php foreach ($steps as $i => $label): ?>
                            <div class="step-item <?= $i === 0 ? 'active' : '' ?>" data-step="<?= $i ?>">
                                <i class="fa-solid fa-circle-dot step-icon"></i>
                                <span class="step-label text-sm"><?= e($label) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="step-content-area">
                    <h1 class="heading-3 mb-2">Website Questionnaire</h1>
                    <p class="text-secondary mb-6">
                        Please complete all steps to ensure we have the necessary information for your project.
                    </p>
                    
                    <div id="ajax-message" class="hidden mb-4 p-4 rounded transition-all"></div>
                    <form id="project-data-form" 
                      method="POST" 
                      action="<?= BASE_PATH ?>/client/project-data/update/<?= $projectId ?>" 
                      enctype="multipart/form-data"
                      data-project-id="<?= $projectId ?>">
    <!-- Your form fields -->


                    <!-- Your form fields -->                        
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="project_id" value="<?= e($projectId) ?>">
                        <input type="hidden" id="current_step" name="current_step" value="0">

                        <?php for ($i = 0; $i < count($steps); $i++): ?>
                            <div class="step-content <?= $i === 0 ? 'active' : '' ?>" id="step-<?= $i ?>" data-step-name="<?= e($steps[$i]) ?>">
                                <?php 
                                $stepFile = __DIR__ . "/steps/step" . ($i + 1) . ".php";
                                if (file_exists($stepFile)) {
                                    // NOTE: Step files (containing the form fields) are now correctly included.
                                    include $stepFile;
                                } else {
                                    echo "<div class='alert alert-error'>Step file not found: step" . ($i + 1) . ".php</div>";
                                }
                                ?>
                            </div>
                        <?php endfor; ?>

                        <div class="form-navigation-footer flex justify-between mt-8 pt-6 border-t">
                            <button type="button" id="prevBtn" class="btn btn-ghost px-6 py-2" style="display: none;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Back
                            </button>
                            <div class="flex gap-3">
                                <button type="button" id="nextBtn" class="btn btn-primary px-6 py-2">
                                    Next
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-success px-6 py-2 flex items-center gap-2" style="display: none;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Submit Questionnaire
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</section>

<div id="autosave-indicator" class="fixed bottom-4 right-4 bg-success text-white px-4 py-2 rounded-lg shadow-lg hidden z-50">
    <span id="autosave-text">Saved ✓</span>
</div>

<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="card p-6 text-center max-w-sm">
        <div class="spinner spinner-lg mx-auto mb-4"></div>
        <p class="text-secondary">Processing your request...</p>
    </div>
</div>

<script src="<?= BASE_PATH ?>/assets/js/onboarding.js"></script>

<?php require_once __DIR__ . '/../../_partials/Footer.php'; ?>