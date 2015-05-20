<?php
/**
 * 
 */
class ZendExt_MessageBus_EventQueue {
    public $queue;

    /**
     *
     */
    public function  __construct() {
        $this->queue = array();
    }

    /**
     *
     * @param string $subject
     * @param string $event
     * @param array $data
     */
    public function Enqueue($subject, $event, $data){
        $item = new stdClass();
        $item->subject = $subject;
        $item->event = $event;
        $item->data = $data;
        array_push($this->queue,$item);

	    }

    /**
     *
     * @return object
     */
    public function Dequeue() {
        if(count($this->queue))
            return array_shift($this->queue);
    }

    /**
     *
     * @return bool
     */
    public function IsEmpty(){
        if(count($this->queue))
            return false;
        return true;
    }
}
?>
