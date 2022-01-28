<?php

namespace Panaxin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    protected function onEnable(): void{
        #Registers our PlayerJoinEvent at the bottom of this class
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        #Runs our initDB function in our Database class
        Database::initDB();
    }

    #Function to tell the server what to do if a player runs a command
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{

        #Checking if the command sender is a player
        if ($sender instanceof Player){

            #Setting the players name into a variable to access his name more easily
            $name = $sender->getName();
            switch ($command->getName()){
                case "getlevel":

                    #Runs our getPlayerLevel function in our Database class
                    $level = Database::getPlayerLevel($name);

                    #Since the getPlayerLevel function returns us an integer we will convert into a string with the strval() function
                    #to implement it into a message for the player
                    $level = strval($level);
                    $sender->sendMessage("Your current Level is $level");
                    break;

                case "setlevel":

                    #Getting the first argument after the command and store it in a variable and cast it to an integer
                    $level = (int)$args[0];

                    #Runs our setPlayerLevel function in our Database class.
                    Database::setPlayerLevel($name, $level);

                    #Since the $level variable was cast to an integer we convert it back to a string
                    #to implement it into a message for the player
                    $level = strval($level);
                    $sender->sendMessage("You have set your Level to $level");
                    break;

                case "levelup":

                    #Runs our addPlayerLevel function in our Database class
                    Database::addPlayerLevel($name);

                    #Sends the player a little message that he got a levelup
                    $sender->sendMessage("You have been leveled up");
                    break;
            }
        }
        return false;
    }


    #This is our event when a player is joining our server
    public function onJoin(PlayerJoinEvent $event){

        #Storing the players name into a variable
        $name = $event->getPlayer()->getName();

        #Runs our registerPlayerInDB function in our Database class
        Database::registerPlayerInDB($name);
    }
}
