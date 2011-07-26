<?php

class Users {

    // percentage of satisfied users
    private $iPcHappyUsers;

    public function __construct()
    {
    
    }

    /**
     * Display users.
     */
    public function displayUsers()
    {

    }

    /**
     * Getters & Setters.
     */
    public function setIPcHappyUsers($pcHappyUsers)
    {
        $this->iPcHappyUsers = $pcHappyUsers;
    }
  
    public function getIPcHappyUsers()
    {
        return $this->iPcHappyUsers;
    }
}
?>