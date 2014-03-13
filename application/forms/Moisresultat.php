<?php

class Application_Form_Moisresultat extends Zend_Form
{
    public function init()
    {
        $submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Suivant')));
																								
		$this->setName("resultats");
        $this->setMethod('post');
		$this->setAction('/rapport/resultats');
		
		$annee = $this->createElement('text', 'annee');
		$annee	->setRequired(true)
				->setLabel('Annee :')
				->addValidator(new Zend_Validate_Digits());
		$this->addElement($annee);
		
		$send = $this->createElement('submit', 'send');
		$send->setRequired(true)
				->setIgnore(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
    }
}

