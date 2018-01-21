<?php

function getVideo($vid_file) {

  	$vid_file = $_FILES['vid_file'];
	//extract data of img_file
	$vid_fileName = $_FILES['vid_file']['name']; //name of the file
	$vid_fileTmpName = $_FILES['vid_file']['tmp_name']; //create tmp of the file
	$vid_fileSize = $_FILES['vid_file']['size'];
	$vid_fileError = $_FILES['vid_file']['error'];
	$vid_fileType = $_FILES['vid_file']['type'];

	$fileExt = explode('.', $vid_fileName);
	$vidName = $fileExt[0];
	$fileActualExt = strtolower(end($fileExt)); //make all ext be lowercase

	$allowd = array('mp4');

	if (in_array($fileActualExt, $allowd)) {
		if ($vid_fileError === 0) {
			if ($vid_fileSize < 10000000000) {

				$fileNameNew = $vidName.".".$fileActualExt; //unique name
		        $fileDestination = './videos/'.$fileNameNew;
		        //Add image
		        move_uploaded_file($vid_fileTmpName, $fileDestination);

		        return $fileNameNew;
		    }
			else { echo "Your file is too big."; }
		}
		else { echo "There was an error uploading the file."; }
	}
	else { echo "Video type is invalid."; }
}

function exclude_word($word, array $exclude_list) {
	$w = array($word);
	if (array_diff($w, $exclude_list)) {
		return true;
	}
	else {
		return false;
	}
}