<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BundleProxy
 *
 * @author dalfonso
 */
class ZendExt_Component_BundleProxy {

    protected $bundle;
    protected $bundleClass;
    protected $claimantBundle;
    protected $resolverBundle;

    function __construct(ZendExt_Component_Bundle $claimantBundle, ZendExt_Component_Bundle $resolverBundle, $bundleFile) {
        $this->claimantBundle = $claimantBundle;
        $this->resolverBundle = $resolverBundle;
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $bundle_includePath = $this->searchIncludePath($dirapps . $this->resolverBundle->getPath());
        set_include_path($bundle_includePath);
        $bundleFile = $dirapps . $resolverBundle->getPath() . DIRECTORY_SEPARATOR . $bundleFile;
        require_once $bundleFile;
        $file = new Zend_Reflection_File($bundleFile);
        $bundleClass = $file->getClass()->getName();
        $this->bundleClass = $bundleClass;
        $this->bundle = new $bundleClass;
    }

    /**
     * Incluir en el include path todos los ficheros que se encuentran recursivamente dentro de la dirección del $resolverBundle
     */
    private function searchIncludePath($bundleSolverPath) {
        $bundleSeeker = new ZendExt_Component_BundleSeeker();
        $include_path = get_include_path() . PATH_SEPARATOR;
        $bundleSeeker->searchDir($bundleSolverPath);
        $include_paths = $bundleSeeker->getIncludePath();
        $include_paths.=PATH_SEPARATOR . $bundleSolverPath;
        return $include_path . $include_paths;
    }

    /**
     * Funcion magica para obtener el servicio solicitado, obtener los
     * parametros y executarlo segun la descripcion del mismo
     * 
     * @param string $service - Nombre del servicio solicitado
     * @param array $params - Parametros que necesita el servicio
     * @return Midex 
     */
    public function __call($service, $params) {
        if (!method_exists($this->bundle, $service)) {
            throw new ZendExt_Exception('IOC004');
        }

        //si es un bundle con comportamiento transaccional
        if ($this->bundle instanceof ZendExt_Component_TransactionalService) {
            $this->bundle->startTransaction();
            $result = call_user_func_array(array($this->bundle, $service), $params);
            $this->bundle->endTransaction();
        } else {
            $result = call_user_func_array(array($this->bundle, $service), $params);
        }
        //registrar traza de integración
        $trace = ZendExt_Aspect_Trace::getInstance();
        $trace->beginTraceBundle($this->claimantBundle->getName(), $this->resolverBundle->getName(), $this->bundleClass, $service);
        return $result;
    }

}

?>
