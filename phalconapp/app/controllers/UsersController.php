<?php
declare(strict_types=1);

use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

class UsersController extends ControllerBase{
	public function build(): string {
		// JWT
		$signer  = new Hmac();
		// Builder object
		$builder = new Builder($signer);

		$now        = new DateTimeImmutable();
		$issued     = $now->getTimestamp();
		$notBefore  = $now->modify('-1 minute')->getTimestamp();
		$expires    = $now->modify('+10 min')->getTimestamp();
		$passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
		
		// Setup
		$builder
			->setAudience('https://target.phalcon.io')  // aud
			->setContentType('application/json')        // cty - header
			->setExpirationTime($expires)               // exp 
			->setId('abcd123456789')                    // JTI id 
			->setIssuedAt($issued)                      // iat 
			->setIssuer('https://phalcon.io')           // iss 
			->setNotBefore($notBefore)                  // nbf
			->setSubject('my subject for this claim')   // sub
			->setPassphrase($passphrase)                // password 
		;

		// Phalcon\Security\JWT\Token\Token object
		$tokenObject = $builder->getToken();

		// The token
		return $tokenObject->getToken();
	}
	
	public function loginAction(){
		$login    = $this->request->getPost('login');
		$password = $this->request->getPost('password');

		$user = Users::findFirstByLogin($login);
		if ($user) {
			if ($this->security->checkHash($password, $user->password)
				&& $this->security->checkToken()) {
				// The password is valid

				$this->session->set('auth', [
					'id' => (int) $user->id,
					'token' => (string) $this->build()
				]);

				$this->flash->success('You have logged in.');

				return $this->dispatcher->forward([
						'controller' => 'products',
						'action' => 'index'
					]);
			}
		} else {
			// To protect against timing attacks. Regardless of whether a user
			// exists or not, the script will take roughly the same amount as
			// it will always be computing a hash.
			$this->security->hash((string) rand());
		}

		// The validation has failed
		$this->flash->error('Incorrect Credentials!');
		return $this->dispatcher->forward([
			'controller' => 'index',
			'action' => 'index'
		]);

	}

	public function logoutAction(){
		$this->session->remove('auth');
		$this->session->remove('token');
		
		return $this->dispatcher->forward([
			'controller' => 'index',
			'action' => 'index'
		]);
	}
}
