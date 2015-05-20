<?php

/**
 * FactoryProxy actua como proxy para poder obtener la instancia de ZendExt_Component_Factory que es un singleton.
 * De esta manera se pueden cambiar los atributos de ZendExt_Component_Factory sin crear conflictos en los controladores.
 * En esta clase se declaran los métodos mágicos _call y _get para utilizar ZendExt_Component_Factory de manera similar a IOC.
 *
 * @author Abraham Calas
 * @package ZendExt
 * @version 1.0-0
 */
class ZendExt_Component_FactoryProxy {

    private $newInstance;
    private $service;
    private $who;

    /**
     * Función para especificar que objeto solicita el servicio
     * 
     * @param Mixed $who - Objeto que pide el componente
     * @return void 
     */
    public function setClaimmant($who) {
        $this->who = $who;
    }

    /**
     * Función para especificar si se desean nuevas instancias de los objetos bundleproxy
     * 
     * @param bool $newInstance - Permite setear si se desean obtener nuevas instancias de los objetos ZendExt_Component_BundleProxy.
     * @return void 
     */
    public function setNewInstance($newInstance) {
        $this->newInstance = $newInstance;
    }

    /**
     * Constructor de la clase, se le pasa 
     * 
     * @param Mixed $who - Objeto solicita el servicio
     * @param bool $newInstance - Se le pasa true si al pedir el componente se quiere obtener un nuevo componente(nuevo objeto de tipo ZendExt_Component_BundleProxy). Por defecto es false, lo que devolverá el mismo objeto de tipo ZendExt_Component_BundleProxy.
     * 
     * @return void
     */
    public function __construct($who, $newInstance = false) {
        $this->who = $who;
        $this->newInstance = $newInstance;
    }

    /**
     * Funcion magica para capturar el disparo de eventos
     * @param string $service - Nombre del servicio solicitado
     * @param array $params - Parametros que necesita el servicio
     * @return Midex 
     */
    public function __call($service, $params) {
        if ($service == "dispatch") {
            $factory = ZendExt_Component_Factory::getInstance();
            $reflection = new ReflectionClass($this->who);
            $direccion = $reflection->getFileName();
            $claimantBundle = $factory::searchClaimantBundle($direccion);
            foreach ($claimantBundle->getListaSourceEvent() as $source) {
                if ($source->getId() == $params[0]) {
                    unset($params[0]);
                    $source->NotifyChange($params);
                    break;
                }
            }
        }
    }

    /**
     * Funcion magica para obtener el servicio solicitado
     * 
     * @param string $servicio - Nombre del servicio solicitado.
     * @return Mixed - El objeto pedido.
     */
    public function __get($servicio) {
        $this->service = $servicio;
        $factory = ZendExt_Component_Factory::getInstance();
        return $factory->getComponent($this->who, $servicio, $this->newInstance);
    }

}

?>
