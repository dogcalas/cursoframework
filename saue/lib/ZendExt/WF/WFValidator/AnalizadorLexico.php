<?php

class ZendExt_WF_WFValidator_AnalizadorLexico {

    private $arrtabla_simbolo = array();
    private $intproceso_a_validar;
    private $arrlistaelmentos;
    private $objpaquete;

    public function __construct($objpaquete) {
        $this->objpaquete = $objpaquete;
        $this->intproceso_a_validar = $this->objpaquete->getActiveProcessId();
        $this->arrtabla_simbolo = self::guardar_TablaSimbolo();
    }

    public function getPaquete() {
        return $this->objpaquete;
    }

    public function getTablaSimbolo() {
        return $this->arrtabla_simbolo;
    }

    public function accederModeloClases() {
        for ($i = 0; $i < $this->objpaquete->getWorkflowProcesses()->count(); $i++) {
            if ($this->objpaquete->getWorkflowProcesses()->get($i)->getId() == $this->intproceso_a_validar) {
                $work_process = $this->objpaquete->getWorkflowProcesses()->get($i);


                for ($j = 0; $j < $work_process->getActivities()->count(); $j++) {
                    if ($work_process->getActivities()->get($j)->getActivityType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Base_ComplexChoice) {

                        $elmentos[] = $work_process->getActivities()->get($j)->getActivityType()->getSelectedItem()->getSelectedItem();
                    } else {
                        $elmentos[] = $work_process->getActivities()->get($j)->getActivityType()->getSelectedItem();
                    }
                }
                for ($x = 0; $x < $work_process->getTransitions()->count(); $x++) {
                    $elmentos[] = $work_process->getTransitions()->get($x);
                }
            }
        }


        return $elmentos;
    }

    public function accederFlujosMensajes() {
        for ($i = 0; $i < $this->objpaquete->getMessageFlows()->count(); $i++) {
            $elmentos[] = $this->objpaquete->getMessageFlows()->get($i);
        }
        return $elmentos;
    }

