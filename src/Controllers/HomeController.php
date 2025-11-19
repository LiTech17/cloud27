<?php
// src/Controllers/HomeController.php

namespace Controllers;

use Models\ServiceModel;
use Models\PackageModel; // NEW: Import the Package Model

class HomeController extends BaseController {

    /**
     * Handles the Home Page request, fetching services and packages.
     */
    public function index(): void {
        // 1. Instantiate Models
        $serviceModel = new ServiceModel();
        $packageModel = new PackageModel(); // NEW: Instantiate PackageModel
        
        // 2. Fetch the data
        $services = $serviceModel->getAllServices(); 
        $packages = $packageModel->getAllPackages(); // NEW: Fetch all packages
        
        // 3. Render the home view, passing the dynamic data
        $this->render('home', [
            'title' => 'Welcome to Cloud27',
            'services' => $services ?: [], 
            'packages' => $packages ?: [] // NEW: Pass packages data to the view
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
     */
    public function contact(): void {
        $this->render('contact', ['title' => 'Contact Us']);
    }
}