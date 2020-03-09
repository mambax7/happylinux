<?php
// $Id: language.EN.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated

// 2006-12-17 K.OHWADA
// BUG 4417: singleton done not work correctly
// change get_local_instance() get_instance_by_language() etc

// 2006-10-05 K.OHWADA
// add happy_linux_language_factory
// move get_google_url() to locate.php

// 2006-09-10 K.OHWADA
// this is new file
// porting from weblinks_language.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class LanguageBase
//=========================================================
class LanguageBase
{
    public $_DEFAULT_LANGAGE = 'english';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    //---------------------------------------------------------
    // get value
    //---------------------------------------------------------
    public function get_default_language()
    {
        return $this->_DEFAULT_LANGAGE;
    }

    //---------------------------------------------------------
    // convert encoding
    //---------------------------------------------------------
    public function convert_telafriend_subject($text)
    {
        return $text;
    }

    public function convert_telafriend_body($text)
    {
        return $text;
    }

    public function convert_download_filename($text)
    {
        return $text;
    }

    //---------------------------------------------------------
    // system param
    //---------------------------------------------------------
    public function get_xoops_language()
    {
        global $xoopsConfig;

        return $xoopsConfig['language'];
    }

    public function get_xoops_langcode()
    {
        return _LANGCODE;
    }

    //---------------------------------------------------------
    // country code
    //---------------------------------------------------------
    public function get_country_code()
    {
        return 'us';    // USA
    }

    // --- class end ---
}

//=========================================================
// class LanguageFactory
//=========================================================
class LanguageFactory extends Happy_linux\LanguageBase
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance($lang = null)
    {
        static $instance;
        if (!isset($instance)) {
            // Assigning the return value of new by reference is deprecated
            $factory = new self();

            $instance = &$factory->get_local_instance($lang);
        }

        return $instance;
    }

    public function &get_local_instance($lang = null)
    {
        // BUG: singleton done not work correctly
        if (empty($lang)) {
            $lang = $this->get_xoops_language();
        }
        $local = &$this->get_instance($lang);

        return $local;
    }

    //---------------------------------------------------------
    // get_instance
    //---------------------------------------------------------
    public function &get_instance($language = null, $prefix = 'happy_linux', $dirname = 'happy_linux')
    {
        if ($language) {
            $instance = &$this->get_instance_by_language($language, $prefix, $dirname);
            if ($instance) {
                return $instance;
            }
        }

        $instance = &$this->get_instance_by_language($this->get_default_language(), $prefix, $dirname);
        if ($instance) {
            return $instance;
        }

        // Assigning the return value of new by reference is deprecated
        $instance = new happy_linux_language_base();

        return $instance;
    }

    public function &get_instance_by_language($language, $prefix = 'happy_linux', $dirname = 'happy_linux')
    {
        $instance = false;
        $file     = XOOPS_ROOT_PATH . '/modules/' . $dirname . '/language/' . $language . '/language_local.php';
        $class    = $prefix . '_language_local';

        // BUG: singleton done not work correctly
        // if include file alreary
        if (class_exists($class)) {
            // Assigning the return value of new by reference is deprecated
            $instance = new $class();
        } // if NOT include file yet
        elseif (file_exists($file)) {
            include_once $file;
            if (class_exists($class)) {
                // Assigning the return value of new by reference is deprecated
                $instance = new $class();
            }
        }

        return $instance;
    }

    // --- class end ---
}
