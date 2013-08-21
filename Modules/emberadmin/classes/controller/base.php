<?php

namespace Emberadmin;

class Controller_Base extends \Ember\Controller_Auth {
    
    /**
     * Implementation of Ember\Controller_Base::_load_configs
     */
    protected function _load_configs() {
        
    }
    
    /**
     * Implementation of Ember\Controller_Base::_load_packages
     */
    protected function _load_packages() {
        
    }
    
    /**
     * Implementation of Ember\Controller_Theme::_set_theme
     */
    protected function _set_theme()
    {
        $this->switch_theme('Ember');
        $this->theme->set_template('views/layouts/admin');
    }
    
    /**
     * Implementation of Ember\Controller_Auth::is_admin
     */
    public function is_admin()
    {
        return true;
    }
    /**
     * Implementation of Ember\Controller_Auth::_admin_dashboard
     */
    protected function _admin_dashboard()
    {
        return '*/*';
    }
    
    /**
     * Implementation of Ember\Controller_Auth::_admin_login
     */
    protected function _admin_login()
    {
        return '*/*/users/login';
    }
    
    /**
     * Implementation of Ember\Controller_Auth::_frontend_dashboard
     */
    protected function _frontend_dashboard()
    {
    }
    
    /**
     * Implementation of Ember\Controller_Auth::_frontend_login
     */
    protected function _frontend_login()
    {
    }
    
}