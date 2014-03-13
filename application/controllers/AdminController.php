<?php

class AdminController extends Zend_Controller_Action
{

    private function checkIdentity()
    {
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$this->_helper->redirector('Index', 'Auth'); // back to login page
		}
    }

    public function init()
    {
        /* Initialize action controller here */
		$this->view->courant = 'admin';
    }

    public function indexAction()
    {
        $this->checkIdentity();
		
		// action body
    }

    public function webAction()
    {
        $this->checkIdentity();
		
		// action body
    }

    public function apptsAction()
    {
        $this->checkIdentity();
		
		// action body
    }

    public function newsiteAction()
    {
        $this->checkIdentity();
		
		// action body
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'save', 
																																'value'=>'Enregistrer')));
		$form = new Zend_Form();
		$form	->setName("newsite")
        		->setMethod('post')
				->setAction('/admin/newsite');
				
		$nom = $form->createElement('text', 'nom');
		$nom	->setLabel('Nom : ')
				->setRequired(true);
		$form->addElement($nom);
		
		$lien = $form->createElement('text', 'lien');
		$lien	->setLabel('Adresse : ');
		$form->addElement($lien);
		
		$actif = $form->createElement('checkbox', 'actif');
		$actif	->setLabel('Actif :');
		$form->addElement($actif);
		
		$save = $form->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$form->addElement($save);
		$save->setDecorators($submitDecorators);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				
				// sauvegarde des donnees dans la base
				$siteMapper = new Application_Model_SitesMapper();
				$site = new Application_Model_Sites();
				$site	->setNom($values['nom'])
						->setLien($values['lien'])
						->setActif($values['actif']);
				$siteMapper->save($site);
				
				// retour au menu principal :
				$this->_helper->redirector('web');
			}
		}
		
		$this->view->form = $form;
		$this->render('modifysite');
    }

    public function modifysiteAction()
    {
        $this->checkIdentity();
		
		// action body
		$siteId = $this->getRequest()->getParam('id');
		
		$siteMapper = new Application_Model_SitesMapper();
		$site = new Application_Model_Sites();
		$siteMapper->find($siteId, $site);
		
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'save', 
																																'value'=>'Enregistrer')));
		$form = new Zend_Form();
		$form	->setName("modifysite")
        		->setMethod('post')
				->setAction('/admin/modifysite');
				
		$id = $form->createElement('hidden', 'id');
		$id	->setValue($site->getId());
		$form->addElement($id);
		
		$nom = $form->createElement('text', 'nom');
		$nom	->setLabel('Nom : ')
				->setRequired(true)
				->setValue($site->getNom());
		$form->addElement($nom);
		
		$lien = $form->createElement('text', 'lien');
		$lien	->setLabel('Adresse : ')
				->setValue($site->getLien());
		$form->addElement($lien);
		
		$actif = $form->createElement('checkbox', 'actif');
		$actif	->setLabel('Actif :')
				->setValue($site->getActif());
		$form->addElement($actif);
		
		$save = $form->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$form->addElement($save);
		$save->setDecorators($submitDecorators);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				$site	->setId($values['id'])
						->setNom($values['nom'])
						->setLien($values['lien'])
						->setActif($values['actif']);
				$siteMapper->save($site);
				
				// retour au menu principal :
				$this->_helper->redirector('web');
			}
		}
		
		$this->view->form = $form;
    }

    public function modifyapptAction()
    {
        $this->checkIdentity();
		
		// action body
		$apptId = $this->getRequest()->getParam('id');
		
		$apptsMapper = new Application_Model_ApptsMapper();
		$appt = new Application_Model_Appts();
		$apptsMapper->find($apptId, $appt);
		
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'save', 
																																'value'=>'Enregistrer')));
		$form = new Zend_Form();
		$form	->setName("modifyappt")
        		->setMethod('post')
				->setAction('/admin/modifyappt');
		
		$id = $form->createElement('text', 'id');
		$id	->setLabel('Numero : ')
				->setRequired(true)
				->addValidator(new Zend_Validate_Digits())
				->setValue($appt->getId());
		$form->addElement($id);
		
		$adresse = $form->createElement('textarea', 'adresse');
		$adresse->setLabel('Adresse : ')
				->setAttrib('cols', '40')
    			->setAttrib('rows', '5')
				->setRequired(true)
				->setValue($appt->getAdresse());
		$form->addElement($adresse);
		
		$save = $form->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$form->addElement($save);
		$save->setDecorators($submitDecorators);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				$appt	->setId($values['id'])
							->setAdresse($values['adresse']);
				$apptsMapper->save($appt);
				
				// retour au menu principal :
				$this->_helper->redirector('appts');
			}
		}
		
		$this->view->form = $form;
    }

    public function newapptAction()
    {
        $this->checkIdentity();
		
		// action body
		$submitDecorators = array(	array(	'ViewHelper'=>array(	'1er'=>'HtmlTag'),
																									'options'=>array(	'tag'=>'input', 
																																'class'=>'button save', 
																																'type'=>'submit', 
																																'name'=>'save', 
																																'value'=>'Enregistrer')));
		$form = new Zend_Form();
		$form	->setName("modifyappt")
        		->setMethod('post')
				->setAction('/admin/newappt');
				
		$id = $form->createElement('text', 'id');
		$id	->setLabel('Numero : ')
				->setRequired(true)
				->addValidator(new Zend_Validate_Digits());
		$form->addElement($id);
		
		$adresse = $form->createElement('textarea', 'adresse');
		$adresse->setLabel('Adresse : ')
				->setRequired(true)
				->setAttrib('cols', '40')
    			->setAttrib('rows', '5');
		$form->addElement($adresse);
		
		$save = $form->createElement('submit', 'save'); 
		$save	->setRequired(false)
				->setIgnore(true);
		$form->addElement($save);
		$save->setDecorators($submitDecorators);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				
				// les sauvegarde dans la base
				$apptsMapper = new Application_Model_ApptsMapper();
				$appt = new Application_Model_Appts();
				$appt	->setId($values['id'])
							->setAdresse($values['adresse']);
				$apptsMapper->save($appt);
				
				// retour au menu principal :
				$this->_helper->redirector('appts');
			}
		}
		
		$this->view->form = $form;
		$this->render('modifyappt');
    }

    public function deleteapptAction()
    {
        $this->checkIdentity();
		
		// action body
		$ApptId = $this->getRequest()->getParam('id');
		$ApptMapper = new Application_Model_ApptsMapper();
		$ApptMapper->delete($ApptId);
		
		$this->_helper->redirector('appts');
    }
}
