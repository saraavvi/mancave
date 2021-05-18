<?php
class CustomerModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    public function fetchCustomerById($id)
    {
        $statement = "SELECT * FROM customers WHERE id = :id";
        $params = array(":id" => $id);
        $customer = $this->db->select($statement, $params);
        return $customer[0] ?? false;
    }

    public function fetchAllCustomers()
    {
        $statement = "SELECT * FROM customers";
        $customers = $this->db->select($statement);
        return $customers ?? false;
    }

    //Do we need to redo Customer password later?
    public function updateCustomerById($id, $data)
    {
        $statement = "UPDATE customers SET
            first_name = :first_name, 
            last_name = :last_name,
            email = :email,
            password = :password, 
            address = :address
        WHERE id = :id";
        $params = array(
            ':id' => $id,
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':address' => $data['address']
        );
        $this->db->update($statement, $params);
    }

    public function deleteCustomerById($id)
    {
        $statement = "DELETE FROM customers WHERE id = :id";
        $params = array(':id' => $id);
        $row_count = $this->db->delete($statement, $params);
        return $row_count;
    }

    public function createCustomer($data)
    {
        $statement = "INSERT INTO customers (
                first_name, 
                last_name, 
                email, 
                password, 
                address
            ) 
            VALUES 
            ( 
                :first_name,
                :last_name,
                :email, 
                :password,
                :address
            )";

        $params = array(
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':address' => $data['address']
        );

        $last_insert_id = $this->db->insert($statement, $params);
        return $last_insert_id;
    }
}
