<?php

class RegistrationController extends Zend_Controller_Action
{

    protected $_form = null;
    protected $_namespace = 'RegistrationController';
    protected $_session = null;

    public function getForm()
    {
        if (null === $this->_form) {
            $this->_form = new Application_Form_Registration();
        }
        return $this->_form;
    }

    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     *
     */
    public function getSessionNamespace()
    {
        if (null === $this->_session) {
            $this->_session =
                new Zend_Session_Namespace($this->_namespace);
        }
 
        return $this->_session;
    }

    /**
     * Get a list of forms already stored in the session
     *
     * @return array
     *
     */
    public function getStoredForms()
    {
        $stored = array();
        foreach ($this->getSessionNamespace() as $key => $value) {
            $stored[] = $key;
        }
 
        return $stored;
    }

    /**
     * Get list of all subforms available
     *
     * @return array
     *
     */
    public function getPotentialForms()
    {
        return array_keys($this->getForm()->getSubForms());
    }

    /**
     * What sub form was submitted?
     *
     * @return false|Zend_Form_SubForm
     *
     */
    public function getCurrentSubForm()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return false;
        }
 
        foreach ($this->getPotentialForms() as $name) {
            if ($data = $request->getPost($name, false)) {
                if (is_array($data)) {
                    return $this->getForm()->getSubForm($name);
                    break;
                }
            }
        }
 
        return false;
    }

    /**
     * Get the next sub form to display
     *
     * @return Zend_Form_SubForm|false
     *
     */
    public function getNextSubForm()
    {
        $storedForms    = $this->getStoredForms();
        $potentialForms = $this->getPotentialForms();
 
        foreach ($potentialForms as $name) {
            if (!in_array($name, $storedForms)) {
                return $this->getForm()->getSubForm($name);
            }
        }
        return false;
    }

    /**
     * Is the sub form valid?
     *
     * @param  Zend_Form_SubForm $subForm
     * @param  array $data
     * @return bool
     *
     */
    public function subFormIsValid(Zend_Form_SubForm $subForm, array $data)
    {
        $name = $subForm->getName();
        if ($subForm->isValid($data)) {
            $this->getSessionNamespace()->$name = $subForm->getValues();
			// check if both password and conf password are the same
			if ($name == "user") {
					if ($data['user']['password1'] != $data['user']['password2']) return false;
				}
            return true;
        }
        return false;
    }

    /**
     * Is the full form valid?
     *
     * @return bool
     *
     */
    public function formIsValid()
    {
        $data = array();
        foreach ($this->getSessionNamespace() as $key => $info) {
            $data[$key] = $info;
        }
 
        return $this->getForm()->isValid($data);
    }

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		// Either re-display the current page, or grab the "next"
        // (first) sub form
		Zend_Session::namespaceUnset($this->_namespace);		// Delete all former forms datas
		
        if (!$form = $this->getCurrentSubForm()) {
            $form = $this->getNextSubForm();
        }
        $this->view->form = $this->getForm()->prepareSubForm($form);
    }

    public function processAction()
    {
        if (!$form = $this->getCurrentSubForm()) {
            return $this->_forward('index');
        }
 
        if (!$this->subFormIsValid($form,
                                   $this->getRequest()->getPost())) {
            $this->view->form = $this->getForm()->prepareSubForm($form);
            return $this->render('index');
        }
 
        if (!$this->formIsValid()) {
            $form = $this->getNextSubForm();
			if ($form) {
            	$this->view->form = $this->getForm()->prepareSubForm($form);
				return $this->render('index');
			}
        }
 
        // Valid form!
        // Render information in a verification page
		$this->view->info = $this->getSessionNamespace(); 	
        $this->render('validation');
    }

    public function saveAction()
    {
        // action body							
		$infos = $this->getSessionNamespace();
		
		// add the new entry in the Users table
		$salt = 'ce8d96d579d389e783f95b3772785783ea1a9854';
		$username = $infos->user['user']['username'];
		$sha1password = sha1($infos->user['user']['password1'].$salt);
		$role = 'administrateur';
		$now = getdate();
		$date = $now['year'].'-'.$now['mon'].'-'.$now['mday'].' '.$now['hours'].'-'.$now['minutes'].'-'.$now['seconds'];
		
		$userDBentry = new Application_Model_Users();
		$userDBentry	->setUsername($username)
								->setPassword($sha1password)
								->setRole($role)
								->setDateCreated($date)
								->setSalt($salt)
								->setFirstname($infos->demog['demog']['givenName'])
								->setLastname($infos->demog['demog']['familyName'])
								->setEmail($infos->demog['demog']['email'])
								->setPays($infos->demog['demog']['pays']);
								
		$usersDBMapper = new Application_Model_UsersMapper();
		$usersDBMapper->save($userDBentry);
		
		// create the Account (i.e - the postes_username table), and fill it out with the required options
		//--
		//-- Table : postes_username
		//--
		$stmt = 		'CREATE TABLE IF NOT EXISTS `postes_'.$username.'` (
  							  `id` int(11) NOT NULL AUTO_INCREMENT,
							  `numero` int(4) NOT NULL,
							  `type` enum(\'ACTIF\',\'PASSIF\',\'CAPITAL\',\'REVENU\',\'DEPENSE\') COLLATE utf8_unicode_ci NOT NULL,
							  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
							  PRIMARY KEY (`id`),
							  UNIQUE KEY `numero` (`numero`)
							) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
		
		$tablePostes = new Application_Model_PostesMapper();
		$p = new Application_Model_Postes();
		
		foreach ($infos->list['list']['posteCheckBox'] as $poste) {
			$tablePostes->find($poste, $p);
			$stmt .= 'INSERT INTO postes_'.$username.' (numero, type, nom) VALUES (\''.$p->getNumero().'\', \''.$p->getType().'\', \''.$p->getNom().'\');';
		}
		
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$dbAdapter->query($stmt);

		$this->view->confirmation = 'Profile saved successfully';
    }
}
