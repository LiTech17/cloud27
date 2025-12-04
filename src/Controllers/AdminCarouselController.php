<?php

namespace Controllers;

use Models\HeroCarousel;
use Services\ImageUploadHandler;

class AdminCarouselController extends BaseController
{
    private HeroCarousel $carouselModel;
    private ImageUploadHandler $uploadHandler;

    public function __construct()
    {
        // Ensure Admin Access
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }

        $this->carouselModel = new HeroCarousel();
        $this->uploadHandler = new ImageUploadHandler();
    }

    public function index(): void
    {
        $slides = $this->carouselModel->getAllSlides();
        
        $this->render('admin/carousel', [
            'title' => 'Manage Hero Carousel',
            'slides' => $slides
        ]);
    }

    public function upload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(405, ['error' => 'Method not allowed']);
        }

        if (!isset($_FILES['image'])) {
            $this->jsonResponse(400, ['error' => 'No image uploaded']);
        }

        $result = $this->uploadHandler->handleUpload($_FILES['image']);

        if (isset($result['error'])) {
            $this->jsonResponse(400, ['error' => $result['error']]);
        }

        // Create initial DB record
        $slideId = $this->carouselModel->createSlide([
            'image_filename' => $result['filename'],
            'title' => 'New Slide',
            'is_active' => 0 // Inactive by default until configured
        ]);

        if ($slideId) {
            $this->jsonResponse(200, [
                'success' => true, 
                'slide' => $this->carouselModel->getSlideById($slideId)
            ]);
        } else {
            $this->jsonResponse(500, ['error' => 'Database error']);
        }
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(405, ['error' => 'Method not allowed']);
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'cta_text' => $_POST['cta_text'] ?? '',
            'cta_link' => $_POST['cta_link'] ?? ''
        ];

        if ($this->carouselModel->updateSlide($id, $data)) {
            $this->jsonResponse(200, ['success' => true]);
        } else {
            $this->jsonResponse(500, ['error' => 'Update failed']);
        }
    }

    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(405, ['error' => 'Method not allowed']);
        }

        $slide = $this->carouselModel->getSlideById($id);
        if (!$slide) {
            $this->jsonResponse(404, ['error' => 'Slide not found']);
        }

        // Delete files
        $this->uploadHandler->deleteImage($slide['image_filename']);

        // Delete DB record
        if ($this->carouselModel->deleteSlide($id)) {
            $this->jsonResponse(200, ['success' => true]);
        } else {
            $this->jsonResponse(500, ['error' => 'Delete failed']);
        }
    }

    public function reorder(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(405, ['error' => 'Method not allowed']);
        }

        $order = $_POST['order'] ?? [];
        if (empty($order) || !is_array($order)) {
            $this->jsonResponse(400, ['error' => 'Invalid order data']);
        }

        if ($this->carouselModel->reorderSlides($order)) {
            $this->jsonResponse(200, ['success' => true]);
        } else {
            $this->jsonResponse(500, ['error' => 'Reorder failed']);
        }
    }

    public function toggle(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(405, ['error' => 'Method not allowed']);
        }

        if ($this->carouselModel->toggleActive($id)) {
            $this->jsonResponse(200, ['success' => true]);
        } else {
            $this->jsonResponse(500, ['error' => 'Toggle failed']);
        }
    }
}
