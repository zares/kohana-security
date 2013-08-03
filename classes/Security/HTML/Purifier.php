<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Modifies Kohana to use [HTML Purifier](http://htmlpurifier.org/) for the
 * [Security::xss_clean] method.
 *
 * @package    Purifier
 * @category   Security
 * @author     Kohana Team
 * @copyright  (c) 2010 Woody Gilk
 * @license    BSD
 */
abstract class Security_HTML_Purifier {

    /**
     * @var  HTMLPurifier  singleton instance of the HTML Purifier object
     */
    protected static $htmlpurifier;

    /**
     * Returns the singleton instance of HTML Purifier. If no instance has
     * been created, a new instance will be created. Configuration options
     * for HTML Purifier can be set in `APPPATH/config/security.php` in the
     * "settings" key.
     *
     * $purifier = Security::htmlpurifier();
     *
     * @return  HTMLPurifier
     */
    public static function htmlpurifier()
    {
        if ( ! static::$htmlpurifier)
        {
            // Create a new configuration object
            $config = HTMLPurifier_Config::createDefault();

            if ( ! Kohana::$config->load('security.purifier.finalize'))
            {
                // Allow configuration to be modified
                $config->autoFinalize = FALSE;
            }

            // Use the same character set as Kohana
            $config->set('Core.Encoding', Kohana::$charset);

            if (is_array($settings = Kohana::$config->load('security.purifier.settings')))
            {
                // Load the settings
                $config->loadArray($settings);
            }

            // Configure additional options
            $config = Security::configure($config);

            // Create the purifier instance
            static::$htmlpurifier = new HTMLPurifier($config);
        }

        return static::$htmlpurifier;
    }

    /**
     * Modifies the configuration before creating a HTML Purifier instance.
     *
     * [!!] You must create an extension and overload this method to use it.
     *
     * @param   HTMLPurifier_Config  configuration object
     * @return  HTMLPurifier_Config
     */
    public static function configure(HTMLPurifier_Config $config)
    {
        return $config;
    }

    /**
     * Removes broken HTML and XSS from text using [HTMLPurifier](http://htmlpurifier.org/).
     *
     * $text = Security::xss_clean(Arr::get($_POST, 'message'));
     *
     * The original content is returned with all broken HTML and XSS removed.
     *
     * @param   mixed   text to clean, or an array to clean recursively
     * @return  mixed
     */
    public static function xss_clean($data = NULL)
    {
        if (empty($data))
        {
            return NULL;
        }

        if (is_array($data))
        {
            $result = array();

            foreach ($data AS $i => $s)
            {
                // Recursively clean arrays
                $result[$i] = Security::xss_clean($s);
            }

            return $result;
        }

        // Load HTML Purifier
        $purifier = Security::htmlpurifier();

        // Clean the HTML and return it
        return $purifier->purify($data);
    }

}
