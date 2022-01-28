<?php

namespace Panaxin;

class Database{
    #This Function returns your Database. 
    #You only need to use this function in your Database Class.
    public static function getDB(){
        $ip = "";
        $username = "";
        $password = "";
        $databasename = "";
        $port = 3306;
        $db = new \mysqli($ip, $username, $password, $databasename, $port);
        
        return $db;
    }
    
    
    
    
    
    #This Function initializes your Database.
    #It will check if The Table "playerinfo" exists 
    #and if not it will make a table with the name "playerinfo"
    #and creates the columns "name", "level" and "xp"
    #The "name" column will only save strings in it. It is called Varchar there and can store a maximum number of 255 letters.
    #The "level" column will only store Integers like 1, 2, 183 in it.
    #The "xp" column will only store Floats like 1.3, 5.24, 1832.34 in it.
    #After all was done it will close the open connection to the Database
    #This Function gets usally called in the on enable Method
    public static function initDB(){
        $db = self::getDB();
        $db->query("CREATE TABLE IF NOT EXISTS playerinfo (name VARCHAR(255), level INT, xp FLOAT)");
        $db->close();
    }
    
    
    
    
    
    #This Function returns if a Player is registered in the Database.
    #It returns his name when he is regsitered.
    #It returns null if he is not registered.
    #This will be used to prevent saving more then 1 row of Data from the Player in the Database.
    public static function checkIfPlayerIsRegisteredInTheDatabase(string $name){
        $db = self::getDB();
        $ret = $db->query("SELECT name FROM playerinfo WHERE name = '$name'");
        $db->close();
        $info = $ret->fetch_array();
        
        return $info;
    }
    
    
    
    
    
    #This Function will register a Player in your Database if the Player is not registered yet.
    #It will create a new Row with the Players name in the Column "name", his starting level(here set to 1) and his starting xp amount (here set to 0)
    #This is urgent before you want to get any Data out of the Database.
    #If the player is not registered in the Database you will always get a null
    #if your trying to get data from the Database from the specific Player.
    public static function registerPlayerInDB(string $name){
        if (self::checkIfPlayerIsRegisteredInTheDatabase($name) === null) {
            $db=self::getDB();
            $db->query("INSERT INTO playerinfo VALUES ('$name', 1, 0)");
            $db->close();
        }
    }
    
    
    
    
    
    #This Function gets the Level of the Player.
    #It is saving the result from the SQL as an Array.
    #The Players Level is stored in the first array key(0).
    public static function getPlayerLevel(string $name){
        $db = self::getDB();
        $ret = $db->query("SELECT level FROM playerinfo WHERE name = '$name'");
        $db->close();
        $playerLevel = $ret->fetch_array()[0];
        
        return $playerLevel;
    }
    
    
    
    
    
    #This function sets a Players level in the Database.
    #This can be used for Levelups you will see in the next Function, or to set a Players Level if you are a Admin on a Server and the Player Level was bugged, or they abused a bug to get more Levels.
    public static function setPlayerLevel(string $name, int $level){
        $db = self::getDB();
        $db->query("UPDATE playerinfo SET level = $level WHERE name = '$name'");
        $db->close();
    }
    
    
    
    
    
    #This Function adds a Level to a Player.
    #It is getting the Players current level and add it with 1.
    #Then its calling the Function above this to set the Players Level in the Database.
    public static function addPlayerLevel(string $name){
        $currentLevel = self::getPlayerLevel($name);
        $newLevel = $currentLevel + 1;
        self::setPlayerLevel($name, $newLevel);
    }
}