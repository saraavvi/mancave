<?php
class AdminModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function fetchAdminByEmail($email)
    {
        $statement = "SELECT * FROM employees WHERE email = :email";
        $params = array(":email" => $email);
        $customer = $this->db->select($statement, $params);
        return $customer[0] ?? false;
    } 
}
