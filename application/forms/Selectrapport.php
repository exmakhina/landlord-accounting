<?php

class Application_Form_Selectrapport extends Zend_Form
{
    public function init()
    {
        $submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Suivant')));
		$this->setName("selectrapport");
        $this->setMethod('post');
		$this->setAction('/rapport/livres');
		
		$options = array('1' => 'complet', 
						 '2' => 'annuel', 
						 '3' => 'mensuel');
		
		$periode = new Zend_Form_Element_Select('periode');
		$periode->setLabel('Rapport :')
				->setRequired(true)
				->addMultiOptions($options);
		$this->addElement($periode);

		$send = $this->createElement('submit', 'send');
		$send->setRequired(false)
				->setIgnore(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
    }
}

