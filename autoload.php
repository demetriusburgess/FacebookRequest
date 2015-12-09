<?php 
	/**
	 * Autoload fucntion
	 *
	 */

	spl_autoload_register(function( $class ) {
		// project-specific namespace prefix
		$prefix = 'DBurgess\\FacebookRequest\\';

		 // base directory for the namespace prefix
		$base_dir = __DIR__ . '/src/';

		// if clase doens't user namespace return
		$len = strlen($prefix);

		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}


		$class_name = substr( $class, $len );


		$file = $base_dir . str_replace('\\', '/', $class_name) . '.php';

		
		if (file_exists($file)) {
			require $file;
		}

	});
?>