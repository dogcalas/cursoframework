<?php
class includemodule
{
    private $con;

    public function init($moduledir)
    {
        if (!is_array($moduledir)) {
            $this->setIncludePath($moduledir);
            $this->Connect();
        } else {
            foreach ($moduledir as $dir)
                $this->setIncludePath($dir);
            $this->Connect();
        }
    }

    private function setIncludePath($srcmodule)
    {
        $register = Zend_Registry::getInstance();
        $srcmodule = $register->get('config')->dir_aplication . '/' . $srcmodule;
        $srcservice = $srcmodule;
        $include_path = get_include_path() . PATH_SEPARATOR . $srcservice;
        $include_path .= PATH_SEPARATOR . $srcmodule . '/models';
        $include_path .= PATH_SEPARATOR . $srcmodule . '/models/bussines';
        $include_path .= PATH_SEPARATOR . $srcmodule . '/models/domain';
        $include_path .= PATH_SEPARATOR . $srcmodule . '/models/domain/generated';
        $include_path .= PATH_SEPARATOR . $srcmodule . '/validators';
        $include_path .= PATH_SEPARATOR . $srcmodule . '/controllers';
        set_include_path($include_path);
    }

    private function SetShema($Shema)
    {
        //Se inicializan los esquemas
        $this->con->exec("set search_path=pg_catalog,$Shema;");
    }

    private function Connect()
    {
        $bdInstance = Zend_Registry::get('config')->bd;
        $connStr = "$bdInstance->gestor://$bdInstance->usuario:$bdInstance->password@$bdInstance->host/$bdInstance->bd";
        $this->con = Doctrine_Manager::connection($connStr);
    }

    public function __get($component)
    {
        $componentobj = new $component;
        $this->SetShema($componentobj->getShema());
        return $componentobj;
    }

    public function End()
    {
        $bdInstance = Zend_Registry::get('config')->bd;
        $this->SetShema($bdInstance->esquema);
    }
}

?>