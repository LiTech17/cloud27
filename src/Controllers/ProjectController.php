<?php

namespace Controllers;

use Models\ProjectModel;

class ProjectController extends BaseController
{
    private ProjectModel $projectModel;
    // Assuming BASE_PATH is defined in your BaseController or global scope
    private const BASE_PATH = BASE_PATH; 

    public function __construct()
    {
        // parent::__construct(); // Uncomment if needed
        $this->projectModel = new ProjectModel();
    }

    // ------------------------------------------------------------------------
    // HELPER METHODS (No changes needed, but kept for context)
    // ------------------------------------------------------------------------

    private function getUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }

    private function isClient(): bool
    {
        // Use a more explicit check for client role
        return !empty($_SESSION['user_id']) && ($_SESSION['role'] ?? 'client') === 'client';
    }

    private function redirect(string $path): void
    {
        header('Location: ' . self::BASE_PATH . $path);
        exit;
    }

    /**
     * Helper to safely get and sanitize POST data against the model's allowed fields.
     * @param array $postData The raw $_POST data.
     * @return array The filtered data.
     */
    private function filterPostData(array $postData): array
    {
        // We can't directly call a private method from ProjectModel, so we'd 
        // ideally move the filterAllowed logic to a public/protected method on BaseModel 
        // or recreate a minimal filter here. For simplicity, we manually map expected fields.
        return [
            'title'        => htmlspecialchars(trim($postData['title'] ?? '')),
            'description'  => htmlspecialchars(trim($postData['description'] ?? '')),
            'package_name' => htmlspecialchars(trim($postData['package_name'] ?? '')),
            'budget'       => filter_var($postData['budget'] ?? 0.00, FILTER_VALIDATE_FLOAT, ['options' => ['default' => 0.00]]),
            // Include other allowed fields if needed, like start_date, due_date
        ];
    }

    // ------------------------------------------------------------------------
    // CLIENT PROJECT METHODS (Optimized)
    // ------------------------------------------------------------------------

    // index() - No changes needed

    public function index(): void
    {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        // The Model's method is perfect here
        $projects = $this->projectModel->getAllByClient($clientId);

        $this->render('client/projects/index', [
            'title' => 'My Projects',
            'projects' => $projects,
            'message' => $_SESSION['message'] ?? null
        ]);

        unset($_SESSION['message']);
    }

    public function create(): void
    {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        // Redirect to the main onboarding flow
        $this->redirect('/get-started');
    }

    public function store(): void
    {
        if (!$this->isClient() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        
        // 1. Filter and Sanitize POST Data
        $data = $this->filterPostData($_POST);
        $data['client_id'] = $clientId; // Add required client_id

        // 2. Delegate Validation
        $errors = $this->projectModel->validate($data);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $this->redirect('/client/projects/create');
        }

        // 3. Delegate Creation
        $success = $this->projectModel->createProject($data);
        
        if ($success) {
            $_SESSION['message'] = 'Project created successfully!';
        } else {
            $_SESSION['message'] = 'Project creation failed due to a server error.';
            // Log error if possible
        }
        
        $this->redirect('/client/projects');
    }

    // show() - No changes needed, logic is sound

    public function show(int $id): void
    {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        $project = $this->projectModel->getProjectById($id);

        // Access checks
        if (!$project) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        if ((int)$project['client_id'] !== $clientId) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // Fetch onboarding details
        $onboardingDetailsModel = new \Models\OnboardingDetailsModel();
        $onboardingDetails = $onboardingDetailsModel->findBy('project_id', $id);

        $this->render('client/projects/show', [
            'title' => 'Project: ' . htmlspecialchars($project['title']),
            'project' => $project,
            'onboardingDetails' => $onboardingDetails
        ]);
    }

    public function edit(int $id): void
    {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        $project = $this->projectModel->getProjectById($id);

        if (!$project || (int)$project['client_id'] !== $clientId) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        $this->render('client/projects/edit', [
            'title' => 'Edit Project',
            'project' => $project,
            // OPTIMIZATION: Get packages dynamically from the Model/DB
            'packages' => $this->projectModel->getAvailablePackages(), 
            'errors' => $_SESSION['error'] ?? []
        ]);

        unset($_SESSION['error']);
    }

    public function update(int $id): void
    {
        if (!$this->isClient() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        $project = $this->projectModel->getProjectById($id);

        // Security check: ensure project exists and belongs to the user
        if (!$project || (int)$project['client_id'] !== $clientId) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        // 1. Filter and Sanitize POST data, and merge with existing project data 
        //    to ensure all required validation fields are present.
        $postData = $this->filterPostData($_POST);
        
        $data = [
            'client_id'    => $clientId, // Required for the ProjectModel's validate method
            'title'        => $postData['title'] ?: $project['title'],
            'description'  => $postData['description'] ?: $project['description'],
            'package_name' => $postData['package_name'] ?: $project['package_name'],
            'budget'       => $postData['budget'] ?? $project['budget'],
            // Note: If fields like 'start_date' or 'due_date' are in the form, they need to be handled here too.
        ];

        // 2. Delegate Validation
        // The model's validate method now receives all necessary fields
        $errors = $this->projectModel->validate($data);
        
        if (!empty($errors)) {
            $_SESSION['error'] = $errors;
            $this->redirect("/client/projects/edit/{$id}");
        }

        // 3. Delegate Update
        $success = $this->projectModel->updateProject($id, $data);
        
        if ($success) {
            $_SESSION['message'] = 'Project updated successfully!';
        } else {
             $_SESSION['message'] = 'Project update failed.';
        }
        
        $this->redirect('/client/projects');
    }

    // destroy() - No changes needed, logic is sound
    
    public function destroy(int $id): void
    {
        if (!$this->isClient()) {
            $this->redirect('/login');
        }

        $clientId = $this->getUserId();
        $project = $this->projectModel->getProjectById($id);

        if (!$project || (int)$project['client_id'] !== $clientId) {
            http_response_code(403);
            $this->render('403');
            return;
        }

        $this->projectModel->deleteProject($id);
        $_SESSION['message'] = 'Project deleted successfully!';
        $this->redirect('/client/projects');
    }
}