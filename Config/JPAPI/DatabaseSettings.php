<?php
namespace JPAPI;

/**
 * A class that holds the database settings
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
                                'name'      =>  'jsp_project',
                                'username'  =>  'root',
                                'password'  =>  'root'
                            );
}
