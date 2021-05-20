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
        $statement = "SELECT *, CONCAT(admins.first_name, ' ', admins.last_name) AS name FROM admins WHERE email = :email";
        $params = array(":email" => $email);
        $customer = $this->db->select($statement, $params);
        return $customer[0] ?? false;
    } 
}
