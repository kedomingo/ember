<?php

namespace Install;

class Controller_Install extends \Core\Controller_Base {
    
    /**
     * Implementation of Controller_Base::_load_configs
     */
    protected function _load_configs(){}
    
    /**
     * Implementation of Controller_Base::_load_packages
     */
    protected function _load_packages(){}
    
    /**
     * Implementation of \Core\Controller_Theme::_set_theme
     */
    protected function _set_theme()
    {
        $this->switch_theme('Ember');
    }
    
    
    
    public function action_index()
    {
    }
    
    public function action_database()
    {
        
    }
    
    public function post_finalize() {
        
        $dbconfig = \Input::post();
        
//        print_r($dbconfig);exit;
        if (empty($dbconfig['host'])) {
            $dbconfig['host'] = 'localhost';
        }
        
        if (empty($dbconfig['password'])) {
            $dbconfig['password'] = '';
        }
        
        try{
            if ( !$this->test_db($dbconfig) ) {
                \Session::set_flash('error', ___('Database configuration is not working'));
                \Response::redirect('/install/database');
            }
        } catch(\Exception $e) {            
            \Session::set_flash('error', $e->getMessage());
            \Response::redirect('/install/database');
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
            
        copy(APPPATH.'config/db.php', APPPATH.'config/db.'.date('Ymd').'.php');
        $f = fopen(APPPATH.'config/db.php', 'w');
        if ($f) {
            fwrite($f, $s);
            fclose($f);
        }
        else {
            \Session::set_flash('error', sprintf(___('Unable to write to database configuration file %s'), APPPATH.'config/db.php'));
            \Response::redirect('/install/database');
        }
        
        /* 
         * MORE INSTALLATION ROUTINE HERE
         */
        
        // ...
        
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
    public static function test_db($config)
    {   
        if (empty($config['username']))
            throw new \Exception(___('Username can not be empty'));
        
        if (empty($config['database']))
            throw new \Exception(___('Database can not be empty'));
        
        if (empty($config['host'])) {
            $config['host'] = 'localhost';
        }
        
        $dbcon = \Database_Connection::instance('test', array('type' => $config['type'], 'connection' => $config));
        
        $dbcon = $dbcon->connection();
        
        return !empty($dbcon);
    }
    
}
