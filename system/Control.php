<?php
    require_once("Auth.php");
    require_once("View.php");

    $control = $_GET['control'];
    if($control == "logout"){
        $auth = new Auth();
        $auth->logout();
    }
    else if($control == "dashboard"){
        $_SESSION['current_menu'] = "dashboard";
        header("location:../views/dashboard/dashboard.php");
    }
    //Control For User -----------------------------------------------------------
    else if($control == "bank_user"){
        $_SESSION['current_menu'] = "bank_user";
        View::bank_user();
    }
    else if($control == "pelanggan_user"){
        $_SESSION['current_menu'] = "pelanggan_user";
        View::pelanggan_user();
    }

    //Control For Tarif ----------------------------------------------------------
    else if($control == "tarif"){
        $_SESSION['current_menu'] = "tarif";
        View::tarif();
    }
    else if($control == "tarif_gol"){
        $_SESSION['current_menu'] = "tarif_gol";
        View::tarif_gol();
    }

    //Control For Penggunaan -----------------------------------------------------
    else if($control == "peng_listrik"){
        $_SESSION['current_menu'] = "peng_listrik";
        View::penggunaan_listrik();
    }
    else if($control == "peng_tagihan"){
        $_SESSION['current_menu'] = "peng_tagihan";
        View::penggunaan_tagihan();
    }

    //Control For Pembayaran ---------------------------------------------------
    else if($control == "bayar"){
        $_SESSION['current_menu'] = "bayar";
        View::bayar();
    }
    else if($control == "bayar_confirmation"){
        $_SESSION['current_menu'] = "bayar_confirmation";
        View::bayar_confirmation();
    }
    else if($control == "bayar_check"){
        $_SESSION['current_menu'] = "bayar_check";
        View::bayar_check();
    }

    //Control For Laporan
    else if($control == "lap_tunggakan"){
        $_SESSION['current_menu'] = "lap_tunggakan";
        View::lap_tunggakan();
    }
    else if($control == "lap_penggunaan"){
        $_SESSION['current_menu'] = "lap_penggunaan";
        View::lap_penggunaan();
    }
?>