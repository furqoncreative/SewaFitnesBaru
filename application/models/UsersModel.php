<?php

class UsersModel extends MY_Model {

    public $_table = 'users';

    public function attemptLogin($email,$password)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('users.email',$email);
        $this->db->where('users.password',$password);
        $query = $this->db->get();
        $result = $query->row();

        return $result;

	}

	public function attemptLoginAdmin($email,$password)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('admin.email',$email);
        $this->db->where('admin.password',$password);
        $query = $this->db->get();
        $result = $query->row();

        return $result;

	}
	
	public function signUp() {
        $data = array (
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
		);
		$this->db->set('created_at', 'NOW()', FALSE);
		$this->db->set('updated_at', 'NOW()', FALSE);
        $this->db->insert('users', $data);
    }

}
