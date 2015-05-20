<?php

/**
 *
 */
class ZendExt_MessageBus { //implements ZendExt_Aspect_ISinglenton

    private static $instance;
    private $observers;
    private $xmpp = null;
    private $xmppHost;
    private $xmppout;
    private $enviaout;

    private function __construct() {
        $this->observers = ZendExt_MessageBus_Catalog::getInstance();

        $this->xmppHost = $_SERVER['SERVER_ADDR'];
        $this->hostName = gethostname();

        $this->xmpp = new ZendExt_Xmpp($this->xmppHost, 5222, 'notification', 'notification', $this->hostName, $this->hostName);

        /* $this->xmpp = ZendExt_Xmpp::getInstance();
          $this->xmpp->connect();
          $this->xmpp->processUntil('session_start');
          $enviaout = false;
          $xml1 = ZendExt_FastResponse::getXML('xmpp');
          $register = Zend_Registry::getInstance();
          if ((string) $xml1->conn_out['server'] != "nodefinido.sub") {
          $this->xmppout = ZendExt_Xmppout::getInstance();
          $this->xmppout->connect();
          $this->xmppout->processUntil('session_start');
          $enviaout = true;
          } */
    }

    static public function getInstance() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new self();
            } catch (Exception $ee) {
                $ee->getTraceAsString();
            }
        }

        return self::$instance;
    }

    private function getXmpp() {
        if ($this->xmpp !== null) {
            return $this->xmpp;
        }
        return null;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param array $data
     */
    public function FireEvent($subject, $event, $data) {
        if ($event === 'workflowEvent') {
            /*
             * tambien es importante determinar a quien se le debe 
             * enviar la notificacion, pero solo en la notificacion de
             * workflow, porque en la logica del componente de notificaciones
             * solo se le envia la notificacion a los componentes suscritos
             * a los eventos.
             */
            $this->getXmpp()->sendNotification('instalacion', $data);
            /*
             * Si es un evento de workflow*
             * entonces hago esto, de lo contrario la logica de 
             * otro negocio, en este caso del componente de notificaciones
             */
        } /*else {
            if ($this->observers->IsRegisteredEvent($subject, $event)) {
                $objects = $this->observers->GetObserversByEvent($subject, $event);
                $ioc = ZendExt_IoC::getInstance();
                if (isset($data['sistemas'])) {
                    $aux = array();
                    foreach ($objects as $obj) {
                        if (in_array($obj['nombre'], $data['sistemas'])) {
                            $aux[] = $obj;
                        }
                    }
                    $objects = $aux;
                }
                foreach ($objects as $obj) {
                    $servicio = $obj['servicio'];
                    $ioc->__get($obj['nombre']);
                    $ioc->$servicio($event, $data);
                }
            }
        }*/
    }

    /**
     *
     * @param string $user  usuario
     * @param string $msg   mensaje para enviar
     * 
     */
    public function SendMessage($user, $msg, $loc) {
        if ($loc == "in") {
            $this->xmpp->sendMsg($user, $msg);
        } elseif ($loc == "out" && $this->enviaout) {
            $this->xmppout->sendMsg($user, $msg);
        }
    }

}

?>
