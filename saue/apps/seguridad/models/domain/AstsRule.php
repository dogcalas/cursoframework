<?php

class AstsRule {

    var $id;
   
	var $css;
	var $model;
	var $compModel;
	var $varConditions;
	var $sintaxConditions;
	var $count_conditions;
	var $haveCondisions;
    // Propiedades nuevas ASTS
    const CREATE = 0;
    const UPDATE = 1;
    const REMOVE = 2;
    
    var $styles;
    var $selector;
    var $newProp;
    var $oldProp;
    var $removeProp;
    var $plainText;
    var $action;
    var $remove;
    var $values;
    
    function AstsRule($id=0,$action=AstsRule::CREATE) {

        $this->id=$id;
       
        $this->varConditions =array();
        $this->count_conditions =0;
        $this->haveCondisions =false;
       
        $this->newProp =array();
        $this->oldProp =array();
        $this->action=$action;
        return $this;
        
    }
	
	 public function setStyle($styles){
	   $this->styles=$styles;
	   
	   $this->remove=false;
	   return $this;
	   
	}
	
	public function removeStyle($styles='ASTS REMOVE'){
	   if($styles=='ASTS REMOVE')
			$this->removeProp=array();
		else
			$this->removeProp=split(',',$styles);
	   $this->remove=true;
	   return $this;
	   
	}
	
	static public function update($selector){
	   $new=new AstsRule(0,AstsRule::UPDATE); 
	   $new->selector=$selector;
	   return $new;
	  
	   
	}
	
	static public function create($selector){
	   $new=new AstsRule(0); 
	   $new->selector=$selector;
	   return $new;
	  
	}
	
	static public function remove($selector){
	   $new=new AstsRule(0,AstsRule::REMOVE); 
	   $new->selector=$selector;
	   return $new;
	  
	}
	
	static public function openCss($url){
		$id = fopen($url,'r');
	
		$texto='';
		while ($linea= fgets($id,1024))
			{
			$texto.=$linea;	
			}
		fclose($id);
		return $texto;
	}
	
	static public function closeCss($url,$css){
		$id = fopen($url,'w+');
		fwrite($id,$css);
		fclose($id);
		
	}
	
	
	static public function getRule($selector,$css){
		$new=new AstsRule(0,AstsRule::UPDATE); 
		$new->selector=$selector;
		$new->css=$css;
		
		return $new;
	}
	
	public function get($property){
		$this->createOldProp();
		
		return $this->oldProp[$property];
	}
	
	function set($property,$value){
		$this->newProp[$property]=$value;
		
	}
	
	function addProperty($property){
		$plain=$this->plainText;
		$vali=split('{',$plain);
		$array=str_getcsv($vali[1],'}','{');
		$propertys=split('}',$array[0]);
		$ori=split('}',$vali[1]);
		
		$prop=split(';',$ori[0]);
		
		//$prop[]=$property;
		
		$new=join(';',$prop);
		$new.=$property.";\n ";
		
		//arreglar @!!
		$f=str_replace($ori[0],$new,$plain);
		
		
		return $f;
		
	}
	
	public function exe($css){
		if($this->check()){
		 switch($this->action) {
            case AstsRule::REMOVE :{
				$this->css=$css;
				$one=split($this->selector,$css);
				$two=split('}',$one[0]);
				
				$r=$two[count($two)-1].$this->selector;
				$this->selector=$r;
				$this->createOldProp();
				$this->css=str_replace($this->plainText,'',$this->css);
				return $this->css;
			}
                
			case AstsRule::CREATE :{
				$this->css=$css;
				$this->plainText=$this->selector.' {';
				$this->createNewProp();
				foreach($this->newProp as $key=>$value) {
						$pro=$key.':'.$value.';';
						$this->plainText.=$pro;
					}
					$this->plainText.='}';
					$this->css.=$this->plainText;
					return $this->css;
					
			}
			
			case AstsRule::UPDATE :{
				$this->css=$css;
				$this->createNewProp();
				$this->createOldProp();
				$oldPlain=$this->plainText;
				
				if($this->remove==false){
					foreach($this->newProp as $key=>$value) {
						$pro=$key.':'.$value;
						
						$plainProp=$this->getPlainProperty($key);
						//print_r(" ffff1 ");
						//print_r($plainProp);
						//print_r(" ffff ");
						if($plainProp=="ASTS-NO-EXIST-PROPERTY")
							$this->plainText=$this->addProperty($pro);
						else
							$this->plainText=str_replace($plainProp,$pro,$this->plainText);
						//print_r(" wwwww1 ");
						//print_r($this->plainText);
						//print_r(" wwwww ");
					}
					//print_r($this->plainText);
					$this->css=str_replace($oldPlain,$this->plainText,$this->css);
					return $this->css;
				}else{
					if(count($this->removeProp)==0){
					
						$this->plainText=$this->selector.'{ }';
						
						$this->css=str_replace($oldPlain,$this->plainText,$this->css);
						return $this->css;
					}else{
						foreach($this->removeProp as $key=>$value) {
						$tok=$this->getPlainProperty($value);
						
						$aux=split($tok.';',$this->plainText);
							if(count($aux)>1)
								$this->plainText=str_replace($tok.';','',$this->plainText);
							else
								$this->plainText=str_replace($tok,'',$this->plainText);
							//print_r("-");
							//print_r($tok);
						}
						$this->css=str_replace($oldPlain,$this->plainText,$this->css);
						return $this->css;
					
					}
				}
			}

           
        }
	}else
		return false;
		
	}
	
