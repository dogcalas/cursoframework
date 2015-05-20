<?php
	interface ZendExt_Nomencladores_Interfaces_ICategory {
		function getCategories ($pOffset, $pLimit);	//ok
		function addCategory($pId,$pCategory); //ok
		function updateCategory ($pOldCategory, $pNewCategory);	//ok
		function deleteCategory ($pIdCategory);  //ok
		function setCategoryToTable ($pTable, $pIdCategory);  //ok
		function dropCategoryToTable ($pIdTable, $pIdCategory);  //ok
		function getCategoriesFromTable ($pTablename); //ok
		function getTablesFromCategory ($pTablename);
	}
?>