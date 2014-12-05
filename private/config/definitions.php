<?php


/**
 * GLOBAL CONSTANTS
 * Here you can put global constants which do not change during an applications
 * execution and are used in all scopes of the application
 */
define("SITE_NAME", "My Site");

/**
 * Determines if the gateway allows direct rendering of views, you set to true
 * if you want to use the index controller as the implied controller when 
 * visiting a view path directly
 * e.g. /person/run would render /person/run.view using the 
 * Index Controller without having to expressly have a method to render it
 */
\sb\Gateway::$allow_direct_view_rendering = false;

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
	try {
        App::connectDb("mysql:dbname=".$dbname.";host=".$dbname.";charset=utf8", $db_user, $db_pass, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_general_ci'"]);
    } catch (\PDO\Exception $e) {
        die("Could not connect to DB ;(");
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

//used to dump variables to a browser in a human readible way with whitespace
function print_raw($x){
    echo '<pre>'.print_r($x, 1).'</pre>';
}

/**
* SET UP SESSIONS HANDLER HERE
* Set up the session handler if using a custom one, by default filesystem sessions are used
<code>
//use plain old sessions with
session_start();

//or use mysql sessions
new \sb\Session\Mysql(App::$db);

//or use memcache sessions
new \sb\Session\Memcache('localhost', 11211);

</code>
*/

//session handler
if (!headers_sent()) {
    session_start();
}

/**
 * ASSIGN APP's OTHER STATIC PROPERTIES HERE
 * All static properties of App class, found in this directory are avaiable throughout your code in any scope.
 * Remember before assigning properties they must be documented on the App class itself in App.php.  If you put the proper phpDoc
 * comments on the properties of App, the code completion will be available in eclipse/zend studio
 * Example include, the application cache and your database connections.
 * 
<code>
//define the App user
App::$user = new \sb\User();
App::$user->uname = 'tester';
</code>
 */

//define the application's main caching engine
App::$cache = new \sb\Cache\FileSystem();

//define the application's main logging engine
App::$logger = new \sb\Logger\FileSystem();

\sb\Application\Debugger::init();

