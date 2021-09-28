<?php


use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Http\Response;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces(
    [
        'Store\Toys' => __DIR__ . '/models/',
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                'host'     => 'mysql',
                'username' => 'root',
                'password' => 'toor',
                'dbname'   => 'phalcon',
            ]
        );
    }
);
function build(): string {
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


function validate(string $tokenReceived): bool {
	try {
		$now           = new DateTimeImmutable();
		$expires       = $now->getTimestamp();
		
		// Parser
		$parser = new Parser();

		// Parse the token received
		$tokenObject = $parser->parse($tokenReceived);

		// Create the validator
		$validator = new Validator($tokenObject, 100); // allow for a time shift of 100
		
		$validator->validateExpiration($expires);

		return true;
	} catch (Exception $ex) {
		return false;
	}
}

function is_logedin(){
	$token = null;
	if($_SESSION['auth'])
		$token = $_SESSION['auth']['token'];
		
	if( !validate($token) )
		return false;

	return (int) $_SESSION['auth']['id'];
}

// Create and bind the DI to the application
$app = new Micro($di);

$app->post(
	'/api/login',
	function () use ($app) {
		$user = $app->request->getJsonRawBody();

		$phql = 'SELECT *
				FROM Store\Toys\Users
				WHERE login = :login: AND password = :password:
				ORDER BY id';

		$user = $app->modelsManager->executeQuery(
			$phql,
			[
				'login' => $user->login,
				'password' => sha1($user->password),
			]
		);

		if ($user)
			$_SESSION['auth'] = [
				'id' => (int) $user->id,
				'token' => (string) build()
			];

	}
);



// Retrieves all products
$app->get(
	'/api/products',
	function () use ($app) {
		$user_id = is_logedin();

		// Create a response
		$response = new Response();

		if( !$user_id )
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);

		$phql = 'SELECT * FROM Store\Toys\Products WHERE user_id = :user_id: ORDER BY id';

		$products = $app->modelsManager->executeQuery(
			$phql
			// [
			// 	'user_id' => $user_id
			// ]
		);

		$data = [];

		foreach ($products as $product) {
			$data[] = [
				'id'   => $product->id,
				'quantity' => $product->quantity,
				'address' => $product->address,
				'shippingDate' => $product->shippingDate,
				'orderCode' => $product->orderCode,
			];
		}

		echo json_encode($data);
	}
);

// Retrieves products based on primary key
$app->get(
	'/api/products/{id:[0-9]+}',
	function ($id) use ($app) {
		$user_id = is_logedin();

		// Create a response
		$response = new Response();
		
		if( !$user_id )
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);

		$phql = 'SELECT * FROM Store\Toys\Products WHERE id = :id: AND user_id = :user_id:';

		$products = $app->modelsManager->executeQuery(
			$phql,
			[
				'id' => $id,
				'user_id' => $user_id,
			]
		)->getFirst();

		if ($products === false) {
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);
		} else {
			$response->setJsonContent(
				[
					'status' => 'FOUND',
					'data'   => [
						'id'   => $products->id,
						'quantity' => $products->quantity,
						'address' => $products->address,
						'shippingDate' => $products->shippingDate,
						'orderCode' => $products->orderCode,
					]
				]
			);
		}

		return $response;
	}
);

// Adds a new products
$app->post(
	'/api/products',
	function () use ($app) {
		$user_id = is_logedin();

		// Create a response
		$response = new Response();
		
		if( !$user_id )
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);

		$products = $app->request->getJsonRawBody();

		$phql = 'INSERT INTO Store\Toys\Products
				(user_id, quantity, address, shippingDate, orderCode)
				VALUES (:user_id:, :quantity:, :address:, :shippingDate:, :orderCode:)';
		
		$status = $app->modelsManager->executeQuery(
			$phql,
			[
				'user_id' => $user_id,
				'quantity' => $products->quantity,
				'address' => $products->address,
				'shippingDate' => $products->shippingDate,
				'orderCode' => $products->orderCode,
			]
		);

		// Check if the insertion was successful
		if ($status->success() === true) {
			// Change the HTTP status
			$response->setStatusCode(201, 'Created');

			$products->id = $status->getModel()->id;

			$response->setJsonContent(
				[
					'status' => 'OK',
					'data'   => $products,
				]
			);
		} else {
			// Change the HTTP status
			$response->setStatusCode(409, 'Conflict');

			// Send errors to the client
			$errors = [];

			foreach ($status->getMessages() as $message) {
				$errors[] = $message->getMessage();
			}

			$response->setJsonContent(
				[
					'status'   => 'ERROR',
					'messages' => $errors,
				]
			);
		}

		return $response;
	}
);

// Updates products based on primary key
$app->put(
	'/api/products/{id:[0-9]+}',
	function ($id) use ($app) {
		$user_id = is_logedin();

		// Create a response
		$response = new Response();
		
		if( !$user_id )
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);

		$status = false;

		$products = $app->request->getJsonRawBody();

		$phql = 'UPDATE Store\Toys\Products 
				SET quantity = :quantity:, address = :address:, shippingDate = :shippingDate:, orderCode = :orderCode:
				WHERE id = :id: AND user_id = :user_id:';

		$is_shipped = $app->modelsManager->executeQuery(
			'SELECT shippingDate
			FROM Store\Toys\Products
			WHERE id = :id: AND user_id = :user_id:',
			[
				'id' => $id,
				'user_id' => $user_id,
			]
		);
		
		if(!$is_shipped->success())
			$status = $app->modelsManager->executeQuery(
				$phql,
				[
					'id'   => $id,
					'user_id'   => $user_id,
					'quantity' => $products->quantity,
					'address' => $products->address,
					'shippingDate' => $products->shippingDate,
					'orderCode' => $products->orderCode,
				]
			);

		// Check if the insertion was successful
		if ($status->success() === true) {
			$response->setJsonContent(
				[
					'status' => 'OK'
				]
			);
		} else {
			// Change the HTTP status
			$response->setStatusCode(409, 'Conflict');

			$errors = [];

			foreach ($status->getMessages() as $message) {
				$errors[] = $message->getMessage();
			}

			$response->setJsonContent(
				[
					'status'   => 'ERROR',
					'messages' => $errors,
				]
			);
		}

		return $response;
	}
);

// Deletes products based on primary key
$app->delete(
	'/api/products/{id:[0-9]+}',
	function ($id) use ($app) {
		$user_id = is_logedin();

		// Create a response
		$response = new Response();
		
		if( !$user_id )
			$response->setJsonContent(
				[
					'status' => 'NOT-FOUND'
				]
			);

		$phql = 'DELETE FROM Store\Toys\Products WHERE id = :id: AND user_id = :user_id:';

		$status = $app->modelsManager->executeQuery(
			$phql,
			[
				'id' => $id,
				'user_id' => $user_id,
			]
		);

		if ($status->success() === true) {
			$response->setJsonContent(
				[
					'status' => 'OK'
				]
			);
		} else {
			// Change the HTTP status
			$response->setStatusCode(409, 'Conflict');

			$errors = [];

			foreach ($status->getMessages() as $message) {
				$errors[] = $message->getMessage();
			}

			$response->setJsonContent(
				[
					'status'   => 'ERROR',
					'messages' => $errors,
				]
			);
		}

		return $response;
	}
);

$app->handle($_SERVER['REQUEST_URI']);