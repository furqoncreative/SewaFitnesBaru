<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignupController extends CI_Controller {
    /**
     * SignupController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UsersModel');
    }

    /**
     * Set validation rule for Login form
     */
    public function setValidationRules()
    {
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
        $this->form_validation->set_rules('password','Password','trim|required');
    }

    /**
     * Show Login form. Attempt Login if POST request.
     */
    public function index()
    {
        $this->load->view("auth/signup");
	}
	
	public function signUp()
	{
		$this->UsersModel->signUp();
        redirect("login");
	}


}
