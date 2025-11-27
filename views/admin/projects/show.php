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