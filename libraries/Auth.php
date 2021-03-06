<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth
{
	var $CI;
	
	//this is the expiration for a non-remember session
	var $session_expire	= 7200;

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
	//	$this->CI->load->library('encrypt');
		
		$admin_session_config = array(
		    'sess_cookie_name' => 'rs_session_config',
		    'sess_expiration' => 0
		);
	//	$this->CI->load->library('session', $admin_session_config, 'admin_session');
		
		$this->CI->load->helper('url');
	}
	
	function check_access($access, $default_redirect=false, $redirect = false)
	{
		/*
		we could store this in the session, but by accessing it this way
		if an admin's access level gets changed while they're logged in
		the system will act accordingly.
		*/
		
		$admin = $this->CI->session->userdata('admin');
		
		$this->CI->db->select('access');
		$this->CI->db->where('id', $admin['id']);
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('admin');
		$result	= $result->row();
		
		//result should be an object I was getting odd errors in relation to the object.
		//if $result is an array then the problem is present.
		if(!$result || is_array($result))
		{
			$this->logout();
			return false;
		}

		if ($access)
		{
			if ($access == $result->access)
			{
				return true;
			}
			else
			{
				if ($redirect)
				{
					$this->CI->session->set_flashdata('error', lang('access_denied'));
					redirect($redirect);
				}
				elseif($default_redirect)
				{
					redirect($this->CI->config->item('admin_folder').'/dashboard');
				}
				else
				{
					return false;
				}
			}
		}
	}
	
    /*
	this checks to see if the admin is logged in
	we can provide a link to redirect to, and for the login page, we have $default_redirect,
	this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
	*/
	function is_logged_in($redirect = false, $default_redirect = true)
	{
		//$redirect allows us to choose where a customer will get redirected to after they login
		//$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.

		$admin = $this->CI->session->userdata('admin');
		
		if (!$admin)
		{
			if ($redirect)
			{
				$this->CI->session->set_flashdata('redirect', $redirect);
			}
				
			if ($default_redirect)
			{
				redirect('login');
			}
			
			return false;
		}
		else
		{
			//check if the session is expired if not reset the timer
			if($admin['expire'] && $admin['expire'] < time())
			{
				$this->logout();
				if($redirect)
				{
					$this->CI->session->set_flashdata('redirect', $redirect);
				}

				if($default_redirect)
				{
					redirect('login');
				}

				return false;
			}
			else
			{
				//update the session expiration to last more time if they are not remembered
				if($admin['expire'])
				{
					$admin['expire'] = time()+$this->session_expire;
					$this->CI->session->set_userdata(array('admin'=>$admin));
				}
			}

			return true;
		}
	}
	
	/*
	this function does the logging in.
	*/
	function login_admin($email, $password, $remember=false)
	{
		$this->CI->db->select('*');
		$this->CI->db->where('email', $email);
		$this->CI->db->where('password',  sha1($password));
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('admin');
		$result	= $result->row_array();
		
		if ($result)
		{
			$admin = array();
			$admin['admin']			= array();
			$admin['admin']['id']		= $result['id'];
			$admin['admin']['access'] 	= $result['access'];
			$admin['admin']['firstname']	= $result['firstname'];
			$admin['admin']['lastname']	= $result['lastname'];
			$admin['admin']['email']	= $result['email'];
			
			if(!$remember)
			{
				$admin['admin']['expire'] = time()+$this->session_expire;
			}
			else
			{
				$admin['admin']['expire'] = false;
			}

			$this->CI->session->set_userdata($admin);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	this function does the logging out
	*/
	function logout()
	{
		$this->CI->session->unset_userdata('admin');
		$this->CI->session->sess_destroy();
	}
	
	/*
	This function gets the admin by their email address and returns the values in an array
	it is not intended to be called outside this class
	*/
	private function get_admin_by_email($email)
	{
		$this->CI->db->select('*');
		$this->CI->db->where('email', $email);
		$this->CI->db->limit(1);
		$result = $this->CI->db->get('admin');
		$result = $result->row_array();

		if (sizeof($result) > 0)
		{
			return $result;	
		}
		else
		{
			return false;
		}
	}
	
	/*
	This function takes admin array and inserts/updates it to the database
	*/
/*	function save($admin)
	{
		if ($admin['id'])
		{
			$this->CI->db->where('id', $admin['id']);
			$this->CI->db->update('admin', $admin);
		}
		else
		{
			$this->CI->db->insert('admin', $admin);
		}
	}
	*/
	
	/*
	This function gets a complete list of all admin
	*/
	function get_admin_list()
	{
		$this->CI->db->select('*');
		$this->CI->db->where('id !=', 1);
		$this->CI->db->order_by('lastname', 'ASC');
		$this->CI->db->order_by('firstname', 'ASC');
		$this->CI->db->order_by('email', 'ASC');
		$result = $this->CI->db->get('admin');
		$result	= $result->result();
		
		return $result;
	}

	/*
	This function gets an individual admin
	*/
	function get_admin($id)
	{
		$this->CI->db->select('*');
		$this->CI->db->where('id', $id);
		$result	= $this->CI->db->get('admin');
		$result	= $result->row();

		return $result;
	}		
	
	function check_email($str, $id=false)
	{
		$this->CI->db->select('email');
		$this->CI->db->from('admin');
		$this->CI->db->where('email', $str);
		if ($id)
		{
			$this->CI->db->where('id !=', $id);
		}
		$count = $this->CI->db->count_all_results();
		
		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}