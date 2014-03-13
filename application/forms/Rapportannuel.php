<?php

class Application_Form_Rapportannuel extends Zend_Form
{
    public function init()
    {
        $submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Suivant')));
																								
		$this->setName("rapportannuel");
        $this->setMethod('post');
		$this->setAction('/rapport/annuel');
		
		$annee = $this->createElement('text', 'annee');
		$annee	->setRequired(true)
				->setLabel('Annee :')
				->addValidator(new Zend_Validate_Digits());
		$this->addElement($annee);
		
		$send = $this->createElement('submit', 'send');
		$send->setRequired(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
    }


}

