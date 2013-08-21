<?php

namespace Ember;

class Model_Userinfo extends Model_Base {
    
    protected static $_table_name = 'userinfo';

    protected static $_belongs_to = array(
        'user' => array(
            'model_to' => 'Ember\\Model_User',
            'key_from' => 'user_id',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );
}