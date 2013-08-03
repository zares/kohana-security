<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'csrf_key' => 'csrf_token',
    'purifier' => array(
        'finalize' => TRUE,
        'settings' => array(
            'Cache.SerializerPath' => APPPATH.'cache',
            'HTML.AllowedElements' => array('b', 'i', 'u', 'a'),
        ),
    ),
);
