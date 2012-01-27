<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User_Autologin
 *
 * This model represents user autologin data. It can be used
 * for user verification when user claims his autologin passport.
 * 
 */
class User_Autologin extends CI_Model
{
	private $session_details_table	= 'login_session_details';
	private $UserAccount	= 'UserAccount';
        

	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$this->session_details_table= $ci->config->item('db_table_prefix', 'tank_auth').$this->session_details_table;
		$this->UserAccount	= $ci->config->item('db_table_prefix', 'tank_auth').$this->UserAccount;
	}

	/**
	 * Get user data for auto-logged in user.
	 * Return NULL if given key or user ID is invalid.
	 *
	 * @param	int
	 * @param	string
	 * @return	object
	 */
	function get($user_id, $key)
	{       
                $user_id=$this->db->escape($user_id);
                $key=$this->db->escape($key);
                
//		$this->db->select('UserAccount_ID','username','active');		
//		$this->db->from($this->session_details_table);  
//		$this->db->join($this->UserAccount,$this->UserAccount.'.ID = '.$this->session_details_table.'.UserAccount_ID','inner');
//		$this->db->where($this->session_details_table.'.UserAccount_ID', $user_id);
//		$this->db->where($this->session_details_table.'.key_id', $key)->order_by('login_session_details.ID','desc')->limit(1);
//		$query = $this->db->get();
                
                $query=$this->db->query('select username,active,UserAccount_ID from login_session_details inner join UserAccount on UserAccount.ID=login_session_details.UserAccount_ID where key_id='.$key.' and UserAccount_ID='.$user_id.' order by login_session_details.ID desc limit 1');
                
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set($user_id, $key)
	{
		return $this->db->insert($this->session_details_table, array(
			'UserAccount_ID' => $user_id,
			'key_id'	 => $key,
					));
	}

	/**
	 * Delete user's autologin data
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
//	function delete($user_id, $key)
//	{
//		$this->db->where('user_id', $user_id);
//		$this->db->where('key_id', $key);
//		$this->db->delete($this->table_name);
//	}

	/**
	 * Delete all autologin data for given user
	 *
	 * @param	int
	 * @return	void
	 */
//	function clear($user_id)
//	{
//		$this->db->where('user_id', $user_id);
//		$this->db->delete($this->table_name);
//	}

	/**
	 * Purge autologin data for given user and login conditions
	 *
	 * @param	int
	 * @return	void
	 */
//	function purge($user_id)
//	{
//		$this->db->where('user_id', $user_id);
//		$this->db->where('user_agent', substr($this->input->user_agent(), 0, 149));
//		$this->db->where('last_ip', $this->input->ip_address());
//		$this->db->delete($this->table_name);
//	}
}

/* End of file user_autologin.php */
/* Location: ./application/models/auth/user_autologin.php */