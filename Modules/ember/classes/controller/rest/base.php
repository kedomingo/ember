<?php

namespace Ember;


// Ride with the tide. Load a bootstrap file along with this
// surely-loaded-everytime class
require_once realpath(dirname(__FILE__).'/../../../bootstrap.php');



/**
 * Rest Controller
 */
abstract class Controller_Rest_Base extends \Controller_Rest {
    
    /**
     * Response messages
     */
    protected static $_responses;
    
    /*
     * Response codes
     */
    const RESPONSE_CODE_SUCCESS         = 0;
    const RESPONSE_CODE_TOKEN_FAIL      = 101;
    const RESPONSE_CODE_LOGIN_FAILED    = 200;
    const RESPONSE_CODE_BAD_REQUEST     = 400;
    const RESPONSE_CODE_NOT_FOUND       = 404;
    const RESPONSE_CODE_INTERNAL_ERROR  = 500;
    const RESPONSE_CODE_DB_ERROR        = 1000;
    const RESPONSE_CODE_METHOD_NOT_ALLOWED        = 1100;
    
    
    /**
     * Forces calling of MUST IMPLEMENT methods.
     */
    public function before()
    {
        parent::before();
        $this->_load_configs();
        $this->_load_packages();
        set_exception_handler(array($this, 'handle_exception'));
        
        
        static::$_responses[ self::RESPONSE_CODE_SUCCESS ]             = ___('Success');
        static::$_responses[ self::RESPONSE_CODE_TOKEN_FAIL ]          = ___('Token Failed');
        static::$_responses[ self::RESPONSE_CODE_LOGIN_FAILED ]        = ___('Login Failed');
        static::$_responses[ self::RESPONSE_CODE_BAD_REQUEST ]         = ___('Bad Request');
        static::$_responses[ self::RESPONSE_CODE_NOT_FOUND ]           = ___('Data not found');
        static::$_responses[ self::RESPONSE_CODE_INTERNAL_ERROR ]      = ___('Internal error');
        static::$_responses[ self::RESPONSE_CODE_DB_ERROR ]            = ___('Database error');
        static::$_responses[ self::RESPONSE_CODE_METHOD_NOT_ALLOWED ]  = ___('Incorrect request method');

        
    }
    
    /**
     * Caches the exception to be displayed on the requested format as well.
     * Instead of throwing an html page by default, the response is formatted
     * to json or xml, depending on the user's request
     * @param \Exception $e The exception caught
     */
    public function handle_exception($e) {
        echo $this->_response(self::RESPONSE_CODE_INTERNAL_ERROR, null, null, $e->getMessage());
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
     * Callable with no parameters if apiResponse is set.
     * 
     * @param int $statusCode See constants Controller_Rest_Base::RESPONSE_CODE_*
     * @param mixed $output Response output structure
     * @param string $custom_key If another key is preferred instead of message or data
     * @param type $statusMessage If a custom status message needs to be included along with the status code, set this
     */
    protected function _response($statusCode = null, $output = null, $custom_key = null, $statusMessage = '') {
        
        if (!empty($this->apiResponse)) {
            $this->set(array(
                'response' => array('status' => $this->apiResponse),
                '_serialize' => array('response')
            ));
        } else {
			
            $message = $output;

            if (empty($output)) {
                $key = 'message';
            }
            else {
                $key = is_string($custom_key) ? $custom_key : 'data';
            }

            if (empty($message)) {
                $message = !empty(self::$_responses[$statusCode]) ? self::$_responses[$statusCode] : '';
            }

            // Status Code and Message
            $status = array('code' => $statusCode);
            if ($statusMessage or isset(Controller_Rest_Base::$_responses[$statusCode])) {
                $status['message'] = !empty($statusMessage) ? $statusMessage : Controller_Rest_Base::$_responses[$statusCode];
            }
            $response = compact('status');

            if (!empty($output)) {
                $response[$key] = $output;
            }

            return $this->response(array('response' => $response));
        }
    }
    
}