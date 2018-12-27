<?php

class Database
{
    private $host = 'localhost';
    private $dbName = 'cms';
    private $userName = 'user2';
    private $password = 'blade2005';
    private $table;
    private $options = ['cost' => 12];


    public function databaseConnection()
    {
        try{
            $database = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->userName, $this->password);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $database;
        } catch (PDOException $e){
            echo 'Connection Failed' . $e->getMessage();
            exit();
        }

    }


    public function insertStatement($title, $body)
    {
        $stmt = $this->databaseConnection()->prepare("INSERT INTO articles (title, body) VALUES (:title, :body)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->execute();

    }

    public function register($fullName, $email, $pass, $userName)
    {
        if($fullName == '' || $email == '' || $pass == '' || $userName == ''){
            echo 'Need to make sure all appropriate fields are populated';
        } else {
           
            //encrypts password
            $pass = $this->passwordEncrypt($pass);
            
            //prepare the insert into database
            $stmt = $this->databaseConnection()->prepare("INSERT INTO users (FullName, email, password, username) VALUES (:FullName, :email, :pass, :userName)");
            $stmt->bindParam(':FullName', $fullName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass',$pass);
            $stmt->bindParam(':userName', $userName);
            $stmt->execute();
            header('Location: http://www.localhost/cms/views/member.php');
        }

        
    }

    private function passwordEncrypt($password)
    {
        return password_hash($password, CRYPT_BLOWFISH, $this->options);
    }

}