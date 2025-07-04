<?php
// filepath: c:\xampp\htdocs\MLSystem-1\application\models\analytics_model.php

class analytics_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        date_default_timezone_set('Asia/Manila');
    }

    /**
     * Retrieves all student GWA and mock exam scores,
     * sends them to the external Python API for analytics,
     * and returns the API's response.
     *
     * @return mixed
     */
    public function send_scores_to_api()
    {
        // Fetch all student scores from admin_model
        $students = $this->admin_model->get_all_student_scores(); // Implement this in admin_model

        // Prepare data for API
        $data = [
            'students' => $students
        ];

        // Python API endpoint
        $url = 'https://mlr-analytics-tsukkimen.replit.app/';

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the request
        $response = curl_exec($ch);

        // Error handling
        if (curl_errno($ch)) {
            curl_close($ch);
            return null; // Or handle error as needed
        }

        curl_close($ch);

        // Decode and return the API response
        return json_decode($response, true);
    }
}