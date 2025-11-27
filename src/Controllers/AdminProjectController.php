<?php
namespace Controllers;

use Models\ProjectModel;
use Models\UserModel; 

class AdminProjectController extends BaseController
{
    // Define the application's base path for redirects and AJAX URLs
    private const BASE_PATH = BASE_PATH; // Use the defined constant

    // Model properties for efficiency (initialized in constructor)
    private ProjectModel $projectModel;
    private UserModel $userModel;

    public function __construct()
    {
        // Assuming BaseController's constructor does not need arguments.
        // If BaseController has its own constructor logic, make sure to call parent::__construct()
        // parent::__construct(); 
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
    }

    // Helper to send JSON responses
    private function jsonResponse(int $code, array $data): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ------------------------------------------------------------------------
    // ADMIN CRUD METHODS
    // ------------------------------------------------------------------------

    /** Display all projects with optional filters */
    public function index(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        $filters = [];
        if (!empty($_GET['client_id'])) {
            $filters['client_id'] = (int)$_GET['client_id'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['package_name'])) {
            $filters['package_name'] = $_GET['package_name'];
        }

        $projects = $this->projectModel->getAllProjects($filters);

        $this->render('admin/projects/index', [
            'title' => 'All Projects',
            'projects' => $projects,
            'message' => $_SESSION['message'] ?? null
        ]);

        unset($_SESSION['message']);
    }

    /** Display form to create a new project */
    public function create(): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // Use the initialized property
        $clients = $this->userModel->getAllClients(); 

        $this->render('admin/projects/create', [
            'title' => 'Create New Project',
            'errors' => $_SESSION['error'] ?? [],
            'packages' => ['Basic', 'Standard', 'Enterprise'],
            'clients' => $clients 
        ]);

        unset($_SESSION['error']);
    }

    /** Handle storing a new project (Now handles AJAX JSON response) */
    public function store(): void
    {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(403, ['error' => 'Unauthorized or invalid request method.']);
        }

        $resolved_client_id = null;
        
        // Check for client_id or client_username
        if (isset($_POST['client_id']) && is_numeric($_POST['client_id'])) {
            $resolved_client_id = (int)$_POST['client_id'];
        } elseif (!empty($_POST['client_username'])) {
            // Use the initialized property
            $client = $this->userModel->getClientByUsername($_POST['client_username']);

            if ($client && isset($client['id'])) {
                $resolved_client_id = (int)$client['id'];
            } else {
                $errors = ['client_username' => 'Selected client username is invalid or not found.'];
                $this->jsonResponse(422, ['errors' => $errors]);
            }
        }

        $data = [
            'client_id'      => $resolved_client_id, 
            'title'          => $_POST['title'] ?? '',
            'description'    => $_POST['description'] ?? null,
            'package_name'   => $_POST['package_name'] ?? '',
            'status'         => $_POST['status'] ?? 'New',
            'budget'         => $_POST['budget'] ?? 0.00,
            'start_date'     => $_POST['start_date'] ?? null,
            'due_date'       => $_POST['due_date'] ?? null
        ];

        // Use the initialized property
        $errors = $this->projectModel->validate($data);

        if (!empty($errors)) {
            $this->jsonResponse(422, ['errors' => $errors]);
        }

        $this->projectModel->createProject($data);
        
        // Success response (HTTP 201 Created)
        $this->jsonResponse(201, [
            'message' => 'Project created successfully!',
            'redirect' => self::BASE_PATH . '/admin/projects'
        ]);
    }
    
    /** Show a single project - FIXED to accept parameter from route */
    public function show(int $id): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // Load project (Use initialized property)
        $project = $this->projectModel->getProjectById($id);

        if (!$project) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        // Load client info - FIXED: Changed findOne() to getUserById()
        $client = $this->userModel->getUserById($project['client_id']);

        // Render the view
        $this->render('admin/projects/show', [
            'title' => 'Project Details',
            'project' => $project,
            'client'  => $client
        ]);
    }

    /** Display edit form - FIXED to accept parameter from route */
    public function edit(int $id): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // Use the initialized property
        $project = $this->projectModel->getProjectById($id);

        if (!$project) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        // Use the initialized property
        $clients = $this->userModel->getAllClients();

        $this->render('admin/projects/edit', [
            'title' => 'Edit Project',
            'project' => $project,
            'clients' => $clients,
            'packages' => ['Basic', 'Standard', 'Enterprise'],
            'errors' => $_SESSION['error'] ?? []
        ]);

        unset($_SESSION['error']);
    }

    /** Handle updating a project - FIXED to accept parameter from route */
    public function update(int $id): void
    {
        // Use self::BASE_PATH for redirects
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . self::BASE_PATH . '/login');
            exit;
        }

        // Use the initialized property
        $project = $this->projectModel->getProjectById($id);

        if (!$project) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $resolved_client_id = $project['client_id']; 
        $clientUsername = $_POST['client_username'] ?? null;

        // Resolve Client ID from Username for Update
        if ($clientUsername) {
            // Use the initialized property
            $client = $this->userModel->getClientByUsername($clientUsername);

            if ($client && isset($client['id'])) {
                $resolved_client_id = (int)$client['id'];
            } else {
                $_SESSION['error'] = ['client_username' => 'Selected client username is invalid or not found.'];
                header("Location: " . self::BASE_PATH . "/admin/projects/edit/{$id}");
                exit;
            }
        } elseif (isset($_POST['client_id'])) {
            $resolved_client_id = (int)$_POST['client_id'];
        }

        $data = [
            'client_id'      => $resolved_client_id, 
            'title'          => $_POST['title'] ?? $project['title'],
            'description'    => $_POST['description'] ?? $project['description'],
            'package_name'   => $_POST['package_name'] ?? $project['package_name'],
            'status'         => $_POST['status'] ?? $project['status'],
            'budget'         => $_POST['budget'] ?? $project['budget'],
            'start_date'     => $_POST['start_date'] ?? $project['start_date'],
            'due_date'       => $_POST['due_date'] ?? $project['due_date']
        ];

        // Use the initialized property
        $errors = $this->projectModel->validate($data);
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            header("Location: " . self::BASE_PATH . "/admin/projects/edit/{$id}");
            exit;
        }

        $this->projectModel->updateProject($id, $data);
        $_SESSION['message'] = 'Project updated successfully!';
        header('Location: ' . self::BASE_PATH . '/admin/projects');
        exit;
    }

    /** Handle deleting a project - FIXED to accept parameter from route */
    public function destroy(int $id): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // Use the initialized property
        $project = $this->projectModel->getProjectById($id);

        if (!$project) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $this->projectModel->deleteProject($id);
        $_SESSION['message'] = 'Project deleted successfully!';
        header('Location: ' . self::BASE_PATH . '/admin/projects');
        exit;
    }

    /** Helper method to check if current user is admin */
    private function isAdmin(): bool
    {
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
}