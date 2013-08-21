<?php

namespace Ember;

/**
 * Interface that all controllers must adhere
 */
interface Interface_Authentication {
    
    /**
     * Provides authentication routine for implementers
     */
    function authenticate();
    
    /**
     * Provides decision making powers to implementing classes
     * for users authenticated or not
     * 
     * @param bool $allowed Set to true if the user was authenticated
     */
    function authorize($allowed);
    
    /**
     * Provides way of clearing the ACL cache
     */
    function clear_auth_cache();
}