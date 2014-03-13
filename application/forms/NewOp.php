<?php

class Application_Form_NewOp extends Zend_Form
{
	protected $_rangeMin;
	protected $_rangeMax;
	
	public function __construct($rangeMin, $rangeMax)
	{
		$this->_rangeMin = $rangeMin;
		$this->_rangeMax = $rangeMax;
		
		parent::__construct();
	}
	
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
		
        $this	->setName("newOp")
        		->setMethod('post')
				->setAction('/operation/processrequest');		
				
		// ------------ global parameters --------------
		$rangeMin = $this->createElement('hidden', 'rangeMin');
		$rangeMin->setValue($this->_rangeMin);
		$rangeMax = $this->createElement('hidden', 'rangeMax');
		$rangeMax->setValue($this->_rangeMax);
		$this->addElement($rangeMin);
		$this->addElement($rangeMax);

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
		$listePostes = $tablePostes->fetchRange(1000, 1099);
		
		//----
		$valeur = $this->createElement('text', 'valeur');
		$valeur	->setLabel('Montant :')
				->setRequired(true)
				->addValidator(new Zend_Validate_Float($locale));
		$this->addElement($valeur);
		$valeur->setDecorators($elementDecorators);
		
		$mouv_de = new Zend_Form_Element_Select('mouv_de');
		$mouv_de->setLabel('Payé avec : ')
				->setRequired(true)
				->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)))
				->addMultiOption('0', '------ choisissez un poste ------');
		foreach ($listePostes as $p) {
			$mouv_de->addMultiOption($p->getId(), $txt[$p->getNom()].' ('.$txt[$p->getType()].')');
		}
		$this->addElement($mouv_de);
		$mouv_de->setDecorators($elementDecorators);
		
		//----
		$tablePostes = new Application_Model_PostesMapper();
		$listePostes = $tablePostes->fetchRange($this->_rangeMin, $this->_rangeMax);
		
		$mouv_vers = new Zend_Form_Element_Select('mouv_vers');
		$mouv_vers	->setLabel('Type de dépense :')
							->setRequired(true)
							->addValidator(new Zend_Validate_GreaterThan(array('min' => 0)))
							->addMultiOption('0', '------ choisissez un poste ------');
		foreach ($listePostes as $p) {
			$mouv_vers->addMultiOption($p->getId(), $txt[$p->getNom()].' ('.$txt[$p->getType()].')');
		}
		$this->addElement($mouv_vers);
		$mouv_vers->setDecorators($elementDecorators);
		
		//----
		$this->addDisplayGroup(array('date', 'description', 'ref', 'valeur', 'mouv_de', 'mouv_vers'), 'desc_ref');
		
		$desc_ref = $this->getDisplayGroup('desc_ref');
		$desc_ref->setDecorators($desc_ref_formDecorators);
		
		$save = $this->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$this->addElement($save);
		$save->setDecorators($submitDecorators);
    }
}

