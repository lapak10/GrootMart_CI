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
                function send_validation_mail($user_id){
                    
                }
        // Function to send mail to confirm that user has been validated
                function user_validated($user_id,$secret_code){}
        //Function to send that users password is updated
                function password_changed($user_id){}
        
         //function to send out password_reset_link
                function password_reset_link($user_id){}
         
        //function to send message that order has been placed
        function order_placed_mail($param){} 
        
        //function to mail about order status e.g DELIVERD OF DEFFERED
        function order_status_update_mail($param) {}
        
       
        
            
    
                
        
        
                
                
                
                }

?>
