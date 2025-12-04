<?php 
// views/client/dashboard.php

/**
 * @var array $data Contains 'projects' and 'stats' passed from the controller
 */

include VIEW_PATH . '_partials/AdminNav.php'; 

$username = $_SESSION['username'] ?? 'Client';
$projects = $data['projects'] ?? [];
$stats = $data['stats'] ?? [
    'total' => 0,
    'new' => 0,
    'in_progress' => 0,
    'completed' => 0
];

// Helper for status colors
function getStatusBadgeClass($status) {
    return match($status) {
        'New' => 'badge-info',
        'In Progress' => 'badge-primary',
        'Completed' => 'badge-success',
        'On Hold' => 'badge-warning',
        default => 'badge-ghost'
    };
}
?>

<style>
    .client-dashboard { padding-top: 20px; }
    .welcome-section { margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #ddd; }
    .role-info { font-weight: bold; color: #28a745; }
    
    .client-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #eee;
        transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-card h3 { margin: 0 0 10px 0; font-size: 1rem; color: #666; }
    .stat-card .value { font-size: 2rem; font-weight: 700; color: #333; }
    
    .projects-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #eee;
    }
    .projects-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .projects-header h2 { margin: 0; font-size: 1.25rem; }
    
    .project-list { list-style: none; padding: 0; margin: 0; }
    .project-item {
        padding: 20px;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.1s;
    }
    .project-item:last-child { border-bottom: none; }
    .project-item:hover { background: #f9f9f9; }
    
    .project-info h4 { margin: 0 0 5px 0; font-size: 1.1rem; }
    .project-meta { font-size: 0.9rem; color: #666; }
    
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-info { background: #e3f2fd; color: #0d47a1; }
    .badge-primary { background: #e8f5e9; color: #1b5e20; }
    .badge-success { background: #e8f5e9; color: #1b5e20; }
    .badge-warning { background: #fff3e0; color: #e65100; }
    .badge-ghost { background: #f5f5f5; color: #616161; }
    
    .btn-view {
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        color: #333;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-view:hover { background: #f5f5f5; border-color: #ccc; }

    .empty-state {
        padding: 40px;
        text-align: center;
        color: #666;
    }
</style>

<div class="container client-dashboard">
    <div class="welcome-section">
        <h2>👋 Welcome, <?= htmlspecialchars($username) ?>!</h2>
        <p class="role-info">Client Portal</p>
    </div>

    <!-- Stats Grid -->
    <div class="client-grid">
        <div class="stat-card">
            <h3>Active Projects</h3>
            <div class="value"><?= $stats['total'] - $stats['completed'] ?></div>
        </div>
        <div class="stat-card">
            <h3>In Progress</h3>
            <div class="value"><?= $stats['in_progress'] ?></div>
        </div>
        <div class="stat-card">
            <h3>Completed</h3>
            <div class="value"><?= $stats['completed'] ?></div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="projects-section">
        <div class="projects-header">
            <h2>Your Projects</h2>
            <a href="<?= BASE_PATH ?>/get-started" class="btn-view" style="background: #28a745; color: white; border-color: #28a745;">+ New Project</a>
        </div>
        
        <?php if (empty($projects)): ?>
            <div class="empty-state">
                <p>You don't have any active projects yet.</p>
                <a href="<?= BASE_PATH ?>/get-started">Start a new project</a>
            </div>
        <?php else: ?>
            <ul class="project-list">
                <?php foreach ($projects as $project): ?>
                    <li class="project-item">
                        <div class="project-info">
                            <h4><?= htmlspecialchars($project['title']) ?></h4>
                            <div class="project-meta">
                                <span class="badge <?= getStatusBadgeClass($project['status']) ?>">
                                    <?= htmlspecialchars($project['status']) ?>
                                </span>
                                <span style="margin-left: 10px;">
                                    Package: <?= htmlspecialchars($project['package_name']) ?>
                                </span>
                                <span style="margin-left: 10px; color: #999;">
                                    Created: <?= date('M j, Y', strtotime($project['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        <!-- 
                            Future: Link to a detailed project view if needed.
                            For now, we just show the list as requested ("abstract away").
                        -->
                        <!-- <a href="<?= BASE_PATH ?>/client/projects/<?= $project['id'] ?>" class="btn-view">View Details</a> -->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>