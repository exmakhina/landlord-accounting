<?php

class Application_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setName("login");
        $this->setMethod('post');
		$this->setAction('/auth/index');
		
		$formDecorators = array(	'FormElements',
                        			array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'table', 'class' => 'credentials')));
		
		$elementDecorators = array(array('ViewHelper'),
                           array('Errors'),
                           array('decorator'=>array('1er'=>'HtmlTag'),'options'=>array('tag'=>'td')),
                           array('label',array('tag' => 'td')),
                           array('decorator'=>array('2eme'=>'HtmlTag'),'options'=>array('tag'=>'tr')));
        
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'login', 
																																'value'=>'login')),
													array(	'decorator'=>array(	'2eme'=>'HtmlTag'),
																								'options'=>array(	'tag'=>'td', 
																															'class'=>'submit',
																															'colspan'=>'2')),
													array(	'decorator'=>array(	'3eme'=>'HtmlTag'),
																								'options'=>array(	'tag'=>'tr')));
						   
		$username = $this->createElement('text', 'username'); 
		$username	->setRequired(true)
							->addValidator('alnum')
         					->addValidator('regex', false, array('/^[a-z][a-z0-9]{2,}$/'))
							->addValidator('StringLength', false, array(6))
							->setLabel('Username : ');
		$this->addElement($username);
		$username->setDecorators($elementDecorators);


		$password = $this->createElement('password', 'password'); 
		$password	->setRequired(true)
							->addValidator('alnum')
         					->addValidator('regex', false, array('/^[a-z][a-z0-9]{2,}$/'))
							->addValidator('StringLength', false, array(6))
							->setLabel('Password : ');
		$this->addElement($password);
		$password->setDecorators($elementDecorators);
		
		$login = $this->createElement('submit', 'login'); 
		$login	->setRequired(false)
					->setIgnore(true);
		$this->addElement($login);
		$login->setDecorators($submitDecorators);
		
		$this->addDisplayGroup(array('username', 'password', 'login'), 'credentials');
		$credentials = $this->getDisplayGroup('credentials');
		$credentials->setDecorators($formDecorators);
    }
}

