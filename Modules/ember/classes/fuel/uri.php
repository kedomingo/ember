<?php

/**
 * Language support for URIs
 * From http://www.marcopace.it/blog/2012/12/fuelphp-i18n-internationalization-of-a-web-application
 */

class Uri extends Fuel\Core\Uri {

    public function __construct($uri = NULL) {

        parent::__construct($uri);
        $this->detect_language();
        
        $this->__set_locale();
    }
    
    private function __set_locale() {
        
        // Locale was set forcefully, i.e., the locale is part of the uri
        $locale_switched = \Config::get('hard_locale');
        
        if ($locale_switched) {
            $locale = \Config::get('locale');
        }
        else {
            $locale = \Session::get('locale');
            if (empty($locale)) {
                // Session empty fallback
                $locale = \Config::get('locale');
            }
        }
        \Session::set('locale', $locale);
        
        $domain = 'messages';
        
        // Somehow setlocale doesn't work
        setlocale(LC_ALL, $locale);
        
        // but this does
        putenv('LC_ALL='.$locale);
        
        /*
         * Read <domain>.mo
         */
        bindtextdomain($domain, realpath(VENDORPATH."../lang"));
        textdomain($domain);
        
        $mode = null;
        
        // Test native gettext plugin
        if (_('locale') != 'locale') {
            $mode = 'native';
        }
        
        // Try caching workaround
        elseif ($this->__set_locale_quirks_mode($locale, $domain)) {
            $mode = 'quirks';
        }
        
        // Try php-gettext
        elseif ($this->__set_locale_php_fallback($locale, $domain)) {
            $mode = 'php-gettext';
        }
        
        // Try fuelphp Lang fallback
        elseif ($this->__set_locale_fuel($locale, $domain)) {
            $mode = 'fuel';
        }
        
        // MUST DEFINE ___ function
        if ($mode === 'fuel') {
            
            // FuelPHP Lang implementation
            if (!function_exists('___')) {
                function ___() {
                    $result = call_user_func_array('\\Lang::get', func_get_args());
                    if ($result === null) {
                        $args = func_get_args();
                        return array_shift($args);
                    }
                    return $result;
                }
            }
            return true;
        }
        else {
            
            // Default gettext function
            if (!function_exists('___')) {
                function ___($str) {
                    return call_user_func_array('_', array($str));
                }
            }
            
            if (in_array($mode, array('native', 'quirks', 'php-gettext'))) {
                return true;
            }
        }
        
        // Unable to do translations
        return false;
    }

    /**
     * Work around the caching issue by creating a copy of <domain>.mo
     * depending on its last modification date
     * @param string $locale
     * @param string $domain
     * @return boolean
     */
    private function __set_locale_quirks_mode($locale, $domain) {
        
        // Get the .mo file
        $file = realpath(VENDORPATH."../lang").'/'.$locale.'/LC_MESSAGES/'.$domain.'.mo';
        
        if (!file_exists($file))
            return false;
        
        // check its modification time
        $mtime = filemtime($file);
        
        // Create a copy of this .mo file
        $domain_new = $domain.'_'.$mtime;
        $file_new = preg_replace('/\.mo$/', '_'.$mtime.'.mo', $file); // our new unique .MO file
        
        if (!file_exists($file_new) and !copy($file, $file_new))
            return false;
        
        bindtextdomain($domain_new, realpath(VENDORPATH."../lang"));
        textdomain($domain_new);
        
        // Test if this works
        return _('locale') != 'locale';

    }
    
    /**
     * Fall back to php gettext emulation
     * @param string $locale
     * @param string $domain
     * @return boolean
     */
    private function __set_locale_php_fallback($locale, $domain) {
        
        require_once realpath(__DIR__.'/../../lib/php-gettext/gettext.inc');
        
        // gettext setup
        T_setlocale(LC_MESSAGES, $locale);
        bindtextdomain($domain, realpath(VENDORPATH."../lang"));
        textdomain($domain);
        
        // Test if this works
        return _('locale') != 'locale';
    }
    
    
    /**
     * Sets the locale for FuelPHP's Lang class
     * @param string $locale
     * @param string $domain
     * @return boolean
     */
    private function __set_locale_fuel($locale, $domain) {
        
        $file = realpath(realpath(VENDORPATH."../lang").'/'.$locale.'/LC_MESSAGES/'.$domain.'.php');
        
        if (!file_exists($file))
            return false;
        
        Lang::load($file);
        
        // Test if this works
        return \Lang::get('locale') != 'locale';
    }
    
    
    public function detect_language() {

        if (!count($this->segments)) {
            return false;
        }

        $first = $this->segments[0];
        $locales = Config::get('locales');

        if (array_key_exists($first, $locales)) {
            array_shift($this->segments);
            $this->uri = implode('/', $this->segments);

            Config::set('language', $first);
            Config::set('locale', $locales[$first]);
            Config::set('hard_locale', true);
        }
        
        
    }

    public static function generate($uri = null, $variables = array(), $get_variables = array(), $secure = null) {
        
        $language = Config::get('language');

        if (!empty($uri)) {
            $language .= '/';
        }

        return \Uri::create($language . $uri, $variables, $get_variables, $secure);
    }

    /**
     * Switches the current URL to the language in $lang
     * @param type $lang
     */
    public static function current($lang = null) {
        
        // original function
        $uri = parent::current();
        if ($lang === null) return $uri;
        
        return (!is_string($lang)) ? $uri : parent::base() .$lang.'/'.str_replace(parent::base(), '', $uri);
        
    }
    
}