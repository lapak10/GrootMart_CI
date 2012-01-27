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
class Mail extends CI_Model {

    var $from = 'no-reply@grootmart.com';
    var $user_id = NULL;
    var $to = NULL;
    var $message = 'Hi';
    var $dbc = NULL;
    var $sender_name = 'GrootMart.com';
    var $sub = '';
    var $code = NULL;
    var $oder_id = '';

    //Constructor Function for which USER_ID is Required and ORDER_ID is Optionall
    function __construct($user_id, $order_id = NULL) {

        //Validation to check that the Deliverd user_id is NUMERIC
        if (is_numeric($user_id)) {
            $this->user_id = $user_id;
        }

        parent::__construct();
        $this->dbc = $this->load->database('', TRUE);

        if (!is_null($user_id)) {

            $this->dbc->select('email');

            $this->to = $this->dbc->get_where('User', array('ID' => $this->user_id));
        }
        //to set the order ID value to the class properties.
        $this->order_id = $order_id;
        $this->load->library('email');
    }

    // Functio to send validation mail to the user after the registration
    function send_validation_mail() {

        // Setting up the email required fields
        $this->sub = "Email Verification";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        // Generating the Authencation Code Which will send to the user's email
        $this->code = md5($this->user_id . time());

        // Now Storing Authentication Code into DataBase for cross checking
        $this->dbc->where('ID', $this->user_id);
        $data = array('code' => $this->dbc->code);
        $this->dbc->update('User', $data);

        //After Storing the Authntication Code, Lets mail it to the user!                    
        $this->email->message($this->code);
        $this->email->send();
    }

    // Function to send mail to confirm that user has been validated
    function user_validated() {
        // Setting up the email required fields
        $this->sub = "Email Confirmed";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        // Since This is Just Confirmation of User validation,Message will be Static
        $this->message = "<<<Confirmation Message Goes Here!!>>>";

        // Send mail to the user
        $this->send();
    }

    //Function to send mail that users password is updated
    function password_changed() {
        // Setting up the email required fields
        $this->sub = "Password Chaged";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        //Message Body Telling about user's paddword Change Activity
        $this->message = "This is to notify that your password has been modified few moments ago";

        //Sending the email to user
        $this->send();
    }

    //function to send out password_reset_link
    function password_reset_link() {
        // Setting up the email required fields
        $this->sub = "Password Reset Link";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        //Message Body
        $this->message = "Here is your password reset link";

        //send mail
        $this->email->send();
    }

    //function to send message that order has been placed
    function order_placed_mail() {
        // Setting up the email required fields
        $this->sub = "Order Placed";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        //Message body
        $this->message = "Your Order has been placed, grootmart is now at your work";


        //send mail();
        $this->email->send();
    }

    //function to mail about order status e.g DELIVERD OF DEFFERED
    function order_status_update_mail() {
        // Setting up the email required fields
        $this->sub = "Your Order Status";
        $this->email->subject($this->sub);
        $this->email->from($this->from, $this->sender_name);
        $this->email->to($this->to);

        // Get the order status from the database
        $this->dbc->select('status');
        $this->dbc->where('ID', $this->order_id);
        $query = $this->dbc->get('Order');
        $status=$query->row('status');


        //prepare message body
        $this->message = "Your Order Has been" . $status;

        //send mail to the user
        $this->email->send();
    }

}

?>
