<?php

namespace Inpsyde\Core\Api;

// Block direct access to file

defined('ABSPATH') or die('Not Authorized!');

use Httpful;

use Inpsyde\Core\Api;

/**
 * API child class controller for Users, this class is responsible for providing all methods related to the users model of the external api
 *
 * @author João Vieira
 * @license MIT
 */
class Users {
	
	/**
	 * Assigns page class to this controller
	 *
	 * @param Api $page The API page class
	 * @method __construct
	 */
	public function __construct(Api &$page){
		
		$this->Page = $page;
		
	}
	
	/**
	 * Provides user listing from the external api
	 *
	 * @method list
	 * @param StdClass $response The API page response object
	 * @return Bool
	 */
	public function list(&$response){
		
		//Build API endpoint
		
		$endpoint = $this->Page->Api->url.'/users';
		
		//Get from cache
		
		$data = $this->Page->Cache->get('inpsyde_user_list'); 
		
		if($data === false){

			//No data in cache, call API instead
			
			$apiResponse = Httpful\Request::get($endpoint)
			->expectsJson()
			->send();
			
			//Set data

			$data = $apiResponse->body;
			
			//Add to cache
			
			$this->Page->Cache->add('inpsyde_user_list',$data);

		}
		
		//Data transform
 		
		$responseData = [];
		
		foreach($data as $user){
			
			//Set address
			
			unset($user->address->geo);
			
			//Build data
			
			$responseData[] = [
				'id' => $user->id,
				'name' => $user->name,
				'username' => $user->username,
				'email' => $user->email,
				'address' => implode('<br>',(array)$user->address),
				'phone' => $user->phone,
				'website' => $user->website,
				'company' => $user->company->name,
			];
			
		}
		
		$response->data = $responseData;
		
		return true;
		
	}
	
	/**
	 * Provides user details from the external api
	 *
	 * @method list
	 * @param StdClass $response The API page response object
	 * @return Bool
	 */
	public function item(&$response){
		
		//Get user id
		
		$id = get_query_var('id');
		
		if(!is_numeric($id))
			throw new Exception('User id is not valid');
		
		//Build API endpoint
		
		$endpoint = $this->Page->Api->url.'/users/'.$id;
		
		//Get from cache
		
		$data = $this->Page->Cache->get('inpsyde_user_'.$id); 
		
		if($data === false){

			//No data in cache, call API instead
			
			$apiResponse = Httpful\Request::get($endpoint)
			->expectsJson()
			->send();
			
			//Set data

			$data = $apiResponse->body;
			
			//Add to cache
			
			$this->Page->Cache->add('inpsyde_user_'.$id,$data);

		}
		
		//Data transform
		
		$user = $data;
		
		unset($user->address->geo);
		
		//Build data
		
		$responseData = [
			'id' => $user->id,
			'name' => $user->name,
			'username' => $user->username,
			'email' => $user->email,
			'address' => implode('<br>',(array)$user->address),
			'phone' => $user->phone,
			'website' => $user->website,
			'company' => $user->company->name,
		];
		
		//Add to cache

		$response->data = $responseData;
		
		return true;
		
	}
	
}