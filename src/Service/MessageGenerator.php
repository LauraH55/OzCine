<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

/**
 * Classe (service) qui retourne un message au hasard
 */
class MessageGenerator
{
    
    /**
     * Autre service dont dépend notre Service MessageGenerator
     * c'est le contenur de service qui va se charger de l'instancier pour nous
     */
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getHappyMessage()
    {
        $messages = [
            'Tu l\'as fait ! Tu as mis à jour le système ! Impressionnant !',
            'C\'est la mise à jour la plus cool que j\'ai vue aujourd\'hui',
            'Bon travail, continue comme ça !',
        ];

        $randomMessage = $messages[array_rand($messages)];

        $this->logger->info('Message généré', ['message' => $randomMessage]);

        return $randomMessage;
    }
}