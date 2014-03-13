<?php

class Application_Form_Newresa extends Zend_Form
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
		$locale = Zend_Registry::get('locale');
		
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
		$this->setAction('/clients/newresa');
		
		///////////////////
		$nom = $this->createElement('text', 'nom');
		$nom	->setRequired(true)
				->setLabel('Nom:')
				->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)));	
		$this->addElement($nom);
		$nom->setDecorators($elementDecorators);
		
		///////////////////
		$email = $this->createElement('text', 'email');
		$email	->setLabel('Email:')
				->addValidator(new Zend_Validate_EmailAddress(array(
																'allow' => Zend_Validate_Hostname::ALLOW_DNS,
																'mx'    => true)));	
		$this->addElement($email);
		$email->setDecorators($elementDecorators);
		
		///////////////////
		$adresse = $this->createElement('textarea', 'adresse');
		$adresse->setLabel('Adresse:')
				->setAttrib('cols', '40')
    			->setAttrib('rows', '5');
		$this->addElement($adresse);
		$adresse->setDecorators($elementDecorators);
		
		///////////////////
		$tablePays = new Application_Model_PaysMapper();
		$listePays = $tablePays->fetchAll();
		$pays = new Zend_Form_Element_Select('pays');
		$pays	->setLabel('Pays:')
				->setRequired(true);
		foreach ($listePays as $p) {
			$pays->addMultiOption($p->getId(), $p->getFr());
		}
		$this->addElement($pays);
		$pays->setDecorators($elementDecorators);
		
		///////////////////
		$phone = $this->createElement('text', 'phone');
		$phone->setLabel('Tel.:');	
		$this->addElement($phone);
		$phone->setDecorators($elementDecorators);
		
		///////////////////
		$px_sejour = $this->createElement('text', 'px_sejour');
		$px_sejour	->setLabel('Prix du sejour:')
					->setRequired(true)
					->addValidator(new Zend_Validate_Float($locale));	
		$this->addElement($px_sejour);
		$px_sejour->setDecorators($elementDecorators);
		
		///////////////////
		$dateArr = $this->createElement('text', 'dateArr');
		$dateArr	->setRequired(true)
				->setLabel('Date d\'arrivée: ');	
		$this->addElement($dateArr);
		$dateArr->setDecorators($elementDecorators);
		
		///////////////////
		$dateDep = $this->createElement('text', 'dateDep');
		$dateDep	->setRequired(true)
				->setLabel('Date de départ: ');	
		$this->addElement($dateDep);
		$dateDep->setDecorators($elementDecorators);
		
		/////////////////
		$tableSites = new Application_Model_SitesMapper();
		$listeSites = $tableSites->fetchAll();
		$site = new Zend_Form_Element_Select('site');
		$site	->setLabel('Site web:')
				->addMultiOption('0', '------ aucun ------');
		foreach ($listeSites as $p) {
			if ($p->getActif()) $site->addMultiOption($p->getId(), $p->getNom());
		}
		$this->addElement($site);
		$site->setDecorators($elementDecorators);
		
		///////////////////
		$nb_guests = $this->createElement('text', 'nb_guests');
		$nb_guests	->setLabel('Nombre de personnes:')
					->setRequired(true)
					->addValidator(new Zend_Validate_Digits());	
		$this->addElement($nb_guests);
		$nb_guests->setDecorators($elementDecorators);
		
		/////////////////
		$tableAppt = new Application_Model_ApptsMapper();
		$listeAppts = $tableAppt->fetchAll();
		$appt = new Zend_Form_Element_Select('appt');
		$appt	->setLabel('Numero d\'appartement / chambre:');
		foreach ($listeAppts as $p) {
			$appt->addMultiOption($p->getId(), $p->getId().' - '.$p->getAdresse());
		}
		$this->addElement($appt);
		$appt->setDecorators($elementDecorators);
		
		///////////////////
		$dateResa = $this->createElement('text', 'dateResa');
		$dateResa	->setRequired(true)
				->setLabel('Date de paiement réservation: ');	
		$this->addElement($dateResa);
		$dateResa->setDecorators($elementDecorators);
		
		///////////////////
		$px_accpte = $this->createElement('text', 'px_accpte');
		$px_accpte	->setLabel('Montant de l\'accompte: ')
					->setRequired(true)
					->addValidator(new Zend_Validate_Float($locale));	
		$this->addElement($px_accpte);
		$px_accpte->setDecorators($elementDecorators);
		
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
		$this->addDisplayGroup(array('dateArr', 'dateDep', 'nom', 'email', 'adresse', 'pays', 'phone','px_sejour', 'nb_guests', 'appt', 'site'), 'contact');
		$this->addDisplayGroup(array('dateResa', 'px_accpte', 'compte'), 'accompte');
		
		$contact = $this->getDisplayGroup('contact');
		$contact->setDecorators($contact_formDecorators);
		$accompte = $this->getDisplayGroup('accompte');
		$accompte->setDecorators($contact_formDecorators);
		
		///////////////////
		$send = $this->createElement('submit', 'send');
		$send->setRequired(true);
		$this->addElement($send);
		$send->setDecorators($submitDecorators);
    }


}

