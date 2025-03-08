<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

            
if (isset($_POST['uploadButton'])) {
    $title = htmlspecialchars($_POST['news-title']);
    $content = htmlspecialchars($_POST['news-content']);
    $_SESSION['title'] = $title;
    $_SESSION['content'] = $content;
    include "news/news-upload.php";  
    // echo "<div class='mt-4'>";
    // echo "<h5 class='text-primary text-center'>$title</h5>";
    // Display thumbnail if it exists in the thumbnail directory
    $thumbnailPath = "news/uploads/thumbnail_of_uploads/thumb_" . basename($_FILES['picture']['name']);
    if (file_exists($thumbnailPath)) {
        $_SESSION['thumnailPath'] = $thumbnailPath;

    }
    if (!empty($targetedFile)) { // $targetedFile is set in `news-upload.php`
        $sql = "INSERT INTO news (title, content, thumbnail_path, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $title, $content, $thumbnailPath);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success mt-3'>News uploaded successfully!</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error saving news: " . $conn->error . "</div>";
        }

        $stmt->close();
    }

}

?>
<main class="flex-grow-1">
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
        <h4 class="card-title text-center mb-4 text-primary">Upload a File</h4>
        <form action="" enctype="multipart/form-data" method="post">
            <!-- Title -->
            <div class="form-group mb-3">
                <label for="news-title" class="form-label">Title</label>
                <input type="text" id="news-title" name="news-title" class="form-control" placeholder="Enter the title" required>
            </div>
            <!-- Content -->
            <div class="form-group mb-3">
                <label for="news-content" class="form-label">Content</label>
                <textarea name="news-content" id="news-content" class="form-control" rows="5" placeholder="Content goes here..." required></textarea>
            </div>
            <!-- File Input -->
            <div class="form-group mb-4">
                <label for="file-input" class="form-label">Select File</label>
                <input type="file" name="picture" id="file-input" class="form-control" required>
            </div>
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100" name="uploadButton">
                Upload
            </button>
        </form>

    </div>
</div>
    </main>