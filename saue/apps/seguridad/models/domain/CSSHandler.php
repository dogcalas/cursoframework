<?php
//include("PropiedRegla.php");

class CSSHandler {
	var $rules;
	var $src;
	var $css;
	var $data;
	var $comparisonData;
	
    function CSSHandler() {
		$this->rules=array();
	}
	
	public function addRule($rule){
	
	
	$rule->setSrc($this->css);
	$this->rules[]=$rule;
	
	$rule->setModel($this->data,$this->comparisonData);	
	
	}
	
	public function setSource($src){
	
	$this->src=$src;
	$id = fopen($src,'r');
	
	$texto='';
	 while ($linea= fgets($id,1024))
	{
	$texto.=$linea;	
	}
	fclose($id);
	
	$this->css=$texto;
		
	}
	
	public function getDeclaredRules(){
	
	return $this->rules;
	
	}
	
	public function getDeclaredRule($name){
	
	return $this->rules[$name];
	
	}
	
	
	public function get($selector){
		$rule=AstsRule::update($selector);
		$rule->setSrc($this->css);
		
	}
	
	public function set($selector,$property,$value){
		$rule=AstsRule::update($selector);
		$rule->setSrc($this->css);
		$rule->set($property,$value);
		$result=$rule->exe($this->css);
		if($result!=false)
			$this->css=$result;
		
	}
	
	public function save(){
	$texto=$this->css;
	$decl=$this->getDeclaredRules();
	foreach($decl as $valores=>$valor) {
			
			
			$mod=$valor->exe($texto);
			
			if($mod!=false)
				$texto=$mod;
			//print_r($texto.'dd');		
	}
	$id = fopen($this->src,'w+');
	fwrite($id,$texto);
	fclose($id);
	//print_r($texto);
		
	}
	
	public function setModel($model,$comparisonModel){
		$this->data=$model;
		$this->comparisonData=$comparisonModel;
		if($model['ModelSource'])
			$this->setSource($model['ModelSource']);
			
	}
	
	static public function fillDoctrineRecord($doctrine_record,$css_model){
		$record=$doctrine_record;
		
			foreach($record as $valores=>$valor) {
			if($css_model[$valores])
			 $record[$valores]=$css_model[$valores];
		//	if(is_bool($new_theme['bold']))
		//  print_r('es bool' );
		//  else
		 // print_r('no es bool' );
		// print_r($css_model[$valores]."\n");
		 $ta=$record->getTable()->getDefinitionOf($valores);
			if($ta['type']=='boolean'){
				if(is_bool($css_model[$valores]))
				  $record[$valores]=$css_model[$valores];
				else{
					//print_r($css_model[$valores]);
					if($css_model[$valores]=='true')
						$record[$valores]=true;
					else
						$record[$valores]=false;
				}
			}
			//	$temaP->getTable()->getDefinitionOf('inicio_bold')
			}
		
		return $record;
	}
	
	
	static public function createModel($modelName,$data=array()){
		$model=array('ModelName'=>$modelName);
		if($data){
			foreach($data as $valores=>$valor) {
			$model[$valores]=$valor;
			//$data[$valores]='wil';
			//print_r($data[$valores]."\n");
				
			}
		}
		return $model;
	}
	
	static public function createTags(){
		$id = fopen('../lib/ExtJS/temas/personal4/css/desktop.css','r');
	
	$texto='';
	 while ($linea= fgets($id,1024))
	{
	$texto.=$linea;	
	}
	fclose($id);
		
		$class='#parab';
		$cs=split($class,$texto);
		
		//$der=split('\*/',$cs[0]);
	//	$der=split('}',$cs[0]);
		
		//$der=split('\*/',$der[count($der)-1]);
		//print_r($cs);
		//die;
		
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
		//$ProperA['RuleDefinition']=$class;
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
		$ProperA['background-image']='william';
		foreach($ProperA as $valores=>$operator) {
			if($valores=='RuleDefinitionFull')
				$new.=$ProperA['RuleDefinitionFull'].'{'."\n";
			else
				if(is_string($valores))
				$new.=$valores.':'.$operator.';'."\n";
		}
		$new.='}';
		
		$n=str_replace($original,$new,$texto);
		print_r($new);
		print_r($original);
		//print_r($n);
		}
		}
		//self::ddd=89;
		//$this->rt=89;
		//print_r($this->rt);
		die;
	}
	}
	
	static public function convertirHex($text){
                           // $pri=$text.substring(0,2);
                            $pri=  substr($text, 0,2);
                           //  $sec=$text.substring(2,4);
                              $sec=  substr($text, 2,2);
                             //$ter=$text.substring(4,6);
                              $ter=  substr($text, 4,2);
                              
                            
                             $re=array(CSSHandler::convHex($pri),CSSHandler::convHex($sec), CSSHandler::convHex($ter));
                             return $re[0].",".$re[1].",".$re[2];
    }
    
    static public function convHex($num){
                          
                            //num=num.toString();
                            
                            $num2=substr($num,0,1);
                            
                            switch($num2){
                                case 'A':{
                                   $num2=10*16;
                                   break;
                                        
                                }
                                case 'B':{
                                     $num2=11*16;   
                                       break; 
                                }
                                case 'C':{
                                     $num2=12*16;   
                                       break; 
                                }
                                case 'D':{
                                     $num2=13*16;   
                                       break; 
                                }
                                case 'E':{
                                      $num2=14*16;  
                                       break; 
                                }
                                case 'F':{
                                      $num2=15*16;  
                                        break;
                                }
                                default:{
                                      $num2=(int)$num2*16;  
                                       break; 
                                }
                                
                                
                            }
                            
                            
                            $num3=substr($num,1,1);
                            
                            switch($num3){
                                case 'A':{
                                   $num3=$num2+10;
                                   break;
                                        
                                }
                                case 'B':{
                                     $num3=$num2+11;   
                                       break; 
                                }
                                case 'C':{
                                     $num3=$num2+12;   
                                       break; 
                                }
                                case 'D':{
                                     $num3=$num2+13;   
                                       break; 
                                }
                                case 'E':{
                                      $num3=$num2+14;  
                                       break; 
                                }
                                case 'F':{
                                      $num3=$num2+15;  
                                        break;
                                }
                                default:{
                                     
                                      $num3=(int)$num3+$num2;  
                                        break;
                                }
                                
                                
                            }
                           
                            return $num3;
                            
    }	
	
	

}

?>
