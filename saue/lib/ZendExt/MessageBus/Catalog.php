<?php
/**
 *
 */
class ZendExt_MessageBus_Catalog {

    public $catalog;
    private static $instance;


    /**
     *
     */
    public function __construct() {
        $this->catalog = array();
        $this->Inicialize();
    }

    /**
     *
     * @param string $subject
     */
    public function RegisterSubject($subject){
        if(!isset($this->catalog[$subject]))
            $this->catalog[$subject] = array();
    }

    /**
     *
     * @param string $subject
     */
    public function UnRegisterSubject($subject){
        if(isset($this->catalog[$subject]))
            unset($this->catalog[$subject]);
    }

    /**
     *
     * @param string $subject
     * @param string $event
     */
    public function RegisterEvent($subject, $event){
        if(isset($this->catalog[$subject]) && !isset($this->catalog[$subject][$event]))
            $this->catalog[$subject][$event]= array();
    }

    /**
     *
     * @param string $subject
     * @param string $event
     */
    public function UnRegisterEvent($subject, $event){
        if(isset($this->catalog[$subject][$event]))
            unset($this->catalog[$subject][$event]);
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function RegisterObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event]) && !$this->IsRegisteredObserver($subject, $event, $observer)){
            $this->catalog[$subject][$event][] = $observer;
        }
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     */
    public function UnRegisterObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event])){
            $key = array_search($observer, $this->catalog[$subject][$event], TRUE);
            if($key){
                unset($this->catalog[$subject][$event][$key]);
                $this->catalog[$subject][$event] = array_values($this->catalog[$subject][$event]);
            }
        }
    }

    /**
     *
     * @param string $subject
     * @return bool
     */
    public function IsRegisteredSubject($subject){
        if(isset($this->catalog[$subject]))
            return true;
        return false;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @return bool
     */
    public function IsRegisteredEvent($subject, $event){
        if(isset($this->catalog[$subject][$event]))
            return true;
        return false;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param object $observer
     * @return bool
     */
    public function IsRegisteredObserver($subject, $event, $observer){
        if(isset($this->catalog[$subject][$event])){
            foreach ($this->catalog[$subject][$event] as $item){
                if($item['nombre'] == $observer['nombre'] && $item['servicio'] == $observer['servicio'])
                    return true;
            }
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function GetSubjects(){
        $subjects = array();
        foreach($this->catalog as $subjet=>$events){
            $subjects[]=$subjet;
        }
        return $subjects;
    }

    /**
     *
     * @param string $subject
     * @return array
     */
    public function GetEventsBySubject($subject){
        $events = array();
        if($this->IsRegisteredSubject($subject))
            foreach($this->catalog[$subject] as $event=>$observers){
                $events[] = $event;
            }
        return $events;
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @return array
     */
    public function GetObserversByEvent($subject,$event){
        $observers = array();
        if($this->IsRegisteredEvent($subject, $event))
            foreach($this->catalog[$subject][$event] as $observer){
                $observers[]=$observer;
            }
        return $observers;
    }

    private function Inicialize() {
        
        //******* obtengo todos los subsistemas de los XMLs********
        $notifican = ZendExt_FastResponse::getXML('subsemitennotif'); //cargando xml de subsistemas que envian notificaciones
        $reciben = ZendExt_FastResponse::getXML('subsrecibennotif'); //cargando xml de subsistemas que reciben notificaciones
        //*********llenando un arreglo con los sub que envian notificaciones******  
        
        foreach ($notifican->children() as $sub) {
            $subsistemas = (string) $sub->getName();
            $this->RegisterSubject($subsistemas);

                foreach ($sub->children() as $event) {
                    $this->RegisterEvent($subsistemas, (string)$event['cod']);
                   } 
            }

        foreach ($reciben->children() as $obser) {
                
               $yo = (string) $obser->getName();   
            
               foreach ($obser->children() as $subsc) {
                    
                    $observers = array('nombre' => $yo, 'servicio' => (string)$subsc['servicio'], 'mid' => (string)$subsc['mid']);
                    $this->RegisterObserver((string)$subsc['sub'], (string)$subsc['cod'], $observers);
                   } 
            }


        $cache = ZendExt_Cache::getInstance();
        $cache->save(self::$instance, 'catalogo');
        return;
    }
	
	
	
	public function Actualizar(){
		$cache = ZendExt_Cache::getInstance();
		$cache->remove('catalogo');
		$this->Inicialize();
		//echo'<pre>';print_r($this->catalog);die;
		
		}
		
		
		
		
    static public function getInstance() {

        if (!isset(self::$instance)) {
           
            $cache = ZendExt_Cache::getInstance();
            try {
                self::$instance = $cache->load('catalogo');
                if (!self::$instance) {
                    self::$instance = new self();
                    $cache->save(self::$instance, 'catalogo');
                }
            } catch (Exception $ee) {
                self::$instance = new self();
                $cache->save(self::$instance, 'catalogo');
            }
        }

        return self::$instance;
    }


}
?>
