<?php
declare(strict_types=1);

class SeederController extends ControllerBase{
	public function indexAction(){
		
		echo 1;
		$faker = Faker\Factory::create();

		echo $faker->name;
		echo $faker->address;
		echo $faker->text;

	}
}

