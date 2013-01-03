<?php
namespace JPAPI;

/**
 * A class that holds the database settings, please rename to database_settings.php
 *
 * @package default
 * @author Johnathan Pulos
 */
class DatabaseSettings
{
    /**
     * The default database to use
     *
     * @var array
     * @access public
     */
    public $default = array(    'host'      =>  'localhost',
                                'name'      =>  'jp',
                                'username'  =>  'jp',
                                'password'  =>  'jp'
                            );
}
