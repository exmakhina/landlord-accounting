<?php

class Application_Model_Translations
{
	protected $_id;
	protected $_label;
	protected $_en;
	protected $_fr;
	
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
            throw new Exception('Invalid Translations property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Translations property');
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
	
	public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }
 
    public function getLabel()
    {
        return $this->_label;
    }
	
	public function setEn($text)
    {
        $this->_en = $text;
        return $this;
    }
 
    public function getEn()
    {
        return $this->_en;
    }

	public function setFr($text)
    {
        $this->_fr = $text;
        return $this;
    }
 
    public function getFr()
    {
        return $this->_fr;
    }
}

