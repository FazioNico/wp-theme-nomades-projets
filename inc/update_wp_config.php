<?php
/*
@Author: Nicolas Fazio <webmaster-fazio>
@Date:   26-12-2016
@Email:  contact@nicolasfazio.ch
# @Last modified by:   webmaster-fazio
# @Last modified time: 01-02-2017
*/

/*
  Bof -
  Function to replace dbName on wp-config.php by the right Nomade students db name
  EXEMPLE USAGE:
    $filePath = "test.php";
    $lookfor = 'old_db_name';
    $newtext = 'new_db_name';
    $update_wp_config = new Update_WP_Config($filePath,$lookfor,$newtext);
    print_r($update_wp_config->result);
*/

 class Update_WP_Config {

    public $result = false;
    private $fileData;
    private $newData;
    private $lookFor;
    private $newtext;

    function __construct() {
    }

    function upd_wp_config_data($file,$lookfor,$newtext) {

        $this->newData = array();
        $this->filePath = $file;
        //$this->fileData = file($this->filePath);
        $this->lookFor = $lookfor;
        $this->newText = $newtext;

        $result_file_data = $this->search_and_replace();
        $this->fileData = $result_file_data;
        //print_r($result_file_data);
        if(file_put_contents($this->filePath, $result_file_data) === false){
          $result = false;
        }
        else {
          $result = true;
        };
        $this->result = $result;
    }

    function search_and_replace($inputFile = true){
      $dataToForeach = file($this->filePath);
      if($inputFile != true ){
        $dataToForeach = $inputFile;
      }

      foreach ($dataToForeach as $filerow) {
        $this->newData[] = $this->replace_between($filerow, $this->lookFor, $this->newText);
      }
      if(!$this->newData[0]){
        // TODO: test if is ok
        $result = "Error: ".$inputFile;

      }
      else {
        $result = implode('',$this->newData);
      }
      //$result = 'pp';
      return $result;
    }

    function replace_between($str, $needle, $replacement)  {
      $repl = $replacement;
      $patt = "/$needle/";
      return preg_replace($patt, "". $repl ."", $str);
    }


    function upd_sql_dataTXT($file,$lookfor,$newtext) {
      //$this->fileData = file($this->filePath);
      $this->lookFor = $lookfor;
      $this->newText = $newtext;

      $result_file_data = $this->search_and_replace($file);
      //$this->fileData = $result_file_data;
      //print_r($result_file_data);
      // if(file_put_contents($this->filePath, $result_file_data) === false){
      //   $result = false;
      // }
      // else {
      //   $result = true;
      // };
      //$this->result = $result_file_data;
      $final = false;
      if(!empty($result_file_data)){
        $final = true;
      }
      $this->result = $final;
    }

    function getFileDatas(){
      return $this->fileData;
    }
}

/*
  Bof -
  Function to create user project SQL ddb on Nomade Server
  EXEMPLE USAGE:
    $file = 'student_42.sql';
    $dbConf = array(
     'host' => 'localhost',
     'username' => 'fazio',
     'passwd' => '',
     'dbname' => 'import_test',
     'port' => '3306' // 3306
    );
    print_r(createSqlBdd($file,$dbConf));
*/
function createSqlBdd($file,$dbConf){
  try {
      $db = new PDO('mysql: host='.$dbConf['host'].';dbname='.$dbConf['dbname'], $dbConf['username'], $dbConf['passwd']);
      //$db = new PDO($dsn, $user, $password);
      $sql = file_get_contents($file);
      $qr = $db->exec($sql) ;//or die(print_r($db->errorInfo(), true));
      return true;
  } catch (PDOException $e) {
      //print_r( "Error: " . $e->getMessage());
      //die();
      return false;
  }
}

/*
  Eof -
  Function to create user project SQL ddb on Nomade Server
*/
