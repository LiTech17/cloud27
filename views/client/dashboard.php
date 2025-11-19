<?php 
// views/client/dashboard.php

/**
 * @var array $data Contains data passed from the controller, including 'title'
 */

// Include AdminNav to display the protected navigation strip (Client links)
include VIEW_PATH . '_partials/AdminNav.php'; 

$username = $_SESSION['username'] ?? 'Client';
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
    .client-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    .admin-card { /* Reusing Admin card style structure */
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .admin-card.primary {
        background-color: #e9f7ef; /* Light green/success background */
        border-left: 5px solid #28a745;
    }
    .admin-card.secondary {
        background-color: #f8f9fa; 
        border-left: 5px solid #6c757d; /* Grey border */
    }
    .btn-action-card {
        margin-top: 15px;
        display: inline-block;
        padding: 8px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        transition: background-color 0.2s;
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
</style>


<div class="container client-dashboard">
    <div class="welcome-section">
        <h2>👋 Welcome, <?= htmlspecialchars($username) ?>!</h2>
        <p class="role-info">You are logged in to the **Client Portal**.</p>
        <p>This is your restricted area. Here you can view your active projects, access support, and manage your invoices (future development).</p>
    </div>

    <hr>
    
    <div class="client-grid">
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
    </div>
</div>

