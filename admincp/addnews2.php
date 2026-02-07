<?php
    // Lay du lieu tu form
    require("./db/connect.php");
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/','-', $name)));
    $description = $_POST['description'];
    $summary = $_POST['summary'];
    $category = $_POST['category'];
    //$thumbnail = $_POST['thumbnail'];

    // Xu ly hinh anh


    // $imgs = '';
    // for($i=0; $i<$countfiles; $i++) {
        $filename = $_FILES['thumbnails']['name'];
        ## location
        $location = "uploads/news/".uniqid().$filename;
        
        $extension = pathinfo($location, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        ## file upload allowed extensions
        $valid_extensions = array("jpg", "jpeg", "png");

        $response = 0;
        ## check file extension
        if(in_array(strtolower($extension), $valid_extensions)) {
            ## them vao csdl - them thanh cong moi upload len
            ## upload file
            if(move_uploaded_file($_FILES['thumbnails']['tmp_name'], $location)) {
                //echo "file name : ".$filename."<br/>";
                //$totalFileUploaded++;
                // $imgs .= $location . ";";
               
            }
        }

    // $imgs = substr($imgs, 0, -1);   
    //echo substr($imgs, 0, -1); exit;

    $sql_str = "INSERT INTO `news` ( `title`, `thumbnails`, `slug`, `description`, `summary`, `newscategory_id`, `created_at`, `updated_at`) VALUES
    ('$name', '$location', '$slug', '$description', '$summary', '$category', NOW(), NOW());";
    //echo $sql_str; exit;
    mysqli_query($conn, $sql_str);

    header("location: listnews.php");
?>