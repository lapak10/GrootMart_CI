<?php
class Orders extends CI_Model{    

function __construct(){
                       parent::__construct();
                    $this->load->database();
}

// Function to Insert New Order                       
function new_order($user_id,$seller_id,$delivery_slot,$product_id){
	$data=array('');
        //A New Entry ;)
}

//function to modify status flag of any order
function order_status_update($order_id,$new_status){}

//function to get order history of any person
function  user_recent_orders_record($user_id,$limit){}

//function to get order history by shopkeeper
function shopkeeper_recent_orders_history($seller_id,$limit,$user_id=NULL){}

//function get order details
function order_details($order_id){}

//function to modify and order

//TEST function to get the tests details
function slots(){
    
    $query=$this->db->get('DeliverySlots',5,5);
    foreach ($query->result() as $row)
{
    echo $row->time."<br>";
}
    
    }

}

?>