<?php
add_action( 'wp_ajax_nopriv_copy_distant_folder', 'copy_distant_folder' );
add_action( 'wp_ajax_copy_distant_folder', 'copy_distant_folder' );

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
  // 3 Check if $path existe
  if(file_exists($path.'/toto')){
    print_r( 'project dir exist! ' );
    die();
  }
  else {
    // 4 Create $path
    // if (!mkdir($path, 0777, true)) {
    //     print_r('Echec lors de la création des répertoires... ');
    //     die();
    // }
    // 5 copy distant files into the created $path
    $copyResult = copyFolder($path,$userName,$password);
    // 6 returne the result
    $result = $copyResult;
  }
  //print_r($path);
  //print_r('path-> '.$path);
  //print_r('params-> '.$_POST['params']);
  //print_r('pwd-> '.$password."\n");
  if($result == 1){
    $resultTXT = 'Successfully copyed!';
  }
  else {
    $resultTXT = 'Error... there was a problem';
  }
  print_r('Copy distant Folder result-> '.$resultTXT."\n");
	die();
}

function copyFolder($path,$userName,$password){
  /* Source File Name and Path */
  $remote_file = '/public_html/exercice05ajax/';
  /* FTP Account */
  $ftp_host = 'ateliers.nomades.ch'; /* host */
  $ftp_user_name = $userName; /* username */
  $ftp_user_pass = $password; /* password */

  /* New file name and path for this file */
  $local_file =$path.'index.html';

  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host );
  /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );
  /* Loop in all directory */
  // TODO
  //$buff = ftp_rawlist($connect_it, $remote_file);
  $path = $remote_file;
  $directory = new RecursiveDirectoryIterator("./");
  foreach (new RecursiveIteratorIterator($directory) as $filename=>$current) {

          $src = $current->getPathName();
          $dest = "./destination_path/" . $current->getFileName();

          echo "file: " .  $src. "\n";

   }
  //print_r($buff);
  ftp_close( $connect_it );
  die();
  /* Download $remote_file and save to $local_file */
  if ( ftp_get( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {
      //print_r( "WOOT! Successfully written to $local_file\n");
      return 1;
  }
  else {
      //print_r( "Doh! There was a problem\n");
      return 0;
  }
  /* Close the connection */
  ftp_close( $connect_it );
}
 ?>
