<?php

namespace Ember;

/**
 * Themed controller. provides common functionalities across modules in this app
 */
abstract class Controller_Base extends Controller_Theme {

    /**
     * Forces calling of MUST IMPLEMENT methods.
     */
    public function before()
    {
        parent::before();
        
        /*
         * Load core settings and credentials
         */
        \Config::load('Core::settings');
        
        /*
         * LOAD CORE PACKAGES
         */
        \Package::load('orm');
        
        $this->_load_configs();
        $this->_load_packages();
    }
    
    /**
     * Forces a uniform way of loading config files
     */
    abstract protected function _load_configs();
    
    /**
     * Forces a uniform way of loading packages
     */
    abstract protected function _load_packages();
    
    /**
     * Hashes the given url using the configured SALT.
     * This is used in obfuscating redirection urls in login pages
     * @param string $url The url that needs to be hashed
     * @return string The hashed URL
     */
    protected function _hash_url($url)
    {  
        $salt = \Config::get('SALT');
        $salt64 = rtrim(base64_encode($salt), '=');
        $dest = base64_encode($url);
        return $salt64.$dest;
    }
    
    
    /**
     * Unhashes the given url using the configured SALT.
     * This is used in obfuscating redirection urls in login pages
     * @param string $hash The string that needs to be converted back to a url
     * @return string The unhashed URL
     */
    protected function _unhash_url($hash)
    {   
        $salt = \Config::get('SALT');
        $salt64 = rtrim(base64_encode($salt), '=');
        $hash = preg_replace('/^'.$salt64.'/', '', $hash);
        return base64_decode($hash);
    } 
    
    /**
     * Does redirection cakephp style
     * @param string|array $url
     */
    public function redirect($url) {
        
//        $request = \Request::active();
//        print_r($request);exit;
//        if (is_string($url)) {
//            if ($url[0] == '/' or preg_match('/^[a-zA-Z0-9]+:\/\//', $url)) {
//                return \Response::redirect($url);
//            }
//            
//            $segments = explode('/', $url);
//            
//            switch (count($segments)) {
//                case 1:
//                    $module     = $request->module;
//                    $controller = preg_replace('/^.*Controller_(.*)$/', '$1', $request->controller);
//                    $action     = array_shift($segments);
//                    $params     = '';
//                    break;
//                
//                case 2:
//                    $module     = $request->module;
//                    $controller = array_shift($segments);
//                    $action     = array_shift($segments);
//                    $params     = '';
//                    break;
//                
//                default:
//                    $module     = array_shift($segments);
//                    $controller = array_shift($segments);
//                    $action     = array_shift($segments);
//                    $params     = implode('/', $segments);
//                    break;
//            }
//            
//            $url = \Uri::current().(!empty($module) ? $module.'/' : '/').$controller.'/'.$action.(!empty($params) ? '/'.$params : '');
//            echo $url;exit;
//        }
        
    }
    
}
