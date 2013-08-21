<?php

namespace Emberadmin;

class Controller_Users extends Controller_Base {
    
    /**
     * The group names that should be the same as in the name field
     * of the auth groups table (in this case users_groups)
     */
    const GROUP_ADMINISTRATORS  = 'Administrators';
    const GROUP_CUSTOMERS       = 'Customers';
    
    
    
    
    function action_login($redirect_hash = null, $force_login = false) {
        
        if (!$this->is_admin()) {
            $this->switch_theme('PopBootstrap');
        }
        
        if (\Input::method() === 'POST' or $force_login)
		{
            if (\Input::post('mode') === 'login' or $force_login) {
                
                if (\Auth::login()) {
                    
                    $id = \Auth::get_user_id();
                    $user = \Ember\Model_User::find($id[1]);
                    
                    // For verified users only
                    if ($user->verified) {
                        
                        // Store user in session
                        \Session::set('logged_user', $user->id);

                        if (\Auth::check())
                        {
                            $this->current_user = $user;

                            // Upon login, renew ACL rights
                            $this->clear_auth_cache();

                        }

                        // Redirect
                        if ($this->is_admin()) {
                            $url = $this->_admin_dashboard();
                        }
                        else {
                            $url = $this->_frontend_dashboard();
                        }
                        if (!empty($redirect_hash)) {

                            $redirect_url = $this->_unhash_url($redirect_hash);
                            if (!empty($redirect_url)) {
                                $url = $redirect_url;
                            }
                        }
                        \Response::redirect($url);
                    }
                    else {
                        \Session::set_flash('error', __('Your account is not yet verified through email'));
                    }
                }
                else {
                    \Session::set_flash('error', __('Login Failed'));
                }
            }
            if (\Input::post('mode') === 'register') {
                
                if ($userid = $this->__register(\Input::post(), self::GROUP_CUSTOMERS)) {
                    
                    // Generate a nonce key for account activation
                    $user = \Ember\Model_User::find($userid);
                    $user->nonce = sha1(\Config::get('SALT').$user->id);
                    $user->save();

                    $result = \Ember\Events::trigger(\Ember\Events::EVENT_USER_REGISTRATION_SUCCESS, $userid);
                    
                    // Expecting the first event handler to return boolean true
                    if (reset($result) === true) {
                        \Session::set_flash('success', __('A confirmation link has been sent to your email address'));
                    }
                    else {
                        \Session::set_flash('error', 'Fatal: Our system cannot send the confirmation email');
                    }
                }
            }
        }
    }
    
    /**
     * Performs email verification for newly registered users
     * @param type $userid
     * @param type $hash
     * @return type
     */
    function action_verify($userid, $hash) {
        
        $user = \Ember\Model_User::find($userid);
        
        if (!$user) {
            \Session::set_flash('error', 'Verification failed. User not found');
        }
        elseif ($hash != sha1(\Config::get('SALT').$userid)) {
            \Session::set_flash('error', 'Verification failed. Hash mismatch');
        }
        else {
            $user->verified = true;
            if ($user->save()) {
                \Session::set_flash('success', 'You can now log in using your account');
            }
            else {
                \Session::set_flash('error', 'Verification failed. Database error');
            }
        }
        return \Response::redirect('/pop/account/login');
        
    }
    
    /**
     * Sign up method
     * @param array $data
     */
    private function __register($data, $group) {
        
        // Check if existing
        $user = \Ember\Model_User::find('first', array(
            'where' => array(
                'username'   => $data['username'],
            )
        ));

        // user is existing
        if ($user) {
            \Session::set_flash('error', __('The email address you entered is already registered'));
            return false;
        }
        
        // Get the group object
        $_g = \Auth\Model\Auth_Group::find('first', array('where' => array('name' => $group)));
        if (!$_g)
            throw new \Exception('Fatal: could not find group '.$group);

        // Save the user in the DB
        return \Auth::create_user($data['username'], $data['password'], $data['username'], $_g->id);

    }
    
    function action_logout() {
        
        \Auth::logout();
        \Session::destroy();

        // Redirect
        if ($this->is_admin()) {
            $url = $this->_admin_login();
        }
        else {
            $url = $this->_frontend_login();
        }
        if (!empty($redirect_hash)) {

            $redirect_url = $this->_unhash_url($redirect_hash);
            if (!empty($redirect_url)) {
                $url = $redirect_url;
            }
        }
        \Response::redirect($url);

    }
    
    
    function action_index() {
        
        $this->set(array(
            'users' => \Ember\Model_User::find('all')
        ));
        
    }
    
    function action_view($id) {
        
        $user = \Ember\Model_User::find($id);
        if (!$user)
            throw new \Exception(sprintf(__('Unable to find user with id %d'), $id));
        
        $_groups = \Auth::groups();
        $groups = array('' => __('Select One'));
        foreach ($_groups as $group) {
            $groups[$group->id] = $group->name;
        }
        
        $this->set(array(
            'user' => $user,
            'groups' => $groups,
        ));
        
    }
    
}