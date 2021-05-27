<?php

class Database
{
    private $connection = null;

    public function __construct()
    {
        //Get Heroku ClearDB connection information
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $cleardb_server = $cleardb_url["host"];
        $cleardb_username = $cleardb_url["user"];
        $cleardb_password = $cleardb_url["pass"];
        $cleardb_db = array($cleardb_url["path"], 1);
        $active_group = 'default';
        $query_builder = TRUE;

        try {
            $this->connection = new PDO($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /********************************************************
     * Private instance method that executes a PDO statement.
     */
    private function execute($statement, $input_parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($statement);
            $stmt->execute($input_parameters);

            return $stmt;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**********************
     * Public SELECT method
     */
    public function select($statement, $input_parameters = [])
    {
        $stmt = $this->execute($statement, $input_parameters);
        return $stmt->fetchAll();
    }

    /**********************
     * Public INSERT method
     */
    public function insert($statement, $input_parameters = [])
    {
        $this->execute($statement, $input_parameters);

        return $this->connection->lastInsertId();
    }

    /**********************
     * Public UPDATE method
     */
    public function update($statement, $input_parameters = [])
    {
        $stmt =  $this->execute($statement, $input_parameters);

        return $stmt->rowCount();
    }

    /**********************
     * Public DELETE method
     */
    public function delete($statement, $input_parameters = [])
    {
        $stmt = $this->execute($statement, $input_parameters);

        return $stmt->rowCount();
    }
}
