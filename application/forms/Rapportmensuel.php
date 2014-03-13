<?php

class Application_Form_Rapportmensuel extends Zend_Form
{
    public function init()
    {
        $submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Suivant')));
		$this->setName("rapportmensuel");
        $this->setMethod('post');
		$this->setAction('/rapport/mensuel');
		
		$annee = $this->createElement('text', 'annee');
		$annee	->setRequired(true)
				->setLabel('Annee :')
				->addValidator(new Zend_Validate_Digits());
		$this->addElement($annee);
		
		$liste_mois = array('01' =>	'Janvier',
							'02' =>	'Fevrier',
							'03' =>	'Mars',
							'04' =>	'Avril',
							'05' =>	'Mai',
							'06' =>	'Juin',
							'07' =>	'Juillet',
							'08' =>	'Aout',
							'09' =>	'Septembre',
							'10' =>	'Octobre',
							'11' =>	'Novembre',
							'12' =>	'Decembre');
		
		$mois = new Zend_Form_Element_Select('mois');
		$mois	->setLabel('Mois :')
				->setRequired(true)
				->addMultiOptions($liste_mois);
		$this->addElement($mois);
		
		$send = $this->createElement('submit', 'send');
		$send->setRequired(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
    }


}

