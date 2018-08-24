<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

    function set_env(){
        $env = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/.env'));
        foreach ($env as $key) {
            putenv($key[0].'='.$key[1]);
            $_ENV[$key[0]]=$key[1];
        }
    }

    set_env();

    $cleardb_url = parse_url(!empty(getenv('CLEARDB_DATABASE_URL')) ? getenv('CLEARDB_DATABASE_URL') : $_ENV['DB_URL']);
    // Create a connection, once only.
    $config = array(
        'driver'    => 'mysql', // Db driver
        'host'      => $cleardb_url["host"] ,
        'database'  => substr($cleardb_url["path"],1),
        'username'  => $cleardb_url["user"],
        'password'  => $cleardb_url["pass"],
        'charset'   => 'utf8', // Optional
        'collation' => 'latin1_swedish_ci', // Optional
        'options'   => array( // PDO constructor options, optional
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_EMULATE_PREPARES => false,
        ),
    );

    new \Pixie\Connection('mysql', $config, 'DB');

    function auth_enable(){
        if(!isset($_SESSION['id'])){
            header("Location: ./");
        }
    }

    function fetchFileID($temp_name = ''){
        if(!empty($temp_name)){
            $row = DB::table('temp_files')->find($temp_name, 'name');
            if($row){
                if(time() <= $row->expire_at){
                    return $row->file_id;
                }
                else{
                    deleteTempFile($temp_name);
                }
            }
        }
        return false;
    }
    
    function fetchFile($id = ''){
        if(!empty($id)){
            $row = DB::table('files')->find($id);
            if($row){
                return $row;
            }
        }
        return false;
    }

    function downloadFile($temp_name = ''){
        $fileID = fetchFileID($temp_name);
        if($fileID){
            $file = fetchFile($fileID);
            if($file){
                $file_name = $file->name.'.'.$file->type;
                $file_location = $file->location;
                $path = 'uploads/'.$file_location;

                // check that file exists and is readable
                if (file_exists($path) && is_readable($path)) {
                    // get the file size and send the http headers
                    $size = filesize($path);
                    switch ($file->type) 
                    {
                      case "pdf": $ctype="application/pdf"; break;
                      case "exe": $ctype="application/octet-stream"; break;
                      case "zip": $ctype="application/zip"; break;
                      case "doc": $ctype="application/msword"; break;
                      case "xls": $ctype="application/vnd.ms-excel"; break;
                      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
                      case "gif": $ctype="image/gif"; break;
                      case "png": $ctype="image/png"; break;
                      case "jpeg":
                      case "jpg": $ctype="image/jpg"; break;
                      default: $ctype="application/force-download";
                    }
                    header('Content-Type: '.$ctype);
                    header('Content-Length: '.$size);
                    header('Content-Disposition: attachment; filename='.$file_name);
                    header('Content-Transfer-Encoding: binary');
                    // open the file in binary read-only mode
                    // display the error messages if the file can´t be opened
                    $file = @ fopen($path, 'rb');
                    if ($file) {
                        // stream the file and exit the script when complete
                        fpassthru($file);
                        exit;
                    } 
                    else {
                        echo $err;
                    }
                } 
                else {
                    echo $err;
	            }
                return true;
            }
        }
        return false;
    }

    function deleteTempFile($temp_name = ''){
        if(!empty($temp_name)){
            if(DB::table('temp_files')->where('name', '=', $temp_name)->delete()){
                return true;
            }
        }
        return false;
    }

    function fetchFiles(){
        $query = DB::table('files')->orderBy('id', 'DESC');
        $data = $query->get();
        if($data){
            return $data;
        }
        return false;
    }

    function createTempFile($file_id = ''){
        $file = fetchFile($file_id);
        if($file){
            $temp_name = md5(session_id().rand(10, 400).md5(time())).'-'.$file->name;
            $expire_at = time()+1000;
            $data = [
                "file_id" => $file_id,
                "name" => $temp_name,
                "expire_at" => $expire_at
            ];
            $insertId = DB::table('temp_files')->insert($data);
            if ($insertId) {
                return ["name"=> $temp_name, "expire_at" => $expire_at];
            }
        }
        return false;
    }
    function addFile($data=[])
    {   
        if(!empty($data)){
            $insertId = DB::table('files')->insert($data);
            if ($insertId) {
                return true;
            }
        }
        return false;
    }

function file_upload_max_size() {
  static $max_size = -1;

  if ($max_size < 0) {
    // Start with post_max_size.
    $post_max_size = parse_size(ini_get('post_max_size'));
    if ($post_max_size > 0) {
      $max_size = $post_max_size;
    }

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size(ini_get('upload_max_filesize'));
    if ($upload_max > 0 && $upload_max < $max_size) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size($size) {
  $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
  $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
  if ($unit) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
  }
  else {
    return round($size);
  }
}

    function uploadFileController(){
        $errors = [];
        $valid = true; 
        $success = false;
        $response = [];
        if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
        {
            $valid = false;
            $errors[] = "Upload a file!";
        }
        if($_FILES['file']['size'] > file_upload_max_size()){
            $valid = false;
            $errors[] = "Exceeded file upload limit!";
        }
        // if (empty($_POST['title'])) {
        //     $valid = false;
        //     $errors[] = "Title field is required";
        // }
        
        $response = ["errors" => $errors];
        if($valid == true){
            $filename = str_replace(' ', '-', basename($_FILES["file"]["name"]));
            $file_name = pathinfo($filename, PATHINFO_FILENAME);
            $file_type =  strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $file_size = $_FILES["file"]["size"];
            $uploaded_file_name = $_FILES["file"]["tmp_name"];
            $destination = $file_name.time();
            $mf = move_uploaded_file($uploaded_file_name, 'uploads/'.$destination);
            if($mf){
                $data = [
                    "name" => $file_name,
                    "type" => $file_type,
                    "size" => $file_size,
                    "location" => $destination,
                ];
                if(addFile($data)){
                    $success = true;
                    $response = ["success" => $success];
                    return $response;
                }
            }
        }
        return false;
    }
?>