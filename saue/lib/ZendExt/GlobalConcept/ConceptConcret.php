<?php
/**
* ZendExt_GlobalConcept_ConceptConcret
*Clase para obtener y conformar los conceptos concretos
*
* @author Elianys Hurtado Sola
* @package ZendExt
* @subpackage GlobalConcept
* @copyright ERP Cuba 
* @version 1.5.0
*/
class ZendExt_GlobalConcept_ConceptConcret {
	public function ZendExt_GlobalConcept_ConceptConcret(){
		
	}
	/**
	 * Método para obtener un concepto en especifico
	 *
	 * @param string $concept
	 * @param SimpleXMLElement $xml
	 * @return object
	 */
	public function ObtenerConcepto($concept, $xml)
	{
			foreach ($xml as $global)//recorre por los conceptos globales
			{
				foreach ($global as $concret)//recorer por los conceptos del concepto global actual
				{
					if (strtolower($concret['nombre'])==strtolower($concept)) {//si encuentra el concepto
						$temp=$concept;//le asigno el nombre del concepto a otra variable para sacar el puntero
						$dir='ZendExt_GlobalConcept_';
						$classname=$dir.$temp;
						$puntero= new $classname();//creo el puntero para llamar al metodo que me calcula el concepto
						$return=$puntero->ObtenerConcepto();//llamo al metodo Obtener del concepto que se pide
						return $return;
					}
				}
			}
			return false;//Si no encontro el concepto
	}
	/**
	 * Método para obtener los conceptos concretos que pertenen al concepto global que se pasa por parametro
	 *
	 * @param string $concret
	 * @param SimpleXMLElement $xml
	 * @return array
	 */
	public function ObtenerConceptoGlobal($globalconcept,$xml)
	{
		$arr=array();//array para guardar los conceptos	
		$dir='ZendExt_GlobalConcept_';//para concatenar el nombre d ela clase
		foreach ($xml as $global) {//recorrer por el arreglo de conceptos globales
			if ((string)$global['nombre']==(string)$globalconcept){
				if (count($global)>0){
				foreach ($global as $concret){//ciclo para construir el arreglo con los conceptos concretos del global que s epasa por parametro
					$var=(string) $concret['nombre'];					
					$classname=$dir.$var;
					$puntero=new $classname();
					$arr[(string) $concret['nombre']]=$puntero->ObtenerConcepto();//adiciona el concepto al array e incrementa el indice
				}
					if (count($arr)>0){
					return $arr;//en caso de que si se encontro algo
				}
				}
				else{
					$classname=$dir.$globalconcept;
					$puntero= new $classname();
					return  $puntero->ObtenerConcepto();
				}
						
			}								
		}
		return false;//si el array queda vacio	o no se pudo leer el xml		
	}
	/**
	 * Obtener el nombre del concepto global al que pertenece un concepto concreto
	 *
	 * @param string $concret
	 * @param SimpleXMLElement $xml
	 * @return string
	 */
	public function ObtenerNombreGlobal($concept,$xml)
	{
		foreach ($xml as $global)//recorre por los conceptos globales
		{
			foreach ($global as $concret)//recorer por los conceptos del concepto global actual
			{
				if (strtolower($concret['nombre'])==strtolower($concept)) {//si encuentra el concepto
				
						return (string)$global['nombre'];//retorno el nombre del concepto global
				}				
			}
		}
		return false;//retorno falso en el caso de que no encontró el concepto
	}
}

?>
