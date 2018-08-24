<?php
    require 'src/controller.php';

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
    elseif(isset($_GET['upload']) && isset($_FILES['file'])){
        $uf = uploadFileController();
        if(isset($uf['success'])){
            header('Location: ./');
        }
    }
    
?>


    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Upload File</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous">
        <!-- UIkit CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/css/uikit.min.css" />
        <link href="https://fonts.googleapis.com/css?family=Mukta" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384--JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

        <!-- UIkit JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/js/uikit.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-rc.10/js/uikit-icons.min.js"></script>
    </head>

    <body>

        <div class="uk-background-cover uk-background-fixed uk-section">
            <div class="uk-container">
                <div>
                    <img src="img/upload.png" width="50" height="50">
                    <span class="uk-text-middle uk-margin-left">Upload</span>
                </div>

                <div class="uk-width-1-2" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p>Max upload size of 2MB</p>
                </div>
                <form action="?upload" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file"></label>
                        <input type="file" name="file" class="form-control-file" id="file">
                    </div>
                    <?php
                        if(isset($_FILES['file']) && !isset($uf['success'])){
                            echo '
                            <div class="uk-alert-danger" uk-alert>
                                <a class="uk-alert-close" uk-close></a>
                                <p>An error occured while uploading!</p>
                            </div>
                            <br>';
                        }
                    ?>
                    <button class="btn btn-success uk-button  uk-button-default" title="Upload">Upload File</button>
                </form>
            </div>
        </div>
        <div class="uk-background-cover uk-background-fixed uk-section">
            <div class="uk-container">
                <div>
                    <img src="img/logo.png" width="50" height="50">
                    <span class="uk-text-middle uk-margin-left">File Download</span>
                </div>
                <table class="uk-table">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Download</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
            $files = fetchFiles();
            if(!$files){
                // require 'pages/404.php';
            }
            foreach($files as $file){
                $temp_file= createTempFile($file->id);
                $temp_name = $temp_file['name'];
                echo '
                <tr>
                    <td>'.$file->name.'.'.$file->type.'</td>
                    <td><a href="?download&file='.$temp_name.'">Download</a></td>
                    <td>'.$file->created_at.'</td>
                </tr>
                ';
            }
        ?>

                    </tbody>
                </table>
            </div>
        </div>

    </body>

    </html>