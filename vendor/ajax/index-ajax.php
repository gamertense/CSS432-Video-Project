<?php
include '../SrtParser/Caption.php';
include '../SrtParser/Parser.php';
include '../SrtParser/Time.php';
include_once('../../functions.php');

use Benlipp\SrtParser\Parser;

$root_dir = '../../';

//This is not AJAX - just don't want to create a new PHP file.
if (isset($_FILES['vid_file'])) {
    getVideo($root_dir);
    header('Location: ' . $root_dir . "index.php");
}

if (isset($_POST['getThumbs'])) {
    if (glob($root_dir . "videos/*.mp4") == true) {
        $video = $root_dir . "videos/Acer Nitro 5.mp4"; //path to video
        $parser = new Parser();
        $parser->loadFile($root_dir . 'videos/Acer Nitro 5 sub.srt');
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
        echo "Generate thumbnails";
    }
}
