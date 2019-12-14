<?php
    require_once("system/config.php");
    require_once("system/view.php");
    class Session{

        public function status(){
            $status = new Config();
            $view  = new View();
            //Jika Sudah Login maka redirect ke Dashboard
            if($status->connect_to_server() == "success"){
                if(isset($_SESSION['status'])){
                    if($_SESSION['status'] == TRUE){
                        $view->dashboard();
                    }
                }
                else{
                    $view->login();
                }
            }
        }
    }
?>