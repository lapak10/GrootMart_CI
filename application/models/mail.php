<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Class file which contains all the functions related to the mail funcationality which can be used
 * to sending mails to the user.
 *
 * @author Anand Kumar Chaudhary
 */
class Login extends CI_Model{
        var $from='no-reply@grootmart.com';
        var $user_id=NULL;
        var $to=NULL;
        var $message='Hi';
        var $dbc=NULL;
        var $sender_name='GrootMart.com';
        var $sub='';
        var $code=NULL;

    
    
	function __construct($user_id){
            
            //Validation to check that the Deliverd user_id is NUMERIC
            if(is_numeric($user_id))
           {
                $this->user_id = $user_id;
                
                
                }
           
            parent::__construct();
            $this->dbc=$this->load->database('',TRUE);
            
            if(!is_null($user_id)){
                 
                $this->dbc->select('email');
                
                $this->to = $this->dbc->get_where('User', array('ID' =>$this->user_id));
                
            }
             $this->load->library('email');
        
		}
        // Functio to send validation mail to the user after the registration
                function send_validation_mail(){
                    
                    $this->sub="Email Verification";
                    $this->email->subject($this->sub);
                    $this->email->from($this->from,$this->sender_name);
                    $this->email->to($this->to);
                    $this->code=md5($this->user_id.time());
                    
                    
                    
                    
                    
                    
                    
                }
        // Function to send mail to confirm that user has been validated
                function user_validated(){}
        //Function to send that users password is updated
                function password_changed(){}
        
         //function to send out password_reset_link
                function password_reset_link(){}
         
        //function to send message that order has been placed
        function order_placed_mail(){} 
        
        //function to mail about order status e.g DELIVERD OF DEFFERED
        function order_status_update_mail() {}
        
       
        
            
    
                
        
        
                
                
                
                }

?>
