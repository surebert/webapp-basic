<?php

/**
 * Initializes a surebert framework project - do not edit
 * 
 * @author: Paul Visco
 * @version: 2.58 10-01-08 02-12-09
 *
 */

#####################################################################
##### DO NOT TOUCH THIS FILE UNLESS YOU KNOW WHAT YOU ARE DOING #####
#####################################################################

ob_start();

//replaces windows slashes that were messing up paths
define("ROOT", str_replace('/public/gateway.php', '', str_replace("\\", "/", __FILE__)));

/**
 * Loads the .phtml file that corresponds to the request
 * @author visco
 *
 */
class sb_View{
	
	/**
	 * Determines which file is loaded in the view directory if one is not specified.  When not set renders index.view.  To set just use template name minus the .view extension e.g. $this->default-file = index;
	 *
	 * @var string
	 */
	protected $default_file = 'index';
	 
	/**
	 * The argument delimeter e.g. the comma in: /view/action/1,2,3 
	 * @var string
	 */
	protected $input_args_delimiter = ',';
	
	/**
	 * Set to true if the view is loaded from within another view via Gateway::load_view, otherwise false
	 *
	 * @var boolean
	 */
	public $included = false;
	
	/**
	 * The requested data and input
	 * @var sb_Request
	 */
	public $request;
	
	 /**
	  * Left in for backwards compat with v1.0 
	  * @todo depreciate this and use $this->request->args instead
	  * @var array
	  */
	protected $args;
	
	/**
	 * Filters input arguments /page/read/1,2 e.g. 1,2 as array
	 * @param $arr Array reference to $this->request->args array
	 */
	protected function filter_args(&$arr){}
	
	/**
	 * Filters input arguments /page/read/1?one=two e.g. one=>two as array same as $_GET
	 * @param $arr Array reference to $this->request->get array
	 */
	protected function filter_get(&$arr){}
	
	/**
	 * Filters input arguments from $_POST
	 * @param $arr Array reference to $this->request->post array
	 */
	protected function filter_post(&$arr){}
	
	/**
	 * Filters the output after the view is rendered but before 
	 * it is displayed so that you can filter the output
	 *
	 * @param $output
	 * @return string
	 */
	protected function filter_output($output){
		return $output;
	}
	
	/**
	 * Filters input arguments /page/read/1,2 e.g. 1,2 as array
	 * @param $arr Array reference to $this->request->args array
	 * @return boolean determines if the view should render anything or not, false == no render
	 */
	protected function on_before_render(){
		return true;
	}
	
	/**
	 * Fires after rendering but before filtering of output
	 */
	protected function on_after_render(){
		return true;
	}
	
	/**
	 * Fires when view template is not found
	 */
	protected function template_not_found(){
	
		header("HTTP/1.0 404 Not Found");
		if(is_file(ROOT.'/private/views/error/404.view')){		
			echo Gateway::load_view('/error/404');
		}
	}
	
	/**
	 * Render the view from the template file based on the request
	 *
	 * @param String $template the template to use e.g. /dance
	 */
	final public function render($template=''){
		
		$output = '';
		$path = $this->request->path;
		
		if(count($this->request->path_array) == 1){
			$path .='/'.$this->default_file;
		} else if(!empty($template)){
			$this->included = true;
			$path = preg_replace("~/".$this->request->path_array[1]."$~", $template, $path);
		}
	
		$pwd = ROOT.'/private/views'.$path.'.view';
		
		$output = '';
		
		//capture view to buffer
		ob_start();
		
		//use template not found if view not found
		if(!is_file($pwd)){
			$this->template_not_found(basename($path));
			
		} else {
			
			if($this->on_before_render() !== false){
				
				require($pwd);
				$this->on_after_render();
				
			}
		}
	
		$output = ob_get_clean();
			
		$output = $this->filter_output($output);
		
		return $output;
	}
	
	/**
	 * Sets the request paramter
	 *
	 * @param sb_Request $request The request instance fed to the view
	 */
	final public function set_request(sb_Request $request ){
		
			$this->request = $request;
		
			$request->set_input_args_delimiter($this->input_args_delimiter);
		
			$this->filter_args($request->args);
		
			$this->filter_get($request->get);
		
			$this->filter_post($request->post);
		
			
		/**
		 * Hold over for backward compatibility
		 * @var array
		 * @todo depreciate this
		 */
		$this->args = $request->args;
	}
	
}
/**
 * Models an incoming request's path and data e.g. /_surebert/custom
 * @author visco
 *
 */
class sb_Request{
	
	/**
	 * The path as a string eg. /surebert/load
	 * @var string
	 */
	public $path;
	
	/**
	 * The path requested e.g. /user/dance
	 * @var string
	 */
	public $request;
	
	/**
	 * The remote address, IP of the user
	 * @var string
	 */
	public $remote_addr = '';
	
	/**
	 * The user agent if coming from an http request, or 'command line' when coming from the command line
	 * @var string
	 */
	public $agent = '';
	
