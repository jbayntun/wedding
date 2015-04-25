<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * controllers/Home.php
 * 
 * Controller for the Home page.
 */
    
class Home extends MY_Controller
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
        $carousel = '<div class="data-slick">';
        $carousel .= '<div><img src="/assets/images/carousel/c1.jpg" width="800px"></div>';
        $carousel .= '<div><img src="/assets/images/carousel/c2.jpg" width="800px"></div>';
        $carousel .= '<div><img src="/assets/images/carousel/c3.jpg" width="800px"></div>';
        $carousel .= '<div><img src="/assets/images/carousel/c4.jpg" width="800px"></div>';
        $carousel .= '<div><img src="/assets/images/carousel/c5.jpg" width="800px"></div>';
        $carousel .= '</div>';
        
        if (!$this->session->has_userdata('username'))
        {
            // User is not logged in.
            $this->data['carousel'] = '';
        }
        else
        {
            $this->data['carousel'] = $carousel;
        }
        
        $this->data['page_body'] = 'home';
        $this->render();
    }
        
    
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */