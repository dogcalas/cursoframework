<?php

/**
 * ZendExt_XMPP
 * 
 * @author: Juan Jose Rosales
 * @copyright DESPROD-ERP Cuba
 * @package: ZendExt
 * @version 1.0-0
 */
require_once dirname(__FILE__) . "/Xmpp/XMPP.php";

class ZendExt_Xmpp {

    /**
     * Instancia para la implementación del patrón Sigleton
     *
     * @var String
     */
    private $domain;
    private $host;
    private $port;
    private $user;
    private $password;
    private $server;

    /*
     * Unique instance
     */
    private $xmpp = null;

    /**
     * Constructor
     */
    public function __construct($host, $port, $user, $password, $server, $domain) {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->server = $server;
        $this->domain = $domain;
    }
    
    private function initMessenger(){
        $this->xmpp = new XMPPHP_XMPP($this->host, $this->port, $this->user, $this->password, '/http-bind', $this->server/* , true, 3 */);
    }

    public function sendNotification($user, $notification) {
        try {
            
            if($this->xmpp === NULL){
                $this->initMessenger();
            }
            
            $this->xmpp->useEncryption(false);
            
            if($this->xmpp->isDisconnected()){
                $this->xmpp->connect();
            }

            while (!$this->xmpp->isDisconnected()) {
                $events = $this->xmpp->processUntil(array('presence', 'session_start'));

                foreach ($events as $event) {
                    $partEvent0 = $event[0];
                    $partEvent1 = $event[1];
                    switch ($partEvent0) {
                        case 'presence': {
                                $this->notifyThis($partEvent1, $user, $notification);
                        }break;
                        case 'session_start': {
                                $this->xmpp->presence($status = 'online');
                        }break;
                    }
                }
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }
    
    private function notifyThis($presence, $user, $msg) {
        $_from = $presence['from'];
        
        /*
         * Hasta ahora no estoy usando el status
         * de la persona a quien voy a enviar la notificacion
         */
        $status = $presence['status'];
        $from = substr($_from, 0, strpos($_from, '@'));
        if($from === $user){
            $this->notify($user, $msg);
        }        
    }
    private function notify($user, $msg) {        
        $this->xmpp->message($user.'@'.$this->server, $msg);
        $this->xmpp->disconnect();
    }

}

?>
