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
  $path = $rootPath.$_POST['params'];
  // 3 Check if $path existe
  if(file_exists($path)){
    echo 'project dir exist! ';
  }
  else {
    // 4 Create $path
    if (!mkdir($path, 0777, true)) {
        die('Echec lors de la création des répertoires... ');
    }
    // 5 copy distant files into the created $path
    // TODO --
    // 6 returne the result
    $copyResult = 1;
    $result = $copyResult;
  }
  //print_r($path);
  print_r('result-> '.$result);
	die();
}

function copyFolder(){
  /* Source File Name and Path */
  $remote_file = 'files.zip';

  /* FTP Account */
  $ftp_host = 'http://ateliers.nomades.ch/'; /* host */
  $ftp_user_name = 'fazio@ateliers.nomades.ch'; /* username */
  $ftp_user_pass = 'nicfaz'; /* password */


  /* New file name and path for this file */
  $local_file = 'files.zip';

  /* Connect using basic FTP */
  $connect_it = ftp_connect( $ftp_host );

  /* Login to FTP */
  $login_result = ftp_login( $connect_it, $ftp_user_name, $ftp_user_pass );

  /* Download $remote_file and save to $local_file */
  if ( ftp_get( $connect_it, $local_file, $remote_file, FTP_BINARY ) ) {
      echo "WOOT! Successfully written to $local_file\n";
  }
  else {
      echo "Doh! There was a problem\n";
  }

  /* Close the connection */
  ftp_close( $connect_it );
}
 ?>
