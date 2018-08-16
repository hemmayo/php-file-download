<?php
    require 'vendor/autoload.php';
    require 'controller.php';
    if(isset($_GET['download'])){
        if (isset($_GET['file']) && basename($_GET['file']) == $_GET['file']) {
            $filename = $_GET['file'];
        } 
        else {
            $filename = NULL;
        }
        $temp_name = !empty($filename) ? $filename : '';
        if(!downloadFile($temp_name)){
            require 'pages/404.php';
        }
    }
    elseif(isset($_GET['view'])){
        $files = fetchFiles();
        if(!$files){
            require 'pages/404.php';
        }
        foreach($files as $file){
            $temp_file= createTempFile($file->id);
            $temp_name = $temp_file['name'];
            echo 'File - '.$file->name.'<br>';
            echo "<a href='?download&file=".$temp_name."'>Download</a><br>";
        }
    }
?>