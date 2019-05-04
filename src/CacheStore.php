<?php
require('CacheInterface.php');
define('DS', DIRECTORY_SEPARATOR);
class CacheStore implements CacheInterface{
	private  $ci;
    public $storage = '';
    
    public function __construct($storage='') {
        if(!$storage) {
        	// 
            $this->storage = ".".DS.'storage' . DS;
        } else {
            // 
            $this->storage = $storage;
        }
       
        if(!is_writable($this->storage)) {
            die('Cache folder ('.$this->storage.') need to be 777 permissions');
        }
    }
    
    public function __destruct() {
        /*
         * 
         */
        $cached_files = glob($this->storage . '*');
        if(count($cached_files)) {
            foreach($cached_files as $filename) {
                if(!$this->is_actual($filename)) {
                    $this->delete($filename);
                }
            }
        }
    }
    
    public function save($cache_key, $source, $minutes=0) {
        /*
         * ���������� ���-�����
         */
        $cache_filename = md5($cache_key);
        $Open = fopen($this->storage . $cache_filename, 'w');
        $final_source = "TIME=". ($minutes * 60) ."\n". $source;
        fwrite($Open, $final_source);
        return $source;
    }
    
    public function has($cache_key) {
        /*
         * �������� �� ������������� � ������������ ���-�����
         */
        $cache_filename = $this->storage . md5($cache_key);
        if(!file_exists($cache_filename)) {
            return False;
        }
         else {
             return $this->is_actual($cache_filename);
         }
    }
    
    public function view($cache_key, $source='', $time=0) {
        /*
         * �������� ������������ ���-����� � ����������� �������������
         */
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
    
    public function forget($cache_key) {
        /*
         * �������� ���-����� �� ��� �����
         */
        $cache_filename = $this->storage . md5($cache_key);
        return $this->delete($cache_filename);
    }
    
    private function delete($filename) {
        /*
         * �������� ���-����� ����� ��� ����
         */
        if(file_exists($filename)) {
            return unlink($filename);
        }
         else {
             return True;
         }
    }
    
    public function is_actual($filename) {
        /*
         * �������� �� ������������ ���-�����
         */
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
    public function set(string $key, $value, int $duration){
    	return $this->save($key,json_encode($value),$duration/60);
    }
    public function get(string $key){
    	return json_decode($this->view($key),true);
    }
    
    
}

Class SimpleWorker {
	private $register;
	public function __construct(CacheInterface $Ci, $storage='') {
		$this->register = $Ci;
	}
		public function dowork(){
			$res = $this->register->get("mykey");
			return  ($res)?$this->register->get("mykey"):$this->register->set('mykey','Я был сохранен ' . date('Y-m-d H:i:s'),600);
		}
	
	
}

?>