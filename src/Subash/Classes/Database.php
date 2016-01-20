<?php
namespace Subash\Classes;

class Database
{

    protected $dbLink;

    private function create()
    {
        $this->dbLink = new \mysqli('localhost', 'root', 'root', 'football', '3306');
        if ($this->dbLink->connect_errno) {
            printf("Connect failed: %s\n", $this->dbLink->connect_error);
            exit();
        }
    }

    public function getConnection()
    {
        $this->create();
    }

    public function closeConnection()
    {
        if ($this->dbLink) {
            $this->dbLink->close();
        }
    }
}