<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of Mailer
 *
 * @author Jeffrey
 */
class Mailer extends MY_Controller 
{    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('My_PHPMailer');
        $this->load->model('guests');
        $this->load->model('groups');
    }
    
    public function index()
    {
        // check admin
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        
        //get groups
        $groups = $this->groups->all();
        
        //for each group, get guests
        foreach($groups as $group)
        {            
            $group->guests = $this->guests->get_by_group($group->id);
            foreach($group->guests as $guest)
            {
                $guest->disabled = ($guest->email) ? "": 'disabled="disabled"';
            }
        }
        $this->data['groups']  = $groups;
        $this->data['page_body']  = 'emails';
        $this->render();
    }
    
    private function handle_group($group, $mail_info, $results)
    { 
        $group->guests = $this->guests->get_by_group($group->id);
        $message = $this->make_invitation($group);
        
        
        
        foreach($group->guests as $guest)
        {
            if($this->input->post($guest->id) == 2)
            {
                $results->none_sent++;
                return;
            }

            $good;
            if($this->input->post($guest->id) == 'hers')
            {
                $good = $this->send_mail($guest->email, $message, "test invitation", $guest->first_name, $mail_info->her_email, $mail_info->her_pass, $mail_info->her_name, $mail_info->her_server, true);
                $results->sent_hers = ($good === 0) ? $results->sent_hers + 1 : $results->sent_hers;
            }
            else if($this->input->post($guest->id) == 'his')
            {
                $good = $this->send_mail($guest->email, $message, "test invitation", $guest->first_name, $mail_info->his_email, $mail_info->his_pass, $mail_info->his_name, $mail_info->his_server, true);
                $results->sent_his = ($good === 0) ? $results->sent_his + 1 : $results->sent_his;
            }
            else
            {
                $results->none_sent++;
                $good = 0;
            }
            
            if($good !== 0)
            {
                $results->errors[$guest->email] = $good;
            }
        }
        
        $group->value = '';
        unset($group->guests);
        $this->groups->update($group);
    }
    
    public function send_invitations()
    {
        // check admin
        if (!$this->session->userdata('is_admin'))
        {
            // No access if not admin.
            redirect('/not_admin');
        }
        ini_set('max_execution_time', 600);
                
        $mail_info =  new stdClass();
        $mail_info->his_email = $this->input->post('his_email');
        $mail_info->his_pass = $this->input->post('his_pass');
        $mail_info->his_server = $this->input->post('his_server');
        $mail_info->his_name = "Jeff Bayntun";
        
        $mail_info->her_email = $this->input->post('her_email');
        $mail_info->her_pass = $this->input->post('her_pass');
        $mail_info->her_server = $this->input->post('her_server');
        $mail_info->her_name = "Sarah Wu";
        
        $results = new stdClass();
        $results->sent_his = 0;
        $results->sent_hers = 0;
        $results->none_sent = 0;
        $results->errors = array();
        
        $groups = $this->groups->all();
        
        foreach($groups as $group)
        {
            $this->handle_group($group, $mail_info, $results);
        }
        
        $message =  "His Sent: " . $results->sent_his . '<br/>';
        $message .= "Hers Sent: " . $results->sent_hers . '<br/>';
        $message .= "None Sent: " . $results->none_sent . '<br/>';
        $message .= "Errors:" . '<br/>';
        foreach($results->errors as $key => $value)
        {
            $message .= "    " . $key . "  " . $value . '<br/><br/>';
        }
        $this->thankyou($message);
    }
    
    private function send_mail($address, $message, $subject, $name, $user, $pass, $from_name, $server, $invitation) {
        
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "tls";  // prefix for secure protocol to connect to the server
        $mail->Host       = $server;      // setting GMail as our SMTP server
        $mail->Port       = 587;                   // SMTP port to connect to GMail
        $mail->Username   = $user;  // user email address
        $mail->Password   = $pass;            // password in GMail
        $mail->setFrom($user, $from_name);  //Who is sending the email
        $mail->addReplyTo($user, $from_name);  //email address that receives the response
        $mail->Subject    = $subject;
        $mail->Body      = $message;
        $mail->AltBody    = "Plain text message";
        $mail->addAddress($address, $name);
		if($invitation)
		{
			$mail->addEmbeddedImage('C:\xampp\htdocs\wedding\assets\images\invitation.jpg', 'invitation');
		}
        
        if(!$mail->send()) {
            return "Mailer Error: " . $mail->ErrorInfo . '</br>';
        } else {
            return 0;
        }
        
    }
    
    
    private function make_invitation($group)
    {
        $message = '<html><head><title>Wedding Invitation</title></head><body>';
        
        $message .= '<p>Dear ';
        foreach($group->guests as $g)
        {
            $message .= $g->first_name. ', ';
        }
        
        $message .= '</p>';
        
        $message .= "<p>You're Invited...</p>";
        
        $message .= '<img src="cid:invitation" width="600"/>';
        
        $message .= '<p>For more information, and to RSVP, please visit our ';
        $message .= '<a href="http://wedding.mellifluous.ca">Website</a></p>';
        
        $message .= '<p><span class="bold">Username: </span> ' . $group->username . '<br/>';
        $message .= '<p><span class="bold">Password: </span> ' . $group->value . '</p>';
        
        $message .= '<p>In lieu of gifts, the betrothed would prefer money to spend on shoes.</p>';
        $message .= '<p>Please RSVP no later than August 1st, 2015.</p>';
        
        $message .= ' </table></body></html>';
        
        return $message;
        
        }
		
        public function feedback()
        {
            if (!$this->session->has_userdata('username'))
            {
                // User is not logged in.
                $this->data['page_body'] = '/login';
                $this->render();
                return;
            }

            // User is logged in as a guest.
            $group = $this->groups->get_by_username(
            $this->session->userdata('username'));

            $message = $this->input->post('message');
            $subject = "Wedding Feedback - " . $group->username . " - " . $this->input->post('subject');
            $name = $this->input->post('name');
            $address = "j_bayntun@hotmail.com";

            $this->send_mail($address, $message, $subject, $name, "j_bayntun@hotmail.com", $need_pass, "Jeff Bayntun", 'smtp-mail.outlook.com', false);

            $message = "Thanks for the email!  We'll get back to you soon";
            $this->thankyou($message);
        }
		
        public function thankyou($message)
	{
            $this->data['page_body']  = 'thankyou';
            $this->data['message'] = $message;
            $this->render();
	}
}
