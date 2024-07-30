<?php 
$serial_no = "";
if (isset($_GET["serial_no"])) {
    $serial_no = $_GET["serial_no"];
}

$directory = "uploads/";
$files = glob($directory . "*.{jpg,jpeg,png,gif,jfif,pdf}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Image Gallery</title>
<style>
    .gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .gallery img,
    .gallery embed {
        max-width: 200px;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, opacity 0.5s ease-in-out;
        opacity: 0;
        transform: scale(0.9);
    }
    .gallery img.visible,
    .gallery embed.visible {
        opacity: 1;
        transform: scale(1);
    }
    .image-container {
        cursor: pointer;
        display: inline-block;
    }
</style>
</head>
<body>

<div class="gallery">
    <?php 
    foreach ($files as $file) {
        $filename = basename($file);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($serial_no === "" || strpos($filename, $serial_no) === 0) {
            echo '<div class="image-container" onclick="showFile(\'' . $directory . $filename . '\', \'' . $ext . '\')">';
            if ($ext === 'pdf') {
                echo '<embed src="' . $directory . $filename . '" type="application/pdf" />';
            } else {
                echo '<img src="' . $directory . $filename . '" alt="Image">';
            }
            echo '</div>';
        }
    }
    ?>
</div>

<div id="lightbox" style="display:none;">
    <span onclick="closeLightbox()" style="position:absolute; top:10px; right:10px; font-size:30px; cursor:pointer;">&times;</span>
    <div id="lightboxContent" style="max-width:90%; margin:auto; display:block; max-height:80vh;"></div>
</div>

<script>
function showFile(src, ext) {
    var lightbox = document.getElementById('lightbox');
    var lightboxContent = document.getElementById('lightboxContent');
    if (ext === 'pdf') {
        lightboxContent.innerHTML = '<embed src="' + src + '" type="application/pdf" style="width:100%; height:100%;"/>';
    } else {
        lightboxContent.innerHTML = '<img src="' + src + '" style="max-width:100%; max-height:100%;" />';
    }
    lightbox.style.display = 'block';
    setTimeout(() => {
        lightboxContent.style.opacity = '1';
    }, 100);
}

function closeLightbox() {
    var lightbox = document.getElementById('lightbox');
    var lightboxContent = document.getElementById('lightboxContent');
    lightboxContent.style.opacity = '0';
    setTimeout(() => {
        lightbox.style.display = 'none';
    }, 300);
}

// Fade-in effect for images on page load
window.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('.gallery img, .gallery embed');
    images.forEach((img, index) => {
        setTimeout(() => {
            img.classList.add('visible');
        }, index * 100);
    });
});
</script>

</body>
</html>