	/**
	 * The path as an array /view/action Array('view', 'action')
	 * @var array
	 */
	public $path_array = Array();
	
	/**
	 * The framework path args in e.g. /view/action/1,2,3 it would be 1,2,3
	 * @var array
	 */
	public $args = Array();
	
	/**
	 * The "get" based input arguments from the request e.g. ?dog=cat as an array of key value pairs
	 * @var array
	 */
	public $get = Array();
	
	/**
	 * The post based input from the request - used to pass or similate $_POST 
	 * @var array
	 */
	public $post = Array();
	
	/**
	 * The cookies with the request
	 * @var array
	 */
	public $cookie = Array();
	
	/**
	 * A copy of the global $_FILES array, can be used to simulate file uploads
	 * @var array
	 */
	public $files = Array();
	
	/**
	 * Creates a new request instance
	 * @param $request The string request with args e.g. /_surebert/custom/strings.numPad
	 */
	public function __construct($request){
		
		if(preg_match("~\?(.*)$~", $request, $match)){
			$request = preg_replace("~\?.*$~", '', $request);
			if(isset($match[1])){
				parse_str($match[1], $this->get);
			}
		}
		
		if(method_exists('App', 'filter_all_input')){
			App::filter_all_input($this->get);
		} 
		
		$this->request = $request;
		
		$arr = explode("/", substr_replace($request, "", 0, 1));
		
		$this->path_array[0] = $arr[0];
		if(isset($arr[1]) && !empty($arr[1])){
			$this->path_array[1] = $arr[1];
		}
		
		$this->path = "/".implode("/", $this->path_array);
	
		$this->set_client(Gateway::$remote_addr, Gateway::$agent);
		$this->set_input(Gateway::$post, Gateway::$cookie, Gateway::$files);
	}
	
	public function set_input(&$post, &$cookie, &$files){
		
		$this->post = $post;
		$this->cookie = $cookie;
		$this->files = $files;
	}
	
	public function set_client(&$remote_addr, &$agent){
		
		$this->remote_addr = $remote_addr;
		$this->agent = $agent;
		
	}
	
	/**
	 * Sets the input argument delimeter and parses it
	 * @param $input_args_delimiter
	 */
	public function set_input_args_delimiter($input_args_delimiter){
		
		//parse arguments by removing path
		$args = preg_replace("~^".$this->path."/?~", "", $this->request);
		
		//remove $_GET string
		$args = preg_replace("~\?.*?$~", "", $args);
		
		if(!empty($args)){
			
			$this->args = explode($input_args_delimiter, $args);
			
			//decodes url encoding
			foreach($this->args as &$arg){
				$arg = urldecode($arg);
			}
			
			if(method_exists('App', 'filter_all_input')){
				App::filter_all_input($this->args);
			}
			 
		}
		
	}
	
}

/**
 * A basic abstract class for a model
 * @todo work with team on this
 * @author visco
 *
 */
abstract class sb_Model{
	public $id = 0;
	abstract public function load();
	abstract public function save();
	abstract public function delete();
	public function log(){
		file_put_contents(ROOT.'/private/logs/'.__CLASS__.'_id_'.$this->id, "\n".date('m/d/Y H:i:s'), FILE_APPEND);
	}
}

/**
 * The main gateway
 * @author visco
 * 
 */
class Gateway{
	
	//Gateway::$remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	/**
	 * The request path being requested
	 * @var sb_Request
	 */
	public static $request;
	
	/**
	 * The post data sent to the gateway
	 *
	 * @var array
	 */
	public static $post = Array();
	
	/**
	 * The cookie data sent to the gateway
	 *
	 * @var array
	 */
	public static $cookie = Array();
	
	/**
	 * The files data sent to the gateway
	 *
	 * @var array
	 */
	public static $files = Array();
	
	/**
	 * The agent of the client from the HTTP_USER_AGENT or command line if from the command line
	 *
	 * @var string
	 */
	public static $agent = 'command line';
	
	/**
	 * The remote addr of the client
	 *
	 * @var string
	 */
	public static $remote_addr = '127.0.0.1';
	
	/**
	 * If the request comes from the command line
	 *
	 * @var boolean
	 */
	public static $command_line = false;
	
	/**
	 * Loads a view for rendering
	 * 
	 * @author: Paul Visco
	 *
	 */
	public static function load_view($request){
		
		if(is_string($request)){
			$request = new sb_Request($request);
		}
		
		if(!$request instanceof sb_Request){
			trigger_error('$request must be a sb_Request instance');
		}
	
		$view = $request->path_array[0];
		
		if(!empty($view)){
			$viewClass = ucwords($view).'View';
			$viewFile = ROOT.'/private/views/'.$view.'/'.$viewClass.'.php';
		} else {
			$viewClass = 'IndexView';
			$viewFile = ROOT.'/private/views/IndexView.php';
		}	

		$viewClass = (is_file($viewFile)) ? $viewClass : 'sb_View';

		$view = new $viewClass();
		
		if($request != Gateway::$request){
			
			$request->get = array_merge(Gateway::$request->get, $request->get);
		
			$view->included = true;
		}
		
		if(!$view instanceof sb_View){
			trigger_error("Your custom view ".$viewClass." must extend sb_View");
		}
		
		$view->set_request($request);
		
		return $view->render();
		
	}
	
