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
print_r($qT->translate('SELECT a from (SELECT * FROM some_table,hut) as nase,zug WHERE d > 5;',"JENS_"));


class queryTranslator {

    //Add $username in front of every tablename of $inputquery
    public function translate($inputquery, $username){
        $parser = new PHPSQLParser();
        $parsed = $parser->parse($inputquery, TRUE);
        //print_r($parsed);
        $positions = $this->transformarray($parsed);

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
            if(!is_numeric($tempresult[$key]))
                $answer[] = $tempresult[$key+1]; // next element
        }
        //print_r($answer);

        return $answer;
    }

    //TODO: check for forbidden commands
    private function checkForbidden(){

    }

} 