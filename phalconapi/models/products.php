<?php

namespace Store\Toys;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Messages\Message;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;


class Products extends Model
{
	public function validation()
	{
		$validator = new Validation();
		
		$validator->add(
			"quantity",
			new InclusionIn(
				[
					'field'   => 'quantity',
					'message' => 'Quantity must be integer',
				]
			)
		);

		$validator->add(
			'address',
			new Uniqueness(
				[
					'field'   => 'address',
					'message' => 'Address must be string',
				]
			)
		);
		
		$validator->add(
			'shippingDate',
			new Uniqueness(
				[
					'field'   => 'shippingDate',
					'message' => 'Shipping date must be Y-m-d H:i:s',
				]
			)
		);

		$validator->add(
			'orderCode',
			new Uniqueness(
				[
					'field'   => 'orderCode',
				]
			)
		);

		// Check if any messages have been produced
		if ($this->validationHasFailed() === true) {
			return false;
		}
	}
}