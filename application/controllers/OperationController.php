<?php

class OperationController extends Zend_Controller_Action
{
	protected $_form = null;
	
	private function checkIdentity()
    {
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$this->_helper->redirector('Index', 'Auth'); // back to login page
		}
    }
	
	private function checksum($balance, Application_Model_Mouvement $mv) 
	{	
		if ($mv == NULL) return;
		
		$newBalance = 0.00;
		
		$posteMapper = new Application_Model_PostesMapper();	
		$poste = new Application_Model_Postes();
		$posteMapper->find($mv->getPoste(), $poste);
		
		$mvType = $poste->getType();
		
		// ACTIF et DEPENSE augmentent au debit
		if (($mvType == 'ACTIF')  || ($mvType == 'DEPENSE')) $newBalance = $balance + $mv->getValeur();
		// PASSIF, CAPITAL et RECETTE augmentent au credit
		if (($mvType == 'PASSIF') || ($mvType == 'CAPITAL') || ($mvType == 'REVENU')) $newBalance = $balance - $mv->getValeur();
		
		return $newBalance;
	}
	
	public function init()
    {
        /* Initialize action controller here */
		$this->view->courant = 'operation';
    }
	
	public function indexAction() 
	{
		$this->checkIdentity();
		
		// action body
	}
	
	public function processrequestAction()
	{
		$this->checkIdentity();
		
		$request = $this->getRequest();
		if ($request->isPost()) 
		{
			$values = $request->getPost();
			$form = new Application_Form_NewOp($values['rangeMin'], $values['rangeMax']);
			$form->populate($values);			// recreate the form object to validate its content
			if ($form->isValid($values)) 
			{
				$filter = new Zend_Filter_LocalizedToNormalized();
				
				$newOp = new Application_Model_Operation();
				$newOp	->setDate($values['date'])
								->setDescription($values['description'])
								->setRef($values['ref']);
				$opMapper = new Application_Model_OperationMapper();
				$idOp = $opMapper->save($newOp);
								
				$newMv = new Application_Model_Mouvement();
				$newMv->setValeur(-$filter->filter($values['valeur']))
							->setPoste($values['mouv_de'])
							->setOperation($idOp);
				$mvMapper = new Application_Model_MouvementMapper();
				$mvMapper->save($newMv);
							
				$newMv1 = new Application_Model_Mouvement();
				$newMv1	->setValeur($filter->filter($values['valeur']))
								->setPoste($values['mouv_vers'])
								->setOperation($idOp);
				$mvMapper1 = new Application_Model_MouvementMapper();
				$mvMapper1->save($newMv1);
				
				// retour au menu principal :
				$this->_helper->redirector('index', 'operation');
			}
			$this->view->form = $form;
			$this->render('newop');
		}
	}

    public function newcustomAction()
    {
        $this->checkIdentity();
		
		// action body
		$this->_form = new Application_Form_NewOperation();
		
		$balanceCheckSum = 0.00;
		
        $request = $this->getRequest();
		if ($request->isPost()) {
			if ($this->_form->isValid($request->getPost())) {
				// recupere les donnees du formulaire
				$values = $this->_form->getValues();
				
				// Initialise le filtre
				$filter = new Zend_Filter_LocalizedToNormalized();

				$newOp = new Application_Model_Operation();
				$newOp	->setDate($values['date'])
								->setDescription($values['description'])
								->setRef($values['ref']);
				
				$posteMapper = new Application_Model_PostesMapper();	
				$poste = new Application_Model_Postes();
				$posteMapper->find($values['mouv_de'], $poste);
				$newMvType = $poste->getType();		// determine la nature du poste source (A, P, C, R ou D)
				$posteMapper->find($values['mouv_vers1'], $poste);
				$newMv1Type = $poste->getType();	// determine la nature du poste de destination (A, P, C, R ou D)
				
				$newMv = new Application_Model_Mouvement();
				if (($newMvType == 'ACTIF')  && (($newMv1Type == 'DEPENSE') || ($newMv1Type == 'ACTIF')))
				{
					// Si le poste source est de l'actif (compte) et sert a financer un achat d'actif ou une depense
					// la valeur du compte source diminue, d'ou le '-'
					$newMv->setValeur(-$filter->filter($values['valeur']));
				} 
				else
				{
					// sinon, on garde les valeurs en l'etat
					$newMv->setValeur($filter->filter($values['valeur']));
				}
				$newMv->setPoste($values['mouv_de']);
						
				$balanceCheckSum = $this->checksum($balanceCheckSum, $newMv);
			
				$newMv1 = new Application_Model_Mouvement();
				$newMv1	->setValeur($filter->filter($values['valeur']))
						//->setValeur($filter->filter($values['valeur1']))
						->setPoste($values['mouv_vers1']);
						
				$balanceCheckSum = $this->checksum($balanceCheckSum, $newMv1);
				
				// verifie la balance (>=0.001 pour compenser erreurs de calculs php ...)
				if (($balanceCheckSum >= 0.001) || ($balanceCheckSum <= -0.001)) {
					// ca ne balance pas ... renvoie au formulaire
					$this->_helper->redirector('erreurbalance', 'operation');	
				} 
				
				// si ca balance, on sauvegarde les infos ....
				$opMapper = new Application_Model_OperationMapper();
				$idOp = $opMapper->save($newOp);
				
				$mvMapper = new Application_Model_MouvementMapper();
				$newMv->setOperation($idOp);
				$mvMapper->save($newMv);
							
				$mvMapper1 = new Application_Model_MouvementMapper();
				$newMv1->setOperation($idOp);
				$mvMapper1->save($newMv1);
				
				// retour au menu principal :
				$this->_helper->redirector('index', 'operation');
            }
        }
		
		$this->view->form = $this->_form;	
    }

    public function erreurbalanceAction()
    {
        $this->checkIdentity();
		// action body 
    }

	public function newentretienAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5000, 5099);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newofficeexpAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5100, 5199);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newfraisdepAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5200, 5299);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newofficestuffAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5300, 5399);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newproffeeAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5400, 5499);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newpubAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5500, 5599);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newinvitAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5600, 5699);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newfinancialfeeAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(5700, 5999);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
	
	public function newequipAction()
	{
		$this->checkIdentity();
		// action body
		$this->_form = new Application_Form_NewOp(1700, 1710);
		$this->view->form = $this->_form;
		$this->render('newop');	
	}
}



