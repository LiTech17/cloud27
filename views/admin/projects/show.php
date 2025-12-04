<?php 
// views/admin/projects/show.php
require VIEW_PATH . '_partials/AdminNav.php'; 

// Extract data
$project = $data['project'] ?? null;
$client = $data['client'] ?? null;

if (!$project) {
    echo "<h1>Project not found</h1>";
    exit;
}
?>

<div class="p-6 sm:p-10">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-folder-open text-primary"></i>
            Project Details
        </h1>

        <a href="<?= BASE_PATH ?>/admin/projects" 
           class="btn btn-sm btn-outline">
           <i class="fa-solid fa-arrow-left"></i> Back to Projects
        </a>
    </div>


    <!-- Project Overview Card -->
    <div class="bg-base-100 shadow rounded-xl p-6 border border-base-300">

        <div class="grid md:grid-cols-2 gap-6">

            <!-- Left Column -->
            <div>
                <h2 class="text-xl font-semibold mb-3">Project Information</h2>

                <p class="mb-2">
                    <strong>Title:</strong><br>
                    <?= htmlspecialchars($project['title']); ?>
                </p>

                <p class="mb-2">
                    <strong>Description:</strong><br>
                    <?= nl2br(htmlspecialchars($project['description'])); ?>
                </p>

                <p class="mb-2">
                    <strong>Package:</strong><br>
                    <span class="badge badge-primary text-sm">
                        <?= htmlspecialchars($project['package_name']); ?>
                    </span>
                </p>

                <p class="mb-2">
                    <strong>Status:</strong><br>
                    <span class="badge <?= (new \Models\ProjectModel())->getStatusColor($project['status']); ?> text-sm">
                        <?= htmlspecialchars($project['status']); ?>
                    </span>
                </p>

                <p class="mb-2">
                    <strong>Budget:</strong><br>
                    R <?= number_format($project['budget'], 2); ?>
                </p>
            </div>


            <!-- Right Column -->
            <div>
                <h2 class="text-xl font-semibold mb-3">Client Information</h2>

                <?php if (!empty($client)): ?>
                    <p class="mb-2">
                        <strong>Client Name:</strong><br>
                        <?= htmlspecialchars($client['username']); ?>
                    </p>

                    <p class="mb-2">
                        <strong>Client Email:</strong><br>
                        <?= htmlspecialchars($client['email']); ?>
                    </p>

                <?php else: ?>
                    <p class="text-red-500">Client record not found.</p>
                <?php endif; ?>

                <h2 class="text-xl font-semibold mt-6 mb-3">Timeline</h2>

                <p class="mb-2">
                    <strong>Start Date:</strong><br>
                    <?= $project['start_date'] ?: 'Not set'; ?>
                </p>

                <p class="mb-2">
                    <strong>Due Date:</strong><br>
                    <?= $project['due_date'] ?: 'Not set'; ?>
                </p>

                <p class="mb-2">
                    <strong>Created At:</strong><br>
                    <?= $project['created_at']; ?>
                </p>

                <p class="mb-2">
                    <strong>Last Updated:</strong><br>
                    <?= $project['updated_at']; ?>
                </p>
            </div>

        </div>
    </div>

    <!-- Onboarding Details Section -->
    <?php if (!empty($data['onboardingDetails'])): $details = $data['onboardingDetails']; ?>
    <div class="bg-base-100 shadow rounded-xl p-6 border border-base-300 mt-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Onboarding Submission</h2>
            <!-- Action to Create Business Profile from this data -->
            <a href="<?= BASE_PATH ?>/admin/projects/<?= $project['id'] ?>/create-profile" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-magic-wand-sparkles"></i> Create Business Profile
            </a>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Business Profile</h3>
                <p class="mb-2"><strong>Company:</strong> <?= htmlspecialchars($details['company_name'] ?? '-') ?></p>
                <p class="mb-2"><strong>Industry:</strong> <?= htmlspecialchars($details['industry'] ?? '-') ?></p>
                <p class="mb-2"><strong>Mission:</strong> <?= htmlspecialchars($details['mission_statement'] ?? '-') ?></p>
                <p class="mb-2"><strong>USP:</strong> <?= htmlspecialchars($details['usp'] ?? '-') ?></p>
            </div>
            <div>
                <h3 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Contact & Brand</h3>
                <p class="mb-2"><strong>Rep:</strong> <?= htmlspecialchars($details['rep_name'] ?? '-') ?> (<?= htmlspecialchars($details['rep_role'] ?? '-') ?>)</p>
                <p class="mb-2"><strong>Contact:</strong> <?= htmlspecialchars($details['rep_email'] ?? '-') ?> / <?= htmlspecialchars($details['rep_phone'] ?? '-') ?></p>
                <p class="mb-2"><strong>Colors:</strong> <?= htmlspecialchars($details['brand_colors'] ?? '-') ?></p>
                
                <?php if (!empty($details['logo_path'])): ?>
                    <div class="mt-2">
                        <strong>Uploaded Logo:</strong><br>
                        <a href="<?= BASE_PATH ?>/uploads/logos/<?= htmlspecialchars($details['logo_path']) ?>" target="_blank" class="text-primary underline text-sm">View Logo</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <h3 class="font-bold text-gray-500 mb-2 uppercase text-xs tracking-wide">Project Requirements</h3>
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


    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">

        <!-- FIXED: Use path parameter instead of query string -->
        <a href="<?= BASE_PATH ?>/admin/projects/edit/<?= $project['id']; ?>"
           class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Project
        </a>

        <!-- FIXED: Use correct POST route with path parameter -->
        <form action="<?= BASE_PATH ?>/admin/projects/destroy/<?= $project['id']; ?>" 
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.');">
            <button type="submit" class="btn btn-error">
                <i class="fa-solid fa-trash"></i> Delete
            </button>
        </form>

    </div>

</div>

<?php require VIEW_PATH . '_partials/Footer.php'; ?>