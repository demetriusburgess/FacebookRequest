<?php 
	require 'dbfacebook/autoload.php';

	use DBurgess\FacebookRequest\FacebookRequest;

	$args = ['fields' => 'posts.limit(15){id,type,is_published,link,message,name,object_id,properties,shares,source,updated_time,created_time,likes,comments}'];

	$access_token = 'CAAHC ... y8PCf';

	$facebookRequest = new FacebookRequest( $access_token );

	$posts = $facebookRequest->get('/me', $args );

	if ( !empty($post_data) && ($post_data['data'] || $post_data['posts']['data']) ) {
		$pd = $post_data['data'] ? $post_data['data'] : $post_data['posts']['data'];

		foreach ( $pd as $post ) {

			// var_dump($post);
			$post = new FacebookPost ( $post );
				
			if ( $p->is_photo() ) {
				$imgfields = ['fields' => 'id,album,from,height,width,images,link,name,backdated_time,picture,tags'];

				$img = $fbr->get('/' . $p->object_id, $imgfields );

				$post->set_images( $img['picture'], $img['images'] );
			}

			if ( $p->is_video() ) {
				$vidfields = ['fields' => 'id,from,backdated_time,picture,tags,captions,permalink_url,source,length,format,thumbnails'];
				
				$m;
				preg_match("/([0-9]+)/", $p->link, $m);
				$vid_id = $m[0];

				$post->object_id = $m[0];

				$vid = $fbr->get('/' . $p->object_id , $vidfields);

				$post->set_videos( $vid['format'] );
			}

			$posts[] = $post;
		}
	}

?>