	/**
	 * Loads the main request
	 * @author visco
	 *
	 */
	public static function load_main_request(){
	
		return self::load_view(self::$request);
	}
	
	/**
	 * Autoloads classes from the _classes folder when they are instantiated so that the defintions of the classes never need to be manually included
	 *
	 * @param string $class_name
	 */
	
	public static function sb_autoload($class_name){
		
		$class_name = str_replace('_', '/', $class_name);
		
		if(substr($class_name, 0, 3) == 'sb/'){
			$class_name = substr_replace($class_name, "", 0, 3);
			require(SUREBERT_FRAMEWORK_SB_PATH.'/'.$class_name.'.php');
			
		} else if(substr($class_name, 0, 3) == 'rp/'){
			$class_name = substr_replace($class_name, "", 0, 3);
			require(SUREBERT_FRAMEWORK_RP_PATH.'/'.$class_name.'.php');
			
		} else if($class_name  == 'IndexView'){

			require(ROOT.'/private/views/IndexView.php');
		} else if(preg_match('~View$~', $class_name)){
			$d = preg_replace("~[A-Z][a-z]+$~", "", $class_name);
			require(ROOT.'/private/views/'.strtolower($d).'/'.$class_name.'.php');
		} else if(file_exists(ROOT.'/private/models/'.$class_name.'.php')){
			
			require(ROOT.'/private/models/'.$class_name.'.php');
		} else if(strstr($class_name, 'PHPUnit') && defined('PHPUNIT_PATH')){
			
			require(PHPUNIT_PATH.$class_name.'.php');
		}
	}
	
	
	/**
	 * Grabs the request from the REQUEST_URI or the command line argv
	 * @param $argv array Command line arguments
	 */
	public static function set_main_request($argv){

		//Calculates the path based on REQUEST_URI or the command line args
		if(!empty($_SERVER['REQUEST_URI'])){
			$request = $_SERVER['REQUEST_URI'];
		} else if(isset($argv)){
			$request =  $argv[1];
		} else {
			die("Path not found! Application cannot run in this context");
		}
		
		//requires variable $_GET, $_POST, $_COOKIE, $_FILES, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']
		if (isset($argv[2]) && is_file($argv[2])){
			require_once($argv[2]);
		} 
		
		//allow user to override determination of remote_addr, e.g. using proxy X-FORWARDED-FOR etc
		if(method_exists('App', 'set_remote_addr')){
			self::$remote_addr = App::set_remote_addr();
		} else {
			self::$remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : self::$remote_addr;
		}
		
		self::$agent = (isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] != 'command line') ? $_SERVER['HTTP_USER_AGENT'] : self::$agent;
		
		if(method_exists('App', 'filter_all_input')){
			App::filter_all_input($_POST);
		} 
		
		//$_GET is handled by sb_Request constructor so to allow included views to use ? GET syntax
		self::$post = $_POST;
		self::$cookie = $_COOKIE;
		self::$files = $_FILES;
		
		//empty the input data so as to prevent its use
		$_GET = $_POST = $_FILES = Array();
		
		//convert REQUEST into an array and define Controller which loads view
		self::$request = new sb_Request($request);
	}
	
	/**
	 * 
	 * @param $path The path to the file from ROOT of the framework e.g. /public/surebert/sb.js
	 */
	public static function file_require($path){
		
		require(ROOT.$path);
	}
	
	/**
	 * Reads a file into a variable
	 * @param $path The path to the file from ROOT
	 * @return string or false if file not found
	 */
	public static function file_read($path){
		$contents = '';
		if(is_file(ROOT.$path)){
			$fh = fopen(ROOT.$path, "r");
		
			while (!feof($fh)){
			    $contents .= fgets($fh);
			}
	
			fclose($fh);
		} else {
			$contents = false;
		}
		
		
		return $contents;
	}
	
	/**
	 * Initializes the gateway by determining the 
	 * @param $argv array Command line arguments
	 */
	public static function init($argv = null){
		spl_autoload_extensions('.php');
		spl_autoload_register("Gateway::sb_autoload");
	
	}
	
}
if(isset($argv)){
	Gateway::$command_line = true;
}

//initialize the gateway
Gateway::init();

//require the App class for static global vars
Gateway::file_require('/private/config/App.php');

//set the main request and filter the input if required
Gateway::set_main_request((isset($argv) ? $argv : null));

//include site based definitions/global functions
Gateway::file_require('/private/config/definitions.php');

//load the main request
$output = Gateway::load_main_request();

//filter the output if required and display it
if(method_exists('App', "filter_all_output")){
	echo App::filter_all_output($output);
} else {
	echo $output;
}

ob_flush();
?>