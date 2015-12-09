<?php 
	/**
	 * 
	 *
	 *	@license
	 */

	namespace DBurgess\FacebookRequest;

	/**
	 *
	 * @class Response
	 * @author Demetrius Burgess <dem.burgess@gmail.com>
	 *
	 */

	class Response {

		private $header = [];

		private $body = [];

		private $response_code = 0;

		public function get_response_code() {
			return $this->$response_code;
		}

		public function get_header() {
			return $this->header;
		}

		public function set_header( $header ) {
			$this->header = $header;
		}

		public function get_body() {
			return $this->body;
		}

		public function set_body( $body ) {
			$this->body = $body;
		}
	}

?>