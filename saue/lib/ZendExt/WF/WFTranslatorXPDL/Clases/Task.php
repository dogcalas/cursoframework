<?php

class ZendExt_WF_WFTranslatorXPDL_Clases_Task extends ZendExt_WF_WFTranslatorXPDL_Base_Base {

    function __construct($object, $desassembleClass = TRUE) {
        parent::__construct($object, $desassembleClass);
    }

    public function desassembleClass() {
        $objTypeTask = $this->object->getTaskType()->getSelectedItem();
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskService) {
            $myTaskService = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskService($objTypeTask);
            $this->addAttribute($myTaskService);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskZendAction) {
            $myTaskZendAction = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskZendAction($objTypeTask);
            $this->addAttribute($myTaskZendAction);
            return;
        }        
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskIOCService) {
            $myTaskIOCService = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskIOCService($objTypeTask);
            $this->addAttribute($myTaskIOCService);
            return;
        }        
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskReceive) {
            $myTaskReceive = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskReceive($objTypeTask);
            $this->addAttribute($myTaskReceive);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskManual) {
            $myTaskManual = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskManual($objTypeTask);
            $this->addAttribute($myTaskManual);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskReference) {
            $myTaskReference = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskReference($objTypeTask);
            $this->addAttribute($myTaskReference);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskScript) {
            $myTaskScript = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskScript($objTypeTask);
            $this->addAttribute($myTaskScript);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskSend) {
            $myTaskSend = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskSend($objTypeTask);
            $this->addAttribute($myTaskSend);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskUser) {
            $myTaskUser = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskUser($objTypeTask);
            $this->addAttribute($myTaskUser);
            return;
        }
        if ($objTypeTask instanceof ZendExt_WF_WFObject_Entidades_TaskApplication) {
            $myTaskApplication = new ZendExt_WF_WFTranslatorXPDL_Clases_TaskApplication($objTypeTask);
            $this->addAttribute($myTaskApplication);
            return;
        }
    }

    public function toXPDL($doc, &$objectTag) {
        $thisObjectTag = $doc->createElement("Task");
        foreach ($this->attributes as $value) {
            $value->toXPDL($doc, $thisObjectTag);
        }

        $objectTag->appendChild($thisObjectTag);
    }

    public function fromXPDL($objectTag) {
        /*
          <xsd:complexType>
          <xsd:choice minOccurs="0">
          <xsd:element ref="xpdl:TaskService">
          <xsd:annotation>
          <xsd:documentation>BPMN: TaskType = Service.  In BPMN generally signifies any automated activity.</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskReceive">
          <xsd:annotation>
          <xsd:documentation>BPMN: TaskType = Receive.  Waits for a message, then continues. Equivalent to a "catching" message event.  In BPMN, "message" generally signifies any signal from outside the process (pool).</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskManual">
          <xsd:annotation>
          <xsd:documentation>BPMN: TaskType = Manual.  Used for human tasks other than those accessed via workflow.</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskReference">
          <xsd:annotation>
          <xsd:documentation>BPMN: TaskType = Reference.  Task properties defined in referenced activity.</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskScript">
          <xsd:annotation>
          <xsd:documentation>BPMN: TaskType = Script.  Used for automated tasks executed by scripts on process engine, to distinguish from automated tasks performed externally (Service).</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskSend">
          <xsd:annotation>
          <xsd:documentation>BPMN: Task Type = Send.  Equivalent to a "throwing" message event.  Sends a message immediately and continues.  In BPMN, "message" signifies any signal sent outside the process (pool).</xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskUser">
          <xsd:annotation>
          <xsd:documentation>BPMN: Task Type = User.  Generally used for human tasks.  </xsd:documentation>
          </xsd:annotation>
          </xsd:element>
          <xsd:element ref="xpdl:TaskApplication"/>
          </xsd:choice>
          <xsd:anyAttribute namespace="##other" processContents="lax"/>
          </xsd:complexType>
         */
        $attribs = $objectTag->attributes;

        if ($attribs->length > 0) {
            for ($i = 0; $i < $attribs->length; $i++) {
                $node = $attribs->item($i);
                $prefFuncName = 'set';
                $fullFuncName = $prefFuncName . $node->nodeName;
                $this->object->$fullFuncName($node->nodeValue);
            }
        }

        for ($i = 0; $i < $objectTag->childNodes->length; $i++) {
            $node = $objectTag->childNodes->item($i);
            $nodeName = $node->nodeName;
            
            /*
             * tasktype es de tipo complexchoice
             */
            $taskType = $this->object->getTaskType();
            
            /*
             * seleccionamos un tipo de tarea
             */
            $taskType->selectItem($nodeName);
            
            /*
             * clase manejadora del tipo de tarea
             */
            $prefClassName = 'ZendExt_WF_WFTranslatorXPDL_Clases_';            
            $className = $prefClassName . $nodeName;
            $selectedTaskType = $taskType->getSelectedItem();
            
            /*
             * instanciamos su manejadora
             */
            $newTag = new $className($selectedTaskType,FALSE);
            
            /*
             * llamada recurrente
             */
            $newTag->fromXPDL($node);
        }
    }

}

?> 