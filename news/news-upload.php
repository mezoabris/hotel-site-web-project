<?php
require_once 'dbaccess.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function create_thumbnail($targetedFile, $thumbnailPath){
    $max_width=720;
    $max_height=480;
    
    list($width, $height) = getimagesize($targetedFile);
    $thumbnail_width = $max_width;
    $thumbnail_height = $max_height;

    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
    $source = imagecreatefromjpeg($targetedFile);

    if ($thumb && $source) {
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $width, $height);
        imagejpeg($thumb, $thumbnailPath);
        imagedestroy($thumb);
        imagedestroy($source);
    } else {
        echo "Failed to create thumbnail.";
    }    
}

$filename=$_FILES['picture']['name'];
$tmp_path=$_FILES['picture']['tmp_name'];
$targetDir = "news/uploads/";
$thumbnailDir= $targetDir. "thumbnail_of_uploads/";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));
$targetedFile=$targetDir . $filename;

if (!is_dir($thumbnailDir)) {
    mkdir($thumbnailDir, 0777, true);  // Create directory with necessary permissions
}

if (!empty($tmp_path) && file_exists($tmp_path)) {
    $check = getimagesize($tmp_path);
    if ($check !== false) {
        if ($imageFileType != 'jpg' && $imageFileType != 'jpeg') {
            echo 'Format unsupported. Only JPG and JPEG are allowed.';
            $uploadOk = 0;
        }
        if ($uploadOk && move_uploaded_file($tmp_path, $targetedFile)) {
            $thumbnailPath = $thumbnailDir . 'thumb_' . $filename;
            create_thumbnail($targetedFile, $thumbnailPath);

            if (file_exists($targetedFile)) {
                unlink($targetedFile);
             } // Deletes the original file (it was saved 2 times, once in the original resolution and once in thumbnail resolution)
        } else {
            echo "Upload failed.";
        }
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
} else {
    echo "No file uploaded or file upload failed.";
}
