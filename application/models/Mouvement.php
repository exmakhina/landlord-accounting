<?php

class Application_Model_Mouvement
{
	protected $_id;
	protected $_valeur;
	protected $_poste;
	protected $_operation;
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
            throw new Exception('Invalid mouvement property');
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
	
	public function setValeur($valeur)
    {
        $this->_valeur = (float) $valeur;
        return $this;
    }
 
    public function getValeur()
    {
        return $this->_valeur;
    }
	
	public function setPoste($poste)
    {
        $this->_poste = (int) $poste;
        return $this;
    }
 
    public function getPoste()
    {
        return $this->_poste;
    }
	
	public function setOperation($operation)
    {
        $this->_operation = (int) $operation;
        return $this;
    }
 
    public function getOperation()
    {
        return $this->_operation;
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

