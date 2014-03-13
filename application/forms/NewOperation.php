<?php

class Application_Form_NewOperation extends Zend_Form
{

    public function init()
    {
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'save', 
																																'value'=>'Enregistrer')));
		
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$locale = Zend_Registry::get('locale');
		
		$elementDecorators = array(array('ViewHelper'),
                           array('Errors'),
                           array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'td')),
                           array('label',array('tag' => 'td')),
                           array('decorator'=>array('2eme'=>'HtmlTag'),'options'=>array('tag'=>'tr', 'class'=>'login_row')));
		$desc_ref_formDecorators = array(	'FormElements',
                        			array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'table', 'class'=>'contact')));
		
        $this	->setName("newOperation")
        		->setMethod('post')
				->setAction('/operation/newcustom');
	
		// ------------ Date ---------------
		$date = $this->createElement('text', 'date');
		$date	->setRequired(true)
					->setLabel('Date: ');	
		$this->addElement($date);
		$date->setDecorators($elementDecorators);
		
		
		// ------------ Description & reference ---------------
		
		$description = $this->createElement('textarea', 'description');
		$description->setLabel('Description :')
					->setAttrib('cols', '40')
    				->setAttrib('rows', '5')
					->setRequired(true)
					->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)));
		$this->addElement($description);
		$description->setDecorators($elementDecorators);
		
		$ref = $this->createElement('text', 'ref');
		$ref->setLabel('Reference :')
			->addValidator(new Zend_Validate_Alnum(array('allowWhiteSpace' => true)));
		$this->addElement($ref);
		$ref->setDecorators($elementDecorators);
		
		
		// ------------ Montant & Postes ---------------
	
		//----
		$tablePostes = new Application_Model_PostesMapper();
		$listePostes = $tablePostes->fetchAll();
		
		//----
		$valeur = $this->createElement('text', 'valeur');
		$valeur	->setLabel('Montant :')
				->setRequired(true)
				->addValidator(new Zend_Validate_Float($locale));
		$this->addElement($valeur);
		$valeur->setDecorators($elementDecorators);
		
		$mouv_de = new Zend_Form_Element_Select('mouv_de');
		$mouv_de->setLabel('Provenant de : ')
				->setRequired(true)
				->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)))
				->addMultiOption('0', '------ choisissez un poste ------');
		foreach ($listePostes as $p) {
			$mouv_de->addMultiOption($p->getId(), $txt[$p->getNom()].' ('.$txt[$p->getType()].')');
		}
		$this->addElement($mouv_de);
		$mouv_de->setDecorators($elementDecorators);
		
		$mouv_vers1 = new Zend_Form_Element_Select('mouv_vers1');
		$mouv_vers1	->setLabel('Vers :')
					->setRequired(true)
					->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)))
					->addMultiOption('0', '------ choisissez un poste ------');
		foreach ($listePostes as $p) {
			$mouv_vers1->addMultiOption($p->getId(), $txt[$p->getNom()].' ('.$txt[$p->getType()].')');
		}
		$this->addElement($mouv_vers1);
		$mouv_vers1->setDecorators($elementDecorators);
		
		$this->addDisplayGroup(array('date', 'description', 'ref', 'valeur', 'mouv_de', 'mouv_vers1'), 'desc_ref');
		
		$desc_ref = $this->getDisplayGroup('desc_ref');
		$desc_ref->setDecorators($desc_ref_formDecorators);
		
		$save = $this->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$this->addElement($save);
		$save->setDecorators($submitDecorators);
    }
}

