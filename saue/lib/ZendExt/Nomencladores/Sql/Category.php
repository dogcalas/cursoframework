<?php
class ZendExt_Nomencladores_Sql_Category {
	
	function getCategories($pOfset, $pLimit) {
		$query = "select * from nom_categoria";
                if($pLimit)
                $query .= " offset($pOfset) limit($pLimit)";
            return $query;
	}
	
	function addCategory($pId, $pCategory) {
		return "insert into nom_categoria (id_categoria, n_categoria) values ($pId, '$pCategory')";
	}
	
	function updateCategory($pIdCategory, $pCategory) {
		return "update nom_categoria set n_categoria = '$pCategory' where id_categoria = $pIdCategory";
	}
	
	function deleteCategory($pIdCategory) {
		return "delete from nom_categoria where id_categoria = $pIdCategory";
	}
	
	function setCategoryToTable($pIdTable, $pIdCategory) {
		return "insert into dat_tablas_categoria values ('$pIdTable', $pIdCategory)";
	}
	
	function dropCategoryToTable($pIdTable, $pIdCategory) {
		return "delete from dat_tablas_categoria where tablasnombre = '$pIdTable' and  categoriaid_categoria = $pIdCategory";
	}
	
	function getCategoriesFromTable($pTablename) {
		return "select nom_categoria.n_categoria from dat_tablas_categoria inner
			join nom_categoria on nom_categoria.id_categoria =
			dat_tablas_categoria.categoriaid_categoria
			where dat_tablas_categoria.tablasnombre =  '$pTablename'";
            
	}
	
	function getTablesFromCategory($pIdCategory) {
		return "select tablasnombre as nombre from
                dat_tablas_categoria inner
                join nom_categoria on dat_tablas_categoria.categoriaid_categoria
                = nom_categoria.id_categoria
                where nom_categoria.id_categoria = $pIdCategory";
	}

        public function countCategories() {
            return "SELECT COUNT (*) FROM nom_categoria";
        }

        function haveThisCategory($pIdTable, $pIdCategory) {
		return "select categoriaid_categoria from dat_tablas_categoria where tablasnombre = '$pIdTable' and  categoriaid_categoria = $pIdCategory";
	}

}
?>