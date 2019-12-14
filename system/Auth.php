<?php
    require("config.php");
    require("../library/encryption.php");
    class Auth extends Config{

        private $con;

        public function __construct(){
            $this->connection = new Config();
            $this->connection->connect_to_server();
            $this->encryption = new Encryption();

            $db = Config::getInstance();
            $this->con = $db->connect();
        }

        public function login_action($username, $password, $sebagai){
            //Pertama periksa dulu data untuk bank atau admin
            if($sebagai == "Bank" || $sebagai == "Admin"){
                $check_data = $this->con->query("SELECT * FROM admin JOIN level ON admin.id_level=level.id_level WHERE admin.username='$username'");
                if(mysqli_num_rows($check_data) > 0){
                    $data_user = mysqli_fetch_array($check_data);

                    if($password == $this->encryption->decrypt($data_user['password'])){
                        $_SESSION['id_user'] = $data_user['id_admin'];
                        $_SESSION['level'] = $data_user['nama_level'];
                        $_SESSION['status'] = "TRUE";

                        $result = "Yes";
                    }
                    else{
                        $result = "No";
                    }
                }
                else{
                    $result = "No";
                }
            }
            //Lalu jika tidak cocok lanjut ke pelanggan
            else{
                $check_data = $this->con->query("SELECT * FROM pelanggan WHERE username='$username'");
                if(mysqli_num_rows($check_data) > 0){
                    $data_user = mysqli_fetch_array($check_data);

                    if($password == $this->encryption->decrypt($data_user['password'])){
                        $_SESSION['id_user'] = $data_user['id_pelanggan'];
                        $_SESSION['level'] = "Pelanggan";
                        $_SESSION['status'] = "TRUE";
                        $result = "Yes";
                    }
                    else{
                        $result = "No";
                    }
                }
                else{
                    $result = "No";
                }
            }

            return $result;
        }

        public function logout(){
            session_unset();
            session_destroy();
            header("location:../index.php");
        }
    }
?>