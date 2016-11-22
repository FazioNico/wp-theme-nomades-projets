<?php
add_action( 'wp_ajax_nopriv_copy_distant_folder', 'copy_distant_folder' );
add_action( 'wp_ajax_copy_distant_folder', 'copy_distant_folder' );

add_action( 'wp_ajax_nopriv_delete_local_folder', 'delete_local_folder' );
add_action( 'wp_ajax_delete_local_folder', 'delete_local_folder' );

/* copy_distant_folder() Ajax Call function */
function copy_distant_folder() {
  global $post;
  $result = 0;
  /* Création du répertoire Projet de l'elève */
  // 1. define root website
  //$rootPath = explode("/wp", get_site_url()); // for PROD (http server)
  $rootPath = $_SERVER['DOCUMENT_ROOT']."/"; // for DEV (localhost)
  // 2 Create de $path we need
  $path = $rootPath.$_POST['params']['folder'];
  $password = $_POST['params']['password'];
  $userName = explode('/', $_POST['params']['folder'])[1];
  $projectFolder = 'public_html/'.explode('/', $_POST['params']['folder'])[2];
  //print_r($projectFolder);
  //die();
  // 3 Check if $path existe
  if(file_exists($path)){
    print_r( 'project dir exist! ' );
    die();
  }
  else {
    // connect by FTP
    $ftpResult = ftpConnect();
    if($ftpResult == true){
      // 4 Create $path
      // 5 copy distant files into the created $path
      $copyResult = copyFolder($path,$projectFolder,$userName,$password);
      // 6 returne the result
      if($copyResult == true){
        $resultTXT = 'Successfully copyed!';
        $result = 1;
      }
      else{
        $resultTXT = 'Copy error... there was a problem';
      }
    }
    else{
      $resultTXT = 'Impossible de se connecter au serveur!';
    }

  }
  //print_r($path);
  //print_r('path-> '.$path);
  //print_r('params-> '.$_POST['params']);
  //print_r('pwd-> '.$password."\n");
  //print_r("{'state': $result, 'extract': 'Copy distant Folder result-> $resultTXT\n'}");
  print_r('{"state": '.$result.', "extract": "Copy distant Folder result-> '.$resultTXT.'"}');
	die();
}

function ftpConnect(){
  global $connect_it;
  /* FTP Account */
  $ftp_host = 'ateliers.nomades.ch'; /* host */
  $ftp_user_name = $userName; /* username */
  $ftp_user_pass = $password; /* password */
  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host );
  if($connect_it){
    return true;
  }
  else {
    return false;
  }
}

function copyFolder($path,$remotePath,$userName,$password){
  $result = false;
  /* Source File Name and Path */
  $distantFolder = $remotePath;

  /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
  // create profil path into server
  $localFolderReady = createLocalFolder($path);
  /* Loop in all directory */
  if($localFolderReady == true){
    if($_SERVER['SERVER_NAME'] == 'localhost'){
      localRecursiveCopy($distantFolder, $path, $connect_it);
    }
    else {
      prodRecursiveCopy($distantFolder, $path, $connect_it);
    }
    $result = true;
  }
  else {
    $result =  false;
  }
  /* Close ftp connect */
  ftp_close( $connect_it );
  return $result;
}

function localRecursiveCopy($distantFolder,$localPath,$connect_it){
  $contents = ftp_nlist($connect_it, $distantFolder);
  for($i=0; $i<count($contents); $i++) {
		if (strstr($contents[$i], ".") !== FALSE) {
			if (ftp_get($connect_it, $localPath.$contents[$i], "$distantFolder/$contents[$i]", FTP_ASCII)) {
				echo "Successfully written-> $distantFolder/$contents[$i]\n";
			} else {
				echo "There was a problem with-> $distantFolder/$contents[$i]\n";
			}
		}
		else {
			mkdir($localPath.$contents[$i],0777,true);
      echo "Folder create-> ".$localPath.$contents[$i]."\n";
			$a = localRecursiveCopy("$distantFolder/$contents[$i]",$localPath.$contents[$i]."/",$connect_it);
		}
	}
}

function prodRecursiveCopy($distantFolder, $path, $connect_it){
  print_r('TODO');
}

/* Create local folder*/
function createLocalFolder($path){
  if (!mkdir($path, 0777, true)) {
    print_r('Echec lors de la création des répertoires... ');
    return false;
  }
  else {
    return true;
  }
}

/* delete_local_folder() Ajax Call function */
function delete_local_folder() {
  global $post;
  $result = 0;
  $params = $_POST['params']['folder'];
  if(substr($params, -1)== '/'){
    $params = substr($params, 0,-1);
  }
  // 1. define root website
  //$rootPath = explode("/wp", get_site_url()); // for PROD (http server)
  $rootPath = $_SERVER['DOCUMENT_ROOT']."/"; // for DEV (localhost)
  $path = $rootPath.$params;

  if (is_dir($path)) {
      print_r(delTree($path));
  }
  print_r('path ? ->'.is_dir($path));
  //print_r("Folder to delete-> $path");
  die();
}

function delTree($dir) {
   $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
?>
