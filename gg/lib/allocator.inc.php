<?php
/* Infobox
* @author Christian Steusloff
* @mail Christian.Steusloff(at)googlemail.com
* @date 11.2013
* @project Kooperationsboerse FASA
*/
if(!isset($_SESSION)) {
	session_start();
}


if(isset($_GET) && isset($_GET["section"])){
	switch ($_GET["section"]){
		case "welcome" : require_once 'site/welcome.phtml';
			break;
		case "createtask" : require_once 'site/createTask.phtml';
			break;
        case "exampletask" : require_once 'site/exampleTask.phtml';
            break;
        case "testenvironment" : require_once 'site/testEnvironment.phtml';
            break;
		default: require_once 'site/welcome.phtml';
	}
} else {
	require_once 'site/welcome.phtml';
}