	public function values($values){
		$this->values=split(',',$values);
		return $this;	
	}
	
	function haveComodin($text){
			
			$text=str_replace(' ','',$text);
			$text=str_replace("\n","",$text);
			$text=str_replace("\t","",$text);
			$text=str_replace("\r","",$text);
			$div=split('\?',$text);
			if(count($div)>1)
				return true;
			else{
				if($div[0]=='')
					return true;
				else
					return false;
			}
	}
	
	
	function createNewProp(){
		
		
		
		
		$propertysArray=split(';',$this->styles);
		$indexValues=0;
		foreach($propertysArray as $index=>$prop) {
			if($prop!=''){
			
			if(count($this->model)>0&&count($this->values)>0&&$this->haveComodin($prop)){
			$prop=str_replace('?',$this->model[$this->values[$indexValues]],$prop);
			$indexValues++;
			
			}
			$propArray=split(':',$prop);
			$propName=$propArray[0];
			$propValue=$propArray[1];
			$this->newProp[$propName]=$propValue;
			}
		}
		
	}
	
	function getPlainProperty($name){
		$text=$this->plainText;
		
		
		$vali=split('{',$text);
		$array=str_getcsv($vali[1],'}','{');
		$propertys=split('}',$array[0]);
		
		$list=split(';',$propertys[0]);
		
		
		
		foreach($list as $index=>$value) {
			
			
			if($value!=''){
			
			$tag=split(':',$value);
			$pName=str_replace(' ','',$tag[0]);
			$pName=str_replace("\n","",$pName);
			$pName=str_replace("\t","",$pName);
			$pName=str_replace("\r","",$pName);
			$coment=split('\*/',$pName);
			
			if(count($coment)>1){
				$pName=$coment[1];
				$v=split('\*/',$value);
				$value=$v[1];
				
			}
			if($pName==$name){
				//print_r(" ssss ");
				
				//print_r(" aaaa ");
				
				return $value;
			}
			}
				
		}
		
		return "ASTS-NO-EXIST-PROPERTY";
		
		
	}
	
	function validateSelector($selector,$css){
			$aux=split($selector,$css);
			if(count($aux)>1){
				
			}else
				return $aux;
			
	}
	
