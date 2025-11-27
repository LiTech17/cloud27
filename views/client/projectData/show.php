<?php require_once __DIR__ . '/../../_partials/Header.php'; ?>
<?php require_once __DIR__ . '/../../_partials/ClientNav.php'; ?>

<div class="container mt-4">
    <h2>Project Data Overview</h2>

    <table class="table table-bordered">
        <tr>
            <th>Business Name</th>
            <td><?= htmlspecialchars($data['business_name'] ?? 'N/A') ?></td>
        </tr>

        <tr>
            <th>Business Description</th>
            <td><?= nl2br(htmlspecialchars($data['business_description'] ?? 'N/A')) ?></td>
        </tr>
    </table>

    <a href="/project/edit/<?= $project['id'] ?>" class="btn btn-warning">Edit Data</a>
</div>

<?php require_once __DIR__ . '/../../_partials/Footer.php'; ?>
