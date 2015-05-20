<?php

/**
 * @access public
 */
class Mapper {
	/**
	 * @AssociationType Project
	 */
	public $_project;
	/**
	 * @AssociationType DoctrineVersion
	 * @AssociationKind Composition
	 */
	public $_doctrine_version;
	

	/**
	 * @access public
	 * @param Project aPProject
	 * @return List
	 * @ParamType aPProject Project
	 * @ReturnType List
	 */
	public function generate(Project $aPProject) {
		// Not yet implemented
	}
}
?>