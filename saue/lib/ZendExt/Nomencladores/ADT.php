<?php
/**
 * ZendExt_Nom_ADT
 *
 * Clase interfaz para la gestión de nomencladores.
 *
 * @package ZendExt
 * @subpackage Nomencladores
 * @copyright Centro UCID.
 */
class ZendExt_Nomencladores_ADT {
	/**
	 * @var $_db PDOConnection Conexión de PDO.
	 */
	public $_db;
	/**
	 * Conexiön a la BD
	 *
	 * @var PDO.
	 */
	private $_table_manager;

	/**
	 * Gestor de categorías
	 *
	 * @var $_category_manager
	 */
	private $_category_manager;

	/**
	 * Instancia de la del gestor de campos.
	 *
	 * @var ZendExt_Nom_Concrete_FieldManager
	 */
	private $_field_manager;

	/**
	 * Estructura de datos
	 *
	 * @var $_estructura
	 */
	private $_estructura;

	/**
	 * Constructor
	 *
	 * @return ZendExt_Nom_ADT
	 */
	function __construct() {
		$this->_db = ZendExt_Nomencladores_Db_Singleton::getInstance();
		$this->_table_manager = new ZendExt_Nomencladores_Concrete_TableManager ( );
		$this->_category_manager = new ZendExt_Nomencladores_Concrete_CategoryManager ( );
		//print_r("aaaaa");die;
		$this->_field_manager = new ZendExt_Nomencladores_Concrete_FieldManager ( );
	}



	/**
	*********************************************
	* |Funciones para la gestión con las tablas.|
	*********************************************
	**/

	/**
	 * Crea una tabla.
	 *
	 * @param String $pTable
	 * @param bool $pTree
	 * @param bool $pEnabled
	 * @return bool.
	 **/
	function createTable($pTable, $pTree, $pEnabled, $pCategories, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ($pSchema, 'nom_' . $pTable );

		if (!$query)
			return $this->_table_manager->createTable ($pSchema, $pTable, $pTree, $pEnabled, $pCategories);
		else
			throw new ZendExt_Exception ( 'NOM003' );
	}

        function manageRegisters($pTable, $pCategory, $pAction) {
            $this->_table_manager->manageRegisters($pTable, $pCategory, $pAction);
        }

