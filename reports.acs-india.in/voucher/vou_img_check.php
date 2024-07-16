<?php 
include('web_acsdb.php');

$serial_no = "";
if (isset($_GET["serial_no"])) {
    $serial_no = $_GET["serial_no"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Image Gallery</title>
<style>
    /* Styling for the gallery */
    .gallery {
        display: flex;
        flex-wrap: wrap;
    }
    .gallery img {
        width: 100px; /* Set the initial size of the images */
        height: auto;
        margin: 5px;
        cursor: pointer;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 800px;
    }
    .modal-content img {
        max-width: 100%;
        max-height: 100%;
    }
</style>
</head>
<body>

<div class="gallery">
    <!-- Clicking on these images will display them in a modal -->
    <?php 
    // Fetch the image data and its type from the database
    $image_query = "SELECT * FROM emp_vou_details WHERE serial_no = '$serial_no'"; 
    $vou_data = mysqli_query($mysqli, $image_query);
    $vou_img_count = mysqli_num_rows($vou_data);

    while ($vou_cont = mysqli_fetch_assoc($vou_data)) {
        if (!empty($vou_cont['given_proof']) && !empty($vou_cont['given_proof_type'])) {
            // Output the image
            $imageData = $vou_cont['given_proof'];
            $imageType = $vou_cont['given_proof_type'];
            $base64Image = base64_encode($imageData);
            $src = "data:$imageType;base64,$base64Image";
            echo '<img src="' . $src . '" onclick="openModal(\'' . $src . '\')" alt="Image">';
        } else {
            echo "They are don't add any attachments ";
        }
    }
    ?>
</div>

<!-- The modal -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-content">
        <img id="modal-img" src="" alt="Modal Image">
    </div>
</div>

<script>
    // Function to open the modal and display the clicked image
    function openModal(imageSrc) {
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("modal-img");
        modal.style.display = "block";
        modalImg.src = imageSrc;
    }

    // Function to close the modal
    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }
</script>

</body>
</html>
