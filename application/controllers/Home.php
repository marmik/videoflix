<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	// constructor
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('crud_model');
		$this->load->model('email_model');
		$this->load->library('session');
		
	}
	
	
	// Home browsing page
	public function index()
	{
		$this->login_check();
		$page_data['page_name']		=	'landing';
		$page_data['page_title']	=	'Welcome';
		$this->load->view('frontend/index', $page_data);
	}
	
	function signup()
	{
		$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$signup_result = $this->crud_model->signup_user();
			if ($signup_result == true)
				redirect(base_url().'index.php?browse/purchaseplan' , 'refresh');
			else if ($signup_result == false)
				redirect(base_url().'index.php?home/signup' , 'refresh');
		}
		$page_data['page_name']		=	'signup';
		$page_data['page_title']	=	'Sign up';
		$this->load->view('frontend/index', $page_data);
		
	}
	
	function signin()
	{
		$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$email 			= $this->input->post('email');
			$password 		= $this->input->post('password');
			$signin_result 	= $this->crud_model->signin($email, $password);
			if ($signin_result == true)
			{
				if ($this->session->userdata('login_type') == 1)
					redirect(base_url().'index.php?admin/dashboard' , 'refresh');
				else if ($this->session->userdata('login_type') == 0)
					redirect(base_url().'index.php?browse/switchprofile' , 'refresh');
			}
			else if ($signin_result == false)
				redirect(base_url().'index.php?home/signin' , 'refresh');
		}
		$page_data['page_name']		=	'signin';
		$page_data['page_title']	=	'Sign in';
		$this->load->view('frontend/index', $page_data);
		
	}
	
	function forget()
	{
		$this->login_check();
		if (isset($_POST) && !empty($_POST))
		{
			$signup_result = $this->email_model->reset_password();
			redirect(base_url().'index.php?home/forget' , 'refresh');
		}
		$page_data['page_name']		=	'forget';
		$page_data['page_title']	=	'Forget Password';
		$this->load->view('frontend/index', $page_data);
		
	}
	
	function signout()
	{
		$this->session->set_userdata('user_login_status', '');
        $this->session->set_userdata('user_id', '');
        $this->session->set_userdata('login_type', '');
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url().'index.php?home/signin', 'refresh');
	}
	
	
	
	
	function login_check()
	{
		if ($this->session->userdata('user_login_status') == 1)
			redirect(base_url().'index.php?browse/home' , 'refresh');
	}
	


}
