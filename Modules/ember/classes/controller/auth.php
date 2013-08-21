<?php

namespace Ember;

/**
 * Provides authentication to subclasses
 */
abstract class Controller_Auth extends Controller_Base implements Interface_Authentication {
    
    /**
     * The user currently logged in. This is also available in the
     * views as $current_user
     * @var \Core\Model_User 
     */
    public $current_user;
    
    /**
     * Starts the authentication routine
     */
    public function before() {
        
        parent::before();
        
        /*
         * LOAD CORE PACKAGES
         */
        \Package::load('auth');
        
        $this->authenticate();
        
    }
    
    /**
     * Implementation of Interface_Authentication
     */
    public function authenticate() {
        
        $request = \Request::active();
		if (\Auth::check())
		{
            $this->current_user = Model_User::find(\Session::get('logged_user'));
            
            // Panic: Auth::check passed but current_user is empty ?
            if (empty($this->current_user)) {
                \Auth::logout();
                $this->authorize( in_array($request->action, array('logout', 'login', 'verify')) );
            }
            
            //
            // AUTH ACL
            //
            if ($request->action != 'logout' and $request->action != 'login' and $request->action != 'verify') {
                
                $this->set('current_user', $this->current_user);
		
                $controller = preg_replace('/^[^\\\\]+\\\\/', '', $request->controller);
                
                // Check if superuser
                $su = false;
                $su_role = \Config::get('admin_role');
                if (empty($su_role))
                    throw new \Exception('Admin role is not defined. Please make sure that you have set the key \'admin_role\' in your configuration');
                
                foreach ($this->current_user->group->roles as $role) {
                    if ($role->name === $su_role) {
                        $su = true;
                        break;
                    }
                }
                // Skip this if superuser
                if (!$su) {
                    $this->authorize(
                        \Auth::acl('Ormacl')->has_access(
                            array(
                                $request->module, 
                                $controller.'['.$request->action.']'
                            ), 
                            array('Ormgroup', $this->current_user->group)
                    ));
                }
            }
        }
        else {
            // Not logged in
            // whitelist logout and login
            $this->authorize( in_array($request->action, array('logout', 'login', 'verify')) );
        }
    }
    
    
    
    /**
     * Implementation of Interface_Authenticate::authorize
     * 
     * Override this on a per-controller basis for custom action on
     * unauthorized access
     * @param bool $allowed Set to true when ACL allows the user on the current module/controller/action
     * @throws \Exception
     */
    public function authorize($allowed) {
        
        $request = \Request::active();
        $controller = str_replace(__NAMESPACE__.'\\', '', $request->controller);
        
        // Access not allowed
        if (!$allowed) {
            
            // Set flash
            \Session::set_flash('error', sprintf(___('You are not allowed to access this page: %s'), $request->module.'.'.$controller.'['.$request->action.']'));
            
            if ($this->is_admin()) {
                
                // If logged in, force logout
                if (\Auth::check()) {
                    \Auth::logout();
                }
                \Response::redirect($this->_admin_login());
                
            }
            else {
                
                // If logged in, go to dashboard
                if (\Auth::check()) {
                    \Auth::logout();
                }
                \Response::redirect($this->_frontend_login());
            }
        }
    }
    
    /**
     * Implementation of Interface_Authentication::clear_auth_cache
     * 
     * Clears the Orm ACL cache
     */
    public function clear_auth_cache() {
        
        // flush all the cached groups
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.groups');

        // flush all the cached roles
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.roles');

        // flush all the cached permissions
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');

        // flush the permissions of the current logged in user
        if (!empty($this->current_user) and !empty($this->current_user->id)) {
            $this->_clear_auth_cache_for_user($this->current_user->id);
        }
    }
    
    /**
     * Provides a way of clearing the authentication cache on a per-user level
     */
    protected function _clear_auth_cache_for_user( $userid ) {
        \Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.permissions.user_'.$userid);
    }
    
    /**
     * Provides a way to know if the current location is
     * an admin-side location
     */
    abstract public function is_admin();
    
    /**
     * Provides a way of redirecting to the admin default page (if logged in)
     * Should return the redirect URL to the admin dashboard
     */
    abstract protected function _admin_dashboard();
    
    /**
     * Provides a way of redirecting to the admin login page
     * Should return the redirect URL to the admin login page
     */
    abstract protected function _admin_login();
    
    /**
     * Provides a way of redirecting to the frontend default page (if logged in)
     * Should return the redirect URL to the frontend dashboard
     */
    abstract protected function _frontend_dashboard();
    
    /**
     * Provides a way of redirecting to the frontend login page
     * Should return the redirect URL to the frontend login page
     */
    abstract protected function _frontend_login();
    
}