<?php  

class Database
{
    private $connection = null;

    public function __construct($database, $username = "root", $password = "root", $servername = "localhost", $port = 8888)
    {
        // Data Source Name
        $dsn = "mysql:host=$servername;port=$port;dbname=$database;charset=UTF8";

        try {
            $this->connection = new PDO(
                $dsn,
                $username,
                $password
            );
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
        $this->execute($statement, $input_parameters);
    }

    /**********************
     * Public DELETE method
     */
    public function delete($statement, $input_parameters = [])
    {
        $this->execute($statement, $input_parameters);
    }

}