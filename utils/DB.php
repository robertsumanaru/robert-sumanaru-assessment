<?php

class DB
{
	private $pdo;

	private static $instance = null;

	private function __construct()
	{
		$dsn = 'mysql:dbname=phptest;host=localhost';
		$user = 'root';
		$password = '';

		$this->pdo = new \PDO($dsn, $user, $password);
	}

    /*
     * The object is created from within the class itself only if the class has no instance.
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    /**
     * Executes a SQL query and returns all rows.
     */
	public function select($sql)
	{
		$sth = $this->pdo->query($sql);
		return $sth->fetchAll();
	}

    /**
     * Executes a SQL query.
     */
    public function execute($sql, $parameters = [])
    {
        $sth = $this->pdo->prepare($sql);
        return $sth->execute($parameters);
    }

    /**
     * Retrieves the last inserted ID.
     */
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}