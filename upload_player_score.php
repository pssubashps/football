 <?php
require_once 'vendor/autoload.php';
use Subash\Classes\UploadFileLog;
require_once 'header.php';
?>
<?php

$objFileLog = new UploadFileLog();
ini_set("upload_max_filesize", '10M');
// print_r($_POST);exit;
if (isset($_POST["submit"])) {
    // echo "<pre>";print_r($_FILES);
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    
    // Check if image file is a actual image or fake image
    
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $objFileLog->createUploadFileLog($target_file);
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>
<div class="col-sm-9">

	<hr>

	<h4>Upload CSV</h4>
	<form role="form" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<input type="file" name="fileToUpload">
		</div>




		<button type="submit" class="btn btn-success" name="submit">Submit</button>
	</form>
	<br> <br>

</div>
</div>
</div>
</div>

<?php require_once 'footer.php';?>