    public function clasificarToken($elemt) {
        if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Event) {
            $elemt = $elemt->getEventType()->getSelectedItem();
            if ($elemt instanceof ZendExt_WF_WFObject_Entidades_StartEvent) {
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultMessage) {
                    $tipotoken = 'tk_ev_ini_message';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerTimer) {
                    $tipotoken = 'tk_ev_ini_timer';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerConditional) {
                    $tipotoken = 'tk_ev_ini_rule';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultSignal) {
                    $tipotoken = 'tk_ev_ini_link';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerMultiple) {
                    $tipotoken = 'tk_ev_ini_multiple';
                }
            } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_IntermediateEvent) {

                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultMessage) {
                    $tipotoken = 'tk_ev_inter_message';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerTimer) {
                    $tipotoken = 'tk_ev_inter_timer';
                }
                if ($elemt->getResult()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_TriggerConditional) {
                    $tipotoken = 'tk_ev_inter_rule';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultSignal) {
                    $tipotoken = 'tk_ev_inter_link';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerMultiple) {
                    $tipotoken = 'tk_ev_inter_multiple';
                }
                if ($elemt->getResult()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultLink) {
                    $tipotoken = 'tk_ev_inter_link';
                }
                if ($elemt->getResult()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_ResultError) {
                    $tipotoken = 'tk_ev_inter_error';
                }
                if ($elemt->getResult() == 'Cancel') {
                    $tipotoken = 'tk_ev_inter_cancel';
                }
                if ($elemt->getResult()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_TriggerResultCompensation) {
                    $tipotoken = 'tk_ev_inter_compensation';
                }
            } else {

                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultMessage) {
                    $tipotoken = 'tk_ev_end_message';
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_TriggerTerminate) {
                    $tipotoken = 'tk_ev_end_terminate';
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultSignal) {
                    $tipotoken = 'tk_ev_end_link';
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_ResultError) {
                    $tipotoken = 'tk_ev_end_error'; //arreglar en el analizador sintactico
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_ResultMultiple) {//no
                    $tipotoken = 'tk_ev_end_multiple';
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultCancel) {
                    $tipotoken = 'tk_ev_end_cancel';
                }
                if ($elemt->getResult()instanceof ZendExt_WF_WFObject_Entidades_TriggerResultCompensation) {
                    $tipotoken = 'tk_ev_end_compensation';
                }
            }
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Task) {
            //print_r($elemt->getTaskType()->getSelectedItem());die;
            if ($elemt->getTaskType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_TaskService) {

                $tipotoken = 'tk_task_service';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskReceive) {
                $tipotoken = 'tk_task_receive';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskSend) {
                $tipotoken = 'tk_task_send';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskUser) {
                $tipotoken = 'tk_task_user';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskScript) {
                $tipotoken = 'tk_task_script';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskAbstract) {//consultar este tipo de tare
                $tipotoken = 'tk_task_abstract';
            } else if ($elemt->getTaskType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_TaskManual) {
                $tipotoken = 'tk_task_manual';
            } else {//($elemt->getTaskType()instanceof ZendExt_WF_WFObject_Entidades_TaskReference)			   
                $tipotoken = 'tk_task_reference';
            }
        } else if ($elemt instanceof ZendExt_WF_WFObject_No) {
            $tipotoken = 'tk_no';
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Route) {

            //print_r($elemt->getGatewayType());die;
            if ($elemt->getGatewayType() == 'Exclusive') {
                if ($elemt->getExclusiveType() == 'Data') {
                    $tipotoken = 'tk_route_data';
                } else { //event
                    $tipotoken = 'tk_route_event';
                }
            } else if ($elemt->getGatewayType() == 'Inclusive') {
                $tipotoken = 'tk_route_inclusive';
            } else if ($elemt->getGatewayType() == 'Complex') {
                $tipotoken = 'tk_route_complex';
            } else {
                $tipotoken = 'tk_route_parallel';
            }
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Artifact) {
            if ($elemt->getArtifactType()instanceof ZendExt_WF_WFObject_Entidades_DataObject) {
                $tipotoken = 'tk_artif_data';
            } else if ($elemt->getArtifactType()instanceof ZendExt_WF_WFObject_Entidades_Group) {
                $tipotoken = 'tk_artif_group';
            } else {
                $tipotoken = 'tk_artif_annotation';
            }
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Transition) {//faltan estos por cambiarle el nombre OJO
            //if ($elemt->getConditionType() == 'None') {
            $tipotoken = 'tk_seqflow_none';
            /* } else if ($elemt->getConditionType() == 'Expression') {
              $tipotoken = 'tk_seqflow_conditional';
              } else {
              $tipotoken = 'tk_seqflow_default';
              } */
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_MessageFlow) {
            $tipotoken = 'tk_messageflow';
        } else if ($elemt instanceof ZendExt_WF_WFObject_Entidades_Association) {
            $tipotoken = 'tk_association';
        }
        return $tipotoken;
    }

    public function idPiscina($elemt) {
        //print_r($elemt->getId());die;
        for ($i = 0; $i < $this->objpaquete->getWorkflowProcesses()->count(); $i++) {
            //print_r($this->objpaquete->getWorkflowProcesses()->get($i)->getId().' ');
            if ($this->objpaquete->getWorkflowProcesses()->get($i)->getId() == $this->intproceso_a_validar) {
                $work_process = $this->objpaquete->getWorkflowProcesses()->get($i);
                for ($j = 0; $j < $work_process->getActivities()->count(); $j++) {
                    if ($work_process->getActivities()->get($j)->getId() == $elemt->getId()) {
                        $laneid = $work_process->getActivities()->get($j)->getNodeGraphicsInfos()->get(0)->getLaneId();
                    }
                }
            }
        }
        //print_r($laneid);die;

        for ($i = 0; $i < $this->objpaquete->getPools()->count(); $i++) {
            $pool = $this->objpaquete->getPools()->get($i);
            //print_r($pool->getId());die;
            if ($pool->getLanes()->count() > 0) {
                for ($j = 0; $j < $pool->getLanes()->count(); $j++) {
                    $lane = $pool->getLanes()->get($j);
                    if ($lane->getId() == $laneid) {
                        $idpool = $pool->getId();
                    }
                }
            }
            if ($pool->getId() == $laneid) {
                $idpool = $pool->getId();
            }
        }
        //print_r($idpool);
        return $idpool;
    }

    public function guardar_TablaSimbolo() {

        $objetos = self::accederModeloClases();
        $mensajes = self::accederFlujosMensajes();
        if (count($mensajes) > 0 && count($objetos) > 0) {
            $this->arrlistaelmentos = array_merge($objetos, $mensajes);
        } else if (count($mensajes) == 0) {
            $this->arrlistaelmentos = $objetos;
        } else {
            $this->arrlistaelmentos = $mensajes;
        }

        if (count($this->arrlistaelmentos) > 0) {

            foreach ($this->arrlistaelmentos as $elemt) {

                $tipotoken = self::clasificarToken($elemt);

                if (!($elemt instanceof ZendExt_WF_WFObject_Entidades_Transition) && !($elemt instanceof ZendExt_WF_WFObject_Entidades_MessageFlow)) {
                    $proceso = self::idPiscina($elemt);
                }

                $this->arrtabla_simbolo[] = array(
                    'proceso' => $proceso,
                    'tipotoken' => $tipotoken,
                    'objeto' => $elemt,
                    'visitado' => false
                );
                $proceso = '';
            }
        }
        //print_r($this->arrtabla_simbolo[4]);die;
        return $this->arrtabla_simbolo;
    }

    public function nextToken($elem, $proceso) {
        //self::guardar_TablaSimbolo();
        $this->intproceso_a_validar = $proceso;
        if ($elem == 0) {
            $objetos = self::primeros();
        } else {
            $objetos = self::siguientes($elem);
        }
        return $objetos;
    }

    public function primeros() {

        $tablaaux = $this->arrtabla_simbolo;
        for ($i = 0; $i < count($this->arrtabla_simbolo); $i++) {
            if ($tablaaux[$i]['proceso'] == $this->intproceso_a_validar) {
                $objetos[] = $tablaaux[$i];
            }
            if ($tablaaux [$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition) {
                $flujos1[] = $tablaaux [$i];
            }
        }
        for ($i = 0; $i < count($flujos1); $i++) {
            for ($k = 0; $k < count($objetos); $k++) {
                if ($flujos1[$i]['objeto']->getFrom() == $objetos[$k]['objeto']->getId()) {
                    $puntero = 1;
                }
            }
            if ($puntero == 1) {
                $flujos[] = $flujos1[$i];
                $puntero = 0;
            }
        }

        //print_r(count($elemnt));


        for ($k = 0; $k < count($objetos); $k++) {
            for ($x = 0; $x < count($flujos); $x++) {
                if ($objetos[$k]['objeto']->getId() == $flujos[$x]['objeto']->getTo()) {

                    $cont = 1;
                }
            }
            if ($cont != 1) {
                $token[] = array(
                    'proceso' => $objetos[$k]['proceso'],
                    'tipotoken' => $objetos[$k]['tipotoken'],
                    'objeto' => $objetos[$k]['objeto']);
            }
            $cont = 0;
        }

        return $token;
    }

    public function siguientes($el) {
        //comprueba nada mas los elementos que son del proceso que esta activo 
        $tablaaux = $this->arrtabla_simbolo;
        for ($i = 0; $i < count($this->arrtabla_simbolo); $i++) {
            if ($tablaaux[$i]['proceso'] == $this->intproceso_a_validar) {
                $objetos[] = $tablaaux[$i];
            } else if ($tablaaux[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition) {
                $flujos[] = $tablaaux[$i];
            }
        }

        if ($el['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition) {//arreglar segun se construya el token
            //print_r($el['objeto']->getTo());
            for ($i = 0; $i < count($objetos); $i++) {
                if ($el['objeto']->getTo() == $objetos[$i]['objeto']->getId()) {

                    $token[] = $objetos[$i];
                    return $token;
                }
            }
            for ($i = 0; $i < $this->objpaquete->getPools()->count(); $i++) {
                if ($this->objpaquete->getPools()->get($i)->getId() == $el['objeto']->getTo()) {
                    $token[] = array(
                        'proceso' => $this->objpaquete->getPools()->get($i)->getId(),
                        'objeto' => $this->objpaquete->getPools()->get($i),
                        'tipotoken' => 'proceso'
                    );
                    return $token;
                }
            }
        } else {
            for ($i = 0; $i < count($flujos); $i++) {
                if ($el['objeto']->getId() == $flujos[$i]['objeto']->getFrom()) {
                    $token[] = $flujos[$i];
                }
            }
            return $token;
        }
    }

    public function verificarRoute($token, $proceso) {
        $tablaaux = $this->arrtabla_simbolo;
        if ($token['tipotoken'] == 'tk_route_event') {
            $auxsgtes = self::siguientes($token);
            //print_r(count($auxsgtes));
            foreach ($auxsgtes as $sgtes) {
                $proximos = self::siguientes($sgtes);
                //print_r(count($proximos));die;
                $objetos[] = $proximos[0];
            }
            for ($j = 0; $j < count($objetos); $j++) {
                if ($objetos[$j]['tipotoken'] != 'tk_task_receive' && $objetos[$j]['tipotoken'] != 'tk_ev_inter_message'
                        && $objetos[$j]['tipotoken'] != 'tk_ev_inter_rule' && $objetos[$j]['tipotoken'] != 'tk_ev_inter_timer' &&
                        $objetos[$j]['tipotoken'] != 'tk_ev_inter_link') {
                    $cont = 1;
                }
                if ($objetos[$j]['tipotoken'] == 'tk_task_receive') {
                    for ($i = 0; $i < count($objetos); $i++) {
                        if ($objetos[$i]['tipotoken'] == 'tk_ev_inter_message') {
                            $cont1 = 2;
                        }
                    }
                }
                /* else if($objetos[$j]['tipotoken'] == 'tk_ev_inter_message')
                  {
                  for ($i = 0; $i < count($objetos); $i++)
                  {
                  if ($objetos[$i]['tipotoken']=='tk_task_receive')
                  {
                  $cont=3;
                  }

                  }
                  } */
            }

            if ($cont == 1) {
                $arrnotifications[] = array(
                    'objeto' => $token['objeto'],
                    'info' => 'Una decisión basada en evento solo puede tener como elementos siguientes objetos de flujo de tipo tarea recibir o eventos intermedios de tipo(tiempo,mensaje,regla o enlace).');
            }
            if ($cont1 == 2) {
                $arrnotifications[] = array(
                    'objeto' => $token['objeto'],
                    'info' => 'En los objetos de flujos siguientes a un nodo de decisión basado en eventos no puede haber mezcla entre tareas de tipo recibir y eventos intermedios de tipo mensaje.');
            }
            return $arrnotifications;
        }
        /* else
          {
          for ($i = 0; $i < count($tablaaux) ; $i++)
          {
          if($tablaaux[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition)
          {
          $flujos[]=$tablaaux[$i];
          }
          else if (!($tablaaux[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_MessageFlow))
          {
          $objetos[]=$tablaaux[$i];
          }
          }
          for ($k = 0; $k < count($flujos); $k++)
          {
          if ($flujos[$k]['objeto']->getTo()==$token['objeto']->getId())
          {
          $arrid[]=$flujos[$k]['objeto']->getFrom();
          }
          }
          $ban=0;
          for ($x = 0; $x <count($arrid) ; $x++)
          {
          for ($j = 0; $j <count($objetos) ; $j++)
          {
          if ($arrid[$x]==$objetos[$j]['objeto']->getId()&&!($objetos[$j]['objeto'] instanceof ))
          {
          $ban =1;
          }
          }

          }
          if ($ban==1)
          {
          $arrnotifications[] = array(
          'objeto' => $token['objeto'],
          'info' => 'poner informacion del error.');

          }

          print_r('terminar de implementar');die;
          } */
    }

    public function validarFlujosdeMensajes($token) {
        //print_r($token);
        $tabla = $this->arrtabla_simbolo;
        for ($i = 0; $i < count($tabla); $i++) {
            if ($tabla[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_MessageFlow) {
                $flujomensaje[] = $tabla[$i];
            } else if (!($tabla[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_SequenceFlow)) {
                $objetos[] = $tabla[$i];
            }
        }
        for ($i = 0; $i < count($flujomensaje); $i++) {
            if ($flujomensaje[$i]['objeto']->getSource() == $token['objeto']->getId()) {
                for ($j = 0; $j < count($objetos); $j++) {
                    if ($objetos[$j]['objeto']->getId() == $flujomensaje[$i]['objeto']->getTarget()) {
                        $sgtes[] = $objetos[$j];
                    }
                }
                for ($x = 0; $x < $this->objpaquete->getPools()->count(); $x++) {
                    if ($this->objpaquete->getPools()->get($x)->getId() == $flujomensaje[$i]['objeto']->getTarget()) {
                        $sgtes[] = array(
                            'proceso' => $this->objpaquete->getPools()->get($x)->getId(),
                            'objeto' => $this->objpaquete->getPools()->get($x),
                            'tipotoken' => 'proceso');
                    }
                }
            }
        }
        //print_r(count($sgtes));die(';as');
        return $sgtes;
    }

    public function verificarProceso($token, $sgtes) {

        for ($i = 0; $i < count($sgtes); $i++) {
            $proximo = self::siguientes($sgtes[$i]);
            if ($proximo[0]['proceso'] != $token['proceso']) {
                $notify = array(
                    'objeto' => $sgtes[$i]['objeto'],
                    'info' => 'El objeto no puede tener conexiones de flujos de secuencia con objetos que no están en su mismo contenedor.');
            }
        }
        return $notify;
    }

    public function verificarFlujosdeMensajes($sgtemensaje) {
        //die('hias');
        //print_r(count($sgtemensaje));
        for ($index = 0; $index < count($sgtemensaje); $index++) {
            //print_r($sgtemensaje[$index]);//die('as');
            if ($sgtemensaje[$index]['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event) {
                if ($sgtemensaje[$index]['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_EndEvent) {
                    $bandera = 1;
                }
            }
            if ($sgtemensaje[$index]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Route) {
                $bandera = 2;
            }
            /* if (!( $sgtemensaje[$index]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_WorkFlowProcess)){
              $idpool=$sgtemensaje[$index]['proceso'];
              for ($i = 0; $i < $this->objpaquete->getPools()->count(); $i++)
              {
              if ($this->objpaquete->getPools()->get($i)->getId()==$idpool) {
              $idproceso=$this->objpaquete->getPools()->get($i)->getProcess();
              }
              }
              //print_r($idproceso);die;
              for ($i = 0; $i < $this->objpaquete->getWorkFlowProcesses()->count(); $i++)
              {
              if ($this->objpaquete->getWorkFlowProcesses()->get($i)->getId()==$idproceso) {
              $proceso=$this->objpaquete->getWorkFlowProcesses()->get($i);
              }
              }
              //print_r($proceso);die;
              /*if ($bandera!=1&&$bandera!=2&&$proceso->getAccessLevel()=='PRIVATE') {
              $bandera = 3;
              } */
            // }
            if ($bandera == 1) {
                $notify[] = array(
                    'info' => 'Un evento de fin no puede tener flujos de mensajes entrantes.',
                    'objeto' => $sgtemensaje[$index]['objeto']
                );
            }

            if ($bandera == 2) {
                $notify[] = array(
                    'objeto' => $sgtemensaje[$index]['objeto'],
                    'info' => 'Un nodo de decisión no puede tener flujos de mensajes entrantes.');
            }
            if ($bandera == 3) {
                $notify[] = array(
                    'objeto' => $sgtemensaje[$index]['objeto'],
                    'info' => 'Los objetos de flujos que estén dentro de una piscina con acceso privado no pueden tener flujos de mensajes entrantes.');
            }
        }


        return $notify;
    }

    public function verificarProcesoFlujoMensaje($token, $sgtes) {
        for ($i = 0; $i < count($sgtes); $i++) {
            if ($token['proceso'] == $sgtes[$i]['proceso']) {
                $x = 1;
            }
        }
        if ($x == 1) {
            $notify = array(
                'objeto' => $token['objeto'],
                'info' => 'El objeto no puede tener conexiones de flujos de mensaje con objetos que están en su mismo proceso.');
        }//print_r($notify);die;
        //arreglar para obtener los sgtes de los sgtes que vienen para poder lanzar este error;
        return $notify;
    }

    public function verificarEventoIntermedio($token) {
        //die('asa');
        $tablaaux = $this->arrtabla_simbolo;

        for ($j = 0; $j < count($tablaaux); $j++) {

            if ($tablaaux[$j]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition) {
                $flujos[] = $tablaaux[$j];
            }
        }

        for ($i = 0; $i < count($flujos); $i++) {
            if ($flujos[$i]['objeto']->getTo() == $token['objeto']->getId()) {
                $cont++;
            }
        }

        if ($cont > 1) {
            //print_r($cont.'aki');
            $notify = array(
                'info' => 'El evento intermedio no puede tener más de un flujo de secuencia entrante.',
                'objeto' => $token['objeto']);
        }

        return $notify;
    }

    public function verificarVisitados($token) {
        for ($i = 0; $i < count($this->arrtabla_simbolo); $i++) {
            if ($this->arrtabla_simbolo[$i]['objeto']->getId() == $token['objeto']->getId()) {
                if ($this->arrtabla_simbolo[$i]['visitado'] == false) {
                    $this->arrtabla_simbolo[$i]['visitado'] = true;
                    return false;
                }
            }
        }
        return true;
    }

    public function verificarEventoFin($id, $t) {

        if ((self::contarEventoInicio($id)) != 0) {
            for ($k = 0; $k < count($this->arrtabla_simbolo); $k++) {
                if ($this->arrtabla_simbolo[$k]['proceso'] == $id) {

                    if ($this->arrtabla_simbolo[$k]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Event &&
                            $this->arrtabla_simbolo[$k]['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_EndEvent) {
                        $cont++;
                        if ($this->arrtabla_simbolo[$k]['visitado'] != false) {
                            $aux++;
                        }

                        //$arrfin[]=$k;
                    }
                }
                if ($this->arrtabla_simbolo[$k]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition &&
                        $this->arrtabla_simbolo[$k]['objeto']->getTo() == $t['objeto']->getId()) {
                    $cont1++;
                    $arrfin[] = $k;
                }
            }
        }
        //print_r($aux);die;
        if ($cont == 0 || $cont == null) {
            $notify = 1;
        }
        if ($cont1 == 0 || $cont1 == null) {
            $notify = 2;
        }
        //print_r($notify);die;
        return $notify;
    }

    public function contarEventoInicio($proceso) {
        for ($k = 0; $k < count($this->arrtabla_simbolo); $k++) {
            if ($this->arrtabla_simbolo[$k]['proceso'] == $proceso) {


                if ($this->arrtabla_simbolo[$k]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Event &&
                        $this->arrtabla_simbolo[$k]['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent) {
                    $inicio++;
                }
            }
        }
        return $inicio;
    }

    public function verificarPool($proceso) {
        for ($i = 0; $i < count($this->arrtabla_simbolo); $i++) {
            if ($this->arrtabla_simbolo[$i]['objeto'] instanceof ZendExt_WF_WFObject_Entidades_Transition) {
                $flujos[] = $this->arrtabla_simbolo[$i];
            }
        }

        for ($i = 0; $i < count($flujos); $i++) {
            if ($flujos[$i]['objeto']->getFrom() == $proceso) {
                $contpool = 1;
            }
        }
        //print_r($flujos[0]['objeto']->getFrom());die;
        if ($contpool == 1) {
            for ($i = 0; $i < $this->objpaquete->getPools()->count(); $i++) {
                if ($this->objpaquete->getPools()->get($i)->getId() == $proceso) {
                    $token = array('objeto' => $this->objpaquete->getPools()->get($i));
                }
            }
            $notify = array(
                'info' => 'Una piscina no puede estar conectada a ningún elemento mediante un flujo de secuencia.',
                'objeto' => $token['objeto']);
        }

        return $notify;
    }

    public function verificarPoolMensaje($proceso) {
        $tabla = $this->arrtabla_simbolo;
        for ($i = 0; $i < $this->objpaquete->getPools()->count(); $i++) {
            $pool = $this->objpaquete->getPools()->get($i);
            if ($pool->getId() == $proceso) {
                $token = array(
                    'proceso' => $pool->getId(),
                    'objeto' => $pool,
                    'tipotoken' => 'proceso'
                );
            }
        }
        $sgtes = self::validarFlujosdeMensajes($token);
        $notify = self::verificarFlujosdeMensajes($sgtes);
        return $notify;
    }

}

?>
