<?php

namespace Ember;



// Ride with the tide. Load a bootstrap file along with this
// surely-loaded-everytime class
require_once realpath(dirname(__FILE__).'/../../bootstrap.php');



/**
 * Provides themeing functionality to controllers
 */
abstract class Controller_Theme extends \Controller {
    
    
    /**
     * Array of asset identfiers used in rendering buffered assets in order
     * they were inserted into this stack
     * @var array 
     */
    protected static $_buffer_stack = array();
    
    /**
     * The actual theme instance. Calling \Fuel\Core\Theme::instance() returns
     * the default theme's instance. This is a fix for that.
     * @var type 
     */
    public static $s_theme;
    
    public function before()
    {
        $this->template = null;
        parent::before();
        $this->_set_theme();
        if (empty($this->theme))
            throw new \Exception(___('You must use the _set_theme function to call switch_theme when using a themeable controller. Example: $this->switch_theme(\'MyTheme\')'));
        
    }
    
    /**
     * Provides a way of setting the theme
     */
    abstract protected function _set_theme();
    
    
    /**
     * Implements theming and automatically searches and renders the view
     * for the current module/controller/action
     * 
     * @param type $response
     * @return type
     */
    public function after($response)
    {   
        $controller = str_replace('_', '/', preg_replace('/^[^\\\\]+\\\\Controller_(.*)$/', '$1', $this->request->controller));
        $main_content = 'views/'.$controller.'/'.$this->request->action;
        $sections_blocks = array(
            'content_for_layout' => array(
                'main_content' => $main_content
            )
        );
        
        foreach ($sections_blocks as $section => $blocks) {
            foreach ($blocks as $block => $view) {
                $this->theme->set_partial($section, $this->theme->view($view));
            }
        }
        
        // If no response object was returned by the action,
        if (empty($response) or  ! $response instanceof Response)
        {
            // render the defined template
            $response = \Response::forge($this->theme->render());
        }
        
        return parent::after($response);
        
    }
     
    
    
    /**
     * Opens a buffer for js group $group
     */
    public static function append_js($group)
    {   
        array_push(static::$_buffer_stack, array('group' => $group, 'type' => 'js'));
        ob_start();
        
    }
    
    
    
    /**
     * Opens a buffer for css group $group
     */
    public static function append_css($group)
    {   
        array_push(static::$_buffer_stack, array('group' => $group, 'type' => 'css'));
        ob_start();
        
    }
    
    
    
    /**
     * Closes the most recet open buffer and appends to the appropriate group
     */
    public static function end()
    {   
        $theme = static::$s_theme;
        
        $buffer = array_pop(static::$_buffer_stack);
        
        if ($buffer['type'] == 'js') {
            
            $s = ob_get_clean();
            
            // remove preceeding and ending script tags
            $s = preg_replace('/^<script[^>]+>/e', '', trim($s));
            $s = trim(preg_replace('/<\/script[^>]*>$/e', '', $s));
            
            if (!empty($s)) {
                $theme->asset->js($s, array(), $buffer['group'], $raw = true);
            }
            
        }
        elseif ($buffer['type'] == 'css') {
            
            $s = ob_get_clean();
            
            // remove preceeding and ending style tags
            $s = preg_replace('/^<style[^>]+>/e', '', trim($s));
            $s = trim(preg_replace('/<\/style[^>]*>$/e', '', $s));
            
            
            if (!empty($s)) {
                
                $theme->asset->css($s, array(), $buffer['group'], $raw = true);
            }
            
        }
        
    }
 
    
    /**
     * Sets a variable for the theme and the views inside it
     * @param string $one The variable name that will be available in the theme
     * @param mixed $two The value of the variable
     */
    protected function set($one, $two = null)
    {   
        if ($two === null and is_array($one)) {
            foreach ($one as $k => $v) {
                
                $this->set($k, $v);
            }
        }
        elseif (is_string($one)) {
            
            $this->theme->template->set_global($one, $two);
        }
        
    }
    
    
    
    /**
     * Gracefully changes the current active theme
     * @param string $theme The theme name
     */
    public function switch_theme($theme = null)
    {   
        if ($theme === null) {
            $this->theme = \Theme::instance();
        }
        else {
            $this->theme = \Theme::instance($theme,  array(
                'active'        => $theme,
                'fallback'      => $theme,
                'paths'         => array(
                                       APPPATH.'themes',
                                   ),
                'assets_folder' => 'themes/assets',	// so this implies <localpath>/public/themes/<themename>...
                'view_ext'      => '.php',
            ));
        }
        
        $this->theme->set_template('views/layouts/default');
        
        $controller = str_replace('_', '/', preg_replace('/^[^\\\\]+\\\\Controller_(.*)$/', '$1', $this->request->controller));
        $this->theme->title = $controller.' - '.$this->request->action;
        
        $this->theme->template->set_global('theme', $this->theme, false);
        $this->theme->template->set_global('asset', $this->theme->asset, false);
        
        //
        // Theme is not a true singleton. Even if the theme was already switched
        // to admin, calling Theme::instance returns the default theme set in app/config/theme.php
        // Store the actual instance here. (used in inserting buffered js and css)
        //
        static::$s_theme = $this->theme;
    }
    
    /**
     * Provides a way of gracefully changing the template in the
     * current theme
     * @param string $template The template name
     */
    public function switch_template($template)
    {
        $this->theme->set_template('views/layouts/'.$template);
        // Re-set the theme and asset view vars
        $this->theme->template->set_global('theme', $this->theme, false);
        $this->theme->template->set_global('asset', $this->theme->asset, false);
        
    }
}