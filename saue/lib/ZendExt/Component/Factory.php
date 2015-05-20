<?php

class ZendExt_Component_Factory {

    static private $listaBundles;
    static private $requesterBP;

    /**
     * Retorna una instancia de la clase
     * @return ZendExt_Component_Factory 
     */
    static public function getInstance() {
        static $instance;
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }

    /**
     * Constructor de la clase, es privado para impedir que pueda 
     * ser instanciado y de esta forma garantizar que la instancia 
     * sea un singlenton
     * 
     * @return void
     */
    private function __construct() {
        //obtener el listado de bundles.
        if (self::$listaBundles == null) {
            $component_manager = ZendExt_Component_Manager::getInstance();
            self::$listaBundles = $component_manager->getBundlesList();
            //registrar los bundles e instanciarlos
            if (self::$listaBundles == null) {
                $component_manager->searchBundles();
                $component_manager->solveBundles();
                self::$listaBundles = $component_manager->getBundlesList();
            }
        }
    }

    /**
     * Obtiene el componente que resuelve el servicio especificado
     * 
     * @param Objeto $requester Objeto que solicita el servicio
     * @param String $servicio Nombre del servicio solicitado
     * @return \ZendExt_Component_BundleProxy 
     * @todo Cambiar las excepciones
     */
    public static function getComponent($requester, $servicio, $new = false) {
        //obtener la dirección del solicitante
        $reflection = new ReflectionClass($requester);
        $direccion = $reflection->getFileName();
        //obtener bundle solicitante
        $claimantBundle = self::searchClaimantBundle($direccion);

        if ($claimantBundle->getState() == "unresolved")
            throw new ZendExt_Exception('IOC001');
 
 print_r($claimantBundle->getListaDependencias()); die("aa");
        foreach ($claimantBundle->getListaDependencias() as $dependencia) {
            if ($dependencia->getId() == $servicio) {
                $bundleClass = $dependencia->getImpl();
                $resolverBundle = $dependencia->getBundleResolver();
            }
        }

        if ($bundleClass == null || $resolverBundle == null)
            throw new ZendExt_Exception('IOC001');
        $bundleProxy = new ZendExt_Component_BundleProxy($claimantBundle, $resolverBundle, $bundleClass);
        if (!$new) {
            if (isset(self::$requesterBP[$resolverBundle->getId() . $servicio]))
                return self::$requesterBP[$resolverBundle->getId() . $servicio];
            self::$requesterBP[$resolverBundle->getId() . $servicio] = $bundleProxy;
        }
        return $bundleProxy;
    }

    /**
     * A partir de la dirección, buscar el bundle que solicita la dependencia
     * 
     * @return ZendExt_Component_Bundle
     */
    public static function searchClaimantBundle($direccion) {
        foreach (self::$listaBundles as $bundle) {
            $dirweb = $_SERVER[DOCUMENT_ROOT];
            $dirapps = str_replace('web', 'apps', $dirweb);
            $bundlepath = (string) str_replace('/', DIRECTORY_SEPARATOR, $bundle->getPath());
            $cadena = (string) str_replace('/', DIRECTORY_SEPARATOR, $dirapps) . $bundlepath;
            if (strstr((string) $direccion, $cadena))
                return $bundle;
        }
    }

}

?>
