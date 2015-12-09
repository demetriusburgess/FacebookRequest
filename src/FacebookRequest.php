<?php 
	/**
	 * 
	 *
	 *	@license
	 */

	namespace DBurgess\FacebookRequest;

	/**
	 *
	 * @author Demetrius Burgess <dem.burgess@gmail.com>
	 */

	class FacebookRequest {

		const API_HOST = 'https://graph.facebook.com/v2.5';

		private $response;

		private $access_token;

		public function __construct( $access_token ) {
			$this->access_token = $access_token;
			$this->reset_response();
		}

		/**
		 * Returns last response
		 *
		 * @return Object
		 */
		public function get_last_response () {
			return $this->response;
		}


		/**
		 * Make HTTP GET request 
		 * 
		 * @param string  $parth
		 * @param array   $params
		 * @param boolean $rawdata
		 * 
		 * @return array or string
		 */
		public function get( $path, array $params = [], $rawdata = false ) {
			$p = $params;
			$p['access_token'] = $this->access_token;

			return $this->request( self::API_HOST . $path, 'GET', $p, $rawdata );
		}

		/**
		 * Make HTTP GET request 
		 * 
		 * @param string  $parth
		 * @param array   $params
		 * @param boolean $rawdata
		 * 
		 * @return array or string
		 */
		public function post( $path, array $params = [], $rawdata = false ) {
			$p = $params;
			$p['access_token'] = $this->access_token;

			return $this->request( self::API_HOST . $path, 'POST', $p, $rawdata );
		}

		/**
		 * Make HTTP DELETE request 
		 * 
		 * @param string  $parth
		 * @param array   $params
		 * @param boolean $rawdata
		 * 
		 * @return array or string
		 */
		public function delete( $path, $rawdata = false ) {
			$p = $[];
			$p['access_token'] = $this->access_token;

			return $this->request( self::API_HOST . $path, 'DELETE', $p, $rawdata );
		}


		/**
		 * Creates new Response object
		 * 
		 * @return void
		 */
		private function reset_response() {
			if ( !empty($this->repsonse) ) {
				unset( $this->response );
			}

			$this->response = new Response();
		}


		/**
		 * Make HTTP request 
		 * 
		 * @param string $url
		 * @param string $method
		 * @param array  $postfields
		 * @param boolean $rawdata
		 * 
		 * @return array or string
		 */
		private function request( $url, $method, $postfields, $rawdata = false ) {
			
			$options = [
				CURLOPT_CONNECTTIMEOUT => '0', 
				CURLOPT_HEADER => true,
				CURLOPT_HTTPHEADER => ['Accept: application/json'],
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_URL => $url,
			];


			switch ( $method ) {
				case 'GET':
					break;
				case 'POST':
					$options[CURLOPT_POST] = true;
					$options[CURLOPT_POSTFIELDS] = $postfields;
					break;
				case 'PUT':
					$options[CURLOPT_CUSTOMREQUEST] = 'PUT';
					break;
				case 'DELETE': 
					$options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
					break;
			}


			if ( in_array($method , ['GET', 'PUT', 'DELETE']) && !empty( $postfields ) ) {
				$options[CURLOPT_URL] .= '?' . http_build_query( $postfields );
			}

			$this->reset_response():

			$curl_handle = curl_init();
			curl_setopt_array( $curl_handle, $options );
			$response = curl_exec( $curl_handle );	

			// write some sort of error handining here


			// Split repsonse into to Arrays
			$parts = explode("\r\n\r\n", $response);

			$response_body = array_pop($parts);
			$response_header = array_pop($parts);

			$this->response->set_header( $this->parse_header($response_header) );

			if ( $rawdata ) {
				$this->response->set_body( $response_body );
			} else {
				$this->response->set_body( json_decode($response_body, true) );
			}
			

			curl_close( $curl_handle );

			return $this->response->get_body();
		}


		/**
		 * Converts array into urlencoded String
		 *
		 * @param array $params
		 * 
		 * @return string
		 */
		private function build_querystring( $params ) {
			$size = sizeof($params);
			$i = 0;
			$query = '';

			foreach ($params as $key => $value) {
				if ( $i < $size ) {
					$query .= $key . '=' . $value;
				}

				if ( $i < $size - 1 ) {
					$query .= '&';
				}

				$i++;
			}

			return $query;
		}


		/**
		 * Converts Header String to $key => $value array
		 *
		 * @param string $header
		 * 
		 * @return array 
		 */
		private function parse_header( $header ) {
			$headers = [];

			$header_by_lines = explode("\r\n", $header);

			foreach ($header_by_lines as $line ) {
				list($key , $value) = explode(": ", $line);
				
				$headers[ $key ] = $value;
			}

			return $headers;
		}
	}
?>