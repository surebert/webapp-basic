<?php
/**
 * App loads with every page request.  The App class is not instantiated.  It is basically a storage for globally scoped properties and methods specific to your App.
 * Rememeber, if it is not something that is used everywhere it is not worth loading it into App.  You are better off putting the code in your 
 * model or view objects or making it a static property/method on a helper object that autloads only when needed.
 * 
 * @author visco
 *
 */

class App{

	/**
	* The application user (sb_User or an extension of it)
	*
	* @var \sb\User
	*/
	public static $user;

	/**
	* The primary application db connection, they may be named if there are more than one.
	*
	* @var \sb\PDO
	*/
	public static $db;

	/**
	* The system's main caching engine
	* @var \sb\Cache\Base
	*/
	public static $cache;

	/**
	* The system's main logging engine
	* @var \sb\Logger\Base
	*/
	public static $logger;
    
    /**
     * Connects to main DB and sets up the App::$db reference for use in models, etc
     * @param string $pdo_constructor The PDO contructor e.g. 
     * @param string $db_user The DB user if one is required
     * @param string $db_pass The DB pass if one is required
     * @param array $attributes Any additional PDO attributes to pass
     * @return \sb\PDO\Debugger
     * <code>
     * App::connectDb("mysql:dbname=".$dbname.";host=".$dbname.";charset=utf8", $db_user, $db_pass, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_general_ci'"]);
     * </code>
     */
    public static function connectDb($pdo_constructor, $db_user='', $db_pass='', $attributes=[]) {
      
        if (App::$db instanceOf \sb\PDO) {
                return App::$db;
        }
      
        App::$db = new \sb\PDO($pdo_constructor, $db_user, $db_pass, $attributes);
        
        return App::$db;
    }

}