	function createOldProp(){
		$text=$this->css;
		$class=$this->selector;
		$cs=split($this->selector,$text);
		//print_r($cs);
		if(count($cs)>1){
		foreach($cs as $va=>$val) {
		if($va>0){
		$vali=split('{',$val);
		$array=str_getcsv($vali[1],'}','{');
		$propertys=split('}',$array[0]);
		$ori=split('}',$vali[1]);
		$original=$class.$vali[0].'{'.$ori[0].'}';
		$prop=split(';',$propertys[0]);
		
		$ProperA=array();
		
		$ProperA['RuleDefinitionFull']=$class.$vali[0];
		foreach($prop as $valores=>$operator) {
			$hash=split(':',$operator);
			$hash[0]=str_replace(' ','',$hash[0]);
			$hash[0]=str_replace("\n","",$hash[0]);
			$hash[0]=str_replace("\t","",$hash[0]);
			$hash[0]=str_replace("\r","",$hash[0]);
			if(str_word_count($hash[0])>0)
			if(!array_key_exists($hash[0],$ProperA))
				$ProperA[$hash[0]]=$hash[1];
		}
		$this->oldProp=$ProperA;
		$this->plainText=$original;
		
		break;
		}
		}
		
	}
	}
  
	
	public function removePropertys($properys){
		$this->replace=true;
		return $this;
	}



    
    function operator($izq,$der,$sign){
				$cont=0;
				$izqOp=str_replace(' ','',$izq);
				$derOp=str_replace(' ','',$der);
				$declared=split(':',$izqOp);
					
					if(count($declared)>1){
						if($this->model['ModelName']==$declared[0])
							$de1=$this->model[$declared[1]];
						else{
							if($this->compModel['ModelName']==$declared[0])
							$de1=$this->compModel[$declared[1]];
							else{
							$de1='{no-match-model-left-phpCss-v1.1}';
							return false;
						}
						}
					}else{
						
						if(strcmp('?',$izqOp)==0){
							$de1=$this->getNextVariable();
							
								
						}else{
							$de1=$izqOp;
						}
						
					}
					
					$declared2=split(':',$derOp);
					
					if(count($declared2)>1){
						if($this->model['ModelName']==$declared2[0])
							$de2=$this->model[$declared2[1]];
						else{
							if($this->compModel['ModelName']==$declared2[0])
							$de2=$this->compModel[$declared2[1]];
							else{
							$de2='{no-match-model-left-phpCss-v1.1}';
							return false;
						}
						}
					}else{
						
						if(strcmp('?',$derOp)==0){
							$de2=$this->getNextVariable();
							//print_r('^^^^^^^^^^^^^^^^^^^^');
						}else{
							$de2=$derOp;
							}
						
					}
				
				if($sign){
					if($de2==$de1)
						return true;
					else
						return false;
				}else{
					if($de2!=$de1)
						return true;
					else
						return false;
				}
	}
	
	public function resetCountConditions(){
		$this->count_conditions=0;
	}
    
    public function getNextVariable(){
		$var=$this->varConditions[$this->count_conditions];
		$this->count_conditions=$this->count_conditions+1;
		return $var;
	}

	public function when($sintaxConditions,$varConditions=array()){
		$this->sintaxConditions=$sintaxConditions;
		$this->varConditions=$varConditions;
		$this->haveCondisions=true;
		return $this;
	} 
	
	function check(){
		
		if($this->hasConditions()){
		$ors=split(' or ',$this->sintaxConditions);
		$cont_ors=count($ors);
		$condi=array();
		//print_r($ors);
		//print_r($this->id);
			
		foreach($ors as $valores=>$ands) {
			$operators_ands=split(' and ',$ands);
				
			if(count($operators_ands)>1){
			$cont_valid=0;
			foreach($operators_ands as $valores=>$operator) {
				$sign=split('==',$operator);
				if(count($sign)>1){
					if($this->operator($sign[0],$sign[1],true))
						$cont_valid++;
				}else{
					$sign=split('!=',$operator);
					if(count($sign)>1){
					  if($this->operator($sign[0],$sign[1],false))
						$cont_valid++;
					}
				}
			}
			
			if($cont_valid==count($operators_ands))
				$condi[]=true;
			else
				$condi[]=false;
			
			}else{
				
				$cont_valid=0;
				$sign=split('==',$operators_ands[0]);
				
				if(count($sign)>1){
					if($this->operator($sign[0],$sign[1],true))
					$cont_valid++;
				}else{
					$sign=split('!=',$operators_ands[0]);
					
					if(count($sign)>1){
						if($this->operator($sign[0],$sign[1],false))
						$cont_valid++;
					}
				}
				
				if($cont_valid==1)
				$condi[]=true;
			else
				$condi[]=false;
				
			}
			
		}
		
		
		if($cont_ors>1){
			foreach($condi as $valores=>$val) {
				if($val==true)
					return true;
			}
			return false;
			
		}else{
			if($condi[0]==true)
				return true;
			else
				return false;
		}
	}else{
			return true;
	}
		
	}
//----------------------------------------------------------------
    //METODOS GET Y SET DE LAS VARIABLES

   


    
    public function setSrc($src) {
        $this->css=$src;
    }
    
    
    
    public function setModel($model,$modelC) {
        $this->model=$model;
        $this->compModel=$modelC;
    }

   
    public function addCondition($sintaxConditions,$varConditions,$replace) {
        $con=$replace;
        
        if($this->haveCondisions==false)
			$con=true;
        if($con){
        $this->sintaxConditions=$sintaxConditions;
        $this->varConditions=$varConditions;
        }else{
        $this->sintaxConditions.=' or '.$sintaxConditions;
		foreach($varConditions as $valores=>$valor) {
			$this->varConditions[]=$valor;
		
		}
		}
		$this->haveCondisions=true;
		
		return $this;
       
    }
  
    public function hasConditions() {
        return $this->haveCondisions;
         
    }

	

}
?>
