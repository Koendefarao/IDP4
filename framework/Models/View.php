<?php
include_once FRW_FILES . 'Models/ViewBlock.php';
class View
{
    public $vars = array();
    public $layout = 'default';
    public $controller = 'default';
    public $template = 'default';

    private $ViewBlocks = null;

    public function __construct($controller, $template)
    {
        $this->controller = $controller;
        $this->template = $template;
        $this->ViewBlocks = new ViewBlock();
    }

    public function render()
    {
        ob_start();
        if (file_exists(APP_TEMPLATES . ucfirst($this->controller) . DS . $this->template . '.php')) {
            include APP_TEMPLATES . ucfirst($this->controller) . DS . $this->template . '.php';
        } else {
            throw new Exception('No template in ' . APP_TEMPLATES . ucfirst($this->controller) . DS . $this->template . '.php');
        }
        $buffer = ob_get_clean();
        $this->ViewBlocks->set('content', $buffer);
        include APP_LAYOUTS . $this->layout . '.php';
    }

    public function write($name, $content)
    {
        $this->ViewBlocks->set($name, $content);
    }

    public function fetch($name)
    {
        return $this->ViewBlocks->get($name);
    }

    public function start($name) {
        $this->ViewBlocks->start($name);
    }

    public function end() {
        $this->ViewBlocks->end();
    }
	
	//TODO move into a static helper
	public function link($params) {
		$controller = !empty($params['controller']) ? $params['controller'] : $GLOBALS['controller'];
		$action = !empty($params['action']) ? $params['action'] : 'index';
		$query = !empty($params['query']) ? $params['query'] : null;
		$ret = 'http://'. BASE . '/' . $controller . '/' . $action;
		if(!empty($query)) {
			$first = true;
			foreach($query as $key => $val) {
				if($first) {
					$ret.= '?';
				}
				$ret.= "$key=$val";
				$first = false;
			}
		}
		return $ret;
	}

    public function __set($name, $value)
    {
        $this->vars[$name] = $value;
    }

    public function __get($name)
    {
        if(isset($this->vars[$name])) return $this->vars[$name];
        return null;
    }
}