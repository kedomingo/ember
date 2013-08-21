<?php

namespace Install;

class Controller_Rest_Install extends \Core\Controller_Rest_Base {
    
    /**
     * Implementation of \Core\Controller_Rest_Base::_load_configs
     */
    protected function _load_configs() {
        
    } 
    
    /**
     * Implementation of \Core\Controller_Rest_Base::_load_packages
     */
    protected function _load_packages() {
        
    }
    
    public function post_dbtest() {
        
        $data = \Input::post();
        
        try {
            return $this->_response(Controller_Install::test_db($data) ? self::RESPONSE_CODE_SUCCESS : self::RESPONSE_CODE_INTERNAL_ERROR);
        } catch (\Exception $e) {
            return $this->_response(self::RESPONSE_CODE_INTERNAL_ERROR, null, null, $e->getMessage());
        }
        
        
    }
    
    
    
    
}