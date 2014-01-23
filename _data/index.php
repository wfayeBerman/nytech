<?php

	require_once realpath(dirname(__FILE__).'/../../../..').'/wp-load.php';

	class BaseClass {
		private $vars = array(
			'baseURL' => '/~illatoz/wp-content/themes/illatoz_wp/_data/',
			'routes' => array(
				'people' => 'listPeople'
			),
		);

		public function __construct() {
			$this->parseURL();
			// $this->debug();
		}

		private function parseURL(){
			$requestURI = explode("/", str_replace($this->vars['baseURL'], '', $_SERVER['REQUEST_URI']));
			switch ($requestURI[0]) {
				case 'query':
					$this->vars['query'] = $requestURI[0];
					$this->vars['request'] = $requestURI[1];
					$this->vars['query_string'] = $requestURI[2];
				break;
				
				default:
					$this->vars['request'] = $requestURI[0];
					$this->vars['post_id'] = $requestURI[1];

					$this->getData();
				break;
			}
		}

		private function getData(){
			$returnData;
			foreach ($this->vars['routes'] as $key => $value) {
				if($this->vars['request'] == $key){
					if($this->vars['post_id'] == ""){
						$this->returnJSON(call_user_func($value, 'array'));
					} else {
						$jsonData = call_user_func($value, 'array');
						foreach ($jsonData as $key1 => $value1) {
							if($this->vars['post_id'] == $value1['post_id']){
								$this->returnJSON($value1);
							}
						}
					}
				}
			}
		}

		private function returnJSON($dataSent){
			header('Content-type: application/json');
			echo json_encode($dataSent, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);		
		}

		private function debug(){
			echo "<pre>";
				print_r($this->vars);
			echo "</pre>";
		}
	}

	$obj = new BaseClass();

?>