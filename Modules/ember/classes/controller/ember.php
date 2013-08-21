<?php

namespace Ember;

class Controller_Ember extends Controller_Base {
    
    /**
     * Implementation of Ember\Controller_Base::_load_configs
     */
    protected function _load_configs(){}
    
    /**
     * Implementation of Ember\Controller_Base::_load_packages
     */
    protected function _load_packages(){}
    
    /**
     * Implementation of Ember\Controller_Theme::_set_theme
     */
    protected function _set_theme()
    {
        $this->switch_theme('Ember');
    }
    
    public function action_index() {
        
    }
}