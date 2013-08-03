<?php defined('SYSPATH') OR die('No direct script access.');

abstract class Security_Core extends Kohana_Security {

    /**
     * Checking if the post is real and has a valid CSRF token
     *
     *  Security::valid_post($_POST, TRUE)
     *
     * @param   array $post
     * @param   bool  $token
     * @return  bool
     */
    public static function valid_post( array $post = array(), $token = FALSE)
    {
        if (empty($post))
        {
            return FALSE;
        }

        if (Request::current()->method() !== HTTP_Request::POST)
        {
            return FALSE;
        }

        if ($token !== FALSE)
        {
            $csrf_key = Kohana::$config->load('security.csrf_key') ?: 'csrf_token';

            if ( ! isset($post[$csrf_key]))
            {
                return FALSE;
            }

            return Security::check($post[$csrf_key]);
        }

        return TRUE;
    }

    public static function __callStatic($method, $params)
    {
        return call_user_func_array(array('Security_HTML_Purifier', $method), $params);
    }

}
