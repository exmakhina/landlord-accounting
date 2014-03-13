<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract 
{
    public function loggedInAs ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
			$home = $this->view->url(array('controller'=>'index', 'action'=>'index'));
            $username = $auth->getIdentity()->username;
            $logoutUrl = $this->view->url(array('controller'=>'auth',
                'action'=>'logout'), null, true);
            return '<a class="user_welcome" href="'.$home.'">Bienvenue ' . $username .  '</a><li><a href="'.$logoutUrl.'">Logout</a></li>';
        } 
		
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
        return '<li><a href="'.$loginUrl.'">Login</a></li>';
    }
}