	/**
	 * Eliminar las tablas
	 *
	 * @param String $pTable
	 * @return void.
	 **/
	function dropTable($pTable, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query)
			return $this->_table_manager->dropTable ( $pTable ); else
			throw new ZendExt_Exception ( 'NOM003' );
	}



	public function hasRelatedElements ($pTable, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ($pSchema, $pTable );

		if ($query)
			return $this->_table_manager->hasRelatedElements ($pSchema, $pTable);
		else
			throw new ZendExt_Exception ('NOM001');
	}


	public function getRelatedElements ($pTable) {
		return $this->_table_manager->getRelatedElements ( $pTable );
	}

	/**
	 * Obtiene las tablas que existen
	 *
	 * @param Int $pLimit
	 * @param Int $Offset
	 * @return Array
	 **/

	function getTables($pSchema = 'mod_nomencladores', $Offset = 0 , $pLimit = 0, $pNoms = true) {
		return $this->_table_manager->getTables ($pSchema, $pLimit, $Offset, $pNoms);
	}

	/**
	*Obtiene devuelve una tabla en forma ZendExt_Nomencladores_ADTTree
	*
	*
	**/
	function getTree($pTable, $pIdElement, $pIdExtComun = null, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		$tree = $this->_table_manager->IsTree ($pSchema, $pTable );
		if ($query && $tree){
			return $this->_table_manager->getTree ($pSchema, $pSchema.".".$pTable, $pIdElement, $pIdExtComun );
                } else
			throw new ZendExt_Exception ( 'NOM004' );

	}

	/**
	 * Cuenta los elementos de un nomenclador.
	 *
	 * @param String $pTable
	 * @return Int
	 **/

	function countTables($pSchema = 'mod_nomencladores', $pNoms = true) {
            return $this->_table_manager->countTables ($pSchema, $pNoms );
	}

        function countValues( $pTable, $pWhere = null, $pSchema = 'mod_nomencladores') {
            return $this->_table_manager->countValues($pSchema, $pSchema.".".$pTable, $pWhere );
	}

        function countFields($pTable, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema, $pTable);
		if($query)
			return $this->_table_manager->countFields ($pSchema, $pSchema.".".$pTable );
		throw new ZendExt_Exception ( 'NOM001' );
	}


	/**
	 * Retorna los campos del nomenclador
	 *
	 * @param String $pTable
	 * @return Array
	 **/
	function getNomDetails($pTable, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ($pSchema, $pTable);

		if ($query) {
			$result = $this->_field_manager->getFieldsDetails ($pSchema, $pTable);
			return $result;
		} else throw new ZendExt_Exception ( 'NOM001' );
	}


	public function addSecuencia($pTable,$pPK, $pSchema = 'mod_nomencladores')
	{
		return $this->_table_manager->addSecuencia($pTable,$pPK,$pEsquema);
	}


	public function addExtNom($pTable,$pSchema,$pCategorias)
	{
		return $this->_table_manager->addExtNom($pTable, $pSchema, $pCategorias);
	}


	/**
	*********************************************
	* |Funciones para la gestión con los Datos.|
	*********************************************
	**/

	/**
	 * Retorna la colección de elementos de un nomenclador
	 *
	 * @param String $pNom Nombre del nomenclador.
	 * @param Int $pLimit Límite de la consulta.
	 * @param Int $pOffset Desplazamiento de la consulta
	 *@param string $pWhere Para alguna seleccion especifica
	 * @return Array
	 **/
	function get($pTable, $pLimit = 0, $pOffset = 0, $pWhere = '', $pSchema = 'mod_nomencladores') {

		//$schema = $this->getSchemaByTable($pTable);

		$query = $this->_table_manager->tableExist ($pSchema, $pTable);

		if ($query)
			return $this->_table_manager->selectElements ($pSchema,  $pSchema.".".$pTable, $pLimit, $pOffset, $pWhere);
		else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Retorna los elementos de un nomenclador
	 *
	 * @param String $pTable
	 * @param Array $pPKs
	 * @return Array
	 **/
	function getElement($pTable, $pPKs, $pSelectParams = null, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query)
			return $this->_table_manager->getOneElement ( $pSchema, $pSchema.".".$pTable, $pPKs, $pSelectParams ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Actualiza un elemento
	 *
	 * @param String $pTable
	 * @param Array $pValues
	 * @param Array $pPKs
	 * @return void
	 **/
	function updateElements($pTable, $pValues, $pPKs, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ($pSchema, $pTable );
		if ($query)
			return $this->_table_manager->updateElements ($pSchema,  $pSchema.".".$pTable, $pValues, $pPKs ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Elimina un elemento
	 *
	 * @param String $pTable
	 * @param Array $pKey
	 * @return void
	 **/
	function deleteElements($pTable, $pKey, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ($pSchema,  $pTable );
		if ($query)
			return $this->_table_manager->deleteElements ( $pSchema, $pSchema.".".$pTable, $pKey ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Inserta un elemento
	 *
	 * @param String $pTable
	 * @param Array $pValues
	 * @return void.
	 **/
	function insertElements($pTable, $pValues, $pSchema = 'mod_nomencladores')
		{
		$query = $this->_table_manager->tableExist ($pSchema, $pTable );
		if ($query)
			return $this->_table_manager->insertElements ($pSchema, $pSchema.".".$pTable, $pValues ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}


	public function selectParam($pTable, $pFields, $pLimit=0, $pOffset=0, $pWhere=null, $pOrderBy=null, $pSchema = 'mod_nomencladores')
	{
		$query = $this->_table_manager->tableExist ($pSchema, $pTable );
		if ($query)
			return $this->_table_manager->selectParam( $pSchema, $pSchema.".".$pTable, $pFields, $pLimit, $pOffset, $pWhere, $pOrderBy);
	}

	/**
	*********************************************
	* |Funciones para la gestión con las Categorias.|
	*********************************************
	**/

	/**
	 * Retorna todos los nomencladores que respondan a una categoría.
	 * @param String $pCategory.
	 * @return Array.
	 **/

	function getNomsByCategory($pCategory) {
		$query = $this->_table_manager->categoryExist ( $pCategory );

		if ($query)
			return $this->_table_manager->getNomsByCategory ( $pCategory ); else
			throw new ZendExt_Exception ( 'NOM002' );
	}

	/**
	 * Retorna las categorías de un nomenclador.
	 *
	 * @param String $pTable
	 * @return Array
	 **/
//	function getNomCategories($pTable) {
//		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
//
//		if ($query)
//			return $this->_table_manager->getNomCategories ( $pTable ); else
//			throw new ZendExt_Exception ( 'NOM001' );
//	}



	/**
	 * Devuelve todas las categorías.
	 *
	 * @return Array
	 **/
	function getCategories($pOffset = 0, $pLimit = 0) {
		return $this->_category_manager->getCategories ($pOffset, $pLimit);
	}

	/**
	 * Adiciona una categoría
	 *
	 *@param int $pId
	 * @param String $pCategory
	 * @return void
	 **/
	function addCategory($pId, $pCategory) {
		$this->_category_manager->addCategory ( $pId, $pCategory );
	}

	/**
	 * Actualiza el nombre de una categoría.
	 *
	 * @param Int $pIdCategoria
	 * @param String $pCategory
	 * @return void.
	 **/
	function updateCategory($pIdCategoria, $pCategory) {
		return $this->_category_manager->updateCategory ( $pIdCategoria, $pCategory );
	}

	/**
	 * Elimina una categoría
	 *
	 * @param Int $pIdCategory
	 * @return void
	 **/
	function deleteCategory($pIdCategory) {
		return $this->_category_manager->deleteCategory ( $pIdCategory );
	}

	/**
	 * Le adiciona una categoría a la tabla.
	 *
	 * @param Int $pTable
	 * @param Int $pIdCategory
	 * @return void.
	 **/
	function setCategoryToTable($pTable, $pIdCategory) {
		return $this->_category_manager->setCategoryToTable ( $pTable, $pIdCategory );
	}

	/**
	 * Elimina una categiría de una tabla.
	 *
	 * @param Int $pIdTable
	 * @param String $pIdCategory
	 * @return void
	 **/
	function dropCategoryToTable($pIdTable, $pIdCategory) {
		return $this->_category_manager->dropCategoryToTable ( $pIdTable, $pIdCategory );
	}
	/**
	 * Obtiene las categorías de una tabla
	 *
	 * @param String $pTablename.
	 * @return void.
	 **/
	function getCategoriesFromTable($pTablename) {
		return $this->_category_manager->getCategoriesFromTable ( $pTablename );
	}

	/**
	 * Obtiene las tablas de una categoría.
	 *
	 * @param Int $pTablename.
	 * @return void.
	 **/
	function getTablesFromCategory($pIdCategory) {
		return $this->_category_manager->getTablesFromCategory ( $pIdCategory );
	}


	function getSchemas()
	{
		return $this->_table_manager->getSchemas();
	}



	/**
	*********************************************
	* |Funciones para la gestión con los Campos.|
	*********************************************
	**/

	/**
	 * Adiciona un campo a una entidad de la BD.
	 *
	 * @param String $pTable.
	 * @param String $pNombreField.
	 * @param String $pType.
	 * @param bool $pPK.
	 * @return void.
	 **/
	function addField($pTable, $pNombreField, $pType, $pPK, $pSchema = 'mod_nomencladores') {

		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query) {
			return $this->_field_manager->addField ( $pTable, $pNombreField, $pType, $pPK ,$query[0]['esquema']);
		} else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Adiciona una llave foranea
	 *
	 * @param String $pTable
	 * @param String $pForeignTable
	 * @param String $pForeignField
	 * @return void
	 **/
	function addFK($pTable, $pForeignTable, $pLocalField, $pForeignField, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		$query1 = $this->_table_manager->tableExist ( $pSchema,  $pForeignTable );
		if ($query)
			return $this->_field_manager->addFK ( $query[0]['esquema'].".".$pTable, $query1[0]['esquema'].".".$pForeignTable, $pLocalField, $pForeignField ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Elimina un campo a una tabla
	 *
	 * @param String $pTable
	 * @param String $pField
	 * @return void
	 **/
	function dropField($pTable, $pField, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query)
			return $this->_field_manager->dropField ( $query[0]['esquema'].".".$pTable, $pField ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Elimina una llave foranea
	 *
	 * @param String $pTable
	 * @param String $pForeignTable
	 * @param String $pForeignField
	 * @return void.
	 **/
	function dropFK($pTable, $pForeignField, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query)
			return $this->_field_manager->dropFK ( $query[0]['esquema'].".".$pTable, $pForeignField ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Renombra un campo de la BD.
	 *
	 * @param String $pTable
	 * @param String $pOldName
	 * @param String $pNewName
	 * @return void
	 **/
	function renameField($pTable, $pOldName, $pNewName, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if ($query)
			return $this->_field_manager->renameField ( $query[0]['esquema'].".".$pTable, $pOldName, $pNewName ); else
			throw new ZendExt_Exception ( 'NOM001' );
	}

	/**
	 * Crear llave foranea de externa
	 *
	 * @param String $extSchema Esquema de la tabla externa
	 * @param String $extTable tabla externa
	 * @param String $nomenclador Nomenclador
	 * @param String $field Campo de enlace de la foranea
	 * @return void
	* */
	function addExtFK($pextTable, $pNomenclador,$pextField, $pField, $pSchema = 'mod_nomencladores') {
		$query = $this->_table_manager->tableExist ( $pSchema,  $pNomenclador );
		if ($query)
			return $this->_field_manager->addExtFK ($pextTable, $query[0]['esquema'].".".$pNomenclador, $pextField,$pField );
		throw new ZendExt_Exception ( 'NOM008' );
	}

	/**
	 * Adicionar la presentación de un campo.
	 *
	 * @param String $pTable. Nombre de la tabla.
	 * @param String $pField Nombre del campo.
	 * @param String $pComponent Define el componente de Ext para la presentación.
	 * @param String $pRegEx Expresión regular.
	 * @param String $pDesc Descripción del campo.
	 * @param String $pVisible ¿Se verá o no?
	 * @param String $pValores En el caso de los combos se especifican los valores del combo.
	 * @return void
	 **/
	public function addPresentacion ($pLongitud, $pNombre, $pRegEx, $pDesc, $pVisible, $pComponent, $pDisplayField, $pForeignTable, $pIdField) {
		return $this->_field_manager->addPresentacion ($pLongitud, $pNombre, $pRegEx, $pDesc, $pVisible, $pComponent, $pDisplayField, $pForeignTable, $pIdField);
	}


	public function addUnique($pTable,$pField, $pSchema = 'mod_nomencladores')
	{
		$query = $this->_table_manager->tableExist ( $pSchema,  $pTable );
		if($query)
			return $this->_field_manager->addUnique($query[0]['esquema'].".".$pTable, $pField);
		throw new ZendExt_Exception ( 'NOM001' );
	}


	public function getLlaveForaneaDadoTablaCampo ($pTabla, $pCampo, $pSchema = 'mod_nomencladores') {
            $tabla_exist = $this->_table_manager->tableExist ( $pSchema,  $pTabla );
            return $tabla_exist;
	}

        public function isForeignKey($pTable, $pField, $pSchema = 'mod_datosmaestros') {
            $exist = $this->_table_manager->tableExist($pSchema, $pTable);
            if($exist)
               return $this->_table_manager->isForeignKey($pSchema, $pTable, $pField);
            else
                throw new ZendExt_Exception ( 'NOM001' );
        }

        public function getForeignDatils($pSchema, $pforeignField) {
            $foreign = $this->_table_manager->getForeignDatils($pSchema, $pforeignField);
            return $foreign;
        }

	public function setNextFieldValue () {
		return $this->_field_manager->setNextValue ();
	}


	public function getLastFieldValue () {
		return $this->_field_manager->getLastValue ();
	}


	public  function  getRegExps () {
		return $this->_field_manager->getRegExps ();
	}


	public  function  getTiposDatos () {
		return $this->_field_manager->getTiposDatos ();
	}


	public  function  getTiposComponentes () {
		return $this->_field_manager->getTiposComponentes ();
	}


	public function addExt2FK($pextTable, $pNomenclador, $pextField, $pField) {
		return $this->_field_manager->addExt2FK($pextTable, $pNomenclador, $pextField, $pField);
	}


	function getFieldData ($pIdField) {
		return $this->_field_manager->getFieldData ($pIdField);
	}


	function getParentId ($pTable, $pIdField) {
		return $this->_table_manager->getParentId ($pTable, $pIdField);
	}


	function getSchemaByTable ($pTable){
		return $this->_table_manager->getSchemaByTable ($pTable);
	}


	public function getPKFields ($pTable) {
		return $this->_field_manager->getPKFields ($pTable);
	}


	public function getNonPKFields ($pTable) {
		return $this->_field_manager->getNonPKFields ($pTable);
	}


	public function getForest ($pTable, $pIdExtComun = null, $pSchema = 'mod_nomencladores') {
		return $this->_table_manager->getForest ($pTable, $pIdExtComun, $pSchema);
	}


	public function getFullTree ($pTree, $pLikeParams = null, $pWhereParams = null, $pSelectParams = null, $pSchema = 'mod_nomencladores') {
		return $this->_table_manager->getFullTree ($pTree, $pLikeParams, $pWhereParams, $pSelectParams, $pSchema);
	}

    function getPresentacion ($pIdPresentacion) {
        return $this->_field_manager->getPresentacion ($pIdPresentacion);
    }

    function haveThisCategory($pTable, $pIdCategory) {
        return $this->_category_manager->haveThisCategory($pTable, $pIdCategory);
    }

}

?>