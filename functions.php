<?php
function getVideo($root_dir)
{
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
                $fileNameNew = $vidName . "." . $fileActualExt; //unique name
                $fileNameNew = str_replace(" ", "_", $fileNameNew);
                $fileDestination = $root_dir . 'videos/' . $fileNameNew;
                //Add image
                move_uploaded_file($vid_fileTmpName, $fileDestination);
                return $fileNameNew;
            } else {
                echo "Your file is too big.";
            }
        } else {
            echo "Upload file error! Code: " . $vid_fileError;
        }
    } else {
        echo "Video type is invalid.";
    }
}

function exclude_word($word, array $exclude_list)
{
    $w = array($word);
    if (array_diff($w, $exclude_list)) {
        return true;
    } else {
        return false;
    }
}

function getPathArray($file_extension)
{ //'0' for mp4, '1' for srt
    if ($file_extension == 0) {
        $path = glob("./videos/*.mp4");
    } elseif ($file_extension == 1) {
        $path = glob("./videos/*.srt");
    }
    return $path;
}

function uploadedFilesHTML()
{
    foreach (glob("videos/*") as $filename) {
        $filename = str_replace("videos/", "", $filename);
        $filenamewithoutext = str_replace(".mp4", "", $filename); ?>
        <li class="list-group-item">
            <?php if (checkFileExt($filename, '.mp4')) { ?>
                <a href="" data-toggle="modal" data-id="<?= $filename ?>"
                   class="thumbLink"><?= $filename ?></a>
            <?php } else echo $filename;
            if (checkFileExt($filename, '.mp4')) {
                //Show gen auto sub button
                $files = glob("videos/*");
                if (!in_array("videos/" . $filenamewithoutext . ".srt", $files)) { ?>
                    <button id="<?= $filename ?>" name="gensub" class="btn btn-warning btn-sm">Gen auto sub <i
                                class="fa fa-plus"></i>
                    </button>
                <?php } //Show Make thumbnails button
                else if (!file_exists('thumbs/' . $filenamewithoutext . '/0.png')) { ?>
                    <button id="<?= $filename ?>" name="makethumb" class="btn btn-success btn-sm">Make thumbnails <i
                                class="fa fa-edit"></i>
                    </button>
                <?php } ?>
            <?php } ?>
            <a class="removeFile" name="<?= $filename ?>"><i
                        class="fa fa-trash"
                        style="font-size:24px;color:red"></i></a>
        </li>
    <?php }
}

function checkFileExt($filename, $findme)
{
    $pos = strpos($filename, $findme);
    if ($pos === false)
        return false;
    return true;
}

function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}