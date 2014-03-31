<?php

class Application_Model_ClientsMapper
{
	protected $_dbTable;
 	protected $_userID = NULL;
	
	public function __construct() {
		// if no one is logged in postesMapper will map postes_template, otherwise, it will map postes_($user)
		$auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity()->username;
			$userMapper = new Application_Model_UsersMapper();
			$this->_userID	 = $userMapper->getId($user);
        } 	
	}
	
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Clients');
        }
        return $this->_dbTable;
    }
	
	public function save(Application_Model_Clients $client)
    {
        $data = array(
            'nom'		=>	$client->getNom(),
			'email'		=>	$client->getEmail(),
			'adresse'	=>	$client->getAdresse(),
			'pays'		=>	$client->getPays(),
			'tel'		=>	$client->getTel(),
			'checkin'	=> $client->getCheckin(),
			'checkout'	=> $client->getCheckout(),
			'nb_nuits'	=>	$client->getNb_nuits(),
			'px_nuit'	=>	$client->getPx_nuit(),
			'px_sejour'	=>	$client->getPx_sejour(),
			'px_accpte'	=>	$client->getPx_accpte(),
			'dt_accpte'	=>	$client->getDt_accpte(),
			'site'		=>	$client->getSite(),
			'nb_guests'	=>	$client->getNb_guests(),
			'appt'		=>	$client->getAppt(),
			'paid'		=> $client->getPaid(),
			'user'	=> $this->_userID
        );
 
        if (null === ($id = $client->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
			return $id;
        }
    }
	
	public function find($id, Application_Model_Clients $client)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
		if ($row->user != $this->_userID) return;		// prevent an unauthorized user to access someone else's datas
		
        $client	->setId($row->id)
				->setNom($row->nom)
				->setEmail($row->email)
				->setAdresse($row->adresse)
				->setPays($row->pays)
				->setTel($row->tel)
				->setCheckin($row->checkin)
				->setCheckout($row->checkout)
				->setNb_nuits($row->nb_nuits)
				->setPx_nuit($row->px_nuit)
				->setPx_sejour($row->px_sejour)
				->setPx_accpte($row->px_accpte)
				->setSite($row->site)
				->setNb_guests($row->nb_guests)
				->setAppt($row->appt)
				->setPaid($row->paid)
				->setUser($row->user);
    }
 
    public function fetchAll($order)
    {
        $select = $this->getDbTable()	->select()
														->where('user = ?', $this->_userID)
														->order($order);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Clients();
			$entry	->setId($row['id'])
					->setNom($row['nom'])
					->setEmail($row['email'])
					->setAdresse($row['adresse'])
					->setPays($row['pays'])
					->setTel($row['tel'])
					->setCheckin($row['checkin'])
					->setCheckout($row['checkout'])
					->setNb_nuits($row['nb_nuits'])
					->setPx_nuit($row['px_nuit'])
					->setPx_sejour($row['px_sejour'])
					->setPx_accpte($row['px_accpte'])
					->setSite($row['site'])
					->setNb_guests($row['nb_guests'])
					->setAppt($row['appt'])
					->setPaid($row['paid'])
					->setUser($row['user']);
			$entries[] = $entry;
        }
        return $entries;
    }
	
	public function getCount($what)
	{
		$select = $this->getDbTable()	->select()
														->from('clients', array($what, 'COUNT(*)'))
														->group($what)
														->where('user = ?', $this->_userID);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
	
        return $resultSet;	
	}

	public function getBookings()
	{
		$select = $this->getDbTable()	->select()
														->from('clients', array('checkin', 'checkout', 'nb_nuits', 'appt'))
														->order('checkin ASC')
														->where('user = ?', $this->_userID);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
	
        return $resultSet;		
	}
	
	public function getBookingsFromTo($appt, $from, $to) 	// must be in 'Y-m' format
	{
		$select = $this->getDbTable()	->select()
										->from('clients', array('checkin', 'checkout', 'nb_nuits'))
										->order('checkin ASC')
										->where('checkin >= ?', $from.'-01')
										->where('checkin <= ?', $to.'-31')
										->where('appt = ?', $appt)
										->where('user = ?', $this->_userID);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
	
        return $resultSet;	
	}
	
	public function getMonthlyRevenue($month)		// must be in 'Y-m' format
	{
		// get the revenue from rents
		$select = $this->getDbTable()	->select()
														->from('clients', array('px_sejour', 'px_accpte'))
														->where('checkin >= ?', $month.'-01')
														->where('checkin <= ?', $month.'-31')
														->where('user = ?', $this->_userID);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
        return $resultSet;		
	}
	
	public function getMonthlyAcc($month)		// must be in 'Y-m' format
	{
		// get revenue from deposits
		$select = $this->getDbTable()	->select()
														->from('clients', array('px_accpte'))
														->where('dt_accpte >= ?', $month.'-01')
														->where('dt_accpte <= ?', $month.'-31')
														->where('user = ?', $this->_userID);
		//print $select->__toString();
		$stmt = $select->query();
		$resultSet = $stmt->fetchAll();
		
        return $resultSet;		
	}
	
	public function delete($id)
	{
		$where = 'id = '.$id;
		$this->getDbTable()->delete($where);	
	}
}

