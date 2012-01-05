<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mail
 *
 * @author lucky
 */
class Login extends CI_Model{
        var $from='no-reply@grootmart.com';
         
     
    
    
    
    
	function __construct(){
	parent::__construct();
        $this->load->database();
        $this->load->library('email');
        
		}
        // Functio to send validation mail to the user after the registration
                function send_validation_mail(){
                    
                }
        // Function to send mail to confirm that user has been validated
                function user_validated(){}
        //Function to send that users password is updated
                function password_changed(){}
        
         //function to send out password_reset_link
                function password_reset_link(){}
         
        //function to send message that order has been placed
        function order_placed_mail($param){} 
        
        //function to mail about order status
        function order_status_update_mail($param) {}
        
        //function to 
        
            
    
                
        
        
                
                
                
                }

?>
