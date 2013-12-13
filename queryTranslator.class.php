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
//https://code.google.com/p/php-sql-parser/

echo("<pre>");

$qT = new queryTranslator();
//Create X/ Delete V/ Update V/ Insert Into X
//print_r($qT->translate("INSERT INTO kunden (name, adresse) VALUES ('Hans Meier', 'Wuppernweg 19')","JENS_"));
//print_r($qT->translate("Create Table: CREATE TABLE t (  id INT(11) default NULL auto_increment,  s char(60) default NULL,  PRIMARY KEY (id))","JENS_"));
print_r($qT->translate("CREATE TABLE example (
         id INT;
         data VARCHAR(100)
     );", "Jens_"));
//print_r($qT->translate("UPDATE kunden SET name = 'Donald Duck', adresse = 'Entenhausen' WHERE name = 'Emil Entenich' Order by 'Haus'","JENS_"));
/*print_r($qT->translate("
SELECT 
  MASTER_Cocktail.cname as Cocktail, 
  z.zname as Zutat, 
  zc.menge 
FROM 
  MASTER_Cocktail, 
  MASTER_Zutat z, 
  MASTER_Zutat_Cocktail zc 
WHERE 
  c.cid = zc.cid AND zc.zid = z.zid AND alkoholisch LIKE 'y'", "JENS_"));
*/
//print_r($qT->translate("SELECT Affe FROM Djungel ORDER BY Banane","JENS_"));



class queryTranslator {
	
	//Add $username in front of every tablename of $inputquery
    public function translate($inputquery, $username){
        try{
			$parser = new PHPSQLParser();
			$parsed = $parser->parse($inputquery, TRUE);
			//print_r($parsed);
			$positions = $this->nameSearch($parsed);
		
			$i=0;
			foreach($positions as $pos){
				$inputquery = substr_replace($inputquery, $username, $pos+strlen($username)*$i, 0);
				++$i;
			}
		}
		catch( Exception $e)
		{
			return $e;
		}
		return $inputquery;			
    }
	//Search for Names in Query
	private function nameSearch($parsedarray){	
		function search2($array, $key) { 
			$results = array(); 

			if (is_array($array)){ 
				if (isset($array[$key])) 
					$results[] = $array; 
	
				foreach ($array as $subarray) 
					$results = array_merge($results, search2($subarray, $key)); 
			}
			return $results; 
		} 		
		function search($array, $key, $value) { 
			$results = array(); 

			if (is_array($array)){ 
				if (isset($array[$key]) && $array[$key] == $value) 
					$results[] = $array; 
	
				foreach ($array as $subarray) 
					$results = array_merge($results, search($subarray, $key, $value)); 
			}
			return $results; 
		} 
		//Change if other type required
		//$tempresult = search($parsedarray, "expr_type", "table");
		$tempresult = search2($parsedarray,"table");
		$answer = new ArrayObject();
		
		//Returns ownly the positions matching to the tables
		foreach($tempresult as $key => $val){
			foreach($tempresult[$key] as $key2 => $val2){
				if ($key2 == "position")	
					$answer[] = $val2;
				}
		}
		return $answer;
	}
	//TODO: check for forbidden commands
    private function checkForbidden(){

    }
} 