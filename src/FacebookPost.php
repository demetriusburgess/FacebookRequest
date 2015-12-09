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

	class FacebookPost {

		public $id;

		public $object_id;

		private $name;

		private $type;

		private $post_date;

		private $last_update;

		private $images = [];

		private $videos = [];

		public $link;

		private $likes = [];

		private $like_count = 0;

		private $comments;

		private $tags;

		private $shares;

		private $content;


		public function __construct( array $post_data = [] )  {
			$this->id = $post_data['id'];
			$this->link = $post_data['link'];
			$this->type = $post_data['type'];
			$this->post_date = $post_data['created_time'];
			$this->last_update = $post_data['updated_time'];
			$this->comments = $post_data['comments'];

			if ( $this->is_photo() || $this->is_video() ) {
				$this->object_id = $post_data['object_id'];
			}

			$this->content = $post_data['message'];

			$this->shares = $post_data['shares'];
		}

		public function get_image( $size = '' ) {
			if ( !$this->is_photo() ) {
				return null;
			}

			if ( !$size ) {
				return $this->images['full'];
			}

			if ( in_array($size, ['full', 'medium', 'small', 'thumbnail']) ) {
				return $this->images[ $size ];
			}

			return null;
		}

		public function set_images( $thumnail = null, array $images = [] ) {


			if ( sizeof($images) > 0 ) {
				$this->images['all'] = $images;
				$size = count( $images );
				$mid = $size == 3 ? 2 : ceil( $size/2 );
				
				usort( $this->images['all'], 'cmpf' );

				$this->images['thumnail']['source'] = $thumnail;
				$this->images['thumnail']['width'] = $thumnail;
				$this->images['small']    = current($this->images['all']);
				$this->images['medium']   = $this->images['all'][$mid - 1];
				$this->images['full']     = end($this->images['all']);
			}
		}

		public function set_videos ( $videos ) {
			$this->videos['all'] = $videos;
		}

		public function get_permalink() {
			if ( !empty( $this->link ) ) {
				return $this->link;
			}

			return null;
		}

		public function is_status() {
			return $this->type == 'status';
		}

		public function is_photo() {
			return $this->type == 'photo';
		}

		public function is_video() {
			return $this->type == 'video';
		}
	}
?>