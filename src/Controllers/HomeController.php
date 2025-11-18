<?php
// src/Controllers/HomeController.php

namespace Controllers;

use Models\ServiceModel; // Import the Model to fetch data

class HomeController extends BaseController {

    public function index(): void {
        // 1. Instantiate the Service Model
        $serviceModel = new ServiceModel();
        
        // 2. Fetch the data (all services)
        $services = $serviceModel->getAllServices(); 
        
        // 3. Render the home view, passing the dynamic data
        $this->render('home', [
            'title' => 'Welcome to Cloud27',
            'services' => $services ?: [] // Pass services data or an empty array
        ]);
    }

    public function about(): void {
        $this->render('about', ['title' => 'About Us']);
    }

    public function services(): void {
        // This method is already correct
        $serviceModel = new ServiceModel();
        $services = $serviceModel->getAllServices();
        $this->render('services', [
            'title' => 'Our Services',
            'services' => $services ?: [] 
        ]);
    }
}