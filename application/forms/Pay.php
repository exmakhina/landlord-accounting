<?php

class Application_Form_Pay extends Zend_Form
{
    public function init()
    {
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Enregistrer')));
																																
		$locale = array('locale' => 'en_US');
		
		$elementDecorators = array(array('ViewHelper'),
                           array('Errors'),
                           array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'td')),
                           array('label',array('tag' => 'td')),
                           array('decorator'=>array('2eme'=>'HtmlTag'),'options'=>array('tag'=>'tr', 'class'=>'login_row')));
		$contact_formDecorators = array(	'FormElements',
                        			array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'table', 'class'=>'contact')));
									
		/* Form Elements & Other Definitions Here ... */
		$this->setName("newClient");
        $this->setMethod('post');
		$this->setAction('/clients/pay');
		
		///////////////////
		$client = $this->createElement('hidden', 'client');
		$this->addElement($client);
		$client->setDecorators($elementDecorators);
		
		///////////////////
		$nom = $this->createElement('text', 'nom');
		$nom	->setRequired(true)
				->setLabel('Nom:')
				->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)));	
		$this->addElement($nom);
		$nom->setDecorators($elementDecorators);
		
		///////////////////
		$px_sejour = $this->createElement('text', 'px_sejour');
		$px_sejour	->setLabel('Prix du sejour:')
					->setRequired(true)
					->addValidator(new Zend_Validate_Float($locale));	
		$this->addElement($px_sejour);
		$px_sejour->setDecorators($elementDecorators);
		
		///////////////////
		$date = $this->createElement('text', 'date');
		$date	->setRequired(true)
				->setLabel('Date de paiement: ');	
		$this->addElement($date);
		$date->setDecorators($elementDecorators);
		
		///////////////////
		$tablePostes = new Application_Model_PostesMapper();
		$listePostes = $tablePostes->fetchAll();
		$compte = new Zend_Form_Element_Select('compte');
		$compte	->setLabel('Depose sur le compte:')
				->setRequired(true)
				->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)))
				->addMultiOption('0', '------ choisissez un compte ------');
		foreach ($listePostes as $p) {
			if (($p->getNumero() >= 1000) && ($p->getNumero() <= 1100)) $compte->addMultiOption($p->getId(), $txt[$p->getNom()]);
		}
		$this->addElement($compte);
		$compte->setDecorators($elementDecorators);
		
		///////////////////
		$this->addDisplayGroup(array('date', 'nom', 'px_sejour', 'compte'), 'contact');
		
		$contact = $this->getDisplayGroup('contact');
		$contact->setDecorators($contact_formDecorators);
		
		///////////////////
		$send = $this->createElement('submit', 'send');
		$send->setRequired(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
	}
}