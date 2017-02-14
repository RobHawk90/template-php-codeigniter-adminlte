<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

  private $table;

  public function __construct() {
    parent::__construct();

    $this->table = 'users';
  }

  public function insert($user) {
    $this->db->set('name', $user->name)
      ->set('email', $user->email)
      ->set('password', $user->password)
      ->insert($this->table);
  }

  public function update($user) {
    $this->db->where('id', $user->id)
      ->set('ip', $user->ip)
      ->set('name', $user->name)
      ->set('email', $user->email)
      ->set('password', $user->password)
      ->set('remember', $user->remember)
      ->update($this->table);
  }

  public function findByEmailAndPassword($email, $password) {
    return $this->db->where('email', $email)
      ->where('password', $password)
      ->get($this->table)->row();
  }

  public function findByIp($ip) {
    return $this->db->where('ip', $ip)->get($this->table)->row();
  }

}
