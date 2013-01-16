<?php

class CustomEloquent extends \Laravel\Database\Eloquent\Model {

	public static $timestamps = false;
	
	//
	// Validation from https://github.com/ShawnMcCool/laravel-eloquent-base-model
	//
	
	public static $rules = array();
	public static $messages = array();
	public $validation = false;
	
	public function is_valid()
	{
		if(empty(static::$rules))
			return true;

		// generate the validator and return its success status
		$this->validation = \Validator::make($this->attributes, static::$rules, static::$messages);

		return $this->validation->passes();
	}	

}
