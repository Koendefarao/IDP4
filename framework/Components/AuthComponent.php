<?php
include_once FRW_COMPONENTS . 'Component.php';
include_once FRW_FILES . 'TableLoader.php';
include_once FRW_FILES . 'Models/Request.php';

class AuthComponent extends Component {

	protected $_usersTable = 'Customers';
	protected $_nameField = 'username';
	protected $_passwordField = 'password';
	protected $_currentUser = '';
	
	public function initiate(array $config = array()) {
		parent::initiate($config);
		if(!empty($config['users_table'])) {
			$this->_usersTable = $config['users_table'];
		}
		if(!empty($config['name_field'])) {
			$this->_nameField = $config['name_field'];
		}
		if(!empty($config['password_field'])) {
			$this->_passwordField = $config['name_field'];
		}
	}
	
	public function authenticate($username, $password) {
		$users = TableLoader::get($this->_usersTable);
		// Haal alle gebruikers met hetzelfde gebruikersnaam
		$res = $users->query()->select()->where(array($this->_nameField.'=' => $username))->execute();
		if(empty($res)) { // geen resultaten
			return null;
		}
		
		$user = $res[0]; //pakt eerste resultaat
        //Hash de ingevoerde wachtwoord en chekc of deze overeen komt met de in
        //databse opgeslagen een
		if($user->password == $this->passwordHash($password)) {
			unset($user->password);
			// Zet gebruiker in sessie.
            Request::sessionSet($this->_usersTable, $user->serialize());
			return $user;/// geef de ingelogde gebruiker terug
		}
		return null;
	}

	public function getSession() { // Geef gbruiker uit sessie
	//unset($_SESSION);

	//echo json_encode($_SESSION);
	    if(!empty($_SESSION[$this->_usersTable])) {
	        return $_SESSION[$this->_usersTable];
        }
        return null;
    }

    //Verwijder gebruiker uit sessie aka loguit
    public function clearSession() {
        unset($_SESSION[$this->_usersTable]);
    }

    // Hash wachtwoord gebruik sha512
	public static function passwordHash($password) {
		return hash('sha512', $password . ConfigLoader::get('salt'));
	}
}
?>