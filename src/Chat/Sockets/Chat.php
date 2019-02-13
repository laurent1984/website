<?php

namespace App\Chat\Sockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    public $dialogue = array();
    protected $compteur = 0;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        dump($this->dialogue);
        echo "New connection! ({$conn->resourceId})\n";
        

        if (count($this->dialogue) > 0 ) {
            for ($i=0;$i<count($this->dialogue);$i++){
                $conn->send($this->dialogue[$i]);
            }
        }

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected

                $client->send($msg);
            }
        }
          if (count($this->dialogue) < 11 ) {
            array_push($this->dialogue, $msg);
          } else {
            array_shift($this->dialogue);
            array_push($this->dialogue, $msg);
          }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}