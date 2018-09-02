<?php
// $videos_extension = array('avi','mov','wmv','mp4','3gp','3g2','flv','mkv','rm','webp','mpeg4');
$videos_extension = array('mp4');

$file_extension = explode(".", $image);
$file_extension = strtolower(end($file_extension));

if(in_array($file_extension, $videos_extension)) {
    echo '<a href="'.url($image).'" target="new"><img src="'.url('/storage/img/video_icon.png').'" class="img-fluid img-thumbnail"></a>';
} else {
    echo '<img src="'.asset($image).'" class="img-fluid img-thumbnail">';
}
?>
<!-- <img src="{{ asset($image) }}" class="img-fluid img-thumbnail"> -->
