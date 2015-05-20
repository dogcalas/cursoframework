<?php
class ZendExt_WF_WFValidator_AnalizadorSintactico{
	
        private $objscanner;
        private $arrnotifications;
        private $intproceso_a_validar;
        private $arrtokenactual;
        //private $arrvisitado;        
	
	public function __construct($scan)
	{
	        $this->objscanner = $scan;
            //$this->arrnotifications = $this->objscanner->validarFlujosdeMensajes();
            
            
	}
	public function getNotifications()
	{
	    return $this->arrnotifications;
	}
	public function parse()              
    {			
		
	  for ($k = 0; $k < $this->objscanner->getPaquete()->getPools()->count(); $k++)
	  {
			
     if($this->objscanner->getPaquete()->getActiveProcessId()==$this->objscanner->getPaquete()->getPools()->get($k)->getProcess())
     { 
	        $this->intproceso_a_validar=$this->objscanner->getPaquete()->getPools()->get($k)->getId();
	        
	     if ($this->objscanner->verificarPool($this->intproceso_a_validar)!=null)
		 {
			$this->arrnotifications[]=$this->objscanner->verificarPool($this->intproceso_a_validar);
		 }    
		 if ($this->objscanner->verificarPoolMensaje($this->intproceso_a_validar)!=null)
		 {
			 $aux=$this->objscanner->verificarPoolMensaje($this->intproceso_a_validar);
			 foreach($aux as $au){
			$this->arrnotifications[]=$au;}
		 }      		 
	     $this->arrtokenactual=$this->objscanner->nextToken(0,$this->intproceso_a_validar);
          //print_r(count($this->arrtokenactual).'  ');
	    if(count($this->arrtokenactual)>0){	    
	    
		for ($i = 0; $i <count($this->arrtokenactual) ; $i++)
		{			
		if($this->arrtokenactual[$i]['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event&&
             $this->arrtokenactual[$i]['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent)
             {
				$ban=1; 
	       }				
		}
		
		if($ban==1){
		for ($i = 0; $i < count($this->arrtokenactual); $i++)
		{
			//validar que haya un evento de inicio y que haya otro objeto de flujo sin entradas hacer OJO			
		  if($this->arrtokenactual[$i]['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event&&
              $this->arrtokenactual[$i]['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent)
			{
				$contador=$contador+1;
				//$pos=$i;
				
			}
			else{
			$pos[]=$i;}
			$cont=$cont+1;                  
                  //Los contadores van a ser distintos si hay un 
                  //evento de inicio y una tarea sin secuencias entrantes
                  
			if ($cont!=$contador)
			{
				$flag=1;
			}
			else
			{
				$flag=0;
			}											
		}
            
            $ban=0;
				
		if ($flag==1)
		{
			for ($o = 0; $o < count($pos); $o++)
			{
				$this->arrnotifications[]=array(
			    'info' => 'Hay al menos un evento de inicio y el elemento no tiene flujo de secuencia entrante.',
				'objeto' => $this->arrtokenactual[$pos[$o]]['objeto']);			
			}
		}	
			
	    }
		for ($i = 0; $i < count($this->arrtokenactual); $i++)
		{	
			self::parseInicio($this->arrtokenactual[$i]);
			//print_r('estoy en la corrida '.$i);	
								
		}
	    }
         else {
          
           $arrelementos=$this->objscanner->getTablaSimbolo();
           
           
           for ($i = 0; $i < count($arrelementos); $i++) {
             if ($arrelementos[$i]['proceso']==$this->intproceso_a_validar) {
               $arreleXproceso[]=$arrelementos[$i];
               
             }             
           }       
           
           //die(' done');
           if (count($arreleXproceso)>0) {             
             
            for ($i = 0; $i < count($arreleXproceso); $i++)
		         {	
			self::parseInicio($arreleXproceso[$i]);
			//print_r($arreleXproceso[$i]);die;
								
		        }
           }
           
           
         }
       }
       }
	}
	public function parseInicio($token)
	{
		if ($token!=null)
		{		
			
		for ($i = 0; $i < 6; $i++)//cantidad de posibles inicios
		{
			switch ($i)
			{
				case 0:
					if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event)
					{
						
						if ($token['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent)
						{						
							self::parseEventosInicio($token);
							
                          if ($this->objscanner->verificarEventoFin($this->intproceso_a_validar,$token)==1) {
							   $inf='Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.';
							   
								$this->arrnotifications[]=array(
	                                   'info' => $inf,
                                       'objeto' => $token['objeto']); 
                                
							   }
                         }
						else if ($token['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_IntermediateEvent)
						{
							self::parseEventosInter1($token);
						}					
					
				}
					break;				
				case 1:
					if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Task)
					{
						
						self::parseTareas1($token);						
					}
					break;	
				case 2:
					if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Route)
					{						
						self::parseDecisiones1($token);						  
					}
					break;											
				case 3:
				
					if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event)
					{
						if ($token['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_EndEvent)
						{
							//print_r($token['visitado']);die;
                           if ($this->objscanner->verificarEventoFin($this->intproceso_a_validar,$token)==2) {
							  
                                        $this->arrnotifications[]=array(
						               'objeto' => $token['objeto'],
						               'info' => 'El evento de fin debe tener al menos un flujo de secuencia entrante.');                                      
                                      }
						//if($this->objscanner->verificarVisitados($token)==false){
                                    self::parseEventosFin($token);}
					     //}
					}
									
					break;					
			}			
		}
	  }	
		
	}
	public function parseEventosInicio($token)
	{
		if($this->objscanner->verificarVisitados($token)==false){
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        
        if (count($sgtemensaje)>0) {
			
          $this->arrnotifications[]=array(
             'objeto' => $token['objeto'],
             'info' => 'Los eventos de inicio no pueden tener flujos de mensajes salientes');
        }
		for ($i = 0; $i < 6; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_ev_ini_none')
					{//revisar en donde poner el codigo este de abajo
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
					      if(count($sgtes)!=0)
                                      {                                                                      
						  if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
                                         {
							  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							 
							  $arraux[]=$this->objscanner->verificarProceso($token,$sgtes);						  
							  for ($i = 0; $i < count($arraux); $i++)
							  {
								  for ($j = 0; $j <count($sgtes) ; $j++)
								  {
									if ($this->$arraux[$i]['objeto']->getId()==$sgtes[$j]['objeto']->getId())
								    {
										$sgtes[$j]=null;									
								    }
								  }
							  }
                                         }
                            
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente');
							self::parseInicio($sgtes);
							return;
						}						
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_ev_ini_message')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
							if(count($sgtes)!=0){                                                                       
						 if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
                                                                       
                                          }
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_ev_ini_timer')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
							if(count($sgtes)!=0){
                                                                        
						  if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_ev_ini_rule')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
							if(count($sgtes)!=0){
                                                                       
						  if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 4:
					if ($token['tipotoken']=='tk_ev_ini_link')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
							if(count($sgtes)!=0){
                                                                        
						  if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 5:
					if ($token['tipotoken']=='tk_ev_ini_multiple')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
							if(count($sgtes)!=0){
                                                                        
						  if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'El evento de inicio tiene que tener al menos un flujo de secuencia saliente.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;									
			}			
		}	
            }
	}
	public function parseEventosInter($token)
	{
		if($this->objscanner->verificarVisitados($token)==false){
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
          $this->arrnotifications[]=array(
             'objeto' => $token['objeto'],
             'info' => 'Los eventos intermedios no pueden tener flujos de mensajes salientes');
        }
       $info='El evento intermedio no puede tener más de un flujo de secuencia saliente.';
       $info1='El evento intermedio no puede tener más de un flujo de secuencia entrante.';
		for ($i = 0; $i < 6; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_ev_inter_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
						if(count($sgtes)!=0){                                    
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             
									
								  if (count($sgtes)>1)
								  {
									  $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => 'Los eventos intermedios no pueden tener más de un flujo de secuencia saliente');									
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}						
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_ev_inter_message')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarEventoIntermedio($token)!=null)
		                       {
								 for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }	
		                       }
						if(count($sgtes)!=0){
                                    
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                              									
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }									
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_ev_inter_timer')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarEventoIntermedio($token)!=null)
		                     {
								 for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }		                     
		                     }
						if(count($sgtes)!=0){
                                    
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                            
									
								  if (count($sgtes)>1)
								  {
									 for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_ev_inter_rule')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{
									for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
								}
						if(count($sgtes)!=0){
                             if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             
									
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }									
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 4:
					if ($token['tipotoken']=='tk_ev_inter_link')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarEventoIntermedio($token)!=null)
							{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
						if(count($sgtes)!=0){          
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                            
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 5:
					if ($token['tipotoken']=='tk_ev_inter_multiple')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarEventoIntermedio($token)!=null)
		                         {
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
						if(count($sgtes)!=0){
							if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             
									
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 6:
					if ($token['tipotoken']=='tk_ev_inter_error')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
							{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							
								}
						if(count($sgtes)!=0){           
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }									
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 7:
					if ($token['tipotoken']=='tk_ev_inter_cancel')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{									
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							
								}
						if(count($sgtes)!=0){           
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                            
									
								  if (count($sgtes)>1)
								  {
									   for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;	
				case 8:
					if ($token['tipotoken']=='tk_ev_inter_compensation')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							
								}
						if(count($sgtes)!=0){            
					     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             	
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }									
								  }	
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;												
			}			
		}	
            
            }	
	}
	public function parseTareas($token)
	{
        if($this->objscanner->verificarVisitados($token)==false)
        {         
        
        //$info1='El objeto no puede tener conexiones de flujos de secuencia con objetos que no están en su mismo proceso';
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
			
           if($this->objscanner->verificarProcesoFlujoMensaje($token,$sgtemensaje)!=null){
           $this->arrnotifications[]=$this->objscanner->verificarProcesoFlujoMensaje($token, $sgtemensaje);}
           
            if($this->objscanner->verificarFlujosdeMensajes($sgtemensaje)!=null){				
				$aux=$this->objscanner->verificarFlujosdeMensajes($sgtemensaje);
				foreach($aux as $au){
           $this->arrnotifications[]=$au;
           }
                    
          } 
          }       
		for ($i = 0; $i < 9; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_task_service')
					{
					  $sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);						
                               
                                 if(count($sgtes)!=0){        
						   if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                                         
                                         $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
                                         
                                    }
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							
							$this->arrnotifications[]=array(							
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.',
							'objeto' => $token['objeto']);
							self::parseInicio($sgtes);
							return;
						}						
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_task_receive')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){ 
							if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_task_send')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){       
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_task_user')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){       
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;				
				case 4:
					if ($token['tipotoken']=='tk_task_script')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){      
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;	
				case 5:
					if ($token['tipotoken']=='tk_task_abstract')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){        
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;	
				case 6:
					if ($token['tipotoken']=='tk_task_manual')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){       
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 7:
					if ($token['tipotoken']=='tk_task_reference')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){       
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;	
				case 8:
					if ($token['tipotoken']=='tk_task_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                 if(count($sgtes)!=0){       
						     if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;						
			}			
		}
        }
		
	}
	public function parseDecisiones($token)
	{
        if($this->objscanner->verificarVisitados($token)==false){
		$sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
          $this->arrnotifications[]=array(
             'objeto' => $token['objeto'],
             'info' => 'Los nodos de decisión no pueden tener flujos de mensajes salientes');
        }
		for ($i = 0; $i < 5; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_route_data')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){ 
                                    
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_route_event')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){ 
                                    						
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
                             if ($this->objscanner->verificarRoute($token,$this->intproceso_a_validar)!=null)
								 {
									 $aux=$this->objscanner->verificarRoute($token,$this->intproceso_a_validar);
									 foreach($aux as $au){
									$this->arrnotifications[]=$au;
									}
																		
								 }
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_route_inclusive')
					{
						
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarRoute($token,$this->intproceso_a_validar)!=null)
								 {
									$this->arrnotifications[]=$this->objscanner->verificarRoute($token,$this->intproceso_a_validar);																		
								 }
                                if(count($sgtes)!=0){                                    						
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                                $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
                               }
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_route_compleintpuntero')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){ 
                                    
						
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;
				case 4:
					if ($token['tipotoken']=='tk_route_parallel')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){                                     
						
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null){
                             $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);}
							self::parseFlujoSecuencia($sgtes);
						}
						else
						{
							$this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Siempre que el proceso se inicie con un evento de inicio se debe terminar con un evento de fin.');
							self::parseInicio($sgtes);
							return;
						}
					}					
					break;									
			}			
		}
        }
	}
	
    public function parseEventosFin($token)
    {        
        if($this->objscanner->verificarVisitados($token)==false){
			
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
          if($this->objscanner->verificarProcesoFlujoMensaje($token,$sgtemensaje)!=null){
           $this->arrnotifications[]=$this->objscanner->verificarProcesoFlujoMensaje($token, $sgtemensaje);}
           
            if($this->objscanner->verificarFlujosdeMensajes($sgtemensaje)!=null){				
				$aux=$this->objscanner->verificarFlujosdeMensajes($sgtemensaje);
				foreach($aux as $au){
           $this->arrnotifications[]=$au;
           }
                    
          } 
          
          }
		for ($i = 0; $i < 9; $i++)
		{
			switch ($i)
			{
				case 0:							
					if ($token['tipotoken']=='tk_ev_end_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    
						if (count($sgtes)>0)
						{
							 $this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');
					         self::parseFlujoSecuencia($sgtes);
					         
						}
						//else
						//self::parseInicio($sgtes);
						//return;
						
						//parseFlujoSecuencia($sgtes);
					}					
					break;					
				case 1:
					if ($token['tipotoken']=='tk_ev_end_message')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                  
						if (count($sgtes)>0)
						{							
							 for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId())
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					                  'objeto' => $token['objeto'],
					                  'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                 self::parseFlujoSecuencia($sgtes);
						}
						/*else
						
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 2:
					if ($token['tipotoken']=='tk_ev_end_terminate')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    
						if (count($sgtes)>0)
						{
							 for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId())
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							} 
                                         self::parseFlujoSecuencia($sgtes);
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 3:
					if ($token['tipotoken']=='tk_ev_end_link')
					{ 
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                      
						if (count($sgtes)>0)
						{
							for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId())
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                        self::parseFlujoSecuencia($sgtes);
							 
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 4:
					if ($token['tipotoken']=='tk_ev_end_multiple')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                  
						if (count($sgtes)>0)
						{
							 for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId())
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                         self::parseFlujoSecuencia($sgtes);
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 5:
					if ($token['tipotoken']=='tk_ev_end_cancel')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    
						if (count($sgtes)>0)
						{
							for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']==$token['objeto'])
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                          self::parseFlujoSecuencia($sgtes);
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 6:
					if ($token['tipotoken']=='tk_ev_end_compensation')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    
						if (count($sgtes)>0)
						{
							 for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']==$token['objeto'])
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                         self::parseFlujoSecuencia($sgtes);
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				case 7:
					if ($token['tipotoken']=='tk_ev_end_multiple')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    
						if (count($sgtes)>0)
						{
							for ($i = 0; $i < count($this->arrnotifications); $i++)
							{
								if ($this->arrnotifications[$i]['objeto']==$token['objeto'])
								{
									$ban=1;
								}								
							}
							if ($ban!=1)
							{
								$this->arrnotifications[]=array(
					         'objeto' => $token['objeto'],
					         'info' => 'El evento de fin no puede tener flujos de secuencia saliente');					         
							}
                                          self::parseFlujoSecuencia($sgtes);
						}
						/*else
						self::parseInicio($sgtes);
						return;
						//parseFlujoSecuencia($sgtes);*/
					}	
					break;
				default:
				break;
					
			}
			
		}
	}
	}
	public function parseFlujoSecuencia($tokens)
	{
		//print_r(count($tokens));
		for ($i = 0; $i < count($tokens); $i++)
		{
			self::parseFlujos($tokens[$i]);
		}		
	}
	public function parseFlujos($token)
	{
        $info1='Los objetos de flujos no pueden estar conectado a una piscina mediante un flujo de secuencia';
		for ($i = 0; $i < 3; $i++)//cantidad de opciones
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_seqflow_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						 	for ($i = 0; $i < count($sgtes); $i++) {
                                      if ($sgtes[$i]['tipotoken']=='proceso') {
                                        $bandera=1;							
                                      }
                                    }
                                    if ($bandera==1) {
                                      for ($i = 0; $i < count($this->arrnotifications); $i++)
						   {
						     if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
							 {
								$flag=1;
							}									
						   }
                                     if ($flag!=1)
						 {
						   $this->arrnotifications[]=array(
						    'objeto' => $token['objeto'],
						       'info' => $info1);
						 }
                                     
                                  }
                                                 
								
						self::parseSgtesFlujo($sgtes);
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_seqflow_conditional')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);						
						self::parseSgtesFlujo($sgtes);
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_seqflow_default')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);						
						self::parseSgtesFlujo($sgtes);
					}					
					break;
				default:
				//errores
				break;					
			}			
		}		
	}
	public function parseSgtesFlujo($tokens)
	{
		for ($i = 0; $i < count($tokens); $i++)
		{
			self::parseObjetos($tokens[$i]);
		}		
	}
	public function parseObjetos($token)
	{
		
		for ($i = 0; $i < 6; $i++)//ver opciones
		{
			switch ($i)
			{
				case 0:					 
					break;
				case 1:
					 if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Task)
					 {
					   self::parseTareas($token);	
                                 
				       }
					 break;
				case 2:
				    if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Route)
				    {
					  self::parseDecisiones($token);
				    }		
					break;
				case 3:
				if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event){
				   if ($token['objeto']->getEventType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_IntermediateEvent)
				   {
					self::parseEventosInter($token);
				   }	}	
					break;	
				case 4:
				if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event){
				     if ($token['objeto']->getEventType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_EndEvent)
					 {
						 self::parseEventosFin($token);
						}}
					 break;
				case 5:				
				if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event)
					{
						if ($token['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent)
						{			
							$info3='Un evento de inicio no puede tener flujos de secuencia entrante';
							for ($l = 0; $l < count($this->arrnotifications); $l++)
							{
								if ($this->arrnotifications[$l]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$l]['info']==$info3)
								{
									$punt=1;
								}
							}
								if ($punt!=1)
								{
									$this->arrnotifications[]=array(							
							'info' => $info3,
							'objeto' => $token['objeto']);
								}
										
							
						}
						
                        self::parseEventosInicio($token);
                                    
                             }
                    // self::parseInicio($sgtes);
				
				break;					
			}			
		}		
	}
  
	//para cuando no haya evento de fin
	public function parseEventosInter1($token)
	{
		 if($this->objscanner->verificarVisitados($token)==false){
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
          $this->arrnotifications[]=array(
             'objeto' => $token['objeto'],
             'info' => 'Los eventos intermedio no pueden tener flujos de mensajes salientes');
        }	
        $info='Los eventos intermedios no pueden tener más de un flujo de secuencia saliente.';
        $info1='El evento intermedio no puede tener más de un flujo de secuencia entrante.';	
        for ($i = 0; $i < 6; $i++)
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_ev_inter_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){
                                    
                                  if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
												
								  if (count($sgtes)>1)
								  {
									  $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => 'Los eventos intermedios no pueden tener más de un flujo de secuencia 
									saliente');									
								  }																
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_ev_inter_message')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							      }
                                    if(count($sgtes)!=0){
                                    
                                     if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							     
								  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }	
								  }	
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_ev_inter_timer')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
                                    if(count($sgtes)!=0){
                                    
                                     if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							    
								  if (count($sgtes)>1)
								  {
									   for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_ev_inter_rule')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                     if ($this->objscanner->verificarEventoIntermedio($token)!=null)
									{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
                                    if(count($sgtes)!=0){
                                    
                                    if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							    
                                  if (count($sgtes)>1)
								  {
									   for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 4:
					if ($token['tipotoken']=='tk_ev_inter_link')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
									{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
                                    if(count($sgtes)!=0){
                                    
                                     if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							     
                                   if (count($sgtes)>1)
								  {
									   for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 5:
					if ($token['tipotoken']=='tk_ev_inter_multiple')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
									{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
                                    if(count($sgtes)!=0){
                                    
                                  if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							     
                                  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }										
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 6:
					if ($token['tipotoken']=='tk_ev_inter_error')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
		                              {
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							}
                                    if(count($sgtes)!=0){
                                    
                                 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
					           
                                 
								  if (count($sgtes)>1)
								  {
									   for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }								
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 7:
					if ($token['tipotoken']=='tk_ev_inter_cancel')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if ($this->objscanner->verificarEventoIntermedio($token)!=null)
								{
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							      }
                                    if(count($sgtes)!=0){
                                    
                                 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							     
                                  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }									
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;	
				case 8:
					if ($token['tipotoken']=='tk_ev_inter_compensation')
					{
						
                                     $sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                     if ($this->objscanner->verificarEventoIntermedio($token)!=null)
						           {
								for ($i = 0; $i < count($this->arrnotifications); $i++)
								 {
									if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info1)
									{
										$flag=1;
									}									
								 }
								 if ($flag!=1)
								 {
								   $this->arrnotifications[]=$this->objscanner->verificarEventoIntermedio($token);
								 }
							     }
                                     if(count($sgtes)!=0){
                                     
                                     if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
							     
                                  if (count($sgtes)>1)
								  {
									  for ($i = 0; $i < count($this->arrnotifications); $i++)
									  {
										  
										if ($this->arrnotifications[$i]['objeto']->getId()==$token['objeto']->getId()&&$this->arrnotifications[$i]['info']==$info)
										{
											$ban=1;
										}										
									  }
									  if ($ban!=1)
									  {
										 $this->arrnotifications[]=array(
									'objeto' => $token['objeto'],
									'info' => $info);
									  }										
								  }	
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;												
			}			
		}		
	  }
	}
	public function parseTareas1($token)
	{
		 if($this->objscanner->verificarVisitados($token)==false)
             {
		//print_r($token['objeto']->getId());die;
		$sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
          if (count($sgtemensaje)>0) {
			  
			  if($this->objscanner->verificarProcesoFlujoMensaje($token,$sgtemensaje)!=null){
           $this->arrnotifications[]=$this->objscanner->verificarProcesoFlujoMensaje($token, $sgtemensaje);}
           
            if($this->objscanner->verificarFlujosdeMensajes($sgtemensaje)!=null){				
				$aux=$this->objscanner->verificarFlujosdeMensajes($sgtemensaje);
				foreach($aux as $au){
           $this->arrnotifications[]=$au;
           }
                    
          } 
          } 
		for ($i = 0; $i < 9; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_task_service')
					{
						
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                            
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {                                                   
                                                 $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  //print_r(count($sgtes));
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                              
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_task_receive')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_task_send')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_task_user')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				
				case 4:
					if ($token['tipotoken']=='tk_task_script')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                            
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;	
				case 5:
					if ($token['tipotoken']=='tk_task_abstract')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;	
				case 6:
					if ($token['tipotoken']=='tk_task_manual')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                            
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 7:
					if ($token['tipotoken']=='tk_task_reference')
					{
						//print_r($token);
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					   				
					break;	
				case 8:
					if ($token['tipotoken']=='tk_task_none')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						
                                    if(count($sgtes)!=0){
                                                                                                             
								 if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;						
			}			
		}
             }
			
	}
	public function parseDecisiones1($token)
	{//print_r($token['tipotoken']);die;
	       if($this->objscanner->verificarVisitados($token)==false){
        $sgtemensaje=$this->objscanner->validarFlujosdeMensajes($token);
        if (count($sgtemensaje)>0) {
          $this->arrnotifications[]=array(
             'objeto' => $token['objeto'],
             'info' => 'Los nodos de decisión no pueden tener flujos de mensajes salientes');
        }
		for ($i = 0; $i < 5; $i++)//cantidad de posibles eventos
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_route_data')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						//print_r($sgtes);
                                    if(count($sgtes)!=0){
                                    
                                    if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
                                                
					}					
					break;
				case 1:
					if ($token['tipotoken']=='tk_route_event')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){
                                    
                                    if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
								 if ($this->objscanner->verificarRoute($token,$this->intproceso_a_validar)!=null)
								 {
									 $aux=$this->objscanner->verificarRoute($token,$this->intproceso_a_validar);
									 foreach($aux as $au){
									$this->arrnotifications[]=$au;
									}
																		
								 }
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_route_inclusive')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						if ($this->objscanner->verificarRoute($token,$this->intproceso_a_validar)!=null)
								 {
									 $this->arrnotifications[]=$this->objscanner->verificarRoute($token,$this->intproceso_a_validar);
								 }
                                 if(count($sgtes)!=0){                                    
                                    
                                if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 3:
					if ($token['tipotoken']=='tk_route_complex')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){
                                    
                                    if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;
				case 4:
					if ($token['tipotoken']=='tk_route_parallel')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    if(count($sgtes)!=0){
                                    
                                    if ($this->objscanner->verificarProceso($token,$sgtes)!=null)
								 {									 
                                  $this->arrnotifications[]=$this->objscanner->verificarProceso($token,$sgtes);
							     }
                                  
						          self::parseFlujoSecuencia1($sgtes);
						          
                                    }
                                    else                                    
                                      self::parseInicio($sgtes);                                                                                                                                
                                    return;
					}					
					break;									
			}			
		}
	   }
	}
	
   	public function parseFlujoSecuencia1($tokens)
	{
		for ($i = 0; $i < count($tokens); $i++)
		{
			self::parseFlujos1($tokens[$i]);			
		}
			
	}
	public function parseFlujos1($token)
	{
		for ($i = 0; $i < 3; $i++)//cantidad de opciones
		{
			switch ($i)
			{
				case 0:
					if ($token['tipotoken']=='tk_seqflow_none')
					{
						
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
						for ($i = 0; $i < count($sgtes); $i++) {
                                      if ($sgtes[$i]['tipotoken']=='proceso') {
                                        $bandera=1;							
                                      }
                                    }
                                    if ($bandera==1) {
                                      $this->arrnotifications[]=array(
							'objeto' => $token['objeto'],
							'info' => 'Los objetos de flujos no pueden estar conectado a una piscina mediante un flujo de secuencia');
                                  }	
						self::parseSgtesFlujo1($sgtes);
					}					
					break;
                case 1:
					if ($token['tipotoken']=='tk_seqflow_conditional')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                   	self::parseSgtesFlujo1($sgtes);
					}					
					break;
				case 2:
					if ($token['tipotoken']=='tk_seqflow_default')
					{
						$sgtes=$this->objscanner->nextToken($token,$this->intproceso_a_validar);
                                    self::parseSgtesFlujo1($sgtes);
					}					
					break;
				default:
				//errores
				break;					
			}			
		}		
	}
	public function parseSgtesFlujo1($tokens)
	{		
		for ($i = 0; $i < count($tokens); $i++)
		{
			self::parseObjetos1($tokens[$i]);				
		}		
					
	}
	public function parseObjetos1($token)
	{
		
		for ($i = 0; $i < 4; $i++)//ver opciones
		{
			switch ($i)
			{
				case 0:

				if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event)
                            {
					if ($token['objeto']->getEventType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_IntermediateEvent)
					{
						self::parseEventosInter1($token);
					}}
					break;
				case 1:	
					 if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Task)
					 {
						self::parseTareas1($token);
					 }
					break;
				case 2:
					if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Route)
					{
						self::parseDecisiones1($token);
					}	
					break;
								
				case 3:				
				
				if ($token['objeto']instanceof ZendExt_WF_WFObject_Entidades_Event)
					{
						if ($token['objeto']->getEventType()->getSelectedItem()instanceof ZendExt_WF_WFObject_Entidades_EndEvent)
						{							
						$this->arrnotifications[]=array(
						'objeto' => $token['objeto'],
						'info' => 'Si hay un evento de fin debe haber al menos un evento de inicio iniciando el proceso.');						 
					    }
					   if ($token['objeto']->getEventType()->getSelectedItem() instanceof ZendExt_WF_WFObject_Entidades_StartEvent)
						{							
						$this->arrnotifications[]=array(
						'objeto' => $token['objeto'],
						'info' => 'Un evento de inicio no puede tener flujos de secuencia entrante.');
					   }					   
					}
					self::parseInicio($sgtes);
					break;
									
						
		}		
	}
}
}
?>
