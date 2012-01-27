<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 */
class Users extends CI_Model {

    private $table_name = 'UserAccount';   // user accounts
    private $profile_table_name = 'User'; // user profiles
    private $password_table = 'UserAccountPassword';
    private $login_details_table = 'login_details';

    function __construct() {
        parent::__construct();

        $ci = & get_instance();
        $this->table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->table_name;
        $this->profile_table_name = $ci->config->item('db_table_prefix', 'tank_auth') . $this->profile_table_name;
        $this->password_table = $ci->config->item('db_table_prefix', 'tank_auth') . $this->password_table;
        $this->login_details_table = $ci->config->item('db_table_prefix', 'tank_auth') . $this->login_details_table;
    }

    /**
     * Get user record by Id
     *
     * @param	int
     * @param	bool
     * @return	object
     */
    function get_user_by_id($user_id, $activated) {
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Get user record by login (username or email)
     *
     * @param	string
     * @return	object
     */
    function get_user_by_login($login) {
        // Meri SQL	
//select username,password from UserAccount,UserAccountPassword where `UserAccount`.`ID`=`UserAccountPassword`.`UserAccount_ID` order by `UserAccountPassword`.`ID` desc limit 1;		
        // Active Record
//          $this->db->select('username','active','password','UserAccount_ID');
//          //$this->db->from('UserAccount');
//          $this->db->join('UserAccountPassword', 'UserAccount.ID = UserAccountPassword.UserAccount_ID','inner');
//          $this->db->where('LOWER(UserAccount.username)=',strtolower($login));
//          $this->db->or_where('LOWER(UserAccount.email)=',strtolower($login))->order_by('UserAccountPassword.ID','desc')->limit(1);
//
//          $query = $this->db->get('UserAccount');
        // select username,active,password,UserAccount_ID from UserAccount inner join UserAccountPassword on UserAccount.ID=UserAccountPassword.UserAccount_ID where lower(UserAccount.username)='momos' or lower(UserAccount.email)='momos' order by UserAccountPassword.ID desc limit 1


        $login = $this->db->escape($login);
        $query = $this->db->query('select username,active,password,UserAccount_ID from ' . $this->table_name . ' inner join ' . $this->password_table . ' on UserAccount.ID=UserAccountPassword.UserAccount_ID where lower(UserAccount.username)=' . $login . ' or lower(UserAccount.email)=' . $login . ' order by UserAccountPassword.ID desc limit 1');
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Get user record by username
     *
     * @param	string
     * @return	object
     */
    function get_user_by_username($username) {
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Get user record by email
     *
     * @param	string
     * @return	object
     */
    function get_user_by_email($email) {
        $this->db->where('LOWER(email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1)
            return $query->row();
        return NULL;
    }

    /**
     * Check if username available for registering
     *
     * @param	string
     * @return	bool
     */
    function is_username_available($username) {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Check if email available for registering
     *
     * @param	string
     * @return	bool
     */
    function is_email_available($email) {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        //$this->db->or_where('LOWER(new_email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Create new USER ACCOUNT into the UserAccount table.
     *
     * @param	array
     * @param	bool
     * @return	array
     */
    function create_user($data, $password) {
        //No need to add $data[created] field because DATABASE useses TIMESTAMP by itself
        //$data['created'] = date('Y-m-d H:i:s');
        //Attemp to Create a new userAccount in UserAccount Table
        if ($this->db->insert($this->table_name, $data)) {
            //  UserAccount Created. OK
            $user_id = $this->db->insert_id();              // Returns the ID of the Freshly Created New UserAccount
            $this->update_password($password, $user_id);

            if ($data['active'] == 'Active')
                $this->create_profile($user_id);       //Create User Only When the active=='Active'.
            return array('user_id' => $user_id); // Returns the array containing the [user_id] => $user_id.
        }
        return NULL;  // Error Creating UserAccount, Returns NULL.
    }

    /**
     * --Anand Kumar Chaudhary
     * Makes a password entry into the $this->password_table
     *
     * @param	string
     * @param	int
     * @return	bool
     */
    function update_password($password, $user_id) {
        $pass_data = array(
            'password' => $password,
            'UserAccount_ID' => $user_id
        );
        $this->db->insert('UserAccountPassword', $pass_data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param	int
     * @param	string
     * @param	bool
     * @return	bool
     */
    function activate_user($user_id, $activation_key, $activate_by_email) {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        if ($activate_by_email) {
            $this->db->where('new_email_key', $activation_key);
        } else {
            $this->db->where('new_password_key', $activation_key);
        }
        $this->db->where('activated', 0);
        $query = $this->db->get($this->table_name);

        if ($query->num_rows() == 1) {

            $this->db->set('activated', 1);
            $this->db->set('new_email_key', NULL);
            $this->db->where('id', $user_id);
            $this->db->update($this->table_name);

            $this->create_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Purge table of non-activated users
     *
     * @param	int
     * @return	void
     */
    function purge_na($expire_period = 172800) {
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete($this->table_name);
    }

    /**
     * Delete user record
     *
     * @param	int
     * @return	bool
     */
    function delete_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows() > 0) {
            $this->delete_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function set_password_key($user_id, $new_pass_key) {
        $this->db->set('new_password_key', $new_pass_key);
        $this->db->set('new_password_requested', date('Y-m-d H:i:s'));
        $this->db->where('id', $user_id);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	int
     * @return	void
     */
    function can_reset_password($user_id, $new_pass_key, $expire_period = 900) {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 1;
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	int
     * @return	bool
     */
    function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900) {
        $this->db->set('password', $new_pass);
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Change user password
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function change_password($user_id, $new_pass) {
        
        return $this->update_password($new_pass,$user_id);
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param	int
     * @param	string
     * @param	string
     * @param	bool
     * @return	bool
     */
    function set_new_email($user_id, $new_email, $new_email_key, $activated) {
        $this->db->set($activated ? 'new_email' : 'email', $new_email);
        $this->db->set('new_email_key', $new_email_key);
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param	int
     * @param	string
     * @return	bool
     */
    function activate_new_email($user_id, $new_email_key) {
        $this->db->set('email', 'new_email', FALSE);
        $this->db->set('new_email', NULL);
        $this->db->set('new_email_key', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_email_key', $new_email_key);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param	int
     * @param	bool
     * @param	bool
     * @return	void
     */
    function update_login_info($user_id, $record_ip) {


        if ($record_ip)
        $this->db->set('ip_address', $this->input->ip_address());
        //if ($record_time)
        $this->db->set('user_agent', $this->input->user_agent());

        $this->db->where('UserAccount_ID', $user_id);
        $this->db->update($this->login_details_table);
    }

    /**
     * Ban user
     *
     * @param	int
     * @param	string
     * @return	void
     */
    function ban_user($user_id, $reason = NULL) {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned' => 1,
            'ban_reason' => $reason,
        ));
    }

    /**
     * Unban user
     *
     * @param	int
     * @return	void
     */
    function unban_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned' => 0,
            'ban_reason' => NULL,
        ));
    }

    /**
     * Create an empty profile for a new user
     *
     * @param	int
     * @return	bool
     */
    private function create_profile($user_id) {
        $this->db->set('UserAccount_ID', $user_id);
        $this->db->insert($this->profile_table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Delete user profile
     *
     * @param	int
     * @return	void
     */
    private function deactivate_user($user_id) {
        $this->db->where('ID', $user_id);
       return $this->db->update($this->table_name,array('active'=>'Deactivated'));
        
    }

}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */