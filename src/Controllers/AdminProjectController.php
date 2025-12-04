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
    private \Models\OnboardingDetailsModel $onboardingDetailsModel;

    public function __construct()
    {
        // Assuming BaseController's constructor does not need arguments.
        // If BaseController has its own constructor logic, make sure to call parent::__construct()
        // parent::__construct(); 
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
        $this->onboardingDetailsModel = new \Models\OnboardingDetailsModel();
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

        // Load onboarding details
        $onboardingDetails = $this->onboardingDetailsModel->findBy('project_id', $id);

        // Render the view
        $this->render('admin/projects/show', [
            'title' => 'Project Details',
            'project' => $project,
            'client'  => $client,
            'onboardingDetails' => $onboardingDetails
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

    /**
     * Create/Pre-fill Business Profile from Onboarding Data
     */
    public function createProfile(int $projectId): void
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // 1. Get Project & Onboarding Details
        $project = $this->projectModel->getProjectById($projectId);
        $details = $this->onboardingDetailsModel->findBy('project_id', $projectId);

        if (!$project) {
            $_SESSION['error'] = 'Project not found.';
            header('Location: ' . self::BASE_PATH . '/admin/projects');
            exit;
        }

        // 2. Check if Project Data already exists
        // We need to instantiate ProjectDataModel here.
        // If it doesn't exist yet, we'll need to create the class.
        // Assuming it exists or will exist:
        $projectDataModel = new \Models\ProjectDataModel();
        $existingData = $projectDataModel->getByProjectId($projectId);

        if ($existingData) {
            $_SESSION['message'] = 'Business Profile already exists. Redirecting to edit.';
            header('Location: ' . self::BASE_PATH . '/admin/projects/data/' . $projectId);
            exit;
        }

        // 3. Auto-create the record in DB as a draft and then redirect to edit.
        $newData = [
            'project_id' => $projectId,
            'client_id' => $project['client_id'],
            'business_name' => $details['company_name'] ?? 'New Business',
            'industry' => $details['industry'] ?? null,
            'about_business' => $details['mission_statement'] ?? null,
            'target_audience' => $details['usp'] ?? null,
            'contact_email' => $details['rep_email'] ?? null,
            'contact_phone' => $details['rep_phone'] ?? null,
            'brand_colors' => $details['brand_colors'] ?? null,
            'is_draft' => 1 // Mark as draft
        ];
        
        // Save the new draft
        $projectDataModel->save($projectId, $newData);
        
        $_SESSION['message'] = 'Business Profile draft created from Onboarding Data. You can now edit it.';
        header('Location: ' . self::BASE_PATH . '/admin/projects/data/' . $projectId);
        exit;
    }

    /** Helper method to check if current user is admin */
    private function isAdmin(): bool
    {
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }
}