<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'csrf_key' => 'xid',
    'purifier' => array(
        'finalize' => TRUE,
        'settings' => array(
            'Cache.SerializerPath' => APPPATH.'cache',
        ),
    ),
);
