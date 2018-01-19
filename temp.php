<?php

include_once('functions.php');

if (isset($_POST['submit']) && isset($_FILES['vid_file'])) {
    getVideo($_FILES['vid_file']);
    
    header("Location: ./index.php");
	exit();
}
else {
	echo "Upload error";
}





