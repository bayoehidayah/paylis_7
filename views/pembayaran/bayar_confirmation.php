<?php
    require_once("../../system/config.php");
    require_once("../../system/template.php");
    require_once("../../system/data.php");
    //Check jika belum login
    $template = new Template();
    $data = new Data();
    $config = new Config();
    //Checking connection
    if($config->connect_to_server() == "failed"){
        header("location:../../index.php");
    }
    if(!isset($_SESSION['status'])){
        if($_SESSION['level'] != "Admin"){
            header("location:../../index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $template->head(); ?>
</head>
<body class="theme-red">
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-red">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<?php 
    $template->topbar(); 
    $template->sidebar();
?>
<!-- Main Content -->
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-6 col-sm-7">
                    <h2>Pembayaran</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">Pembayaran</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=bayar_confirmation">Konfirmasi Pembayaran</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Daftar Konfirmasi Pembayaran</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered" id="tablePembayaran">
                                <thead>
                                    <tr>
                                        <td>Kode Pembayaran</td>
                                        <td>Kode Tagihan</td>
                                        <td>Bulan/Tahun Tagihan</td>
                                        <td>Tanggal Pembayaran</td>
                                        <td>Total Harga</td>
                                        <td>Status Pembayaran</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($data->get_konfirmasi_pembayaran_data() as $row){
                                            if($row['status_pembayaran'] == "Requested"){
                                                $status = "warning";
                                            }
                                            else{
                                                $status = "danger";
                                            }
                                            ?>
                                                <tr>
                                                    <td><?php echo $row['id_pembayaran']; ?></td>
                                                    <td><?php echo $row['id_tagihan']; ?></td>
                                                    <td><?php echo $row['bulan']."/".$row['tahun']; ?></td>
                                                    <td><?php echo $row['tanggal_pembayaran']; ?></td>
                                                    <td><?php echo "Rp ".number_format($row['total_harga'],0,',','.'); ?></td>
                                                    <td><span class="label label-<?php echo $status; ?>"><?php echo $row['status_pembayaran']; ?></span></td>
                                                    <td class="action">
                                                        <a href="#" class="icons" onclick="see('<?php echo $row['id_pembayaran']; ?>');">
                                                            <i class="zmdi zmdi-eye"></i>
                                                       </a>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        <div class="row clearfix">
            <div class="col-lg-4" id="pelangganSection" style="display:none;">
                <div class="card">
                    <div class="header">
                        <h2>Data Pelanggan</h2>
                    </div>
                    <div class="body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>ID Pelanggan</td>
                                <td id="tPelangganColumn"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td id="tNamaColumn"></td>
                            </tr>
                            <tr>
                                <td>Tarif/Daya</td>
                                <td id="tTarifDayaColumn"></td>
                            </tr>
                            <tr>
                                <td>Tarif/KwH</td>
                                <td id="tTarifKwHColumn"></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td id="tKeteranganColumn"></td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td id="tAlamatColumn"></td>
                            </tr>
                        </table>  
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="penggunaanSection" style="display:none;">
                <div class="card">
                    <div class="header">
                        <h2>Detail Penggunaan Listrik</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                    <tr>
                                        <td>Penggunaan Listrik</td>
                                        <td id="tPenggunaanColumn"></td>
                                    </tr>
                                    <tr>
                                        <td>Harga Pemakaian</td>
                                        <td id="tPemakaianColumn"></td>
                                    </tr>
                                    <tr>
                                        <td>Biaya Admin</td>
                                        <td>Rp 2.500</td>
                                    </tr>
                                    <tr>
                                        <td>PPJ 3%</td>
                                        <td id="tPpjColumn"></td>
                                    </tr>
                                    <tr>
                                        <td>PPN 10%</td>
                                        <td id="tPpnColumn"></td>
                                    </tr>
                                    <tr>
                                        <td>Total Harga</td>
                                        <td id="tTotalColumn"></td>
                                    </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" id="pembayaranSection" style="display:none;">
                <div class="card">
                    <div class="header">
                        <h2>Detail Pembayaran</h2>
                    </div>
                    <div class="body">
                        <table class="table table-bordered table-hover">
                            <!-- <tr>
                                <td>Kode Pembayaran</td>
                                <td id="tKodePembayaranColumn"></td>
                            </tr> -->
                            <tr>
                                <td>Tanggal Pembayaran</td>
                                <td id="tTglBayarColumn"></td>
                            </tr>
                            <tr>
                                <td>Total Bayar</td>
                                <td id="tTotalBayarColumn"></td>
                            </tr>
                            <tr>
                                <td>Kembalian</td>
                                <td id="tKembalianColumn"></td>
                            </tr>
                        </table>  
                    </div>
                </div>
                <div class="card">
                    <div class="body" id="buttonSection">
                        <button type="button" class="btn btn-raised waves-effect bg-green" id="btnConfirm">Konfirmasi</button>
                        <button type="button" class="btn btn-raised waves-effect bg-red" id="btnRefuse">Tolak</button>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</section>
    <?php $template->js(); ?>
    <script>
        $(document).ready(function(){
            $("#tablePembayaran").DataTable();    
        }); 

        function see(kode_pembayaran){
            $.ajax({
                type: "GET",
                url: "../../system/Ajax.php",
                data: {
                    kode_pembayaran : kode_pembayaran,
                    request : "pelanggan_konfirm_bayar_data"
                },
                dataType: "JSON",
                beforeSend : function(){
                    $(".page-loader-wrapper").attr("style", "display:block");
                },
                success: function (data) {
                    $(".page-loader-wrapper").attr("style", "display:none");

                    //Data Pelanggan
                    $("#tPelangganColumn").text(data.pelanggan.id_pelanggan);
                    $("#tNamaColumn").text(data.pelanggan.nama);
                    $("#tTarifDayaColumn").text(data.pelanggan.tarif_daya);
                    $("#tTarifKwHColumn").text(data.pelanggan.tarif_kwh);
                    $("#tKeteranganColumn").text(data.pelanggan.keterangan);
                    $("#tAlamatColumn").text(data.pelanggan.alamat);

                    //Penggunaan
                    $("#tPenggunaanColumn").text(data.tagihan.penggunaan_listrik);
                    $("#tPemakaianColumn").text(data.pembayaran.harga_pemakaian);
                    $("#tPpjColumn").text(data.pembayaran.ppj);
                    $("#tPpnColumn").text(data.pembayaran.ppn);
                    $("#tTotalColumn").text(data.pembayaran.total_harga);

                    //Pembayaran
                    // $("#tKodePembayaranColumn").text(data.pembayaran.id_pembayaran);
                    $("#tTglBayarColumn").text(data.pembayaran.tanggal_pembayaran);
                    $("#tTotalBayarColumn").text(data.pembayaran.total_bayar);
                    $("#tKembalianColumn").text(data.pembayaran.kembalian);

                    var buttonSection = "";
                    if(data.pembayaran.status_pembayaran == "Requested"){
                        buttonSection = '<button type="button" class="btn btn-raised waves-effect bg-green" onclick="confirm(\''+data.pembayaran.id_pembayaran+'\')">Konfirmasi</button><button type="button" class="btn btn-raised waves-effect bg-red" onclick="refuse(\''+data.pembayaran.id_pembayaran+'\')">Tolak</button>';
                        // $("#btnConfirm").attr("onclick", "confirm('"+data.pembayaran.id_pembayaran+"')");
                        // $("#btnRefuse").attr("onclick", "refuse('"+data.pembayaran.id_pembayaran+"')");
                    }
                    else if(data.pembayaran.status_pembayaran == "Refuse"){
                        buttonSection = '<button type="button" class="btn btn-raised waves-effect bg-green" onclick="confirm(\''+data.pembayaran.id_pembayaran+'\')">Konfirmasi</button>';
                    }

                    $("#buttonSection").html(buttonSection);

                    //Pop up Card
                    $("#pelangganSection").attr("style", "display:block");
                    $("#penggunaanSection").attr("style", "display:block");
                    $("#pembayaranSection").attr("style", "display:block");

                },
                error : function(jqXHR, textStatus, errorThrown){
                    swal({
                        title : "Oops!",
                        text : "Periksa koneksi anda",
                        type : "error"
                    });
                    $(".page-loader-wrapper").attr("style", "display:none");
                }
            });
        }
        
        function confirm(kode_pembayaran){
            swal({
                title: "Perhatian!",
                text: "Dengan menyetujui pembayaran ini berarti data-data yang telah dimasukkan oleh pelanggan sudah benar dan tagihan akan menjadi lunas",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: "../../system/Ajax.php",
                        data: {
                            kode_pembayaran : kode_pembayaran,
                            request : "konfirm_bayar"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "Pembayaran telah dikonfirmasi",
                                    type : "success",
                                    timer : 2000,
                                    showConfirmButton : false
                                }, function(){
                                    location.reload();
                                })
                            }
                            else if(data.checking == "No"){
                                swal({
                                    title : "Gagal!",
                                    text : "Gagal mengkonfirmasi pembayaran",
                                    type : "error"
                                });
                            }
                        },
                        error : function(jqXHR, textStatus, errorThrown){
                            swal({
                                title : "Oops!",
                                text : "Periksa koneksi anda",
                                type : "error"
                            });
                        }
                    });
                }, 2000);
            });
        }

        function refuse(kode_pembayaran){
            swal({
                title: "Perhatian!",
                text: "Menolak pembayaran ini berati data-data yang telah dimasukkan oleh pelanggan salah dan tagihan tidak akan menjadi lunas",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            }, function () {
                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: "../../system/Ajax.php",
                        data: {
                            kode_pembayaran : kode_pembayaran,
                            request : "refuse_bayar"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "Pembayaran telah ditolak",
                                    type : "success",
                                    timer : 2000,
                                    showConfirmButton : false
                                }, function(){
                                    location.reload();
                                })
                            }
                            else if(data.checking == "No"){
                                swal({
                                    title : "Gagal!",
                                    text : "Gagal menolak pembayaran",
                                    type : "error"
                                });
                            }
                        },
                        error : function(jqXHR, textStatus, errorThrown){
                            swal({
                                title : "Oops!",
                                text : "Periksa koneksi anda",
                                type : "error"
                            });
                        }
                    });
                }, 2000);
            });
        }

    </script>
</body>
</html>