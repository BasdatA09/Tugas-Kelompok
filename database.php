<?php

class database
{
    private $dbname;
    private $dbhost;
    private $dbport;
    private $uname;
    private $pass;



    /**
     * database constructor.
     * @param $dbname
     * @param $dbhost
     * @param $dbport
     * @param $uname
     * @param $pass
     */
    public function __construct()
    {
//        $this->dbname = "postgres";
//        $this->dbhost = "basdat.southeastasia.cloudapp.azure.com";
//        $this->dbport = "1999";
//        $this->uname = "affan";
//        $this->pass = "affan1234";
        // $this->dbname = "kelompoka09";
        // $this->dbhost = "basdat.southeastasia.cloudapp.azure.com";
        // $this->dbport = "1999";
        // $this->uname = "basdata09";
        // $this->pass = "basdat";
        $this->dbname = "a09";
        $this->dbhost = "dbpg.cs.ui.ac.id";
        $this->dbport = "5432";
        $this->uname = "a09";
        $this->pass = "j9LhsK";

    }

    public function connectDB(){
        $sql = "set search_path to sisidang";
        try {

            $conn = new PDO("pgsql:host=" . $this->getDbhost() . ";port=" . $this->getDbport() . ";dbname=" . $this->getDbname() . ";user=" . $this->getUname() . ";password=" . $this->getPass());

            $db = $conn->prepare($sql);

            $db->execute();

            return $conn;
        } catch (PDOException $e){
            die("Connection Failed: ".$e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * @return string
     */
    public function getDbhost()
    {
        return $this->dbhost;
    }

    /**
     * @return string
     */
    public function getDbport()
    {
        return $this->dbport;
    }

    /**
     * @return string
     */
    public function getUname()
    {
        return $this->uname;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

}