<?php 
// views/admin/carousel.php
include VIEW_PATH . '_partials/AdminNav.php'; 
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Manage Hero Carousel</h1>
        <button class="btn btn-primary" onclick="document.getElementById('uploadInput').click()">
            <i class="bi bi-cloud-upload me-2"></i> Upload New Slide
        </button>
        <input type="file" id="uploadInput" hidden accept="image/*">
    </div>

    <!-- Upload Progress -->
    <div id="uploadProgress" class="progress mb-4 d-none" style="height: 5px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
    </div>

    <!-- Slides List -->
    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px;"></th> <!-- Drag Handle -->
                            <th style="width: 120px;">Preview</th>
                            <th>Content</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="slidesList">
                        <?php if (empty($data['slides'])): ?>
                            <tr id="emptyState">
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-images display-4 mb-3 d-block"></i>
                                    <p>No slides found. Upload your first image to get started.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['slides'] as $slide): ?>
                                <tr data-id="<?= $slide['id'] ?>">
                                    <td class="text-center text-muted" style="cursor: move;">
                                        <i class="bi bi-grip-vertical"></i>
                                    </td>
                                    <td>
                                        <img src="<?= BASE_PATH ?>/uploads/hero/thumbnails/<?= $slide['image_filename'] ?>" 
                                             class="rounded shadow-sm" style="width: 100px; height: 60px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <form class="slide-form">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control form-control-sm" name="title" 
                                                           placeholder="Title" value="<?= htmlspecialchars($slide['title'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control form-control-sm" name="subtitle" 
                                                           placeholder="Subtitle" value="<?= htmlspecialchars($slide['subtitle'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control form-control-sm" name="cta_text" 
                                                           placeholder="CTA Text" value="<?= htmlspecialchars($slide['cta_text'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control form-control-sm" name="cta_link" 
                                                           placeholder="CTA Link" value="<?= htmlspecialchars($slide['cta_link'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input toggle-active" type="checkbox" 
                                                   <?= $slide['is_active'] ? 'checked' : '' ?> data-id="<?= $slide['id'] ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-success save-slide me-1" data-id="<?= $slide['id'] ?>" title="Save">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-slide" data-id="<?= $slide['id'] ?>" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slidesList = document.getElementById('slidesList');
    const uploadInput = document.getElementById('uploadInput');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = uploadProgress.querySelector('.progress-bar');

    // Initialize Sortable
    new Sortable(slidesList, {
        animation: 150,
        handle: '.bi-grip-vertical',
        onEnd: function() {
            const order = Array.from(slidesList.querySelectorAll('tr')).map(row => row.dataset.id);
            updateOrder(order);
        }
    });

    // Handle Upload
    uploadInput.addEventListener('change', function() {
        if (this.files.length === 0) return;

        const formData = new FormData();
        formData.append('image', this.files[0]);

        uploadProgress.classList.remove('d-none');
        progressBar.style.width = '0%';

        fetch('<?= BASE_PATH ?>/admin/carousel/upload', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to show new slide
            } else {
                alert('Upload failed: ' + data.error);
            }
        })
        .finally(() => {
            uploadProgress.classList.add('d-none');
            this.value = '';
        });
    });

    // Handle Save
    document.querySelectorAll('.save-slide').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            const formData = new FormData(row.querySelector('form'));

            fetch(`<?= BASE_PATH ?>/admin/carousel/update/${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show toast or visual feedback
                    const icon = this.querySelector('i');
                    icon.classList.remove('bi-check-lg');
                    icon.classList.add('bi-check-circle-fill');
                    setTimeout(() => {
                        icon.classList.remove('bi-check-circle-fill');
                        icon.classList.add('bi-check-lg');
                    }, 2000);
                } else {
                    alert('Update failed');
                }
            });
        });
    });

    // Handle Delete
    document.querySelectorAll('.delete-slide').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to delete this slide?')) return;

            const id = this.dataset.id;
            fetch(`<?= BASE_PATH ?>/admin/carousel/delete/${id}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('tr').remove();
                    if (slidesList.children.length === 0) {
                        location.reload();
                    }
                } else {
                    alert('Delete failed');
                }
            });
        });
    });

    // Handle Toggle
    document.querySelectorAll('.toggle-active').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            fetch(`<?= BASE_PATH ?>/admin/carousel/toggle/${id}`, {
                method: 'POST'
            });
        });
    });

    function updateOrder(order) {
        const formData = new FormData();
        order.forEach(id => formData.append('order[]', id));

        fetch('<?= BASE_PATH ?>/admin/carousel/reorder', {
            method: 'POST',
            body: formData
        });
    }
});
</script>
