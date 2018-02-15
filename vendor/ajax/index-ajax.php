<?php
include '../SrtParser/Caption.php';
include '../SrtParser/Parser.php';
include '../SrtParser/Time.php';
include_once('../../functions.php');

use Benlipp\SrtParser\Parser;

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
$root_dir = '../../';

//This is not AJAX - just don't want to create a new PHP file.
if (isset($_FILES['vid_file'])) {
    getVideo($root_dir);
    header('Location: ' . $root_dir . "index.php");
}

//Handle get thumbnails request
if (isset($_POST['ffmpeg_dir'])) {
    $filenamewithoutext = str_replace(".mp4", "", $_POST['filename']);
    //Check if thumbnails already generated
    if (glob($root_dir . "thumbs/" . $_POST['filename']) == true)
        echo "Thumbnails already generated";
    else {
        $video = $root_dir . "videos/" . $_POST['filename']; //path to video
        $parser = new Parser();
        $parser->loadFile($root_dir . 'videos/' . $filenamewithoutext . '.srt');
        $captions = $parser->parse();
        $numFrames = count($captions);

        $frame_count = $numFrames; //Amount of frames to render from video

        $video_path = pathinfo($video);

        // For simplicity, Generate frames from the video using ffmpeg upon request
        // init ffmpeg helper class
        include($root_dir . 'ffmpeg.php');
//        $my_directory = 'C:/ffmpeg/bin/';
        $ffmpeg_dir = $_POST['ffmpeg_dir'];
        $ffmpeg = new ffmpeg($ffmpeg_dir);
        $ffmpeg->ffmpeg_screens($video, $video_path['filename'], $frame_count);

        echo "Generate thumbnails: ";
    }
}

//When clicking remove file
if (isset($_POST['remove'])) {
    $filename = $_POST['remove'];
    unlink($root_dir . "videos/" . $filename);
    echo "Success";
}

//When clicking gen sub
if (isset($_POST['gensub'])) {
    $filename = $_POST['gensub'];
    $file_des = $root_dir . "videos/" . $filename;
    $python_path = $_POST['python_path'];
    $cmd = $python_path . "\python.exe " . $python_path . "\scripts\autosub_app.py -S en -D en " . $file_des;
    exec($cmd, $output, $status);
    echo $status;
}
