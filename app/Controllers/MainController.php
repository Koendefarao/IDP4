<?php
include_once FRW_FILES . 'BaseController.php';
include_once APP_TABLES . 'CustomersTable.php';

class MainController extends BaseController
{
    public function initiate()
    {
        $this->loadComponent('Auth');
        $this->loadComponent('Notification');
        //$this->loadComponent('Cart');
        parent::initiate();
    }

    protected function beforeAction()
    {
        parent::beforeAction();
        $customer = $this->Auth->getSession();
        if ($customer != null) {
            $this->set('customer', $customer);
        }
    }


    public function index()
    {
        $customers = TableLoader::get('customers');
        $result = $customers->query()->select()->where(['subscription=' => 2])->execute();
        /*foreach ($result as $klant) {
            $klant->set('subscription', 2);
            $customers->query()->upsert([$klant])->execute();
        }
        echo json_encode($result);*/
        $this->set('hi', 'Hallo woreld');
    }

    public function qrcode()
    {
        //Als gebruiker niet is ingelogd laat ook niks zien. Redirect
        if ($this->Auth->getSession() == null) {
            $this->Notification->error('Niet toegestaan');
            $this->redirect(array('action' => 'index'));
            return;
        }
        $customers = TableLoader::get('customers');
        $user = $customers->query()->select(['qr_code', 'username'])
            ->where(['username=' => $this->Auth->getSession()['username']])->execute();
        if (!isset($user[0])) {
            $this->redirect(array('action' => 'index'));
            return;
        }

        $this->set('qr_code', $user[0]->get('username') . ':' . $user[0]->get('qr_code'));
        $apparaten = array();

        $history_table = TableLoader::get('history');
        $history = $history_table->query()->select()->where(['customer_id=' => $this->Auth->getSession()['id']])->execute();
        $device_table = TableLoader::get('device');
        $devices = $device_table->query()->select()->execute();
        if(!empty($history)) {
            foreach ($history as $record) {
                foreach ($devices as $device) {
                    if($device->get('id') != $record->get('device_id')) continue;
                    array_push($apparaten, [
                        'name' => $device->get('name'),
                        'image' => $device->get('image'),
                        'created' => $record->get('created'),
                    ]);
                }
            }
        }
        $this->set('devices', $apparaten);

    }

    public function login()
    {
        // Als er iets gepost is check of het goed is. En als wel ga door naar
        // Index want je bent al ingelogd
        if ($this->_request->method == 'POST') {
            $username = $this->_request->post['username'];
            $password = $this->_request->post['password'];
            // Gebruik auth component logi te chekcen
            $res = $this->Auth->authenticate($username, $password);
            if ($res != null) {
                $this->Notification->success("Welcome " . $res->get('username'));
                $this->redirect(array('action' => 'index'));
                return;
            }
            $this->Notification->error('Wrong username or password!');
        }
    }

    public function register()
    {
        // Maak een entity. Vul velden
        if ($this->_request->method == 'POST') {
            $customers = TableLoader::get('customers');
            $customer = $customers->createEntity();
            $customer->set('username', $this->_request->post['username']);
            $customer->set('password', $this->_request->post['password']);
            $customer->set('first_name', $this->_request->post['first_name']);
            $customer->set('last_name', $this->_request->post['last_name']);
            $customer->set('email', $this->_request->post['email']);
            $customer->set('city', $this->_request->post['city']);
            $customer->set('address', $this->_request->post['address']);
            $customer->set('postcode', $this->_request->post['postcode']);
            $customer->set('created', date('Y-m-d H:i:s'));
            $customer->set('subscription', $this->_request->post['subscription']);
            $customer->set('iban_nr', $this->_request->post['iban_nr']);
            // Zet het in
            $res = $customers->query()->insert([$customer])->execute();
            // Laat resultaat zien
            if ($res) {
                $this->Notification->success('Succesvol geregistreerd. Log nu in.');
            } else {
                $this->Notification->error('Please check if you filled in correct info.');
            }
        }
        // STuur altijd door naar login. Zelfs als geregistreerd
        $this->redirect(array('action' => 'login'));
    }

    public function logout()
    {
        //Loguit. DUs wis de sessie waar alle login info staat
        $this->Auth->clearSession();
        $this->Notification->success('Succesfully logged out.');
        $this->redirect(array('action' => 'index'));
    }

    /*Hoe update je dingen in tabellen
     $result = $customers->query()->select()->where(['subscription=' => 2])->execute();
        foreach ($result as $klant) {
            $klant->set('subscription', 2);
            $customers->query()->upsert([$klant])->execute();
        }
     */

    /* Hoe haal je dingen uit tabellen
        $customers = TableLoader::get('customers');
        $result = $customers->query()->select()->where(['subscription=' => 1])->execute();
     */


    /* Hoe zet je dingen in tabel
        $customers = TableLoader::get('customers');
        $customer = $customers->createEntity();
        $customer->set('username', 'egordm');
        $customer->set('password', 'egordm');
        $customer->set('first_name', 'Egor');
        $customer->set('last_name', 'Dmitriev');
        $customer->set('email', 'egordmitriev2@gmail.com');
        $customer->set('city', 'Utrecht');
        $customer->set('address', 'Gvr 13c');
        $customer->set('postcode', '3531aa');
        $customer->set('created', date('Y-m-d H:i:s'));
        $customer->set('subscription', 1);
        $customer->set('iban_nr', 'abc12345678');
        $customers->query()->insert([$customer])->execute();
     */

}