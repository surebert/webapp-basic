<?php


/**
 * GLOBAL CONSTANTS
 * Here you can put global constants which do not change during an applications
 * execution and are used in all scopes of the application
 */
define("SITE_NAME", "My Site");

/**
 * GLOBAL FUNCTIONS
* You can use this space to define any globally accessible variable, constants and functions, 
* in addition to defining static props of the App class which are avaible in any scope.  
*/

/**
* DATABASE CONNECTIONS
* Here you can put global constants which do not change during an applications
* execution and are used in all scopes of the application
* <code>
	define('DB_HOST', 'HOST');
	define('DB_NAME', 'DB');
	define('DB_USER', 'USER');
	define('DB_PASS', 'PASS');

	//here you should instantiate your db connections
	try{
		App::$db = new sb_PDO("mysql:dbname=".DB_NAME.";host=".DB_HOST.";charset=utf8", DB_USER, DB_PASS);
	} catch(Exception $e){
		die('Could not connect to database ;(');
	}
* </code>
*/

/**
* GLOBAL FUNCTIONS
* Example global function prints an object inside of pre tags for easy debugging with line returns.  You should not have very
* many of these.  Think, what do you need to run everywhere.  Everything is better as static methods on helper classes
* as they only load when needed instead of always loading.
* 
*/

/**
* SET UP SESSIONS HANDLER HERE
* Set up the session handler if using a custom one, by default filesystem sessions are used
<code>
//use plain old sessions with
session_start();

//or use mysql sessions
new sb_Session_Mysql(App::$db);

//or use memcache sessions
new sb_Session_Memcache('localhost', 11211);

</code>
*/
session_start();

/**
 * ASSIGN APP's OTHER STATIC PROPERTIES HERE
 * All static properties of App class, found in this directory are avaiable throughout your code in any scope.
 * Remember before assigning properties they must be documented on the App class itself in App.php.  If you put the proper phpDoc
 * comments on the properties of App, the code completion will be available in eclipse/zend studio
 * Example include, the application cache and your database connections.
 * 
<code>
//define the App user
App::$user = new sb_User();
App::$user->uname = 'tester';
</code>
 */

//define the application's main cacheing engine
App::$cache = new \sb\Cache\FileSystem();

//define the application's main logging engine
App::$logger = new \sb\Logger\FileSystem();

\sb\Application\Debugger::init();

?>