<?php

class Orders extends CI_Model {

    var $order_id = NULL;
    var $dbc = NULL;

    function __construct() {
        parent::__construct();
        $this->dbc = $this->load->database('', TRUE);
    }

// Function to Insert New Order                       
    function new_order($user_id, $expected_date, $delivery_date, $ordered_date, $delivery_slot) {
        //Setting up the data for new order
        $data = array(
            'Customer_ID' => $user_id,
            'Expected_Date' => $expected_date,
            'Delivered_Date' => $delivery_date,
            'DeliverySlots_ID' => $delivery_slot
        );
        $this->dbc->insert('Order', $data);
    }

//function to modify status flag of any order
    function order_status_update($order_id, $new_status) {
        
    }

//function to get order history of any person
    function user_recent_orders_record($user_id, $limit) {
        
    }

//function to get order history by shopkeeper
    function shopkeeper_recent_orders_history($seller_id, $limit, $user_id = NULL) {
        
    }

    //function to get the past order history NEEDS RESEARCH!!!
    function order_history($user_id, $limit, $offset) {

        $this->dbc->select('ID,date,ExpectedDeliveryDate,DeliverySlots_ID,ActualDeliveryDate,status');
    }

//function get order details
    function order_details($order_id) {
        
    }

//function to modify and order
//TEST function to get the tests details
    function slots() {

        $query = $this->db->get('DeliverySlots', 3);
        echo $query->row('ID');
    echo "<select>";
    foreach ($query->result() as $row)
{
    echo "<option value=".$row->ID.">".$row->time." AM</option>";
}
    echo "</select>";
    }

}

?>