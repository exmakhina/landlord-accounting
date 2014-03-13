<?php

class Application_Model_Clients
{
	protected $_id;		
	protected $_nom;			
	protected $_email;		
	protected $_adresse;		
	protected $_pays;		
	protected $_tel;	
	protected $_checkin;
	protected $_checkout;		
	protected $_nb_nuits;	
	protected $_px_nuit;		
	protected $_px_sejour;	
	protected $_px_accpte;
	protected $_dt_accpte;
	protected $_site;		
	protected $_nb_guests;	
	protected $_appt;
	protected $_paid;
	protected $_user;
	
	public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Clients property');
        }
        $this->method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Clients property');
        }
        return $this->method();
	}
	
	public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->method($value);
            }
        }
        return $this;
    }
	
	public function getId()
	{
		return $this->_id;
	}		
	
	public function getNom()
	{
		return $this->_nom;
	}			
	
	public function getEmail()
	{
		return $this->_email;
	}		
	
	public function getAdresse()
	{
		return $this->_adresse;
	}		
	
	public function getPays()
	{
		return $this->_pays;
	}		
	
	public function getTel()
	{
		return $this->_tel;
	}
	
	public function getCheckin()
	{
		return $this->_checkin;
	}	
	
	public function getCheckout()
	{
		return $this->_checkout;
	}			
	
	public function getNb_nuits()
	{
		return $this->_nb_nuits;
	}	
	
	public function getPx_nuit()
	{
		return $this->_px_nuit;
	}		
	
	public function getPx_sejour()
	{
		return $this->_px_sejour;
	}
	
	public function getPx_accpte()
	{
		return $this->_px_accpte;
	}
	
	public function getDt_accpte()
	{
		return $this->_dt_accpte;
	}		
	
	public function getSite()
	{
		return $this->_site;
	}		
	
	public function getNb_guests()
	{
		return $this->_nb_guests;
	}	
	
	public function getAppt()
	{
		return $this->_appt;
	}
	
	
	public function setId($id)
	{
		$this->_id = $id;
		return $this;
	}		
	
	public function setNom($nom)
	{
		$this->_nom = $nom;
		return $this;
	}			
	
	public function setEmail($email)
	{
		$this->_email = $email;
		return $this;
	}		
	
	public function setAdresse($adresse)
	{
		$this->_adresse = $adresse;
		return $this;
	}		
	
	public function setPays($pays)
	{
		$this->_pays = $pays;
		return $this;
	}		
	
	public function setTel($tel)
	{
		$this->_tel = $tel;
		return $this;
	}			
	
	public function setCheckin($checkin)
	{
		$this->_checkin = $checkin;
		return $this;
	}	
	
	public function setCheckout($checkout)
	{
		$this->_checkout = $checkout;
		return $this;
	}	
	
	public function setNb_nuits($nb_nuits)
	{
		$this->_nb_nuits = $nb_nuits;
		return $this;
	}	
	
	public function setPx_nuit($px_nuit)
	{
		$this->_px_nuit = $px_nuit;
		return $this;
	}		
	
	public function setPx_sejour($px_sejour)
	{
		$this->_px_sejour = $px_sejour;
		return $this;
	}	
	
	public function setPx_accpte($px_accpte)
	{
		$this->_px_accpte = $px_accpte;
		return $this;
	}	
	
	public function setDt_accpte($dt_accpte)
	{
		$this->_dt_accpte = $dt_accpte;
		return $this;
	}	
	
	public function setSite($site)
	{
		$this->_site = $site;
		return $this;
	}		
	
	public function setNb_guests($nb_guests)
	{
		$this->_nb_guests = $nb_guests;
		return $this;
	}	
	
	public function setAppt($appt)
	{
		$this->_appt = $appt;
		return $this;
	}
 
    public function getPaid()
    {
        return $this->_paid;
    }
	
	public function setPaid($paid)
    {
        $this->_paid = $paid;
        return $this;
    }
	
	public function setUser($user)
	{
		$this->_user = $user;
		return $this;
	}
	
	public function getUser()
	{
		return $this->_user;
	}
}

