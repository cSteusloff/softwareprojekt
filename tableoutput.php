<?php 

class TableHandler {
	// count table objects
	private $cntTable = 0;
}

class Table {
	protected $sql_query = null;
	$conn = oci_connect('test', 'test', 'cSteusloff-PC/XE');
	
	private function process(){
		
	}
	
	public function __construct() {

	}
	
	public function setQuery($query){
		$this->sql_query = $query; 
	}
	
	public function getQuery(){
		return $this->sql_query;
	}
	
	public function getColNum(){
		
	}
	
	public function getRowNum(){
		
	}
	
	
	
}


/*
 * 
 * 
 */
?>
