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
	 * The toolkit tag to use for serving the js from surebert
	 * @var string
	 */
	public $toolkit_root = '/var/www/sbf/toolkit/';

	/**
	 * The version of surebert being served
	 * @var string
	 */
	public $version;

	/**
	 * Removes comments from javascript
	 * @author visco
	 */
	protected function filter_output($output){

		if(!isset($this->request->get['sb_comments'])){
            return preg_replace("~/\*\*.*?\*/~s", "", $output);
        } else {
            return $output;
        }
	}

	/**
	 * Concatenates the javascript/surebert files and turns them into cache if caching is enable
	 * @param $files
	 */
	protected function concat_files($files = Array(), $version=''){

		if($files[0] == 'sb' && strstr(Gateway::$agent, 'MSIE')){

			array_unshift($files, 'js1_5');
		}

		$version = !empty($version) ? $version : SUREBERT_TOOLKIT_PATH;

		$tag = $this->toolkit_root.'/tags/'.$version;
		if(is_numeric($version) && is_dir($tag)){
			$root = $this->toolkit_root.'tags/'.$version;
		} else {
			$root = $this->toolkit_root.'trunk';
		}

		$this->version = basename($root);

		$binary = preg_match("~\.(swf|gif|png)$~", $files[0], $match);

		if($binary){
			if($match[1] == 'swf'){
				header("Content-type: application/x-shockwave-flash");
			} else if($match[1] == 'gif' || $match[1] == 'png'){
				header("Content-type: image/".$match[1]);
			}
		} else {
			$this->add_javascript_headers();
			echo '//v '.$this->version.' - '.date('m/d/Y H:i:s')."\n";

		}

		if($this->cache_enable){
			$cache = isset(App::$cache) ? App::$cache : new sb_Cache_FileSystem();
			$key = '/toolkit/'.md5(implode(",", $files).$version);

			$data = $cache->fetch($key);
			if($data){
				echo $data;
				return true;
			}
		}

		$surebert = $this->default_files;
		$this->loaded_files = Array();
		foreach($files as $file){
			if($binary){
				$surebert[] = $file;
			} else {

				$surebert[] = str_replace('.', '/', $file).'.js';
			}
		}
		ob_start();

		foreach($surebert as $file){
			echo $this->grab_file($file, $root);
		}

		$js = ob_get_clean();

		if(isset($this->request->get['manifest'])){
			$m = $this->request->get['manifest'];
			if($m == 'js'){
				return json_encode($this->loaded_files);
			} else {
				return print_r($this->loaded_files, 1);
			}
		}
		if($this->cache_enable){
			$cache->store($key, $js);
		}

		return $js;

	}

	protected function grab_file($file, $root){
		if(is_file($root.'/'.$file)){

			$this->loaded_files[] = $file;

			$file = $root.'/'.$file;

			$data = file_get_contents($file);
			if(!strstr($file, 'sb.js')){
				preg_match_all("~sb\.include\('(.*?)'\)~", $data, $includes);

				if($includes[1]){
					$precludes = '';
					foreach($includes[1] as $include){
						$include = str_replace('.', '/', $include).'.js';
						if(!in_array($include, $this->loaded_files)){
							$precludes .= $this->grab_file($include, $root);
						}
					}

					$data = $precludes."\n".$data;
				}

			}

		} else {

			echo"\nthrow('ERROR: Surebert module \"".basename($file)."\" could not be located by /surebert/load ');";
		}

		return $data;
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