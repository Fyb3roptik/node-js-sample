<?php
require_once 'inc/global.php';

$action = request_var('action');

switch($action) {
	case 'process_new_image': {
		$AV = new Attribute_Value(post_var('attribute_value_id'));
		if(true == $AV->exists()) {
			$file = exists('new_attribute_value_image', $_FILES);

			if(true == is_array($file)) {
				$file_info = pathinfo($file['name']);
				$new_filename = DIR_ROOT . 'images/av/' . $AV->ID . '.' . $file_info['extension'];
				rename($file['tmp_name'], $new_filename);
				if(true == file_exists($new_filename)) {
					$AV->image = '/images/av/' . $AV->ID . '.' . $file_info['extension'];
					$AV->write();
					$MS->add('attributes', 'File successfully uploaded!', MS_SUCCESS);
				} else {
					$MS->add('attributes', 'Error processing uploaded file.', MS_ERROR);
				}
			}
		}
		$VIEW = 'attribute_value_images.php';
		break;
	}

	default: {
		$VIEW = 'attribute_value_images.php';
		break;
	}
}

require_once 'layouts/default.php';
?>