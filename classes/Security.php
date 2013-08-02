<?php defined('SYSPATH') OR die('No direct script access.');

class Security extends HTML_Security {

    /**
     * Checking if the post is real and has a valid CSRF
     *
     *  Security::valid_post($_POST, TRUE)
     *
     * @param   array $post
     * @param   bool  $csrfv
     * @return  bool
     */
    public static function valid_post( array $post = array(), $csrfv = FALSE)
    {
        if (empty($post))
        {
            return FALSE;
        }

        if (Request::current()->method() !== HTTP_Request::POST)
        {
            return FALSE;
        }

        if ($csrfv !== FALSE)
        {
            $csrf_key = Kohana::$config->load('security.csrf_key') ?: 'csrf';

            if ( ! isset($post[$csrf_key]))
            {
                return FALSE;
            }

            return Security::check($post[$csrf_key]);
        }

        return TRUE;
    }

}
