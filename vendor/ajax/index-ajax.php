<?php
include '../SrtParser/Caption.php';
include '../SrtParser/Parser.php';
include '../SrtParser/Time.php';

use Benlipp\SrtParser\Parser;

session_start();
$root_dir = '../../';

if (isset($_POST['getThumbs'])) {
    if (glob($root_dir . "videos/*.MP4") == true) {
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
        $my_directory = 'C:/ffmpeg/bin/';
        $ffmpeg = new ffmpeg($my_directory);
        $ffmpeg->ffmpeg_screens($video, $video_path['filename'], $frame_count);
        echo "Generate thumbnails";
    }
}