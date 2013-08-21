<?php

namespace Ember;

/**
 * The Ajax controller is basically the same as Controller_Base with only 1 difference:
 * 
 *     1. It uses the ajax template (which will contain only the view and scripts and styles accompanying it)
 */
abstract class Controller_Ajax_Base extends Controller_Base {
    
    public function before()
    {
        parent::before();
        $this->switch_template('ajax');
    }
    
}