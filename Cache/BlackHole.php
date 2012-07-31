<?php
/**
 * Does not actually cache any data, but uses the Cache interface so that you can
 * removed caching without changing application code
 * <code>
 * $cache = new \sb\Cache_BlackHole();
 * $cache->store('/my/key', 'something');
 * echo $cache->fetch('/my/key');
 * </code>
 *
 * @author visco
 * @package Cache
 * 
 */
namespace sb;
class Cache_BlackHole implements Cache_Base{
	
	/**
	 * Store the cache data in memcache
	 */
	public function store($key, $data, $lifetime = 0) {
		return true;
	}
	        
	/**
	 * Fetches the cache from memcache
	 */
	public function fetch($key) {
		return false;
	}
	
	/**
	 * Deletes cache data
	 */
	public function delete($key) {
		return true;
	} 
	
	/**
	 * Clears the whole cache
	 */
	public function clear_all(){
		return true;
	}
	
	/**
	 * Loads the current catalog
	 * @return Array a list of all keys stored in the cache
	 */
	public function get_keys(){
		return Array();
	}
	
}
?>