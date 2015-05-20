<?php

class ZendExt_Component_Manager {

    private $bundlesList;
    private $bundleSeeker;
    private $bundleSolver;

    private function __construct() {
        
    }

    public static function getInstance() {
        static $instance;
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }

    function searchBundles() {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dir = str_replace('web', 'apps', $dirweb);
        $this->bundleSeeker = new ZendExt_Component_BundleSeeker();
        $this->bundleSeeker->setBundlesList();
        $this->bundleSeeker->seek($dir);
        $this->bundlesList = $this->bundleSeeker->getListaBundles();
        $this->saveBundlesList();
    }

    function solveBundles() {
        $this->bundleSolver = new ZendExt_Component_BundleSolver();
        $this->bundleSolver->search_Dependencies($this->getBundlesList());
        $this->bundlesList = $this->bundleSolver->getAllBundles();

        $this->saveBundlesList();
    }

    public function saveBundlesList() {
        //guardar en fichero de configuración    
        if (count($this->bundlesList) == 0) {

            $bundlesxml = "<bundles></bundles>";
            $bundlesxml = new SimpleXMLElement($bundlesxml);
            $bundlesxml->asXML(Zend_Registry::get('config')->xml->bundles);


            $cache = ZendExt_Cache::getInstance();
            $cache->save(null, 'bundles');

            $bundles_file = str_replace("bundles.xml", "bundles.data", Zend_Registry::get('config')->xml->bundles);
            file_put_contents($bundles_file, serialize($this->bundlesList));
        } else {
            $bundlesxml = new SimpleXMLElement($this->bundlesListToXml());
            $bundlesxml->asXML(Zend_Registry::get('config')->xml->bundles);
            //guardar en caché
            $cache = ZendExt_Cache::getInstance();
            $cache->save($this->bundlesList, 'bundles');
            //guardar en fichero
            $bundles_file = str_replace("bundles.xml", "bundles.data", Zend_Registry::get('config')->xml->bundles);
            file_put_contents($bundles_file, serialize($this->bundlesList));
        }
    }

    /**
     * Obtener la lista de bundles, si no existe se carga desde caché y si no está en caché, desde fichero
     */
    public function getBundlesList() {
        if ($this->bundlesList == null) {
            //cargar bundles desde caché            
            $cache = ZendExt_Cache::getInstance();
            $cachedBundles = $cache->load('bundles');
            $this->bundlesList = $cachedBundles;
            if ($cachedBundles == null) {
                //cargar bundles desde fichero
                $this->bundlesList = $this->loadBundlesFile();
            }
        }
        return $this->bundlesList;
    }

    public function getbundleListE() {
        if ($this->bundlesList == null) {
            return null;
        }
        else
            return $this->bundlesList;
    }

    public function saveXML($xml) {
        $bundlesxml = new SimpleXMLElement($xml);
        $bundlesxml->asXML(Zend_Registry::get('config')->xml->bundles);
    }

    /**
     * Carga la lista de bundles que está serializada en el fichero bundles.data, junto al bundles.xml 
     */
    private function loadBundlesFile() {
        $bundles_file = str_replace("bundles.xml", "bundles.data", Zend_Registry::get('config')->xml->bundles);
        $bundles_file_content = file_get_contents($bundles_file);
        if (is_string($bundles_file_content))
            $bundlesInFile = unserialize($bundles_file_content);
        return $bundlesInFile;
    }

    /**
     * Convierte la lista de bundles a la cadena de bundles para persistir en config
     * @return string 
     */
    public function bundlesListToXml() {
        $this->bundlesList = $this->getBundlesList();
        $xml = "<bundles>";
        foreach ($this->bundlesList as $bundle) {
            $state = $bundle->getState();
            if ($state == null || $state == '')
                $state = 'unresolved';
            $xml = $xml . "
		<bundle id=\"" . $bundle->getId() . "\" state=\"" . $state . "\" name=\"" . $bundle->getName() . "\" version=\"" . $bundle->getVersion() . "\">
				<path>" . $bundle->getPath() . "</path>
		</bundle>";
        }
        return $xml . "\n</bundles>";
    }

    /**
     * Carga un bundle a partir de la dirección donde está definido.
     * @param string $path
     * @return ZendExt_Component_Bundle 
     */
    public function loadBundleFromFile($path) {
        $this->bundleSeeker = new ZendExt_Component_BundleSeeker();
        return $this->bundleSeeker->load_Bundle_XML($path);
    }

    /**
     * Adiciona un bundle a la lista de bundles registrados
     * 
     * @param ZendExt_Component_Bundle $newBundle 
     */
    public function addBundle(ZendExt_Component_Bundle $newBundle) {
        $this->getBundlesList();
        foreach ($this->bundlesList as $bundle) {
            $ids[] = $bundle->getId();
            if ($bundle->getName() == $newBundle->getName() && $bundle->getPath() == $newBundle->getPath())
                return false;
        }
        array_multisort($ids);
        $newBundle->setId(1 + $ids[count($ids) - 1]);
        $this->bundlesList[] = $newBundle;
        $this->saveBundlesList();
        return true;
    }

    /**
     * Elimina un bundle del registro de bundles
     * @param string $bundleId
     */
    public function unregisterBundle($direccion) {
        $direccion = $_SERVER['DOCUMENT_ROOT'] . $direccion;
        $direccion = str_replace("web", "apps", $direccion);
        $xml = simplexml_load_file($direccion);
        $xml->data["state"] = "disable";
        $result = $xml->asXML($direccion);
        if ($result == null)
            return false;
        $this->searchBundles();
        $this->solveBundles();
        return true;
    }

    public function setBundleEnable($path) {
        if (file_exists($path)) {
            $xml = simplexml_load_file($path);
            $xml->data["state"] = "enable";
            $xml->asXML($path);
        }
        else
            return false;
    }

    function getDir($path) {
        $length = strrpos($path, DIRECTORY_SEPARATOR);
        $dir = substr($path, 0, $length);
        $dir = str_replace(str_replace("web", "apps", $_SERVER['DOCUMENT_ROOT']), "", $dir);
        $dir = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $dir);
        return $dir;
    }

    /**
     *
     * @param type $oldId Id del bundle a modificar
     * @param type $newId Nuevo
     * @param type $name
     * @param type $version
     * @param type $path
     * @return boolean 
     */
    public function setBundleComponent($id, $name, $version, $path) {
        if (file_exists($path)) {
            $this->getBundlesList();
            chmod($path, 0777);
            $xml = simplexml_load_file($path);
            $xml->data["version"] = $version;
            $result = $xml->asXML($path);
            if ($result == null)
                return false;
            $pathIsNew = false;
            foreach ($this->bundlesList as &$bundle) {
                if ($bundle->getId() == $id) {
                    $bundle->setName($name);
                    $bundle->setVersion($version);
                    $pathIsNew = ($bundle->getPath() != $this->getDir($path));
                    $bundle->setPath($this->getDir($path));
                    break;
                }
            }
            if ($pathIsNew)
                $this->solveBundles();
            return true;
        } else {
            return false;
        }
    }

}

?>
