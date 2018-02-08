<?php

/**
 * Simple FFmpeg helper class
 */
class ffmpeg
{

    public function __construct($ffmpeg_directory)
    {
        $this->ffmpeg_path = $ffmpeg_directory . "/ffmpeg";
        $this->ffprobe_path = $ffmpeg_directory . "/ffprobe";
        $root_dir = '../../../';
        $this->thumbs_path = getcwd() . $root_dir . "/thumbs/";
    }

    /**
     * Get video and codec info using ffprobe
     *
     * @param string $in_file (path to file)
     * @return array
     */
    function ffprobe_info($in_file)
    {
        $cmd = $this->ffprobe_path . ' -v quiet -print_format json -show_format -show_streams "' . $in_file . '"';
        exec($cmd, $output, $status);

        return json_decode(implode('', $output), true);
    }

    /**
     * Generate frames from video using ffmpeg
     *
     * @param string $in_file (path to file)
     * @param string $out_folder (path to thumbs folder)
     * @param string $count (how many frames to render from video)
     * @return bool
     */
    function ffmpeg_screens($in_file, $out_folder, $count = 5)
    {
        $out_file = $this->thumbs_path . $out_folder;

        if (!in_array('exec', explode(', ', ini_get('disable_functions')))) {
            //Make thumbs dir
            if (!file_exists($out_file)) {
                mkdir($out_file, 755, true);
            }

            //Get ffprobe info
            $stream_info = $this->ffprobe_info($in_file);
            $stream_length = floor($stream_info['format']['duration']) / $count;

            //Loop exec and get video frames
            for ($i = 0; $i < $count; $i++) {
                set_time_limit(0);
                ignore_user_abort(true);
                $cmd = $this->ffmpeg_path . ' -ss ' . date('H:i:s', strtotime("00:00:00") + ($i * $stream_length)) . ' -i "' . $in_file . '" -t 00:00:01 -r 1 -f mjpeg "' . $out_file . '/' . $i . '.png"';
                exec($cmd, $output, $status);
            }
            return true;
        } else {
            return false;
        }
    }
}
