<?php

class Application_Form_Registration extends Zend_Form
{

    public function init()
    {
		$session = Zend_Registry::get('session');
		$language = $session->language;
		$translationsMapper = new Application_Model_TranslationsMapper();
		$txt = $translationsMapper->getLanguage($language);
		
		$elementDecorators = array(array('ViewHelper'),
                           array('Errors'),
                           array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'td')),
                           array('label',array('tag' => 'td')),
                           array('decorator'=>array('2eme'=>'HtmlTag'),'options'=>array('tag'=>'tr', 'class'=>'login_row')));
						   
															
		/* Form Elements & Other Definitions Here ... */
		// Create user sub form: username and password
        $user = new Zend_Form_SubForm();
        $user->addElements(array(
            new Zend_Form_Element_Text('username', array(
                'required'   => true,
                'label'      => 'Username:',
                'validators' => array(
                    'Alnum',
                    array('Regex',
                          false,
                          array('/^[a-z][a-z0-9]{2,}$/'))
                ),
				'decorators'	=>	$elementDecorators	
            )),
 
            new Zend_Form_Element_Password('password1', array(
                'required'   => true,
                'label'      => 'Password:',
                'validators' => array(
                    'NotEmpty',
					array('Regex',
                          false,
                          array('/^[a-z][a-z0-9]{2,}$/')),
                    array('StringLength', false, array(6))),
				'decorators'	=>	$elementDecorators
            )),
			
			new Zend_Form_Element_Password('password2', array(
                'required'   => true,
                'label'      => 'Confirm password:',
                'validators' => array(
                    'NotEmpty',
					array('Regex',
                          false,
                          array('/^[a-z][a-z0-9]{2,}$/')),
                    array('StringLength', false, array(6))),
				'decorators'	=>	$elementDecorators
            )),
        ));
 
        // Create demographics sub form: given name, family name, and
        // location										
        $demog = new Zend_Form_SubForm();
        $demog->addElements(array(
            new Zend_Form_Element_Text('givenName', array(
                'required'   => true,
                'label'      => 'Given (First) Name:',
                'validators' => array(
                    array('Regex',
                          false,
                          array('/^[a-z][a-z0-9., \'-]{2,}$/i'))
                ),
				'decorators'	=>	$elementDecorators
            )),
 
            new Zend_Form_Element_Text('familyName', array(
                'required'   => true,
                'label'      => 'Family (Last) Name:',
                'validators' => array(
                    array('Regex',
                          false,
                          array('/^[a-z][a-z0-9., \'-]{2,}$/i'))
                ),
				'decorators'	=>	$elementDecorators
            )),
        ));
		
		$email = $this->createElement('text', 'email');
		$email	->setLabel('Email:')
				->setRequired(true)
				//->addErrorMessage($txt['email_empty'])
				->addValidator(new Zend_Validate_EmailAddress(array(
																'allow' => Zend_Validate_Hostname::ALLOW_DNS,
																'mx'    => true)));
		$demog->addElement($email);
		$email->setDecorators($elementDecorators);
																
		$tablePays = new Application_Model_PaysMapper();
		$listePays = $tablePays->fetchAll();
		$pays = new Zend_Form_Element_Select('pays');
		$pays	->setLabel('Your country:')
				->setRequired(true);
		foreach ($listePays as $p) {
			$pays->addMultiOption($p->getId(), $p->getFr());
		}
		$demog->addElement($pays);
		$pays->setDecorators($elementDecorators);
		
 
        // Create accounting categories sub form
		$list = new Zend_Form_SubForm();
		
		$tablePostes = new Application_Model_PostesMapper();
		$listePostes = $tablePostes->fetchAll();
		
		$elementPostesDecorators = array(
							array('ViewHelper'),
                           	array('Errors'),
						   	array('label'),
						   	array(array('1er'=>'HtmlTag'), array('tag'=>'div', 'class'=>'postesCheckList')),
						   );
		
		foreach ($listePostes as $p) {
			$listOptions[$p->getId()] = $txt[$p->getNom()];
		}
		
		$list->addElements(array(
            new Zend_Form_Element_MultiCheckbox('posteCheckBox', array(
                'label'        	=>	'',
                'multiOptions' 	=> 	$listOptions,
                'required'     	=> 	true,
				'checked'		=> 	true,
                'filters'      	=> 	array('StringTrim'),
                'validators'   	=> 	array(
                    array('InArray',
                          false,
                          array(array_keys($listOptions)))
                )
            )),
        ));
		$list->getElement('posteCheckBox')->setSeparator('</div><div class="postesCheckList">');
		$list->getElement('posteCheckBox')->setDecorators($elementPostesDecorators);			   
 
        // Attach sub forms to main form
        $this->addSubForms(array(
            'user'  => $user,
            'demog' => $demog,
            'list' => $list
        ));
    }
	
	/**
     * Prepare a sub form for display
     *
     * @param  string|Zend_Form_SubForm $spec
     * @return Zend_Form_SubForm
     */
    public function prepareSubForm($spec)
    {
        if (is_string($spec)) {
            $subForm = $this->{$spec};
        } elseif ($spec instanceof Zend_Form_SubForm) {
            $subForm = $spec;
        } else {
            throw new Exception('Invalid argument passed to ' .
                                __FUNCTION__ . '()');
        }
        $this->setSubFormDecorators($subForm)
             ->addSubmitButton($subForm)
             ->addSubFormActions($subForm);
        return $subForm;
    }
 
    /**
     * Add form decorators to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function setSubFormDecorators(Zend_Form_SubForm $subForm)
    {
        $formDecorators = array(	'FormElements',
                        			array(	'decorator'=>array('1er'=>'HtmlTag'),
											'options'=>array('tag'=>'table', 'class'=>'contact')),
									'Form');
						   
		$subForm->setDecorators($formDecorators);
        return $this;
    }
 
    /**
     * Add a submit button to an individual sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubmitButton(Zend_Form_SubForm $subForm)
    {
        $submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'send', 
																																'value'=>'Suivant >>')));
		$send = $subForm->createElement('submit', 'send');
		$send->setRequired(true);
		$subForm->addElement($send);
		$send->setDecorators($submitDecorators);
		
        return $this;
    }
 
    /**
     * Add action and method to sub form
     *
     * @param  Zend_Form_SubForm $subForm
     * @return My_Form_Registration
     */
    public function addSubFormActions(Zend_Form_SubForm $subForm)
    {
        $subForm->setAction('/registration/process')
                ->setMethod('post');
        return $this;
    }
}

