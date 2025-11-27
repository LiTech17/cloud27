<?php 
// views/client/dashboard.php

/**
 * @var array $data Contains data passed from the controller, including 'title'
 */

// Include AdminNav to display the protected navigation strip (Client links)
include VIEW_PATH . '_partials/AdminNav.php'; 

// --- Dummy Data for Demonstration ---
// In a real application, this data would come from the controller
$username = $_SESSION['username'] ?? 'Client';
$stats = [
    'active_projects' => 2,
    'open_tickets' => 1,
    'completed_projects' => 5,
];
// ------------------------------------
?>

<style>
    /* Client Dashboard Specific Styles for visibility */
    .client-dashboard {
        padding-top: 20px;
    }
    .welcome-section {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
    }
    .role-info {
        font-weight: bold;
        color: #28a745; /* Green for Client */
    }
    /* Updated grid for more comprehensive layout: 4 columns */
    .client-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .admin-card { /* Reusing Admin card style structure */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex; /* Flex container for content */
        flex-direction: column;
        justify-content: space-between; /* Push button to the bottom */
    }
    .admin-card.primary {
        background-color: #e9f7ef; /* Light green/success background */
        border-left: 5px solid #28a745;
    }
    .admin-card.secondary {
        background-color: #f8f9fa; 
        border-left: 5px solid #6c757d; /* Grey border */
    }
    .admin-card.danger {
        background-color: #fcebeb; /* Light red background */
        border-left: 5px solid #dc3545; /* Red border */
    }
    .admin-card.info {
        background-color: #e2f4ff; /* Light blue background */
        border-left: 5px solid #007bff; /* Blue border */
    }
    .btn-action-card {
        margin-top: 15px;
        display: inline-block;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s;
        text-align: center;
    }
    .btn-action-card.disabled {
        opacity: 0.6;
        cursor: default;
    }
    .btn-primary-card {
        background-color: #28a745;
        color: white;
    }
    .btn-secondary-card {
        background-color: #6c757d;
        color: white;
    }
    .btn-danger-card {
        background-color: #dc3545;
        color: white;
    }

    /* Stats Card Specific Styles */
    .stats-card h3 {
        margin-bottom: 15px;
        color: #007bff;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #ced4da;
    }
    .stat-item:last-child {
        border-bottom: none;
    }
    .stat-label {
        font-weight: 500;
        color: #495057;
    }
    .stat-value {
        font-weight: bold;
        font-size: 1.1em;
    }
    .stat-value.danger {
        color: #dc3545;
    }
    .stat-value.primary {
        color: #28a745;
    }
</style>


<div class="container client-dashboard">
    <div class="welcome-section">
        <h2>👋 Welcome, <?= htmlspecialchars($username) ?>!</h2>
        <p class="role-info">You are logged in to the **Client Portal**.</p>
        <p>This is your restricted area. Here you can view your active projects, access support, and manage your invoices.</p>
    </div>

    <hr>
    
    <div class="client-grid">
        <div class="admin-card info stats-card">
            <h3>Quick Overview 📊</h3>
            <div>
                <div class="stat-item">
                    <span class="stat-label">Active Projects</span>
                    <span class="stat-value primary"><?= htmlspecialchars($stats['active_projects']) ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Open Support Tickets</span>
                    <span class="stat-value danger"><?= htmlspecialchars($stats['open_tickets']) ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Completed Projects</span>
                    <span class="stat-value"><?= htmlspecialchars($stats['completed_projects']) ?></span>
                </div>
            </div>
            <a href="#" class="btn-action-card btn-secondary-card disabled">View Reports (Soon)</a>
        </div>
        
        <div class="admin-card primary">
            <h3>My Projects 📁</h3>
            <p>View the status, scope, and documents for all your ongoing Cloud27 projects.</p>
            <a href="#" class="btn-action-card btn-primary-card disabled">View Projects (Coming Soon)</a>
        </div>
        
        <div class="admin-card secondary">
            <h3>Invoices & Billing 💳</h3>
            <p>Review and download past and current invoices and manage your billing details.</p>
            <a href="#" class="btn-action-card btn-secondary-card disabled">Manage Billing (Coming Soon)</a>
        </div>

        <div class="admin-card danger">
            <h3>Support & Tickets 💬</h3>
            <p>Need help? Submit a new support request or check the status of existing tickets.</p>
            <a href="#" class="btn-action-card btn-danger-card disabled">Open a Ticket (Coming Soon)</a>
        </div>
    </div>
</div>