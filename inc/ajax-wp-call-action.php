<?php
add_action( 'wp_ajax_nopriv_copy_distant_folder', 'copy_distant_folder' );
add_action( 'wp_ajax_copy_distant_folder', 'copy_distant_folder' );

add_action( 'wp_ajax_nopriv_delete_local_folder', 'delete_local_folder' );
add_action( 'wp_ajax_delete_local_folder', 'delete_local_folder' );

// Load update_wp_config class & function
//require_once('update_wp_config.php');
require get_template_directory() . '/inc/update_wp_config.php';


/* copy_distant_folder() Ajax Call function */
function copy_distant_folder() {
  global $post;
  global $connect_it;
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
  if(isset($_POST['params']['sqlFile'])){
    $sqlFile = $_POST['params']['sqlFile'];
  }
  //print_r($projectFolder);
  //die();
  // 3 Check if $path existe
  if(file_exists($path)){
    print_r( 'project dir exist! ' );
    die();
  }
  else {
    // connect by FTP

    //print_r("test result-> $ftpResult");
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
  if($result == 1){
    if(isset($sqlFile)){
      // 7 createSqlBdd & update wp_config.php
      $resultUpdateProject = updateProjectConfig($path,$sqlFile);
      if($resultUpdateProject == true){
        $resultTXT = 'All run task finish with success!!';
      }
    }

  }


  //print_r($path);
  //print_r('path-> '.$path);
  //print_r('params-> '.$_POST['params']);
  //print_r('pwd-> '.$password."\n");
  //print_r('{"state": '.$result.', "extract": "Copy distant Folder result-> '.$resultTXT.'"}');
  print_rJsonData($result,'Copy distant Folder result-> '.$resultTXT);
	die();
}

function errorFTP(){
  print_rJsonData(0,'FTP connect result-> Imposible de se connecter sur le serveur FTP');
}
function copyFolder($path,$remotePath,$userName,$password){
  $result = false;
  $copyResult = flase;
  /* FTP Account */
  $ftp_host = 'ateliers.nomades.ch'; /* host */
  $ftp_user_name = $userName; /* username */
  $ftp_user_pass = $password; /* password */
  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host ) or errorFTP();
   /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
  if($login_result == false){
    print_rJsonData(0,'Login FTP result-> Mot de passe ou login incorrect.');
  }

  /* Source File Name and Path */
  $distantFolder = $remotePath;
  // create profil path into server
  $localFolderReady = createLocalFolder($path);
  /* Loop in all directory */
  if($localFolderReady == true){
    if($_SERVER['SERVER_NAME'] == 'localhost'){
      $copyResult = localRecursiveCopy($distantFolder, $path, $connect_it);
    }
    else {
      prodRecursiveCopy($distantFolder, $path, $connect_it);
    }
    $result = true;
    if($copyResult == false){
      $result = false;
    }
  }
  else {
    $result =  false;
  }
  /* Close ftp connect */
  ftp_close( $connect_it );
  return $result;
}

function localRecursiveCopy($distantFolder,$localPath, $connect_it){
  $result = true;
  $contents = ftp_nlist($connect_it, $distantFolder);
  for($i=0; $i<count($contents); $i++) {
		if (strstr($contents[$i], ".") !== FALSE) {
			if (ftp_get($connect_it, $localPath.$contents[$i], "$distantFolder/$contents[$i]", FTP_ASCII)) {
				//echo "Successfully written-> $distantFolder/$contents[$i]\n";
			} else {
        $result = false;
				//echo "There was a problem with-> $distantFolder/$contents[$i]\n";
			}
		}
		else {
			mkdir($localPath.$contents[$i],0777,true);
      //echo "Folder create-> ".$localPath.$contents[$i]."\n";
			$a = localRecursiveCopy("$distantFolder/$contents[$i]",$localPath.$contents[$i]."/",$connect_it);
		}
	}
  return $result;
}

function prodRecursiveCopy($distantFolder, $path, $connect_it){
  // TODO: create server function...
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
  //print_r('path ? ->'.is_dir($path));
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

function print_rJsonData($state,$extract){
  print_r('{"state": '.$state.', "extract": "'.$extract.'"}');
  die();
}

/* Change db name in wp_config.php */
function updateProjectConfig($path,$sqlFile){

  // 1: First createSqlBdd of user project
  $file = $sqlFile;
  $dbConf = array(
   'host' => 'localhost',
   'username' => 'fazio',
   'passwd' => '',
   'dbname' => 'import_test',
   'port' => '3306' // 3306
  );
  //$resultCreatSQL = (createSqlBdd($file,$dbConf));

  // 2: then update wp_config.php with the right db config name and prefix
  $filePath = $path."wp_config.php";
  $lookfor = 'student_42';
  $newtext = 'import_test';
  $update_wp_config = new Update_WP_Config($filePath,$lookfor,$newtext);
  $resultUpdateConfigFile = $update_wp_config->result;
  //print_r($update_wp_config->result);
  //if($resultCreatSQL && $resultUpdateConfigFile == true){
  if($resultUpdateConfigFile == true){
    return true;
  }
  else {
    return false;
  }
  //return true;
}
?>
