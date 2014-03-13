<?php

class Zend_View_Helper_Language extends Zend_View_Helper_Abstract 
{
	public function language() 
	{
		/*$div_EN = '<div class="lang_en"></div>';*/
		$div_FR = '<div class="lang_fr">';
		
		/*$lang_select = '<a href="'.$this->view->url(array('controller'=>'language', 'action'=>'set', 'lang'=>'en')).'">'.$div_EN.'</a>';*/
		$lang_select = $div_FR.'<a href="'.$this->view->url(array('controller'=>'language', 'action'=>'set', 'lang'=>'fr')).'"></a></div>';
		
		return $lang_select;
	}
}