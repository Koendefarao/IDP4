<?php 

class Request {

	public $method = null;
	public $query = null;
	public $ip = null;
	public $session = array();
    public $post = array();
	
	public function __construct()
    {
		$this->startSession();
		$this->session = $_SESSION;
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->query = $this->getQuery();
		//$this->ip = $this->$_SERVER['REMOTE_ADDR'];
        $this->post = $_POST;
	}

	// Splitst get queries. Omdat $GET is te mainstream
	public function getQuery() {
		$queries = $_SERVER['QUERY_STRING'];
		$queries = explode('&', $queries);
		$ret = array();
		foreach($queries as $q) {
			$q_exp = explode('=', $q);
			$ret[$q_exp[0]] = isset($q_exp[1]) ? $q_exp[1] : null;
		}
		return $ret;
	}

	//Start altijd een sessie
	public function startSession() {
        if (ini_set('session.use_only_cookies', 1) === FALSE) {
            return;
        }
        //session_name('');
        session_start();
        session_regenerate_id();
    }

    //Check of een variebele is gezet in sessie
    public static function sessionSet($key, $value) {
        $_SESSION[$key] = $value;
    }
}
?>