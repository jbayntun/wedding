<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * controllers/Guest.php
 * 
 * Controller for the Guests page.
 */
class Guest extends MY_Controller
{
	/**
     * Default constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('guests');
        $this->load->model('responses'); 
   }
    
    /**
	 * Index page for this controller.
     * Loads groups and decides which view to load depending on session data.
     */
    public function index()
    {
        if (!$this->session->has_userdata('username'))
        {
            // User is not logged in.
			$this->data['result'] = '';
            $this->data['page_body'] = '/login';
            $this->render();
            return;
        }
        
        if ($this->session->userdata('is_admin'))
        {
            // User is logged in as admin.
            $this->admin();
            return;
        }
        
        $group = $this->groups->get_by_username(
                 $this->session->userdata('username'));
        
        $this->get_guests($group);
        
        $this->data['group_name'] = $group->name;
        $this->data['responses']  = $this->responses->all();
        $this->data['guests']     = $group->guests;
        $this->data['page_body']  = 'guests/guest';
        $this->render();
    }
    
    /**
     * Allows a user to update their group response.
     */
    public function respond()
    {
        if (!$this->session->has_userdata('username'))
        {
            // User is not logged in.
			$this->data['result'] = '';
            $this->data['page_body'] = '/login';
            $this->render();
            return;
        }
        
        // User is logged in as a guest.
        $group = $this->groups->get_by_username(
                 $this->session->userdata('username'));
        
        $this->update_guests($group);
        $this->groups->update($group);
		
		$message = 'Thank you for RSVPing! Your changes have been saved.';
		$this->thankyou($message);
    }
	/**
	* Displays a thank you message
	*/
	public function thankyou($message)
	{
		$this->data['page_body']  = 'thankyou';
        $this->data['message'] = $message;
        $this->render();
	}
    
    /**
     * Loads the admin version of this page.
     */
    public function admin()
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $groups = $this->groups->all();
        
        $total_yes = 0;
        $total_no = 0;
        $total_maybe = 0;
        $total = 0;
        
        foreach($groups as $g)
        {
            if($g->name == 'admin')
            {
                continue;
            }
            $this->get_guests_admin($g);
            $g->yes = 0;
            $g->no = 0;
            $g->size = 0;
            $g->maybe = 0;
            
            foreach($g->guests as $guest)
            {
                switch($guest->response_id)
                {
                    case 0: // yes
                        $g->yes++;
                        $total_yes++;
                        break;
                    case 1: // no
                        $g->no++;
                        $total_no++;
                        break;
                    default: // unknown
                        $total_maybe++;
                        $g->maybe++;
                        break;
                }
                $g->size++;
                $total++;
            }
        }
        
        //groups need a size, yes and no property.
        
