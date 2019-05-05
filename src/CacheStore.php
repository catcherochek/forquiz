<?php
require('CacheInterface.php');
define('DS', DIRECTORY_SEPARATOR);
class CacheStore implements CacheInterface{
	public $storage = '';
    
    public function __construct($storage='') {
        if(!$storage) {
        	$this->storage = ".".DS.'storage' . DS;
        } else {
            $this->storage = $storage;
        }
               
        if (!file_exists($this->storage)) {
        	mkdir($this->storage, 0777);
        }
        if(!is_writable($this->storage)) {
            die('Cache folder ('.$this->storage.') need to be 777 permissions');
        }
    }
    
    public function __destruct() {
        $cached_files = glob($this->storage . '*');
        if(count($cached_files)) {
            foreach($cached_files as $filename) {
                if(!$this->is_actual($filename)) {
                    $this->delete($filename);
                }
            }
        }
    }
    
    protected function save($cache_key, $source, $minutes=0) {
        $cache_filename = md5($cache_key);
        $Open = fopen($this->storage . $cache_filename, 'w');
        $final_source = "TIME=". ($minutes * 60) ."\n". $source;
        fwrite($Open, $final_source);
        return $source;
    }

    /**
     * check if file with that filename exists in store dir
     *
     * @param $cache_key
     * @return bool
     */
    protected function has($cache_key) {
        $cache_filename = $this->storage . md5($cache_key);
        if(!file_exists($cache_filename)) {
            return False;
        }
         else {
             return $this->is_actual($cache_filename);
         }
    }

    /**
     *Retrieves a value from file in store directory
     *
     * @param $cache_key
     * @param string $source
     * @param int $time
     * @return string|string[]|null
     */
    protected function view($cache_key, $source='', $time=0) {

        $cache_filename = $this->storage . md5($cache_key);
        if($this->has($cache_key)) {
            $Source = file_get_contents($cache_filename);
            return preg_replace("#TIME=\d+\n#", '', $Source);
        }
         else {
             if(!$source) {
                 return $source;
             }
              else {
                  return $this->save($cache_key, $source, $time);
              }
         }
    }

    /**
     * Deletes a file
     * @param $cache_key
     * @return bool
     */
    protected function forget($cache_key) {

        $cache_filename = $this->storage . md5($cache_key);
        return $this->delete($cache_filename);
    }

    /**
     * @param $filename
     * @return bool
     */
    protected function delete($filename) {

        if(file_exists($filename)) {
            return unlink($filename);
        }
         else {
             return True;
         }
    }

    /** Check if cache file is actual, retrieves file creation and compare it with  TTL
     * @param $filename
     * @return bool
     */
    protected function is_actual($filename) {
        $Source = file_get_contents($filename);
        preg_match("#TIME=(\d+)#", $Source, $matches);
        if( (filemtime($filename) + $matches[1]) < time() ) {
            $this->delete($filename);
            return False;
        }
         else {
            return True;
         }
    }

    /** Interface implementation, allowing only mixed and generic values
     * @param string $key
     * @param mixed $value
     * @param int $duration
     * @return bool|mixed
     */
    public function set(string $key, $value, int $duration){
    	if (in_array(gettype($value),array('unknown type','NULL','object'))){
    		return false;
    	}
    	return $this->save($key,json_encode($value),$duration/60);
    }

    /** Interface implementation, retrieveing data from cache
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key){
    	return json_decode($this->view($key),true);
    }
    
    
}

