<?php
declare(strict_types=1);

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class ProductsController extends ControllerBase{

	public function validate(string $tokenReceived): bool {
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

	public function is_logedin(){
		$token = null;
		if($this->session->has('auth'))
			$token = $this->session->get('auth')['token'];
			
		if( !$this->validate($token) ){
			$this->flash->error('Incorrect Credentials!');
			return $this->dispatcher->forward([
				'controller' => 'index',
				'action' => 'index'
			]);
		}

		$this->userId = (int) $this->session->get('auth')['id'];
	}
	public function initialize(){
		$this->is_logedin();
	}
	/**
	 * Index action
	 */
	public function indexAction(){
		
		//
	}
	
	public function products(){
		$data = $_GET;
		$data['user_id'] = $this->userId;

		return Criteria::fromInput($this->di, 'Products', $data)->getParams();
	}
	/**
	 * Searches for products
	 */
	public function searchAction(){
		$numberPage = $this->request->getQuery('page', 'int', 1);

		$parameters = $this->products();
		$parameters['order'] = "id";

		$paginator   = new Model(
			[
				'model'      => 'Products',
				'parameters' => $parameters,
				'limit'      => 10,
				'page'       => $numberPage,
			]
		);

		$paginate = $paginator->paginate();

		if (0 === $paginate->getTotalItems()) {
			$this->flash->notice("The search did not find any products");

			$this->dispatcher->forward([
				"controller" => "products",
				"action" => "index"
			]);

			return;
		}

		$this->view->page = $paginate;
	}

	/**
	 * Displays the creation form
	 */
	public function newAction()
	{
		//
	}

	/**
	 * Edits a product
	 *
	 * @param string $id
	 */
	public function editAction($id){
		if (!$this->request->isPost()) {
			$product = Products::findFirstByid($id);
			if (!$product) {
				$this->flash->error("product was not found");

				$this->dispatcher->forward([
					'controller' => "products",
					'action' => 'index'
				]);

				return;
			}

			$this->view->id = $product->id;

			$this->tag->setDefault("id", $product->id);
			$this->tag->setDefault("user_id", $this->userId);
			$this->tag->setDefault("quantity", $product->quantity);
			$this->tag->setDefault("address", $product->address);
			$this->tag->setDefault("shippingDate", $product->shippingDate);
			$this->tag->setDefault("orderCode", $product->orderCode);
			
		}
	}

	/**
	 * Creates a new product
	 */
	public function createAction(){
		if (!$this->request->isPost()) {
			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'index'
			]);

			return;
		}

		$product = new Products();
		$product->userId = $this->userId;
		$product->quantity = $this->request->getPost("quantity", "int");
		$product->address = $this->request->getPost("address");
		$product->shippingDate = $this->request->getPost("shippingDate");
		$product->orderCode = $this->request->getPost("orderCode");
		

		if (!$product->save()) {
			foreach ($product->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'new'
			]);

			return;
		}

		$this->flash->success("product was created successfully");

		$this->dispatcher->forward([
			'controller' => "products",
			'action' => 'index'
		]);
	}

	/**
	 * Saves a product edited
	 *
	 */
	public function saveAction(){
		if (!$this->request->isPost()) {
			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'index'
			]);

			return;
		}

		$id = $this->request->getPost("id");
		$product = Products::findFirstByid($id);

		if (!$product) {
			$this->flash->error("product does not exist " . $id);

			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'index'
			]);

			return;
		}

		$product->userId = $this->userId;
		$product->quantity = $this->request->getPost("quantity", "int");
		$product->address = $this->request->getPost("address");
		$product->shippingDate = $this->request->getPost("shippingDate");
		$product->orderCode = $this->request->getPost("orderCode");
		

		if (!$product->save()) {

			foreach ($product->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'edit',
				'params' => [$product->id]
			]);

			return;
		}

		$this->flash->success("product was updated successfully");

		$this->dispatcher->forward([
			'controller' => "products",
			'action' => 'index'
		]);
	}

	/**
	 * Deletes a product
	 *
	 * @param string $id
	 */
	public function deleteAction($id){
		$product = Products::findFirstByid($id);
		if (!$product) {
			$this->flash->error("product was not found");

			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'index'
			]);

			return;
		}

		if (!$product->delete()) {

			foreach ($product->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward([
				'controller' => "products",
				'action' => 'search'
			]);

			return;
		}

		$this->flash->success("product was deleted successfully");

		$this->dispatcher->forward([
			'controller' => "products",
			'action' => "index"
		]);
	}
}