        $this->data['page_body']  = 'guests/admin_all';
        $this->data['groups'] = $groups;
        $this->data['yes'] = $total_yes;
        $this->data['no'] = $total_no;
        $this->data['maybe'] = $total_maybe;
        $this->data['invited'] = $total;
        $this->render();
    }
    
    /**
     * Allows an admin to edit a user within a group.
     * @param $guest_id  The ID of the guest to edit.
     */
    public function edit_guest($guest_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $guest = $this->guests->get($guest_id);        
        $this->data['page_body']  = 'guests/edit_guest';
        $this->data['id'] = $guest_id;
        $this->data['group_id'] = $guest->group_id;
        $this->data['first_name'] = $guest->first_name;
        $this->data['last_name'] = $guest->last_name;
        $this->data['phone'] = $guest->phone;
        $this->data['email'] = $guest->email; 
        $this->render();
    }
    /**
     * Allows an admin to submit an edit to a user within a group.
     * @param $guest_id  The ID of the guest to edit.
     */
    public function submit_guest($guest_id)
    {        
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $guest = $this->guests->get($guest_id);        
        $guest->first_name = $this->input->post('first_name');
        $guest->last_name = $this->input->post('last_name');
        $guest->phone = $this->input->post('phone');
        $guest->email = $this->input->post('email');

        $this->guests->update($guest);
        redirect('/guest/admin_show_group/' . $guest->group_id);
    }
    
    /**
     * Allows an admin to submit an edit to a user within a group.
     * @param $guest_id  The ID of the guest to edit.
     */
    public function delete_guest($group_id, $guest_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $this->guests->delete($guest_id);
        redirect('/guest/admin_show_group/' . $group_id);
    }
    /**
     * Allows an admin to add a new guest to a group.
     * @param $group_id  The ID of the group to add the guest to.
     */
    public function add_guest($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $this->data['page_body']  = 'guests/add_guest';
        $this->data['group_id'] = $group_id;
        $this->render();
    }
    
    public function submit_new_guest($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
         $guest = $this->guests->create();
         $guest->first_name = $this->input->post('first_name');
         $guest->last_name = $this->input->post('last_name');
         $guest->phone = $this->input->post('phone');
         $guest->email = $this->input->post('email');
         $guest->group_id = $group_id;
         $guest->response_id = 2;
         
         $this->guests->add($guest);
         redirect('/guest/admin_show_group/' . $group_id);
    }
    
    /**
     * Shows the members of a specific group for the admin
     * @param $group_id  The id of the group to be shown.
     */
    public function admin_show_group($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $group = $this->groups->get($group_id);
        $this->get_guests_admin($group);

        $this->data['page_body']  = 'guests/admin_one';
        $this->data['id'] = $group->id;
        $this->data['group_name'] = $group->name;
        $this->data['password'] = $group->value;
        $this->data['guests']  = $group->guests;
        $this->data['username'] = $group->username;
        $this->render();
    }
    /**
     * Displays a page to add a new group
     */
    public function add_group()
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $this->data['page_body']  = 'guests/add_group';
        $this->render();
    }
    /*
     * Handles the submission of a form to create a new group
     */
    public function submit_new_group()
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $group = $this->groups->create();
        $group->name = $this->input->post('group_name');
        $group->username = $this->input->post('username');
        $group->value = $group->username . rand(100, 9999);
        $group->password = password_hash( str_replace(array('"', "'"), '', $group->value), PASSWORD_DEFAULT);
        $this->groups->add($group);
        
        $group_id = $this->groups->highest();
        
        $guest = $this->guests->create();
        $guest->first_name = $this->input->post('first_name');
        $guest->last_name = $this->input->post('last_name');
        $guest->phone = $this->input->post('phone');
        $guest->email = $this->input->post('email');
        $guest->group_id = $group_id;
        $guest->response_id = 2;

        $this->guests->add($guest);
        redirect('/guest/admin_show_group/' . $group_id);
    }
    
    /**
     * Displays a page to edit group info
     * @param type $group_id id of group to edit
     */
    public function edit_group_admin($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $group = $this->groups->get($group_id);
        $this->data['group_name'] = $group->name;
        $this->data['username'] = $group->username;
        $this->data['password'] = $group->value;
        $this->data['id'] = $group->id;
                
        $this->data['page_body']  = 'guests/edit_group';
        $this->render();
    }
    
    /**
     * Handles group edit submission
     * @param type $group_id id of submitted group
     */
    public function submit_group($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $group = $this->groups->get($group_id);
        $group->name = $this->input->post('group_name');
        $group->username = $this->input->post('username');
        $group->password = password_hash( $this->input->post('password'), PASSWORD_DEFAULT );
        $group->value = '';
        $this->groups->update($group);
        
        redirect('/guest/admin_show_group/' . $group_id);
    }
    /**
     * Deletes a group and all its members.
     * DOES NOT check if that group owns any gifts.
     * @param type $group_id group to delete
     */
    public function delete_group($group_id)
    {
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        $guests = $this->groups->get_guests($group_id);
        
        foreach($guests as $guest)
        {
            $this->guests->delete($guest->id);
        }
        
        $this->groups->delete($group_id);
        redirect('/guest');
    }
    
    /**
     * Injects the guests of the specified group with attributes needed to
     * display their reponses.
     * @param $group  The group to be updated.
     */
    private function get_guests($group)
    {
        $group->guests = $this->groups->get_guests($group->id);
        
        foreach($group->guests as $guest)
        {
            $guest->responses = array();
            for($i = 0; $i < $this->responses->size(); $i++)
            {
               $guest->responses[$i]['name']    = 'guest_' . $guest->id;
               $guest->responses[$i]['value']   = $i;
               $guest->responses[$i]['checked'] = ($i == $guest->response_id)
                                                ? ' checked' : '';
            }
        }
    }
    
    /**
     * Injects the guests of the specified group with attributes needed to
     * display their reponses.
     * @param $group  The group to be updated.
     */
    private function get_guests_admin($group)
    {
        $group->guests = $this->guests->get_by_group($group->id);
        
        foreach($group->guests as $guest)
        {
            $guest->response = $this->responses
                             ->get($guest->response_id)->description;
        }
    }
    
    /**
     * Updates the guest responses for the specified group. 
     * @param $group  The group to be updated.
     */
    private function update_guests($group)
    {
        foreach ($this->groups->get_guests($group->id) as $guest)
        {
            unset($guest->responses);
            $guest->response_id = $this->input->post('guest_' . $guest->id);
            $this->guests->update($guest);
        }
    }    
	
	public function email()
	{
		if (!$this->session->has_userdata('username'))
		{
			// User is not logged in.
			$this->data['result'] = '';
			$this->data['page_body'] = '/login';
			$this->render();
			return;
		}
		
		$this->data['page_body']  = 'guests/email';
        $this->render();
		
	}
    
}

/* End of file Guest.php */
/* Location: ./application/controllers/Guest.php */
