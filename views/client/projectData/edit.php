<?php require_once __DIR__ . '/../../_partials/Header.php'; ?>
<?php require_once __DIR__ . '/../../_partials/ClientNav.php'; ?>

<div class="container mt-4">
    <h2>Edit Project Data</h2>

    <form action="/project/save" method="POST">

        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['id']) ?>">

        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" name="business_name"
                   value="<?= htmlspecialchars($data['business_name'] ?? '') ?>"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Business Description</label>
            <textarea name="business_description" class="form-control" required><?= htmlspecialchars($data['business_description'] ?? '') ?></textarea>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>

<?php require_once __DIR__ . '/../../_partials/Footer.php'; ?>
