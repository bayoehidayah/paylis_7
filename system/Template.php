<?php
    class Template{
        
        public function head(){
            include("../../template/head.php");
        }

        public function js(){
            include("../../template/js.php");
        }

        public function sidebar(){
            include("../../template/sidebar.php");
        }

        public function topbar(){
            include("../../template/topbar.php");
        }

        public function login(){
            require_once("../../library/encryption.php");
            $encryption = new Encryption("login");
            $information1 = $encryption->decrypt("dd3f826063d0c4d5274f53f22442066a64353765636135656233343539633762313965373331313534363131313534393633623139316532373135333766653337356662656431353735386665323562ecacebeffa9d8552682193265988e1e0f5f36871453908fb9be94cfcd893f28177b246ea15f013e563db64e51cbe2d1d16861dfb7486846be0039bbffaf78dedcee4408bf23e1357ad0922e85559996b32adef2b6e6c0e59771f2c0c1b4e5cc6bdef32b4701978806b6a");
            // $information2 = $encryption->encrypt("admin");

            $data = array(
                "information" => $information1
                // "data" => $information11
            );
            return $data;
        }
    }
?>