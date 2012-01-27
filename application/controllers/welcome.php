<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->load->view('welcome_message');
    }

    public function inde() {
        //$this->load->view(rm');
        // date_default_timezone_set('Asia/Kolkata');
        //  $data['heading']=date('r');


        $this->load->helper('date');
        $data['heading'] = timezones('UP45');

        $this->load->view('form', $data);
    }

    public function slots() {

        $this->load->model('orders');
        $data['slot'] = $this->orders->slots();
        $this->load->view('slot', $data);
    }
    
    public function mail(){
        $config = Array(
    'protocol' => 'mail',
    
    

    'mailtype'  => 'html', 
    'charset'   => 'iso-8859-1'
);
        
        
$this->load->library('email', $config);
$this->email->set_newline("\r\n");
$this->email->from('i_0@aol.com', 'GrootMart');
    $this->email->to('anand@grootmart.com'); 
$this->email->reply_to('anand@gofca.org', 'Your Name');
    $this->email->subject('Its Working Baby!');
    $this->email->message('Testing the email class.');  

    $this->email->send();
   // $this->email->send();

    echo $this->email->print_debugger();

    }
    
    

 

 
// Send the mail


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
