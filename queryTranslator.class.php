<?php
/**
 * User: Jens Wiemann
 * Date: 09.12.13
 * Time: 21:43
 */
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php 
require_once 'php-sql-parser.php';

echo("<pre>");

$qT = new queryTranslator();
//Create X/ Delete V/ Update V/ Insert Into X
//print_r($qT->translate("INSERT INTO kunden (name, adresse) VALUES ('Hans Meier', 'Wuppernweg 19')","JENS_"));
//print_r($qT->translate("Create Table: CREATE TABLE t (  id INT(11) default NULL auto_increment,  s char(60) default NULL,  PRIMARY KEY (id))","JENS_"));
print_r($qT->translate("UPDATE kunden SET name = 'Donald Duck', adresse = 'Entenhausen' WHERE name = 'Emil Entenich'","JENS_"));

class queryTranslator {
	
	//Add $username in front of every tablename of $inputquery
    public function translate($inputquery, $username){
        $parser = new PHPSQLParser();
		$parsed = $parser->parse($inputquery, TRUE);
		print_r($parsed);
		$positions = $this->transformarray($parsed);
		
		$i=0;
		foreach($positions as $pos){
			$inputquery = substr_replace($inputquery, $username, $pos+strlen($username)*$i, 0);
			++$i;
		}
		//print_r($inputquery);
		return $inputquery;			
    }
	
	//Returns the positions of the Tablenames of the query
	public function transformarray($parsedarray){	
		
		function printValue($value, $key, $userData) 
		{
			if($key == "table" || $key == 'position'){
				$userData[] = $value;
			} 
		}
		//Lists all table names and positions (in the query)
		$tempresult = new ArrayObject();
		array_walk_recursive($parsedarray, 'printValue', $tempresult);
		//print_r($tempresult);
		
		//Returns ownly the positions matching to the tables
		foreach($tempresult as $key => $val){
			if(!is_numeric($tempresult[$key]) && !is_null($tempresult[$key+1]) && is_numeric($tempresult[$key+1]))
				$answer[] = $tempresult[$key+1]; // next element
		}
		print_r($answer);
		
		return $answer;
    }
	
	//TODO: check for forbidden commands
    private function checkForbidden(){

    }

} 