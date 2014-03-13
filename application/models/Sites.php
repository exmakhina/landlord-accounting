<?php

class Application_Model_Sites
{
	protected $_id;
	protected $_nom;
	protected $_lien;
	protected $_actif;
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
            throw new Exception('Invalid Sites property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid mouvement property');
        }
        return $this->$method();
	}
	
	public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
	
	public function setId($id)
    {
        $this->_id = (int) $id;
        return $this;
    }
 
    public function getId()
    {
        return $this->_id;
    }
	
	public function setNom($nom)
	{
		$this->_nom = $nom;
		return $this;
	}
	
	public function getNom()
	{
		return $this->_nom;	
	}
	
	public function setLien($lien)
	{
		$this->_lien = $lien;
		return $this;
	}
	
	public function getLien()
	{
		return $this->_lien;	
	}
	
	public function setActif($status)
	{
		$this->_actif = $status;
		return $this;
	}
	
	public function getActif()
	{
		return $this->_actif;	
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

