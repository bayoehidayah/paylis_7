<?php
/*
    How to use FPDF ----------------------------------------------
    1. AddPage(potrait/landscape, setting_pengukuran, besar_kertas);
    2. SetFont(family, style, size);
    3. Note : 1 Cell itu 1 baris
       - Cell(lebar, tinggi, text, border, new_line, align);
       - MultiCell(lebar, tinggi, text, border, align);
    4. SetMargins(left, top, right);

*/
    require_once("../system/config.php");
    require_once('../third_party/fpdf/pdf_code128.php');
    require_once('Encryption.php');
    class Pdf extends Config{

        private $pdf;
        private $encrypt;
        private $con;

        public function __construct($page = "P", $size = "mm", $page_size = "A4"){
            $this->pdf = new PDF_Code128($page, $size, $page_size);
            $this->connect_to_server();
            $this->encrypt = new Encryption();

            $db = Config::getInstance();
            $this->con = $db->connect();
        }

        private function terbilang($x) {
            $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
            if ($x < 12){
                return " " . $angka[$x];}
            elseif ($x < 20){
              return $this->terbilang($x - 10) . " belas";}
            elseif ($x < 100){
              return $this->terbilang($x / 10) . " puluh" . $this->terbilang($x % 10);}
            elseif ($x < 200){
              return " seratus" . $this->terbilang($x - 100);}
            elseif ($x < 1000){
              return $this->terbilang($x / 100) . " ratus" . $this->terbilang($x % 100);}
            elseif ($x < 2000){
              return "seribu" . $this->terbilang($x - 1000);}
            elseif ($x < 1000000){
              return $this->terbilang($x / 1000) . " ribu" . $this->terbilang($x % 1000);}
            elseif ($x < 1000000000){
              return $this->terbilang($x / 1000000) . " juta" . $this->terbilang($x % 1000000);}
        }

        public function struk_pembayaran($id_pembayaran){
            $get_data = $this->con->query("SELECT * FROM pembayaran WHERE id_pembayaran='$id_pembayaran'");

            if(mysqli_num_rows($get_data) > 0){
                $pembayaran = mysqli_fetch_array($get_data);

                //Collect Data
                $kasir = $pembayaran['id_admin'];
                $id_pelanggan = $pembayaran['id_pelanggan'];
                $id_tagihan = $pembayaran['id_tagihan'];

                $data_pelanggan = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id_pelanggan'");
                $pelanggan = mysqli_fetch_array($data_pelanggan);
                $nama_pelanggan = $pelanggan['nama_pelanggan'];

                $data_tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_tagihan='$id_tagihan'");
                $tagihan = mysqli_fetch_array($data_tagihan);
                $id_penggunaan = $tagihan['id_penggunaan'];

                $data_penggunaan = $this->con->query("SELECT * FROM penggunaan WHERE id_penggunaan='$id_penggunaan'");
                $penggunaan = mysqli_fetch_array($data_penggunaan);

                $this->pdf->AddPage();
                $this->pdf->SetMargins(5, 10, 10);
                $this->pdf->SetTitle("Struk Pembayaran - ".$id_pembayaran);

                $this->pdf->SetFont("Courier", "", 12);
                $this->pdf->Cell(190, 0, "",0, 1);
                $this->pdf->Cell(60, 5, "PayLis",0, 0);
                $this->pdf->Cell(80, 5, $pembayaran['tanggal_pembayaran'],0, 1, "R");
                $this->pdf->Cell(50, 5, "Aplikasi Pembayaran Listrik Online Pascabayar",0, 1);

                $this->pdf->Ln(5);
                $this->pdf->SetFont("Courier", "", 10);
                $this->pdf->Cell(190, 5, "Struk Pembayaran : ",0, 1);
                $this->pdf->Cell(190, 5, "Kasir : ".$kasir,0, 1);
                $this->pdf->Cell(190, 5, "No. Ref : ".$id_pembayaran,0, 1);

                $this->pdf->Ln(2);
                $this->pdf->Cell(190, 5, "ID Pelanggan : ".$id_penggunaan,0, 1);
                $this->pdf->Cell(190, 5, "Nama : ".$nama_pelanggan,0, 1);
                $this->pdf->Cell(60, 5, "Tarif/Daya : ".$pelanggan['gol_tarif']."/".$pelanggan['daya'],0, 0);
                $this->pdf->Cell(80, 5, "Tarif/KwH : Rp ".number_format($pelanggan['tarifperkwh'],0,',','.'),0, 1, "R");

                $this->pdf->Ln(3);
                $this->pdf->Cell(190, 5, "Membayar untuk : ", 0, 1);
                $this->pdf->Cell(60, 5, "Bulan : ".$tagihan['bulan'], 0, 0);
                $this->pdf->Cell(80, 5, "Tahun : ".$tagihan['tahun'], 0, 1, "R");

                $this->pdf->Ln(1);
                $this->pdf->Cell(190, 5, "Penggunaan : ", 0, 1);
                $this->pdf->Cell(60, 5, "Meter Awal : ".number_format($penggunaan['meter_awal'],0,',','.')." KwH", 0, 0);
                $this->pdf->Cell(80, 5, "Meter Akhir : ".number_format($penggunaan['meter_akhir'],0,',','.')." KwH", 0, 1, "R");
                $this->pdf->Cell(190, 5, "Jumlah Meter : ".number_format($tagihan['jumlah_meter'],0,',','.')." KwH", 0, 1);

                $this->pdf->Ln(2);
                $this->pdf->Cell(60, 5, "Rp Tag. PLN : Rp ".number_format($pembayaran['harga_pemakaian'],0,',','.'), 0, 0);
                $this->pdf->Cell(80, 5, "PPJ 3% : Rp ".number_format($pembayaran['ppj'],0,',','.'), 0, 1, "R");
                $this->pdf->Cell(60, 5, "PPN 10% : Rp ".number_format($pembayaran['ppn'],0,',','.'), 0, 0);
                $this->pdf->Cell(80, 5, "Biaya Admin : Rp 2.500", 0, 1, "R");
                $this->pdf->Cell(190, 5, "Total Harga : Rp ".number_format($pembayaran['total_harga'],0,',','.'), 0, 1);
                $this->pdf->Cell(23, 5, "Terbilang : ",0, 0);
                $this->pdf->MultiCell(118, 5, $this->terbilang($pembayaran['total_harga']), 0, "L");
                $this->pdf->Cell(190, 5, "Telah Dibayar Sebesar : Rp ".number_format($pembayaran['total_bayar'],0,',','.'), 0, 1);
                $this->pdf->Cell(190, 5, "Kembalian : Rp ".number_format($pembayaran['total_kembalian'],0,',','.'), 0, 1);

                //Barcode 
                $this->pdf->Code128(35, 130, $pembayaran['id_pembayaran'], 80, 20);
                $this->pdf->SetXY(40,150);
                $this->pdf->Write(5, $pembayaran['id_pembayaran']);

                $this->pdf->Ln(15);
                $this->pdf->Cell(142, 5, "Terimakasih telah menggunakan pelayanan dari paylis",0, 1, "C");
                $this->pdf->Cell(142, 5, "Semoga kedepannya anda tetap bisa bersama dengan kami", 0, 0, "C");
                
                $this->pdf->Output();
            }
            else{
                header('location:../index.php');
            }
        }
    }
?>