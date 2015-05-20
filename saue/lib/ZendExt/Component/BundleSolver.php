<?php

class ZendExt_Component_BundleSolver {

    private $Lista_Bundle_Resueltosl;
    private $Lista_Bundle_NResueltosl;

    function __construct() {
        
    }

    public function getLista_Bundle_Resueltosl() {
        return $this->Lista_Bundle_Resueltosl;
    }

    public function getLista_Bundle_NResueltosl() {
        return $this->Lista_Bundle_NResueltosl;
    }

    public function getAllBundles() {
        if (isset($this->Lista_Bundle_Resueltosl) && isset($this->Lista_Bundle_NResueltosl))
            return array_merge($this->Lista_Bundle_Resueltosl, $this->Lista_Bundle_NResueltosl);
        else
            return isset($this->Lista_Bundle_Resueltosl) ? $this->Lista_Bundle_Resueltosl : $this->Lista_Bundle_NResueltosl;
    }

    function search_Dependencies($ListaBundle) {

        foreach ($ListaBundle as $Bundle) {

            if (count($Bundle->getListaDependencias()) == 0) {
                $Bundle->setState("solved");
                $this->Lista_Bundle_Resueltosl[] = $Bundle;
            } else {
                $Bundle->setState("unresolved");
                $this->Lista_Bundle_NResueltosl[] = $Bundle;
            }
        }

        if (count($this->Lista_Bundle_Resueltosl) != 0 && count($this->Lista_Bundle_NResueltosl) != 0) {

            $this->solve();
        }
    }

    function solve() {

        for ($i = 0; $i < count($this->Lista_Bundle_Resueltosl); $i++) {
            foreach ($this->Lista_Bundle_Resueltosl[$i]->getListaService() as $Servicio) {
                $this->change_Dependency($Servicio, $this->Lista_Bundle_Resueltosl[$i]);
            }
            $this->Set_Bundle_NResolved();

            foreach ($this->Lista_Bundle_Resueltosl[$i]->getListaSourceEvent() as $EventSource) {
                $this->Add_Observer($EventSource);
            }
        }
    }

    function Add_Observer($EventSource) {
        $nameSource = $EventSource->getId();
        settype($nameSource, "String");
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        foreach ($this->Lista_Bundle_Resueltosl as $Bundle_Resolved) {
            foreach ($Bundle_Resolved->getListaObserver() as $Observer) {
                if ($Observer->getSource()) {
                    $nameObserver = $Observer->getSource();
                    settype($nameObserver, "String");
                    if ($nameSource == $nameObserver) {
                        $Observer->setAdded(true);
                        $bundleFile = $dirapps . $Bundle_Resolved->getPath() . DIRECTORY_SEPARATOR . $Observer->getImpls();
                        $Observer->setImpls($bundleFile);
                        $EventSource->AddObserver($Observer);
                    }
                }
            }
        }
    }

    function change_Dependency($Servicio, $Bundle_resolved) {

        foreach ($this->Lista_Bundle_NResueltosl as $Bundle_NResolved) {
            foreach ($Bundle_NResolved->getListaDependencias() as $dependency) {
                //comparación de clase interface
                if ($this->interfaceAreEquivalents($Bundle_resolved->getPath() . DIRECTORY_SEPARATOR . $Servicio->getInterface(), $Bundle_NResolved->getPath() . DIRECTORY_SEPARATOR . $dependency->getInterface())) {
                    if ($dependency->getUseComponent()) {
                        if ($this->checkIfUse($dependency, $Bundle_resolved)) {
                            $dependency->setImpl($Servicio->getImpl());
                            $dependency->setBundleResolver($Bundle_resolved);
                        }
                    } else {
                        $dependency->setImpl($Servicio->getImpl());
                        $dependency->setBundleResolver($Bundle_resolved);
                    }
                }
            }
        }
    }

    function interfaceAreEquivalents($pathInterface1, $pathInterface2) {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);

