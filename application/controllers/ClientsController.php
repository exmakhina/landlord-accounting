<?php

class ClientsController extends Zend_Controller_Action
{
	private function getNbDayInMonth($date)		// !!! $date must be a DateTime object !!!
	{
		// retrouve le nombre de jours dans le mois pour s'en servire de base dans le % d'occupation
		$tm = $date->format('U');
		return date("t",$tm);
	}
	
	private function getCustOrgStat()
	{
		// -----------------------------------
		// statistiques de provenance
		// -----------------------------------
		$clientMapper = new Application_Model_ClientsMapper();
		$DBdata = $clientMapper->getCount('pays');
		
		$result = '[[\'pays\', \'nb_clients\']';
		$tablePays = new Application_Model_PaysMapper();
		$pays = new Application_Model_Pays();
		foreach($DBdata as $DBelement) {
			$tablePays->find($DBelement['pays'], $pays);
			$result .= ', [\''.$pays->getCode_pays().'\', '.$DBelement['COUNT(*)'].']';
		}
		$result .= ']';
		//print_r($result);
		
		return $result;
	}
	
	private function updateOccupationStat($date, $nbjours, &$occupation)
	{
		$_date = $date->format('Y-m');
		$_nbJoursMois = $this->getNbDayInMonth($date);
		
		if (($date->format('d') + $nbjours) > $_nbJoursMois)  {
			$part = $_nbJoursMois - $date->format('d') + 1;
			$date->modify('+ '.$part.' day');		// mois courant ++
			$occupation[$_date] = $part;
			
			$this->updateOccupationStat($date, $nbjours-$part, $occupation);
		} else {
			$occupation[$_date] = $nbjours;
		}
	}
	
	public function getOccupationStats()
    {
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
		
		$clientMapper = new Application_Model_ClientsMapper();
		$apptsMapper = new Application_Model_ApptsMapper();
		$appts = $apptsMapper->fetchAll();
		$today = new DateTime('now');
		$years = array(	'last_year' => ($today->format('Y')-1),
						'this_year' => $today->format('Y'),
						'next_year' => ($today->format('Y')+1));
		
		// initialize global arrays
		$graphData = array();
		$result = array();
		
		// One graph per appartment
		foreach($appts as $appt) {
			// Fetch occupancy stats for each year and create a
			// per month occupancy statistics structure ($graphData[$appt][$year][$mois] = % occ)
			$apptId = $appt->getId();
			$graphData[$apptId] = array();
			foreach($years as $key=>$year) {
				$from = $year.'-01';	// January
				$to = $year.'-12';		// December
				$bookings = $clientMapper->getBookingsFromTo($apptId, $from, $to);
				
				// browse every past and upcoming resarvations 
				$graphData[$apptId][$year] = array();
				foreach($bookings as $book) {
					$checkin = new DateTime($book['checkin']);
					
					$occupation = array();
					$this->updateOccupationStat($checkin, $book['nb_nuits'], $occupation);
					
					foreach ($occupation as $mois=>$nbJoursParMois) {
						$this_month = new DateTime($mois.'-01');	//$mois is formatted as 'Y-m'
						$month = $this_month->format('m');		// extract the 'm' from a 'Y-m-d' format
						
						if (!in_array($month, $graphData[$apptId])) {
							if (isset($graphData[$apptId][$year][$month])) 
								$graphData[$apptId][$year][$month] += $nbJoursParMois;
							else
								$graphData[$apptId][$year][$month] = $nbJoursParMois;	
						} else {
							$graphData[$apptId][$year][$month] = $nbJoursParMois;
						}
						
						unset($this_month);	
					}
					
					unset($occupation);
					unset($checkin);
				}
				
				unset($bookings);
			}
			
			// Create the resuslt string for one appartment:
			// [[Mois, last_year, this_year, next_year]
			// ,[Janvier, x%, y%, z%]
			// ,[Fevrier, ..........]
			// .....
			// ,[Decembre], ........]]
			$result[$apptId] = '[[\'Mois\'';
			$result[$apptId] .= ', \''.$years['last_year'].'\'';
			$result[$apptId] .= ', \''.$years['this_year'].'\'';
			$result[$apptId] .= ', \''.$years['next_year'].'\'';
			$result[$apptId] .= ']';	
			
			foreach($liste_mois as $numMois=>$mois) {
				$result[$apptId] .= ', [\''.$mois.'\'';
				foreach($years as $key=>$year) {
					$this_month_date = new DateTime($year.'-'.$numMois.'-01');
					$nbjours = $this->getNbDayInMonth($this_month_date);
					if (isset($graphData[$apptId][$year][$numMois])) {
						$result[$apptId] .= ', '.round(100 * $graphData[$apptId][$year][$numMois] / $nbjours);		// affichage %
					} else {
						$result[$apptId] .= ', 0';
					}
					unset($this_month_date);
				}
				$result[$apptId] .= ']';
			}
			
			$result[$apptId] .= ']';
		}
		
		unset($graphData);
		unset($years);
		unset($today);
		unset($clientMapper);
		unset($apptsMapper);
		unset($appts);
		
		// print_r($result);
		return $result;
    }
	
