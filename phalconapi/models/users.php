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
			"login",
			new InclusionIn(
				[
					'field'   => 'quantity',
					'message' => 'Email must be unique',
				]
			)
		);

		$validator->add(
			'password',
			new Uniqueness(
				[
					'field'   => 'password',
					'message' => '',
				]
			)
		);

		// Check if any messages have been produced
		if ($this->validationHasFailed() === true) {
			return false;
		}
	}
}