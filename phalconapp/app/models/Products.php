<?php

class Products extends \Phalcon\Mvc\Model
{

	/**
	 *
	 * @var integer
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $orderCode;

	/**
	 *
	 * @var integer
	 */
	public $quantity;

	/**
	 *
	 * @var string
	 */
	public $address;

	/**
	 *
	 * @var string
	 */
	public $shippingDate;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		$this->setSchema("phalcon");
		$this->setSource("products");
		$this->hasMany('id', 'UsersProducts', 'product_id', ['alias' => 'UsersProducts']);
	}

	/**
	 * Allows to query a set of records that match the specified conditions
	 *
	 * @param mixed $parameters
	 * @return Products[]|Products|\Phalcon\Mvc\Model\ResultSetInterface
	 */
	public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
	{
		return parent::find($parameters);
	}

	/**
	 * Allows to query the first record that match the specified conditions
	 *
	 * @param mixed $parameters
	 * @return Products|\Phalcon\Mvc\Model\ResultInterface|\Phalcon\Mvc\ModelInterface|null
	 */
	public static function findFirst($parameters = null): ?\Phalcon\Mvc\ModelInterface
	{
		return parent::findFirst($parameters);
	}

}
