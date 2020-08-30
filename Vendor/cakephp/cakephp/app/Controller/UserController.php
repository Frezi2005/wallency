<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link https://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UserController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

	public function beforeFilter() {
		parent::beforeFilter();
		$this->loadModel('User');
		App::uses('CakeText', 'Utility');
		App::uses('CakeEmail', 'Network/Email');
	}

/**
 * Displays a view
 *
 * @return CakeResponse|null
 * @throws ForbiddenException When a directory traversal attempt.
 * @throws NotFoundException When the view file could not be found
 *   or MissingViewException in debug mode.
 */

	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		if (in_array('..', $path, true) || in_array('.', $path, true)) {
			throw new ForbiddenException();
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));

		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}

	public function registerUser() {
		$data = $this->request->data['RegisterUser'];
		$this->loadModel('Wallet');
		$this->User->set($data);

		$uuid = CakeText::uuid();

		if($this->User->validates()) {
			$this->Wallet->save(array(
				'id' => null,
				'modified' => null,
				'created' => null,
				'userUUID' => $uuid,
				'usd' => 0,
				'eur' => 500,
				'chf' => 0,
				'pln' => 0,
				'gbp' => 0,
				'jpy' => 0,
				'cad' => 0,
				'rub' => 0,
				'cny' => 0,
				'czk' => 0,
				'try' => 0,
				'nok' => 0,
				'huf' => 0,
				'bitcoin' => 0,
				'ethereum' => 0,
				'tether' => 0,
				'XRP' => 0,
				'litecoin' => 0,
				'eos' => 0,
				'tezos' => 0,
				'oil' => 0,
				'gold' => 0,
				'copper' => 0,
				'silver' => 0,
				'palladium' => 0,
				'platinum' => 0,
				'nickel' => 0,
				'aluminum' => 0
			));
	
			$this->User->save(array('id' => null, 'login' => $data['login'], 'name' => $data['name'], 'surname' => $data['surname'], 'password' => $data['password'], 'email' => $data['email'], 'birthdate' => $data['birth_date'] .= ' 00:00:00', 'UUID' => $uuid, 'base_currency' => $data['baseCurrency'], 'verified' => 0));
		
			$email = new CakeEmail('default');
			$email->emailFormat('html')
				->to($data['email'])                            
				->from(array('frezi12345cr@gmail.com' => 'wallency'))
				->viewVars(array('uuid' => $uuid))
				->template('myview', 'mytemplate')
				->attachments(array(
					array(         
						'file' => ROOT.'/app/webroot/img/wallency-email.jpg',
						'mimetype' => 'image/jpg',
						'contentId' => 'background'
					),
					array(         
						'file' => ROOT.'/app/webroot/img/icon-email.jpg',
						'mimetype' => 'image/jpg',
						'contentId' => 'icon'
					)
				))
				->subject('subject')
				->send();
		} else {
			$this->log(print_r($this->User->validationErrors, true), 'validation');
		}
	}

	public function loginUser () {
		$data = $this->request->data['LoginUser'];
		$userData = $this->User->find('first', array('conditions' => array('login' => $data['loginOrEmail'], 'password' => $data['password'])));

		if(empty($userData)) {
			$userData = $this->User->find('first', array('conditions' => array('email' => $data['loginOrEmail'], 'password' => $data['password'])));
		} 
		
		if(empty($userData)) {
			$this->Flash->set('User with set credentials was not found.', array('key' => 'loginError'));
			$this->redirect('/login');
		}

		$this->Session->write('loggedIn', true);
		$this->Session->write('userName', $userData['User']['login']);
		$this->Session->write('userUUID', $userData['User']['UUID']);
		$this->redirect('/wallet');
	
	}

	public function logout () {
		$this->Session->write('loggedIn', false);
		$this->redirect('/home');
	}
}
