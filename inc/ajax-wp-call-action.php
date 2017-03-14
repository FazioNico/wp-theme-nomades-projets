<?php
# @Author: Nicolas Fazio <webmaster-fazio>
# @Date:   03-11-2016
# @Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 14-03-2017

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
  //global $connect_it;
  $result = 0;
  /* Création du répertoire Projet de l'elève */
  // 1. define root website
  //$rootPath = explode("/wp", get_site_url()); // for PROD (http server)
  $rootPath = $_SERVER['DOCUMENT_ROOT']."/"; // for DEV (localhost)
  // 2 Create de $path we need
  $path = $rootPath.$_POST['params']['folder']; //projects-eleves/fazio/exercice05ajax/
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
    //print_r( 'project dir exist! ' );
    print_rJsonData(0,"Echec! project dir '$path' exist...");
    die();
  }
  else {
    // connect by FTP

    //print_r("test result-> $ftpResult");
    // 4 Create $path
    // 5 copy distant files into the created $path
    $copyResult = copyFolder($path,$projectFolder,$userName,$password);
    // 6 returne the result
    if($copyResult === true){
    // if(true === true){
      $resultTXT = 'Successfully copyed!';
      $result = 1;
      if(isset($sqlFile)){
        // 7 createSqlBdd & update wp_config.php
        $userDB_conf = array(
         'username' => $_POST['params']['sqlUSER'],
         'passwd' => $_POST['params']['sqlPASS'],
         'dbname' => $_POST['params']['sqlDB']
        );
        $resultUpdateProject = updateProjectConfig($path,$sqlFile,$projectFolder, $userName, $userDB_conf);
        if($resultUpdateProject === true){
          $resultTXT = 'All run task finish with success!!';
        }
      }
    }
    else{
      $resultTXT = 'Copy error... there was a problem';
    }

  }
  // if($result == 1){
  //   if(isset($sqlFile)){
  //     // 8 createSqlBdd
  //     $resultUpdateProject = updateProjectConfig($path,$sqlFile);
  //     if($resultUpdateProject === true){
  //       $resultTXT = 'All run task finish with success!!';
  //     }
  //   }
  //
  // }

  //print_r($resultTXT);
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
  $ftp_host = 'filesrv.ateliers.nomades.ch'; /* host */
  $ftp_user_name = $userName; /* username */
  $ftp_user_pass = $password; /* password */
  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host ) or errorFTP();
   /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
  if($login_result == false){
    print_rJsonData(0,"Login FTP result-> Mot de passe ($ftp_user_pass) ou login ($ftp_user_name) incorrect.");
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
      $copyResult = localRecursiveCopy($distantFolder, $path, $connect_it);
      //prodRecursiveCopy($distantFolder, $path, $connect_it);
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

      // check for media file
      $file_parts = pathinfo($contents[$i]);
      $file_parts['extension'];
      $cool_extensions = Array('jpg', 'jpeg', 'png', 'mp3', 'mp4', 'mov', 'pfd');

      $ftp_mode = FTP_ASCII; // default ftp mode
      if (in_array($file_parts['extension'], $cool_extensions)){
        $ftp_mode = FTP_BINARY;
      }
      if (ftp_get($connect_it, $localPath.$contents[$i], "$distantFolder/$contents[$i]", $ftp_mode)) {
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

function prodRecursiveCopy($distantFolder,$localPath){
  // TODO: create server function...
  /* FTP Account for local copy folders */
  $ftp_host = ''; /* host */
  $ftp_user_name = ''; /* username */
  $ftp_user_pass = ''; /* password */
  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host ) or errorFTP();
   /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
  if($login_result == false){
    print_rJsonData(0,"Login FTP on 'nomades-projets.ch' result-> Mot de passe ou login incorrect.");
  }
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

/* Create local folder*/
function createLocalFolder($path){
  if (!mkdir($path, 0777, true)) {
    //print_r('Echec lors de la création des répertoires... ');
    print_rJsonData(0,"Echec lors de la création des répertoires... ");
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
  if($state === 2){
    delete_local_folder();
  }
  print_r('{"state": '.$state.', "extract": "'.$extract.'"}');
  die();
}

/* Change db name in wp_config.php */
function updateProjectConfig($path,$sqlFile,$projectFolder,$userName, $userDB_conf){

  //$file = $sqlFile;
  //$file = '../'.$sqlFile.name;

  // default local config:
  // student_42
  // teacher
  // superprof

  $dbConf = array(
   'host' => 'localhost',
   'username' => 'projets_user',
   'passwd' => 'user_projets',
   'dbname' => 'site_projets',
   'port' => '3306' // 3306
  );
  // 1: First update wp_config.php with the right db config name and prefix
  //$filePath = "../../projects-eleves/fazio/wordpress/wp-config.php";
  $filePath = "../../".$_POST['params']['folder']."wp-config.php";
  // projects-eleves/fazio/wordpress/
  $lookfor = '';
  $newtext = '';
  //$update_wp_config_DB_USER = new Update_WP_Config($filePath,$lookfor,$newtext);

  $update_wp_config_DB_NAME = new Update_WP_Config();
  $update_wp_config_DB_NAME->upd_wp_config_data($filePath, $userDB_conf['dbname'], $dbConf['dbname']);

  $update_wp_config_DB_USER = new Update_WP_Config();
  $update_wp_config_DB_USER->upd_wp_config_data($filePath,$userDB_conf['username'],$dbConf['username']);

  $update_wp_config_DB_PASSWORD = new Update_WP_Config();
  $update_wp_config_DB_PASSWORD->upd_wp_config_data($filePath,$userDB_conf['passwd'],$dbConf['passwd']);

  $update_wp_config_DB_HOST = new Update_WP_Config();
  $update_wp_config_DB_HOST->upd_wp_config_data($filePath,'localhost',$dbConf['host']);

  if( //true === true
    $update_wp_config_DB_NAME->result === true &&
    $update_wp_config_DB_USER->result === true &&
    $update_wp_config_DB_PASSWORD->result === true
  ){
    // 2: TODO ::>> then createSqlBdd of user project
    $createSQL = new Update_WP_Config();
    $bddResult = $createSQL->createSqlBdd(stripslashes($sqlFile),$dbConf);
    //
    //print_rJsonData($bddResult,'SQL create with success!');
  	//die(stripslashes($sqlFile));
    // return true;
    return $bddResult;
  }
  else {
    return false;
  }
}

?>
