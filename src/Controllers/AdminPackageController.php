<?php
// src/Controllers/AdminPackageController.php

namespace Controllers;

use Models\PackageModel;

/**
 * Handles both public viewing and administrative CRUD for pricing packages.
 */
class AdminPackageController extends BaseController {

    // ------------------------------------------------------------------------
    // PUBLIC METHODS
    // ------------------------------------------------------------------------

    /**
     * Displays the public pricing page.
     */
    public function showPackages(): void {
        $packageModel = new PackageModel();
        $packages = $packageModel->getAllPackages();

        $this->render('packages/index', [
            'title' => 'Turnkey Website Development Packages',
            'packages' => $packages
        ]);
    }

    // ------------------------------------------------------------------------
    // ADMIN CRUD METHODS
    // ------------------------------------------------------------------------

    /**
     * 1. Display the list of all packages (READ).
     */
    public function listPackages(): void {
        $packageModel = new PackageModel();
        $packages = $packageModel->getAllPackages();
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);

        $this->render('admin/packages', [
            'title' => 'Manage Pricing Packages',
            'packages' => $packages,
            'message' => $message
        ]);
    }

    /**
     * 2. Display the form for adding a new package or editing an existing one (CREATE/UPDATE GET).
     */
    public function showEditForm(): void {
        $package = null;
        $id = $_GET['id'] ?? null; 
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        if ($id) {
            $packageModel = new PackageModel();
            $package = $packageModel->getPackageById((int)$id);

            if (!$package) {
                $_SESSION['message'] = 'Package not found.';
                header('Location: ' . BASE_PATH . '/admin/packages');
                exit;
            }
            // For editing, convert the features array back to newline-separated string for the textarea
            $package['features_string'] = implode("\n", $package['features']);
        } else {
            // Default empty string for new packages
            $package = ['features_string' => ''];
        }

        $this->render('admin/edit_package', [
            'title' => ($id ? 'Edit' : 'Add') . ' Package',
            'package' => $package,
            'error' => $error
        ]);
    }

    /**
     * 3. Process the form submission to save (add or edit) a package (CREATE/UPDATE POST).
     */
    public function savePackage(): void {
        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $priceBase = $_POST['price_base'] ?? 0.00;
        $priceHosting = $_POST['price_hosting_monthly'] ?? 0.00;
        $pagesCount = trim($_POST['pages_count'] ?? '');
        $tag = trim($_POST['tag'] ?? null);
        $featuresString = trim($_POST['features'] ?? '');

        // Basic validation
        if (empty($title) || empty($pagesCount) || $priceBase <= 0) {
            $_SESSION['error'] = 'Title, Pages Count, and Base Price are required.';
            $redirect = BASE_PATH . '/admin/packages/add';
            if ($id) {
                $redirect = BASE_PATH . '/admin/packages/edit?id=' . $id;
            }
            header('Location: ' . $redirect);
            exit;
        }

        // Convert newline-separated string to array, filtering out empty lines
        $featuresArray = array_values(array_filter(array_map('trim', explode("\n", $featuresString))));

        $packageModel = new PackageModel();
        $data = [
            'title' => $title,
            'price_base' => $priceBase,
            'price_hosting_monthly' => $priceHosting,
            'pages_count' => $pagesCount,
            'tag' => $tag ?: null,
            'features' => $featuresArray
        ];

        $success = false;
        $message = 'Something went wrong.';

        if ($id) {
            // UPDATE
            $success = $packageModel->updatePackage((int)$id, $data);
            $message = $success ? 'Package updated successfully!' : 'Failed to update package.';
            if ($success) { \Logger::admin("Updated pricing package ID {$id}: {$title}."); }
        } else {
            // CREATE
            $success = $packageModel->createPackage($data);
            $message = $success ? 'Package added successfully!' : 'Failed to add new package.';
            if ($success) { \Logger::admin("Created new pricing package: {$title}."); }
        }

        $_SESSION['message'] = $message;
        header('Location: ' . BASE_PATH . '/admin/packages');
        exit;
    }

    /**
     * 4. Delete a package (DELETE POST).
     */
    public function deletePackage(): void {
        $id = $_POST['id'] ?? null; 

        if (!$id) {
            $_SESSION['message'] = 'Error: No package ID provided for deletion.';
        } else {
            $packageModel = new PackageModel();
            if ($packageModel->deletePackage((int)$id)) {
                $_SESSION['message'] = 'Package deleted successfully!';
                \Logger::admin("Deleted pricing package ID {$id}.");
            } else {
                $_SESSION['message'] = 'Error: Failed to delete package.';
            }
        }

        header('Location: ' . BASE_PATH . '/admin/packages');
        exit;
    }
}