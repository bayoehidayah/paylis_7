<?php
    require_once("config.php");
    require_once("../library/Pdf.php");

    if(!isset($_SESSION['status'])){
        header("location:../index.php");
    }
    else{
        $data = $_GET['data'];

        $size_page = array(
            150, //Width
            200 //Height
        );

        $pdf = new Pdf("P", "mm", $size_page);
        $pdf->struk_pembayaran($data);
    }
?>