        require_once $dirapps . DIRECTORY_SEPARATOR . $pathInterface1;
        $file = new Zend_Reflection_File($dirapps . DIRECTORY_SEPARATOR . $pathInterface1);
        $interface1 = $file->getClasses();

        require_once $dirapps . DIRECTORY_SEPARATOR . $pathInterface2;
        $file1 = new Zend_Reflection_File($dirapps . DIRECTORY_SEPARATOR . $pathInterface2);
        $interface2 = $file1->getClasses();

        $methods1 = $interface1[0]->getMethods();
        $methods2 = $interface2[0]->getMethods();
        $contadorMIterface2 = count($methods2);
        $contadorMIterface1 = count($methods1);

        if ($contadorMIterface2 <= $contadorMIterface1)
            for ($i = 0; $i < $contadorMIterface2; $i++) {
                for ($j = 0; $j < $contadorMIterface1; $j++) {
                    if ($methods2[$i]->getname() == $methods1[$j]->getname()) {
                        try {
                            $DocBlock1 = $methods2[$i]->getDocblock();
                            $retur1 = $DocBlock1->getTag("return");
                            $DocBlock2 = $methods1[$j]->getDocblock();
                            $retur2 = $DocBlock2->getTag("return");

                            if ($retur1 == $retur2) {
                                break;
                            } else {
                                return false;
                            }
                        } catch (Exception $exc) {
                            throw new ZendExt_Exception('ECI001', $exc);
                        }
                    }
                }
                if ($j == $contadorMIterface1)
                    return false;

                else {
                    $parameter2 = $methods2[$i]->getParameters();
                    $parameter1 = $methods1[$j]->getParameters();
                    if (count($parameter1) == count($parameter2))
                        for ($g = 0; $g < count($parameter1); $g++) {
                            if ($parameter1[$g]->getClass()->name != $parameter2[$g]->getClass()->name) {
                                return FALSE;
                            }
                        }
                }
            } else
            return false;
        return true;
    }

    function set_Bundle_NResolved() {
        $bool = "true";
        foreach ($this->Lista_Bundle_NResueltosl as $Bundle_NResolved) {
            foreach ($Bundle_NResolved->getListaDependencias() as $dependency) {
                if ($dependency->getImpl() == null && !$dependency->isOptional())
                    $bool = "false";
            }

            if ($bool == "true") {
                $Bundle_NResolved->setState("solved");
                $this->delete($Bundle_NResolved);
            }
            $bool = "true";
        }
    }

    function delete($Bundle_AResolved) {
        $key = array_search($Bundle_AResolved, $this->Lista_Bundle_NResueltosl, true);
        $this->Lista_Bundle_Resueltosl[] = $Bundle_AResolved;
        unset($this->Lista_Bundle_NResueltosl[$key]);
        $this->Lista_Bundle_NResueltosl = array_values($this->Lista_Bundle_NResueltosl);
    }

    private function compareVersions($version1, $version2) {
        $arrVersion1 = split("\.", $version1);
        $arrVersion2 = split("\.", $version2);
        if ($arrVersion1[0] == $arrVersion2[0]) {
            if (isset($arrVersion1[1]) && isset($arrVersion2[1])) {
                if ($arrVersion1[1] == $arrVersion2[1]) {
                    if (isset($arrVersion1[2]) && isset($arrVersion2[2])) {
                        if ($arrVersion1[2] == $arrVersion2[2]) {
                            return true;
                        }
                        return false;
                    }
                    return true;
                }
                return false;
            }
            return true;
        }
        return false;
    }

    private function checkIfUse($dependency, $Bundle_resolved) {
        if ($dependency->getUseComponent() == $Bundle_resolved->getName()) {
            if ($dependency->getUseVersion()) {
                if ($this->compareVersions($dependency->getUseVersion(), $Bundle_resolved->getVersion()))
                    return true;
                return false;
            }
            return true;
        }
        return false;
    }

}

?>
