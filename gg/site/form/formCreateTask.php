<?php
/**
 * Created by PhpStorm.
 * User: cSteusloff
 * Date: 12.12.13
 * Time: 15:13
 */

error_reporting (E_ALL | E_STRICT);
ini_set ('display_errors', 'On');
require_once 'init_autoloader.php';


use Zend\Form\View\Helper\Form;
use Zend\Form\View\Helper\FormLabel;
use Zend\Form\View\Helper\FormSubmit;
use Zend\Form\View\Helper\FormText;
use Zend\Form\View\HelperConfig;
use Zend\View\Renderer\PhpRenderer;
use Zend\Form\View\Helper\FormSelect;
use Zend\Form\View\Helper\FormTextarea;
use Zend\Form\View\Helper\FormElementErrors;
use Zend\Form\View\Helper\FormHidden;
use Zend\Form\View\Helper\FormRadio;
use Zend\Form\View\Helper\FormMultiCheckbox;

$loader = new Zend\Loader\StandardAutoloader(array('autoregister_zf' => true));

// Register with spl_autoload:
$loader->register();

require_once 'lib/oracleConnection.class.php';
$db = new oracleConnection();
$prefix = "MASTER_";
$db->Query("SELECT TABLE_NAME FROM ALL_TABLES WHERE UPPER(TABLE_NAME) LIKE '{$prefix}%'");

$tables = array();

while($db->Fetch(false)){
    $tables[$db->row[0]] = substr(strtoupper($db->row[0]),strlen($prefix));
}

var_dump($tables);


// Formular
$form = new Zend\Form\Form();
$form->setAttribute('action', '');
$form->setAttribute ( 'method', 'post' );
$output_form = true;

var_dump($_POST);


//////////////////////////////////////////////////////////////////////////////////
/*
 * Formularfelder
*/
// Thema
$form->add(array(
    'name' => 'tasktopic',
    'type' => 'Zend\Form\Element\Text',
    'attributes' => array(
        'placeholder' => 'Aufgabenname',
        'required' => 'required',
    ),
    'options' => array(
        'label' => 'Aufgabe',
    ),
));

$form->add(array(
    'name' => 'tasktext',
    'type' => 'Zend\Form\Element\Textarea',
    'attributes' => array(
        'required' => 'required',
        'cols' => '70',
        'rows' => '5',
    ),
    'options' => array(
        'label' => 'Aufgabenbeschreibung',
    ),
));

$form->add(array(
    'name' => 'tables',
    'type' => 'Zend\Form\Element\Select',
    'attributes' => array(
        'multiple' => 'multiple',
        'size' => '10',
    ),
    'options' => array(
        'label' => 'Tabellen',
       'options' => $tables,
    ),
));

$form->add(array(
    'name' => 'permission',
    'type' => 'Zend\Form\Element\MultiCheckbox',
    'attributes' => array(
        'value' => '0',
    ),
    'options' => array(
        'label' => 'Berechtigung',
        'value_options' => array(
            '1' => 'Select',
            '2' => 'Insert/Update/Delete',
            '4' => 'Create',
            '8' => 'Drop',
        ),
    ),
));

$form->add(array(
    'name' => 'solution',
    'type' => 'Zend\Form\Element\Textarea',
    'attributes' => array(
        'id' => 'code',
        'cols' => '70',
        'rows' => '5',
    ),
    'options' => array(
        'label' => 'Referenz-Query',
    ),
));

// Send
$form->add(
    array(
        'name'       => 'send',
        'type'       => 'submit',
        'attributes' => array(
            'value' => 'Erstellen',
        ),
    )
);

//////////////////////////////////////////////////////////////////////////////////
/*
 * Eingabe Filter
*/
$inputFilter = new Zend\InputFilter\InputFilter();

$inputFilter->add(array(
    'name' => 'tasktopic',
    'required' => true,
    'filters' => array(
        array('name' => 'StripTags'),
        array('name' => 'StringTrim'),
    ),
    'validators' => array(
        array (
            'name' => 'StringLength',
            'options' => array(
                'encoding' => 'UTF-8',
                'max' => '40',
            ),
        ),
    ),
));

$inputFilter->add(array(
    'name' => 'tasktext',
    'required' => true,
    'filters' => array(
        array('name' => 'StripTags'),
        array('name' => 'StringTrim'),
    ),
    'validators' => array(
    ),
));

$inputFilter->add(array(
    'name' => 'solution',
    'required' => true,
    'filters' => array(
        array('name' => 'StripTags'),
        array('name' => 'StringTrim'),
    ),
    'validators' => array(
    ),
));


//////////////////////////////////////////////////////////////////////////////////
/*
 * Auswertung
*/
$form->setInputFilter($inputFilter);
var_dump($_SERVER['REQUEST_METHOD']);

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->setData($_POST);

    if ($form->isValid()) {
        $data = $form->getData();
        echo("<pre>");
        var_dump($data);
        echo("</pre>");

        require_once 'lib/taskHelper.php';
        $tH = new taskHelper();
        $tH->createTask($data['tasktopic'],$data['tasktext'],$data['tables'],$data['permission'],htmlspecialchars($data['solution']));
        var_dump($db->sqlquery);
    }




}

//////////////////////////////////////////////////////////////////////////////////
/*
 * Ausgabe
*/
$renderer = new PhpRenderer;
$helpers = $renderer->getHelperPluginManager();
$config = new HelperConfig();
$config->configureServiceManager($helpers);

$formHelper = new Form();
$formHelper->setView($renderer);

$formLabel = new FormLabel();
$formLabel->setView($renderer);

$formError = new FormElementErrors();
$formError->setView($renderer);

$formText = new FormText();
$formText->setView($renderer);

$formSelect = new FormSelect();
$formSelect->setView($renderer);

$formArea = new FormTextarea();
$formArea->setView($renderer);

$formSubmit = new FormSubmit();
$formSubmit->setView($renderer);

$formCheck = new FormMultiCheckbox();
$formCheck->setView($renderer);

// Prepare form
$form->prepare();

// Output
echo $formHelper->openTag($form);

    echo $formLabel($form->get('tasktopic'));
    echo $formText($form->get('tasktopic'));
    echo $formError($form->get('tasktopic'));
    echo("<br><br>");
    echo $formLabel($form->get('tasktext'));
    echo $formArea($form->get('tasktext'));
    echo $formError($form->get('tasktext'));
    echo("<br><br>");
    echo $formLabel($form->get('tables'));
    echo $formSelect($form->get('tables'));
    echo("<br><br>");
    echo $formLabel($form->get('permission'));
    echo $formCheck($form->get('permission'));
    echo $formError($form->get('permission'));
    echo("<br><br>");
    echo $formLabel($form->get('solution'));
    echo $formArea($form->get('solution'));
    echo $formError($form->get('solution'));
    echo $formSubmit($form->get('send'));

echo $formHelper->closeTag(); ?>
