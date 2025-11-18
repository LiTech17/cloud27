<?php
// src/Controllers/HomeController.php

namespace Controllers;

use Models\ServiceModel; // Import the Model to fetch data

class HomeController extends BaseController {

    /**
     * Handles the Home Page request, fetching services to potentially display a snippet.
     */
    public function index(): void {
        // 1. Instantiate the Service Model
        $serviceModel = new ServiceModel();
        
        // 2. Fetch the data (all services)
        $services = $serviceModel->getAllServices(); 
        
        // 3. Render the home view, passing the dynamic data
        $this->render('home', [
            'title' => 'Welcome to Cloud27',
            // Pass services data or an empty array if the model failed
            'services' => $services ?: [] 
        ]);
    }

    /**
     * Handles the About Page request.
     */
    public function about(): void {
        $this->render('about', ['title' => 'About Us']);
    }

    /**
     * Handles the Services Page request, fetching and displaying all services dynamically.
     */
    public function services(): void {
        // Instantiate the Service Model
        $serviceModel = new ServiceModel();
        
        // Fetch the data (all services)
        $services = $serviceModel->getAllServices();
        
        // Render the services view, passing the dynamic data
        $this->render('services', [
            'title' => 'Our Cloud Services',
            'services' => $services ?: [] 
        ]);
    }

    /**
     * Placeholder method for the Contact page, handled by ContactController in routing.
     * Note: This method may not be strictly necessary if routing sends /contact to ContactController.
     * We keep it here to ensure the class is complete.
     */
    public function contact(): void {
        $this->render('contact', ['title' => 'Contact Us']);
    }
}