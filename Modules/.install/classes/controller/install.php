<?php

namespace Install;

class Controller_Install extends \Ember\Controller_Base {
    
    /**
     * Implementation of \Ember\Controller_Base::_load_configs
     */
    protected function _load_configs(){}
    
    /**
     * Implementation of \Ember\Controller_Base::_load_packages
     */
    protected function _load_packages(){}
    
    /**
     * Implementation of \Ember\Controller_Theme::_set_theme
     */
    protected function _set_theme()
    {
        $this->switch_theme('Ember');
    }
    
    public function before()
    {
        parent::before();
        $this->theme->set_template('views/layouts/install');
    }
    
    public function action_index()
    {
    }
    
    public function action_database()
    {
        if (\Input::method() == 'POST') {
            
            $connection_name = 'conn';
            
            $dbconfig = \Input::post();

            if (empty($dbconfig['host'])) {
                $dbconfig['host'] = 'localhost';
            }

            if (empty($dbconfig['password'])) {
                $dbconfig['password'] = '';
            }

            try{
                if ( !$this->test_db($dbconfig, $connection_name) ) {
                    return \Session::set_flash('error', ___('Database configuration is not working'));
                }
            } catch(\Exception $e) {            
                return \Session::set_flash('error', $e->getMessage());
            }

            // WRITE DB CONFIGURATION
            $s = "<?php
    return array(
        'default' => array(
            'connection'  => array(
                'dsn'        => '{$dbconfig['type']}:host={$dbconfig['host']}".(!empty($dbconfig['port']) ? ';port='.$dbconfig['port'] : '').";dbname={$dbconfig['database']}".(!empty($dbconfig['charset']) ? ';charset='.$dbconfig['charset'] : '')."',
                'username'   => '{$dbconfig['username']}',
                'password'   => '{$dbconfig['password']}',
            ),
        ),
        ".(!empty($dbconfig['profiling']) ? "'profiling' => true," : '')."
        ".(!empty($dbconfig['caching']) ? "'caching' => true," : '')."
    );
    ";

            if (!is_writable(APPPATH.'config/'.$dbconfig['environment'].'/db.php')) {
                return \Session::set_flash('error', 'fuel/app/config/'.$dbconfig['environment'].'/db.php file is not writable');
            }
            
            // Make backups
            copy(APPPATH.'config/db.php', APPPATH.'config/db.'.date('Ymd').'.php');
            copy(APPPATH.'config/'.$dbconfig['environment'].'/db.php', APPPATH.'config/'.$dbconfig['environment'].'/db.'.date('Ymd').'.php');
            $f = fopen(APPPATH.'config/db.php', 'w');
            $f2 = fopen(APPPATH.'config/'.$dbconfig['environment'].'/db.php', 'w');
            if ($f and $f2) {
                
                fwrite($f, $s);
                fclose($f);
                
                fwrite($f2, $s);
                fclose($f2);
                
                $exprs = explode(';', file_get_contents(dirname(__FILE__).'/tables.sql'));
                foreach ($exprs as $expr) {
                    $expr = trim($expr);
                    if (!empty($expr)) {
                        \DB::query($expr)->execute($connection_name);
                    }
                }
                
                \Response::redirect('/install/adminuser');
            }
            else {
                \Session::set_flash('error', sprintf(___('Unable to write to database configuration file %s'), APPPATH.'config/db.php'));
            }

        }
    }
    
    public function action_adminuser()
    {
        if (\Input::method() == 'POST') {
            
            $data = \Input::post();
            $error = false;
            if (empty($data['username']) or empty($data['password']) or empty($data['email'])) {
                \Session::set_flash('error', ___('Username, password, and email must not be empty'));
                $error = true;
            }
            if ($data['password'] != $data['password_confirm']) {
                \Session::set_flash('error', ___('Password did not match with confirmation'));
                $error = true;
            }
            
            $admin_group = \Config::get('admin_group');
            
            //
            // Create the group
            //
            if (!$error) {

                // Check if existing
                $group = \Auth\Model\Auth_Group::find('first', array('where' => array('name' => $admin_group)));
                
                if (!$group) {
                    $group = \Auth\Model\Auth_Group::forge(array(
                        'name' => $admin_group
                    ));
                    if (!$group->save()) {
                        $error = true;
                    }
                    else {
                        //
                        // Create role
                        //
                        $admin_role = \Config::get('admin_role');
                        $role = \Auth\Model\Auth_Role::find('first', array('where' => array('name' => $admin_role)));
                
                        if (!$role) {
                            $role = \Auth\Model\Auth_Role::forge(array(
                                'name' => $admin_role
                            ));
                            if (!$role->save()) {
                                $error = true;
                            }
                            else {
                                //
                                // Connect the role to the group
                                //
                                $group->roles[] = $role;
                                $group->save();
                            }
                        }
                    }
                }
            }
            
            
            //
            // Create the user
            //
            if (!$error) {

                // Check if existing
                $user = \Ember\Model_User::find('first', array(
                    'where' => array(
                        'username'   => $data['username'],
                    )
                ));

                // user is not existing
                if (!$user) {
                    
                    // Get the group object
                    $group = \Auth\Model\Auth_Group::find('first', array('where' => array('name' => $admin_group)));
                    if (!$group)
                        throw new \Exception('Fatal: could not find group '.$admin_group);

                    if (!\Auth::create_user($data['username'], $data['password'], $data['email'], $group->id)) {
                        return \Session::set_flash('error', 'Unable to create user '.$data['username']);
                    }
                    else {
                        
                        $user = \Ember\Model_User::find('first', array(
                            'where' => array(
                                'username'   => $data['username'],
                            )
                        ));
                        // Automatically verify
                        $user->verified = 1;
                        $user->save();
                        
                        \Response::redirect('/install/finalize');
                    }
                }
                else {
                    \Session::set_flash('error', 'User '.$data['username'].' already exists. Please check if you can log in using it.');
                    \Response::redirect('/install/finalize');
                }
            }
            
        }
    }
    
    public function action_finalize() {
        
        /* 
         * MORE INSTALLATION ROUTINE HERE
         */
        
        // ...
        
        \Response::redirect('/install/enjoy');
        
    }
    
    
    public function action_enjoy() {
        
        if (\Input::method() == 'POST') {
            $module = realpath(dirname(__FILE__).'/../..');
            if (@rename($module, $module.uniqid())) {
                \Response::redirect('/');
            }
            \Session::set_flash('error', ___('Unable to rename the install Module. Please rename it manually'));
        }
    }
    
    
    /**
     * Tests if the database configuration $config works
     * @param array $config
     * @return bool True if the configuration makes a successful database connection
     * @throws \Exception
     */
    public static function test_db($config, $connection_name = null)
    {   
        if (empty($config['username']))
            throw new \Exception(___('Username can not be empty'));
        
        if (empty($config['database']))
            throw new \Exception(___('Database can not be empty'));
        
        if (empty($config['host'])) {
            $config['host'] = 'localhost';
        }
        
        $connection_name = is_string($connection_name) ? $connection_name : 'test';
        
        $dbcon = \Database_Connection::instance($connection_name, array('type' => $config['type'], 'connection' => $config));
        
        $dbcon = $dbcon->connection();
        
        return !empty($dbcon);
    }
    
}