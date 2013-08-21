<?php

return array (
    
    /**
     * The name of the administrators group. This will be used in creating the
     * admin user and the admin group. The admin user will be under this group
     */
    'admin_group' => 'Administrators',
    
    /**
     * The name of the administrator role. This will be used in creating the
     * admin user and the admin role. The admin group will have this role
     */
    'admin_role' => 'superuser',
    
	/**************************************************************************/
	/* Always Load                                                            */
	/**************************************************************************/
    
	 'always_load'  => array(

        /**
         * These modules are always loaded on Fuel's startup. You can specify them
         * in the following manner:
         *
         * array('module_name');
         *
         * A path must be set in module_paths for this to work.
         */
        'packages'  => array('orm', 'auth'),
        'modules'  => array('Ember'),
         
        'config'  => array('Ember::settings2' => null),
         
    ),
    
	/**
	 * Localization & internationalization settings
	 */
    'language'           => 'jp', // Default language
    'language_fallback'  => 'en', // Fallback language when file isn't available for default language
    'locale'             => 'ja_JP', // PHP set_locale() setting, null to not set
    'locales'            => array(
        'en' => 'en_US',
        'jp' => 'ja_JP'
    ),
);