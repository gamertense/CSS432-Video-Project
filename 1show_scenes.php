<!-- Grid column -->
<div class="col-md-8 mb-4 offset-md-2">

    <div class="card">
        <div class="card-block p-3">
            <!--Title-->
            <h3 class="text-center font-up font-bold indigo-text py-2 mb-3"><strong>Responsive
                    image</strong></h3>

            <?php
            if (glob("./videos/*.mp4") == true) {
                if (!file_exists('./thumbs/' . $video_path['filename'] . '/0.png')): ?>
                    <p>
		<span id="notice">Video not yet processed
            <button name="makeThumbnails" class="btn btn-danger btn-md">Click here</button>
            <!--			<a href="?make_thumbs" onClick="document.getElementById('notice').innerHTML='Processing please wait...';">click here</a>-->
		</span>
                    </p>
                <?php else: ?>
                    <p>Roll over the image and wait for a few seconds.</p>

                    <img src="./thumbs/<?php echo $video_path['filename'] ?>/0.png" width="700" height="400"
                         onmouseover="frameRotator.start(this)"
                         onmouseout="frameRotator.end(this)"/>

                <?php endif;
            } ?>
            <a href="?loadsrt">Show subtitle file</a>
        </div>
    </div>

</div>
<!-- Grid column -->