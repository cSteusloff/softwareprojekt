<?php
/**
 * Created by PhpStorm.
 * User: cSteusloff
 * Date: 12.12.13
 * Time: 15:13
 */

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

// Formular
$form = new Zend\Form\Form();
$form->setAttribute('action', '');
$form->setAttribute ( 'method', 'post' );
$output_form = true;

// Inhalt für Auswahllisten
//$activCategory = getCategories();
//$countries = getCountries();
//$germanState = getGermanState();

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
    'name' => 'permission',
    'type' => 'Zend\Form\Element\MultiCheckbox',
    'attributes' => array(
        'value' => '0',
    ),
    'options' => array(
        'label' => 'Berechtigung',
        'value_options' => array(
            '0' => 'Select',
            '1' => 'Insert/Update/Delete',
            '2' => 'Create',
            '3' => 'Drop',
        ),
    ),
));

$form->add(array(
    'name' => 'solution',
    'type' => 'Zend\Form\Element\Textarea',
    'attributes' => array(
        'id' => 'code',
        'required' => 'required',
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

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $form->setData($_POST);

    if ($form->isValid()) {
        $data = $form->getData();
        var_dump($data);
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

?>
Formular
<p>
    <?php
    echo $formLabel($form->get('tasktopic'));
    echo $formText($form->get('tasktopic'));
    echo $formError($form->get('tasktopic'));
    echo("<br><br>");
    echo $formLabel($form->get('tasktext'));
    echo $formArea($form->get('tasktext'));
    echo $formError($form->get('tasktext'));
    echo("<br><br>");
    echo $formLabel($form->get('permission'));
    echo $formCheck($form->get('permission'));
    echo $formError($form->get('permission'));
    echo("<br><br>");
    echo $formLabel($form->get('solution'));
    echo $formArea($form->get('solution'));
    echo $formError($form->get('solution'));
    ?>
    <?= $formSubmit($form->get('send')) ?>
</p>
<?= $formHelper->closeTag() ?>