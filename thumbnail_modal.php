<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');

use Benlipp\SrtParser\Parser;

if (glob("./videos/" . $_POST['filename'] . ".mp4") == true) {
    $video = getPathArray(0);
    $parser = new Parser();
    $parser->loadFile("videos/" . $_POST['filename'] . ".srt");
    $captions = $parser->parse();
    $numFrames = count($captions);
    $frame_count = $numFrames; //Amount of frames to render from video
}
?>

<script type="text/javascript" src="vendor/bootstrap-4.0.0-dist/frame_rotator.js"></script>
<script type="text/javascript">
    /* how many frames to loop */
    frameRotator.frames = <?php echo $frame_count ?>;
</script>
<div class="modal fade" id="thumbnails_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Responsive image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                if (glob("./videos/*.mp4") == true) {
                    if (!file_exists('./thumbs/' . $_POST['filename'] . '/0.png')): ?>
                        <p>
                            <span id="notice">Video not yet processed. Please click "Make thumbnails" button</span>
                        </p>
                    <?php else: ?>
                        <p>Roll over the image and wait for a few seconds.</p>

                        <img src="./thumbs/<?php echo $_POST['filename'] ?>/0.png" width="750" height="400"
                             onmouseover="frameRotator.start(this)"
                             onmouseout="frameRotator.end(this)"/>
                        <a href="?rmthumb=<?= $_POST['filename'] ?>">Remove all thumbnails of this video</a>
                    <?php endif;
                } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>