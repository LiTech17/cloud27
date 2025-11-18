<?php
// src/Controllers/HomeController.php
namespace Controllers; // <-- ADDED

class HomeController extends BaseController {
    
    /**
     * Handles the root route: /
     */
    public function index(): void {
        $data = [
            'pageTitle' => 'Cloud27 - Home | Modern Cloud Solutions',
            'isHomePage' => true 
        ];
        $this->render('home', $data);
    }

    /**
     * Handles the /about route
     */
    public function about(): void {
        $data = [
            'pageTitle' => 'About Us | Our Philosophy and Team'
        ];
        $this->render('about', $data);
    }

    /**
     * Handles the /services route
     */
    public function services(): void {
        $data = [
            'pageTitle' => 'Our Services | Architecture, Development, and Support'
        ];
        $this->render('services', $data);
    }
}