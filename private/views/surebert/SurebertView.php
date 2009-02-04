<?php
/**
 * Creates concatenated javascript files for the surebert toolkit from the arguments it is fed
 * @author visco
 * @version 1.11 05/20/2008 02/04/2009
 *
 */
class SurebertView extends sb_View{
	
	/**
	 * Determines if caching is used
	 * @var boolean
	 */
	public $cache_enable = false;
	
	/**
	 * The base files included with all custom or surebert views, the default is sb for sb.js
	 * @var string
	 */
	protected $default_files = Array();
	
	/**
	 * Removes comments from javascript
	 * @author visco
	 */
	protected function filter_output($output){
		
		return preg_replace("~/\*\*.*?\*/~s", "", $output);
	}
	
	/**
	 * Concatenates the javascript/surebert files and turns them into cache if caching is enable
	 * @param $files
	 */
	protected function concat_files($files = Array()){
		
		$binary = preg_match("~\.(swf|gif)$~", $files[0], $match);
		
		if($binary){
			if($match[1] == 'swf'){
				header("Content-type: application/x-shockwave-flash");
			} else if($match[1] == 'gif'){
				header("Content-type: image/gif");
			}
		} else {
			$this->add_javascript_headers();
		}
		
		if($this->cache_enable){
			$cache = isset(App::$cache) ? App::$cache : new sb_Cache_FileSystem();
			$key = '/toolkit/'.md5(join(",", $this->request->args));
			
			$data = $cache->fetch($key);
			if($data){
				echo $data;
				return true;
			}
		}
		
		$surebert = $this->default_files;
		
		foreach($files as $file){
			if($binary){
				$surebert[] = $file;
			} else {
				
				$surebert[] = str_replace('.', '/', $file).'.js';
			}
		}
		
		ob_start();
		
		foreach($surebert as $file){
			
			$path = SUREBERT_TOOLKIT_PATH.'/'.$file;
			
			if($file == 'sb.js'){	
				echo "var sbBase = '/surebert/load';\n";
			}
			
			if(is_file($path)){
				
				readfile($path);
			} else {
				echo"\nthrow('ERROR: Surebert module \"".basename($file)."\" could not be located by /surebert/load ');";
			}
		}
		
		$js = ob_get_clean();
		echo $js;
		
		if($this->cache_enable){
			$cache->store($key, $js);
		}
		
	}
	
	/**
	 * Adds javascript headers to the file and adds cache control if this is the main
	 * view being displayed.  If it is included in another view do not do this
	 * as you don't want HTML being served as javascript
	 */
	protected function add_javascript_headers(){
		
		if(!$this->included){
			header("Content-type: application/x-javascript");
		}
		
	}
}
?>