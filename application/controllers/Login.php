<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * controllers/Login.php
 * 
 * Controller for the Login page.
 */
class Login extends MY_Controller
{
	/**
     * Default constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
	 * Index page for this controller.
     */
    public function index()
	{
		$this->data['page_body'] = 'login';
        $this->render();
	}
    
    /**
     * Login as a normal user (dummy function).
     */
    public function user()
    {
        $this->session->set_userdata('username', 'guest');
        redirect('/');
    }
    
    /**
     * Login as an admin user (dummy function).
     */
    public function admin()
    {
        $this->session->set_userdata('username', 'admin');
        redirect('/');
    }
    
    /**
     * Logout and destroys the current session.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/login');
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */