<?php

/**
 * ZendExt_IoC
 * Integrador de servicios de negocio entre modulos
 * 
 * @author Manuel, Yoandry Morejon Borbon
 * @package ZendExt
 * @copyright UCID-ERP Cuba
 * @version 1.0-0
 */
class ZendExt_IoC implements ZendExt_Aspect_ISinglenton {
    /**
     * Constante para activar la simulacion del resultado a devolver
     * por el servicio solicitado en caso de error segun su contrato
     */

    const IOC_SIMULATE_RESULT = false;

    /**
     * Nombre del modulo que brinda el servicio
     * 
     * @var string
     */
    private $module;
    protected $idXMLIoC;
    protected $iocTrace = false;
    protected $iocExceptionTrace = false;

    /**
     * Constructor de la clase, es privado para impedir que pueda 
     * ser instanciado y de esta forma garantizar que la instancia 
     * sea un singlenton
     * 
     * @return void
     */
    private function __construct() {
        $this->idXMLIoC = 'ioc';
        $aspectxml = ZendExt_FastResponse::getXML('aspect');
        $this->iocTrace = (string) $aspectxml->beginTraceIoC['active'];
        $this->iocExceptionTrace = (string) $aspectxml->failedTraceIoC['active'];
    }

    /**
     * Obtencion de la instancia de la clase, ya que esta no puede ser 
     * instanciada directamente debido a que es un singlenton
     * 
     * @return ZendExt_IoC | null - Instancia de la clase
     */
    static public function getInstance() {
        static $instance;
        if (!isset($instance))
            $instance = new self();
        return $instance;
    }

    /**
     * Funcion magica para obtener el modulo que brinda el servicio solicitado
     * 
     * @param string $name - Nombre de la propiedad solicitada
     * @return ZendExt_IoC | null - Instancia de la clase
     */
    public function __get($name) {
        $this->module = $name;
        return $this;
    }

    /**
     * Funcion magica para obtener el servicio solicitado, obtener los
     * parametros y executarlo segun la descripcion del mismo en el xml
     * de integracion (ioc.xml)
     * 
     * @param string $service - Nombre del servicio solicitado
     * @param array $params - Parametros que necesita el servicio
     * @return unknow - Desconocido
     */
    public function __call($service, $params) {
        if ($this->module) {
            $flag = false;
            $register = Zend_Registry::getInstance();
            $dirConfig = $register->config->module->{$this->module};
            if (isset($dirConfig)) {
                $xml = simplexml_load_file($dirConfig . $this->xmlPath());
                $xmlservice = $xml->$service;
            }
            if ($xmlservice) {
                $flag = true;
                $count = 0;
                $cad_params = '';
                foreach ($xmlservice->prototipo->parametros->parametro as $xmlparam) {
                    if ($this->Validate($xmlparam, $params[$count]) === false && !$xmlparam['default'])
                        $this->Exception(null, 'IOC002');
                    if ($count)
                        $cad_params .= ',';

                    if (!$params[$count] && $xmlparam['default'])
                        $params[$count] = (string) $xmlparam['default'];
                    $cad_params .= "\$params[{$count}]";
                    $count++;
                }
            } else {
                $xml = $this->getXML($this->idXMLIoC);
                $xmlservice = $xml->{$this->module}->$service;
                if (!$xmlservice)
                    $this->Exception(null, 'IOC001');
                $count = 0;
                $cad_params = '';
                foreach ($xmlservice->prototipo->parametro as $xmlparam) {
                    if ($this->Validate($xmlparam, $params[$count]) === false && !$xmlparam['default'])
                        $this->Exception(null, 'IOC002');
                    if ($count)
                        $cad_params .= ',';

                    if (!$params[$count] && $xmlparam['default'])
                        $params[$count] = (string) $xmlparam['default'];
                    $cad_params .= "\$params[{$count}]";
                    $count++;
                }
            }
            $xmlresult = $xmlservice->prototipo->resultado;
            $class = utf8_encode($xmlservice->inyector['clase']);
            $method = utf8_encode($xmlservice->inyector['metodo']);
            if ($this->iocTrace == 'true') {
                $trace = ZendExt_Aspect_Trace::getInstance();
                $trace->beginTraceIoC($this->module, $class, $method, $this->idXMLIoC);
            }

            if ($flag) {
                $referenceComp = substr($dirConfig, 0, count($dirConfig) - 2);
                $this->setIncludePathIn($referenceComp);
            } else {
                $referenceComp = $this->getRefenceComp($xml, $service);
                $this->setIncludePath($referenceComp);
            }
            if (@class_exists($class)) {
                $moduletmp = $this->module;
                $this->module = null;
                $objService = new $class();
                if (method_exists($objService, 'initConnection'))
                    $objService->initConnection($moduletmp);
                elseif (method_exists($objService, 'setModule'))
                    $objService->setModule($moduletmp);
                if (method_exists($objService, $method)) {
                    $cad_execution = "\$result = \$objService->{$method}({$cad_params});";
                    try {
                        eval($cad_execution);
                    } catch (Exception $ee) {
                        if ($this->iocExceptionTrace == 'true') {
                            if ($ee instanceof ZendExt_Exception)
                                $e = $ee;
                            else //Si es una excepcion no controlada
                                $e = new ZendExt_Exception('NOCONTROLLED', $ee);
                            $this->traceIoCException($e, $class, $method, $moduletmp);
                        }
                        if (!self::IOC_SIMULATE_RESULT)
                            throw $ee;
                        elseif ($xmlresult) {
                            return $this->simulateResult($xmlresult);
                        }
                    }
                    if ($xmlresult) {
                        if (!$this->Validate($xmlresult, $result)) {
                            if (!self::IOC_SIMULATE_RESULT)
                                $this->Exception(null, 'IOC005');
                            else
                                return $this->simulateResult($xmlresult);
                        }
                        return $result;
                    }
                }
                elseif (!self::IOC_SIMULATE_RESULT)
                    $this->Exception(null, 'IOC004');
                elseif ($xmlresult) {
                    return $this->simulateResult($xmlresult);
                }
            } elseif (!self::IOC_SIMULATE_RESULT)
                $this->Exception(null, 'IOC003');
            elseif ($xmlresult)
                return $this->simulateResult($xmlresult);
        }
    }

    /**
     * Obtener un xml segun el identificador
     * 
     * @param string $xmlname - Nombre o identificador del xml
     * @return SimpleXMLElement - Objeto de tipo xml
     */
    public function getXML($xmlname) {
        static $xml;
        if (!isset($xml))
            $xml = array();
        if (!isset($xml[$xmlname]))
            $xml[$xmlname] = ZendExt_FastResponse::getXML($xmlname);
        return $xml[$xmlname];
    }

    /**
     * Validar un parametro o el resulado del servicio solicitado
     * segun la definicion del mismo en el xml de integracion
     * 
     * @param SimpleXMLElement $xmlparam - Objeto xml que contiene la definicion del parametro
     * @param unknow $param - Parametro pasado al servicio
     */
    private function Validate($xmlparam, $param) {
        $typeClass = utf8_encode($xmlparam['clase']);
        if ($typeClass && !($param instanceof $typeClass))
            return false;
        elseif ($xmlparam['tipo'] == 'array' && is_array($param))
            return true;
        elseif ($xmlparam['tipo']) {
            static $xmlexpressions;
            if (!isset($xmlexpressions))
                $xmlexpressions = $this->getXML('expresiones');
            $typeField = utf8_encode($xmlparam['tipo']);
            $typeRegexp = utf8_encode($xmlexpressions->$typeField);
            if ($typeRegexp && !preg_match('/'.$typeRegexp.'/', utf8_encode($param)))
                return false;
        }
        return true;
    }

    /**
     * Dispara una excepcion controlada por el sistema segun el codigo, 
     * si en el primer codigo se pasa null se dispara la excepcion con
     * el segundo
     * 
     * @throws ZendExt_Exception - excepciones controladas por el sistema 
     */
    protected function Exception($error_code, $aux_code = null, $class = null, $method = null) {
        if ($error_code)
            $e = new ZendExt_Exception($error_code);
        elseif ($aux_code)
            $e = new ZendExt_Exception($aux_code);
        $this->traceIoCException($e, $class, $method, $this->module);
        throw $e;
    }

    public function setIncludePath($srcmodule) {
        static $arrSrcModule;
        if (!isset($arrSrcModule))
            $arrSrcModule = array();
        $register = Zend_Registry::getInstance();
        $srcservice = $register->get('config')->dir_aplication . '/' . $srcmodule;
        if (!isset($arrSrcModule[$srcservice])) {
            $arrSrcModule[$srcservice] = true;
            $include_path = get_include_path() . PATH_SEPARATOR . $srcservice;
            $include_path .= PATH_SEPARATOR . $srcservice . '/models';
            $include_path .= PATH_SEPARATOR . $srcservice . '/models/bussines';
            $include_path .= PATH_SEPARATOR . $srcservice . '/models/domain';
            $include_path .= PATH_SEPARATOR . $srcservice . '/models/domain/generated';
            $include_path .= PATH_SEPARATOR . $srcservice . '/validators';
            $include_path .= PATH_SEPARATOR . $srcservice . '/controllers';
            $include_path .= PATH_SEPARATOR . $srcservice . '/services';
            set_include_path($include_path);
        }
    }

    public function setIncludePathIn($srcmodule) {
        $srcservice = $srcmodule;
        $include_path = get_include_path() . PATH_SEPARATOR . $srcservice;
        $include_path .= PATH_SEPARATOR . $srcservice . '/models';
        $include_path .= PATH_SEPARATOR . $srcservice . '/models/bussines';
        $include_path .= PATH_SEPARATOR . $srcservice . '/models/domain';
        $include_path .= PATH_SEPARATOR . $srcservice . '/models/domain/generated';
        $include_path .= PATH_SEPARATOR . $srcservice . '/validators';
        $include_path .= PATH_SEPARATOR . $srcservice . '/controllers';
        $include_path .= PATH_SEPARATOR . $srcservice . '/services';
        set_include_path($include_path);
    }

    private function simulateResult($xmlResult) {
        $class = (string) $xmlResult['clase'];
        $type = (string) $xmlResult['tipo'];
        if ($class) {
            $class = ucfirst($class);
            $class = "ZendExt_IoC_{$class}Class";
            if (@class_exists($class))
                $return = new $class();
            elseif (@class_exists('ZendExt_IoC_NullClass'))
                $return = new ZendExt_IoC_NullClass();
        }
        elseif ($type) {
            $type = ucfirst($type);
            $type = "ZendExt_IoC_{$type}Type";
            if (@class_exists($type))
                $return = new $type();
            elseif (@class_exists('ZendExt_IoC_VoidType'))
                $return = new ZendExt_IoC_NullClass();
        }
        if (is_object($return)) {
            if (method_exists($return, 'getDefault'))
                return $return->getDefault();
            else
                return $return;
        }
        return;
    }

    private function pathReal($path = "") {
        $dirweb = $_SERVER[DOCUMENT_ROOT];
        $dirapps = str_replace('web', 'apps', $dirweb);
        $final = ($path == "") ? DIRECTORY_SEPARATOR : "";
        return $dirapps . $path . $final;
    }

    private function xmlPath() {
        return "comun" . DIRECTORY_SEPARATOR . "recursos" . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "ioc.xml";
    }

    private function getRefenceComp($xml, $service) {
        $srcModule = (string) $xml->{$this->module}['src'];
        $xmlservice = $xml->{$this->module}->$service;
        $referenceService = (string) $xmlservice['reference'];
        $separator = '';
        if ($referenceService)
            $separator = '/';
        return $srcModule . $separator . $referenceService;
    }

    private function traceIoCException($e, $class, $method, $module) {
        if ($this->iocExceptionTrace == 'true') {
            $trace = ZendExt_Aspect_Trace::getInstance();
            $trace->failedTraceIoc($e, $module, $class, $method, $this->idXMLIoC);
        }
    }

}
