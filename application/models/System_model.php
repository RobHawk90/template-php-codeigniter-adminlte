<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_model extends CI_Model {

  public function setUser($user) {
    $this->session->set_userdata('user', $user);
  }

  public function getUser() {
    return $this->session->userdata('user');
  }

}
