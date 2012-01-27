<?php
class User_details extends CI_Model{


	var $uid=NULL;

	function __construct($id)
	{
		// Call the Model constructor
		parent::__construct();

		$this->uid=$id;

	}

	//Function to get users First Name based on UID;
	function get_fname(){}
	function get_lname(){}
	function get_phone(){}
	function get_email(){}
	function get_address(){}



}
?>