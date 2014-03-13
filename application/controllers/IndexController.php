<?php

class IndexController extends Zend_Controller_Action
{

    private function checkIdentity()
    {
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$this->_helper->redirector('Index', 'Auth'); // back to login page (Auth Controller, Index Action)
		}
    }

    public function init()
    {
        /* Initialize action controller here */
		$this->view->courant = 'home';
    }

    public function indexAction()
    {
        $this->checkIdentity();
    }

    public function newaccountAction()
    {
        // action body
    }


}


