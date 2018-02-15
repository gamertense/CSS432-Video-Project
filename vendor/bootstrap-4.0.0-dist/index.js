function main() {
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    $('.thumbLink').on("click", function () {
        var filename = $(this).data('id').replace(".mp4", "");

        $.post("thumbnail_modal.php", {filename: filename},
            function (data, status) {
                $('#callModal').html(data);
                $('#thumbnails_modal').modal('show');
                Pace.stop();
            });
    });

    $('#thumbnails_modal').on('hidden.bs.modal', function () {
        $('#callModal').html('<div id="callModal"></div>');
    });

    $('a.removeFile').click(function () {
        var filename = $(this).attr("name");

        $.post("vendor/ajax/index-ajax.php", {remove: filename}).done(function (data) {
            alert("Remove status: " + data);
            window.location.reload();
        });
    });

    $('button[name="gensub"]').click(function () {
        var python_path = $('input[name="python_path"]').val();
        if (python_path === '') {
            alert('Please specify Python path');
            return false;
        }

        var filename = $(this).attr("id");
        document.getElementById('genspin').innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";

        $.post("vendor/ajax/index-ajax.php", {gensub: filename, python_path: python_path}).done(function (data) {
            alert("Gen status code: " + data + " (0 = no error)");
            window.location.reload();
        });
    });

    $('button[name="makethumb"]').click(function () {
        var ffmpeg_dir = $('input[name="ffmpeg_dir"]').val();
        if (ffmpeg_dir === "") {
            alert('Please specify FFMPEG path');
            return false;
        }

        var filename = $(this).attr("id");
        document.getElementById('genspin').innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";
        $.post("vendor/ajax/index-ajax.php", {ffmpeg_dir: ffmpeg_dir, filename: filename},
            function (data, status) {
                if (data !== "Thumbnails already generated")
                    alert(data + status);
                else
                    alert(data);
                window.location.reload();
            });
    });
}

$(document).ready(main());