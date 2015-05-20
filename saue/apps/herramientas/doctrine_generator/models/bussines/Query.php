<?php

/**
 * @access public
 */
class Query {
	/**
	 * @AttributeType Table
	 */
	private $_from;
	/**
	 * @AttributeType Tree
	 */
	private $_relatedTables;
	/**
	 * @AttributeType List
	 */
	private $_selectedFields;
	/**
	 * @AttributeType int
	 */
	private $_limit;
	/**
	 * @AttributeType int
	 */
	private $_offset;
	/**
	 * @AssociationType Project
	 */
	public $_unnamed_Project_;
	/**
	 * @AssociationType Class
	 */
	public $_unnamed_Class_1_;
	/**
	 * @AssociationType Where
	 * @AssociationKind Aggregation
	 */
	public $_unnamed_Where_;

	/**
	 * @access public
	 * @return string
	 * @ReturnType string
	 */
	public function generateDQL() {
		// Not yet implemented
	}
}
?>