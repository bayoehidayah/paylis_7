<?php
    class View{
        
        public function dashboard(){
            $_SESSION['current_menu'] = "dashboard";
            header("location:views/dashboard/dashboard.php");
        }

        public function login(){
            header("location:views/auth/login.php");
        }

        //User Controller ---------------------------------------
        public function bank_user(){
            header("location:../views/user/bank_user.php");
        }

        public function pelanggan_user(){
            header("location:../views/user/pelanggan_user.php");
        }

        //Tarif Controller ----------------------------------
        public function tarif(){
            header("location:../views/tarif/tarif.php");
        }
        public function tarif_gol(){
            header("location:../views/tarif/tarif_gol.php");
        }

        //Penggunaan Controller -------------------------------
        public function penggunaan_listrik(){
            header("location:../views/penggunaan/listrik.php");
        }
        public function penggunaan_tagihan(){
            header("location:../views/penggunaan/tagihan.php");
        }

        //Pembayaran Controller ---------------------------------
        public function bayar(){
            header("location:../views/pembayaran/bayar.php");
        }
        public function bayar_confirmation(){
            header("location:../views/pembayaran/bayar_confirmation.php");
        }
        public function bayar_check(){
            header("location:../views/pembayaran/check_pembayaran.php");
        }

        //Laporan Controller
        public function lap_tunggakan(){
            header("location:../views/laporan/tunggakan.php");
        }
        public function lap_penggunaan(){
            header("location:../views/laporan/penggunaan.php");
        }
    }
?>