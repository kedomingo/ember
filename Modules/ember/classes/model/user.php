<?php

namespace Ember;

class Model_User extends Model_Base {
    
    protected static $_table_name = 'users';
    
    protected static $_belongs_to = array(
        'group' => array(
            'model_to' => 'Auth\\Model\\Auth_Group',
            'key_from' => 'group_id',
            'key_to' => 'id',
            'cascade_save' => false,
            'cascade_delete' => false,
        )
    );
    
    protected static $_has_one = array(
        
        'info' => array(
            'model_to' => 'Ember\\Model_Userinfo',
            'key_from' => 'id',
            'key_to' => 'user_id',
            'cascade_save' => false,
            'cascade_delete' => true,
            'conditions' => array(
                'where' => array('active' => '1'),
            )
        ),
    );
    
}