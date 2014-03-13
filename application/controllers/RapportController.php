<?php

class RapportController extends Zend_Controller_Action
{

    private function checkIdentity()
    {
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) 
		{
			$this->_helper->redirector('Index', 'Auth'); // back to login page
		}
    }

    public function init()
    {
        /* Initialize action controller here */
		$this->view->courant = 'rapport';
    }

    public function indexAction()
    {
        $this->checkIdentity();
    }

    public function annuelAction()
    {
        $this->checkIdentity();
		
		// action body
		$form = new Application_Form_Rapportannuel();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				$this->view->annee = $values['annee'];
				$this->render('afficherrapportannuel');
			}
		}
		
		$this->view->form = $form;
    }

    public function mensuelAction()
    {
        $this->checkIdentity();
		
		// action body
		$form = new Application_Form_Rapportmensuel();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				$this->view->annee = $values['annee'];
				$this->view->mois = $values['mois'];
				
				$this->render('afficherrapportmensuel');
			}
		}
		
		$this->view->form = $form;
    }

    public function deleteAction()
    {
        $this->checkIdentity();
		
		// action body
		$OpId = $this->getRequest()->getParam('id');
		$OpMapper = new Application_Model_OperationMapper();
		$OpMapper->delete($OpId);
		
		$this->render('complet');
    }

    public function confdeleteAction()
    {
		$this->checkIdentity();
		
		// action body
		$OpId = $this->getRequest()->getParam('id');
		
		$this->view->OpId = $OpId;    
    }

    public function livresAction()
    {
        $this->checkIdentity();
		
		// action body
		$form = new Application_Form_Selectrapport();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				
				switch ($values['periode']) {
					case '1':
						$this->render('complet');
						break;
					case '2':
						$this->_helper->redirector('annuel', 'rapport');
						break;
					case '3':
						$this->_helper->redirector('mensuel', 'rapport');
						break;
					default:
						$this->_helper->redirector('index', 'rapport');	
				}
			}
		}
		
		$this->view->form = $form;
    }

    public function resultatsAction()
    {
        $this->checkIdentity();
		
		// action body
		$form = new Application_Form_Moisresultat();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
				// recupere les infos du formulaire
				$values = $form->getValues();
				$this->view->annee = $values['annee'];
				$this->render('affresultats');
			}
		}
		
		$this->view->form = $form;
    }

    public function bilanAction()
    {
        $this->checkIdentity();
		
		// action body
    }

	public function projectionAction()
    {
        $this->checkIdentity();
		
		// action body
		$liste_mois = array('01' =>	'Janvier',
							'02' =>	'Fevrier',
							'03' =>	'Mars',
							'04' =>	'Avril',
							'05' =>	'Mai',
							'06' =>	'Juin',
							'07' =>	'Juillet',
							'08' =>	'Aout',
							'09' =>	'Septembre',
							'10' =>	'Octobre',
							'11' =>	'Novembre',
							'12' =>	'Decembre');
		
		$today = new DateTime('now');
		
		$result = '[[\'Mois\'';
		$result .= ', \''.($today->format('Y')-1).'\'';
		$result .= ', \''.$today->format('Y').'\'';
		$result .= ', \''.($today->format('Y')+1).'\'';
		$result .= ']';
		
		$clientMapper = new Application_Model_ClientsMapper();
		
		foreach($liste_mois as $numMois=>$mois) {
			$result .= ', [\''.$mois.'\'';
			$RevenueY2 = $clientMapper->getMonthlyRevenue(($today->format('Y')+1).'-'.$numMois);
			$RevenueY1 = $clientMapper->getMonthlyRevenue($today->format('Y'.'-'.$numMois));
			$RevenueY0 = $clientMapper->getMonthlyRevenue(($today->format('Y')-1).'-'.$numMois);
			$AccY2 = $clientMapper->getMonthlyAcc(($today->format('Y')+1).'-'.$numMois);
			$AccY1 = $clientMapper->getMonthlyAcc($today->format('Y'.'-'.$numMois));
			$AccY0 = $clientMapper->getMonthlyAcc(($today->format('Y')-1).'-'.$numMois);
			// année N-1
			$totalRevenue = 0;
			foreach ($RevenueY0 as $income) $totalRevenue += $income['px_sejour'] - $income['px_accpte'];
			foreach ($AccY0 as $income) $totalRevenue += $income['px_accpte'];
			$result .= ', '.$totalRevenue;
			// année N
			$totalRevenue = 0;
			foreach ($RevenueY1 as $income) $totalRevenue += $income['px_sejour'] - $income['px_accpte'];
			foreach ($AccY1 as $income) $totalRevenue += $income['px_accpte'];
			$result .= ', '.$totalRevenue;
			// année N+1
			$totalRevenue = 0;
			foreach ($RevenueY2 as $income) $totalRevenue += $income['px_sejour'] - $income['px_accpte'];
			foreach ($AccY2 as $income) $totalRevenue += $income['px_accpte'];
			$result .= ', '.$totalRevenue;
			$result .= ']';
		}
		$result .= ']';
		
		$this->view->datatableProj = $result;
		//print_r($result);
    }
}

















