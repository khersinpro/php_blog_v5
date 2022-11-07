<?php 
namespace App\Database;

class DbConnect
{
    private $dns;
    private $user;
    private $pwd;
    
    public function pdoConnect()
    {
        $this->dns = 'mysql:host=localhost;dbname=blog';
        $this->user = getenv("DB_USER");
        $this->pwd = getenv("DB_PWD");
        try {
            $pdo = new \PDO($this->dns, $this->user, $this->pwd, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage()) ;
        }
    }
}