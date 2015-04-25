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
            $this->handle_group($group);
        }
    }
    
    private function handle_group($group)
    {
        $group->guests = $this->guests->get_by_group($group->id);
        $message = $this->make_invitation($group);
        
        foreach($group->guests as $guest)
        {
            if($guest->email != NULL)
            {
                $this->send_mail($guest->email, $message, "test invitation", $guest->first_name);
            }
        }
        
        $group->value = '';
        unset($group->guests);
        $this->groups->update($group);
    }
    
    private function send_mail($address, $message, $subject, $name) {
        
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "tls";  // prefix for secure protocol to connect to the server
        $mail->Host       = "smtp-mail.outlook.com";      // setting GMail as our SMTP server
        $mail->Port       = 587;                   // SMTP port to connect to GMail
        $mail->Username   = "@hotmail.com";  // user email address
        $mail->Password   = "";            // password in GMail
        $mail->setFrom('@hotmail.com', 'Jeff Bayntun');  //Who is sending the email
        $mail->addReplyTo('', 'Jeff Bayntun');  //email address that receives the response
        $mail->Subject    = $subject;
        $mail->Body      = $message;
        $mail->AltBody    = "Plain text message";
        $mail->addAddress($address, $name);
        $mail->addEmbeddedImage('C:\xampp\htdocs\wedding\assets\images\invitation.jpg', 'invitation');
       // $mail->addAttachment('C:\xampp\htdocs\wedding\assets\images\invitation.jpg', 'invitation');
        
        if(!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo . '</br>';
        } else {
            echo "Message sent!" . '</br>';
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
}