	private function getWebStat()
	{
		// -----------------------------------
		// Sites referents
		// -----------------------------------
		$clientMapper = new Application_Model_ClientsMapper();
		$DBdata = $clientMapper->getCount('site');
		
		$tableSites = new Application_Model_SitesMapper();
		$site = new Application_Model_Sites();
		
		$result = '[[\'Site\', \'nb_clients\']';
		foreach($DBdata as $DBelement) {
			if ($DBelement['site'] == '0') 
				$siteRef = 'Direct';
			else {
				$tableSites->find($DBelement['site'], $site);
				$siteRef = $site->getNom();	
			}
			$result .= ', [\''.$siteRef.'\', '.$DBelement['COUNT(*)'].']';
		}
		$result .= ']';
		//print_r($result);
		
		return $result;
	}
	
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
		$this->view->courant = 'clients';
    }

    public function indexAction()
    {
        $this->checkIdentity();
		
		// action body
    }

    public function listeAction()
    {
        $this->checkIdentity();
		
		// action body
		$orderby = $this->getRequest()->getParam('orderby');
		if (isset($orderby)) 
			$this->view->order = $orderby.' ASC';
		else
			$this->view->order = 'checkin ASC'; 
    }
	
	public function listeclientsAction()
    {
        $this->checkIdentity();
		
		// action body
		$orderby = $this->getRequest()->getParam('orderby');
		if (isset($orderby)) 
			$this->view->order = $orderby.' ASC';
		else
			$this->view->order = 'nom ASC'; 
    }
	
	public function newresaAction()
	{
		$this->checkIdentity();
		
		// action body
		$form = new Application_Form_Newresa();
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
			
				// recupere les infos du formulaire
				$values = $form->getValues();
				
				$clientMapper = new Application_Model_ClientsMapper();
				$client = new Application_Model_Clients();
				
				// calcule le nb de nuits en fonctions des date checkin/checkout
				$checkin = new DateTime($values['dateArr']);
				$checkout = new DateTime($values['dateDep']);
				if ($checkout <= $checkin) {		// checkout date must be AFTER checkin ....
					$values['dateArr'] = '';
					$values['dateDep'] = '';		// erase previous dates...
					$form->populate($values);
					$this->view->form = $form;
				
				} else {
				
					// following should be commented out when iWeb hosting services will upgrade to PHP 5.3
					/*$diff = $checkin->diff($checkout);
					$nb_nuits = $diff->d;*/
					$nb_nuits = round(abs($checkout->format('U') - $checkin->format('U')) / (60*60*24));		// PHP 5.2 OK
					
					// enregistre la reservation dans la base
					$client	->setNom($values['nom'])
							->setEmail($values['email'])
							->setAdresse($values['adresse'])
							->setPays($values['pays'])
							->setTel($values['phone'])
							->setCheckin($values['dateArr'])
							->setCheckout($values['dateDep'])
							->setNb_nuits($nb_nuits)
							->setPx_nuit($values['px_sejour'] / $nb_nuits)
							->setPx_sejour($values['px_sejour'])
							->setPx_accpte($values['px_accpte'])
							->setDt_accpte($values['dateResa'])
							->setSite($values['site'])
							->setNb_guests($values['nb_guests'])
							->setAppt($values['appt'])
							->setPaid(0);
					$clientId = $clientMapper->save($client);		
					
					// sauve operation correspondant au paiment de l'accompte
					// enregistrement de la transaction dans la BD
					$opMapper = new Application_Model_OperationMapper();
					$deMapper = new Application_Model_MouvementMapper();
					$compteMapper = new Application_Model_MouvementMapper();
					
					$op = new Application_Model_Operation();
					$op	->setDate($values['dateResa'])
						->setRef('clt_'.$clientId)
						->setDescription('Accompte Mr ou Mme '.$values['nom']);
					$opId = $opMapper->save($op);
					
					$posteMapper = new Application_Model_PostesMapper();
					$postes = $posteMapper->fetchRange('4010', '4010');
					foreach ($postes as $poste) {}
					
					$de = new Application_Model_Mouvement();
					$de	->setValeur($values['px_accpte'])
						->setPoste($poste->getId())
						->setOperation($opId);
					$deMapper->save($de);
					
					$compte = new Application_Model_Mouvement();
					$compte	->setValeur($values['px_accpte'])
							->setPoste($values['compte'])
							->setOperation($opId);
					$compteMapper->save($compte);
					
					// Envoi un mail pour notifier de la nouvelle reservation
					// 1 - configure the manorhouseporto.com's SMTP server
					$config = array('auth' => 'login', 
									'port' => 587,
									'username' => 'info@manorhouseporto.com', 
									'password' => 'saperpo1');
					$transport = new Zend_Mail_Transport_Smtp('mail.manorhouseporto.com', $config);
					Zend_Mail::setDefaultTransport($transport);
					// 2 - Create the contact email from the form's datas
					$notification = '<body>';
					$notification .= '<h1>* New reservation *</h1>';
					$notification .= '<h2>----------------------------------------</h2>';
					$notification .= '<span>Name: </span>'.$values['nom'].'<br>';
					$notification .= '<span>Appt: </span>'.$values['appt'].'<br>';
					$notification .= '<span>Expected arrival date: </span>'.$values['dateArr'].'<br>';
					$notification .= '<span>Expected departure date: </span>'.$values['dateDep'].'<br>';
					$notification .= '<h2>----------------------------------------</h2>';
					$notification .= '</body>';
					// 3 - Preparation et envoi du mail
					$mail = new Zend_Mail('utf-8');
					$mail->setReplyTo('info@manorhouseporto.com', 'Manor House Porto');
					$mail->addHeader('MIME-Version', '1.0');
					$mail->addHeader('Content-Transfer-Encoding', '8bit');
					$mail->addHeader('X-Mailer:', 'PHP/'.phpversion());
					$mail->setBodyHtml($notification);
					$mail->setFrom('info@manorhouseporto.com', 'Manor House Porto');
					$mail->addTo('thomas.a.grimault@gmail.com', 'Thomas');
					$mail->addTo('manorhouseporto@gmail.com', 'Alexandre');
					$mail->setSubject('Reservation notification - Manor House Porto');
					$mail->send();
							
					// retour au menu principal :
					$this->_helper->redirector('index');
				}
			} else {
				// poulate the form with the received data before re-submitting to the user
				$form->populate($request->getPost());	
			}
		}
		
		$this->view->form = $form;
	}

	public function payAction()
	{
		$this->checkIdentity();
		
		// action body
		$clientId = $this->getRequest()->getParam('client');
		$client = new Application_Model_Clients();
		$clientMapper = new Application_Model_ClientsMapper();
		$clientMapper->find($clientId, $client);
		
		$paymentDate = date_parse($client->getCheckin());		// default payment is made at checkin
		$data = array(	'client'	=> $clientId,
								'date' => $client->getCheckin(), 
								'nom' => $client->getNom(), 
								'px_sejour' => $client->getPx_sejour() - $client->getPx_accpte());
		
		$form = new Application_Form_Pay();
		$form->populate($data);
		
		$request = $this->getRequest();
        if ($request->isPost()) {
			if ($form->isValid($request->getPost())) {
			
				// recupere les infos du formulaire
				$values = $form->getValues();
				
				// enregistrement de la transaction dans la BD
				$opMapper = new Application_Model_OperationMapper();
				$deMapper = new Application_Model_MouvementMapper();
				$compteMapper = new Application_Model_MouvementMapper();
				
				$op = new Application_Model_Operation();
				$op	->setDate($values['date'])
					->setRef('clt_'.$clientId)
					->setDescription('Location Mr ou Mme '.$values['nom']);
				$opId = $opMapper->save($op);
				
				$posteMapper = new Application_Model_PostesMapper();
				$postes = $posteMapper->fetchRange('4010', '4010');
				foreach ($postes as $poste) {}
				
				$de = new Application_Model_Mouvement();
				$de	->setValeur($values['px_sejour'])
					->setPoste($poste->getId())
					->setOperation($opId);
				$deMapper->save($de);
					
				$compte = new Application_Model_Mouvement();
				$compte	->setValeur($values['px_sejour'])
						->setPoste($values['compte'])
						->setOperation($opId);
				$compteMapper->save($compte);
				
				// actualise le statut du paiement dans la table 'client'
				$clientMapper->find($values['client'], $client);
				$client->setPaid(1);
				$clientMapper->save($client);
				
				// retour a la liste des reservations :
				$this->_helper->redirector('liste');
			}
		}
		$this->view->form = $form;
	}
	
	public function confdeleteAction()
    {
		$this->checkIdentity();
		
		// action body
		$ClientId = $this->getRequest()->getParam('id');
		
		$this->view->ClientId = $ClientId;    
    }
	
	public function deleteAction()
    {
        $this->checkIdentity();
		
		// action body
		$ClientId = $this->getRequest()->getParam('id');
		$clientMapper = new Application_Model_ClientsMapper();
		$clientMapper->delete($ClientId);
		
		// retour a la liste des reservations :
		$this->_helper->redirector('liste');
    }
	
	public function frommapAction()
	{
		$this->checkIdentity();
		// Compile the client origin for the google map applet
		$this->view->datatableGeo = $this->getCustOrgStat();
	}
	
	public function tauxoccupAction()
	{
		$this->checkIdentity();
		// TODO: replace static display by a fully customizable stuff
		$this->view->datatableOcc = $this->getOccupationStats();
	}
	
	public function fromsitesAction()
	{
		$this->checkIdentity();
		// compile the reservation web sites stats for the google charts
		$this->view->datatableRef = $this->getWebStat();
	}
	
	public function advancedstatsAction()
	{
		$this->checkIdentity();
		// TODO: Resercation dates, yera by year stats, by contry, by appt, etc...
	}
	
	public function statsmenuAction()
	{
		$this->checkIdentity();
		
		// nothing to do here... just display a menu
	}
}







