<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author lucky
 */
class User extends CI_Model {

    var $fname = NULL;
    var $mname = NULL;
    var $lname = NULL;
    var $dbc = '';
    var $deactivation_status = 'deactivated';

    function __construct() {
        parent::__construct();

        $this->dbc = $this->load->database('', TRUE);
    }

    //function to add new user to grootmart
    function user_add($fname, $lname, $mname, $w_phone, $m_phone, $email, $secondry_email, $address1, $address2, $city, $country) {

        //setting up the fields for the insert operation.
        $data = array(
            'FName' => $fname,
            'MName' => $mname,
            'LName' => $lname,
            'PhoneM' => $m_phone,
            'PhoneW' => $w_phone,
            'email' => $email,
            'SecondryEmail' => $secondry_email,
            'AddressLine1' => $address1,
            'AddressLine2' => $address2,
            'City' => $city,
            'Country' => $country
        );

        //firing up the insert query.
        $this->dbc->insert('User', $data);
    }

    //Function to add new UserAccount Into DB,By Default it Takes CUSTOMER who is NOT VALIDATED
    function useraccount_add($username, $user_id, $code, $userlevel = 'customer', $active = 'Not Validated') {

        $data = array(
            'username' => $username,
            'Code' => $code,
            'User_ID' => $user_id,
            'userlevel' => $userlevel,
            'active' => $active
        );

// Before creating a userAccount,Lets Check whether the USER exists or NOT!!
        $query = $this->dbc->get_where('User', array('ID', $user_id));
        if ($query->num_rows() > 0) {

            //User Exists....Fire Up the Query!! :)
            $this->dbc->insert('UserAccount', $data);
        } else {
            // Throw Error That the USER_ID Doesnot Exist in USER table... :@ !
        }
    }

    //Set user's Status eg. BANNED, DeActivated, Not_Validated,Active
    function update_user_status($user_id, $user_status) {

        //setting up the data which we have to update
        $data = array(
            'active' => $user_status
        );

        //firing up the query with WHERE clause and UPDATE clause
        $this->dbc->where('ID', $user_id)->update('UserAccount', $data);
    }

}

?>
