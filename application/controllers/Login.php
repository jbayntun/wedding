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
        $this->load->model('guests');
    }
    
    /**
	 * Index page for this controller.
     */
    public function index()
	{
            $result = "Not Logged In";
            // if already logged in, display username and log out option in {result}
            if ($this->session->has_userdata('username'))
            {
                redirect('/');
            }
            
            $this->data['result'] = $result;            
            $this->data['page_body'] = 'login';
            $this->render();
	}
                
        /**
         * 
         * Authenticates the user and redirects as needed.
         * If valid, creates session data for them.
         */
    public function authenticate()
    {
        // ensure feilds are filled in
        if($this->blank_entries())
        {
            $this->invalid("You must fill in both User Name and Password!");
            return;
        }
        
        //get input
        $username = $this->input->post('user_name');
        $password = $this->input->post('password');
        
        $guest = $this->groups->get_by_username($username);
        //valid username?
        if($guest == null)
        {
            $this->invalid("Username does not Exist");
            return;
        }
        // valid password?
        if( !password_verify($password, $guest->password) )
        {
            $this->invalid("Invalid Password");
            return;
        }
        // legit, set session variables
        $this->session->set_userdata('username', $username);
        if($username == "Admin!")
        {
            $this->session->set_userdata('is_admin', true);
        }
        else
        {
            $this->session->set_userdata('is_admin', false);
        }
        redirect('/');
    }
    
    // displays login page with an error message
    private function invalid($result)
    {
        $this->data['result'] = $result;
        $this->data['page_body'] = 'login';
        $this->render();
        return;
    }
    /**
     * 
     * returns true if pass or user are not filled in
     */
    private function blank_entries()
    {
        $user = "" . $this->input->post('user_name');
        $pass = "" . $this->input->post('password');
        return $user == ""  || $pass == "";
    }
        
    /**
     * Logout and destroys the current session.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/login');
    }
    
    public function not_admin()
    {
        $this->data['page_body'] = 'not_admin';
        $this->render();
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
