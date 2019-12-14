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
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=bayar_check">Check Pembayaran</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <?php $level = $_SESSION['level']; if($level != "Pelanggan"){ ?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Pilih Pelanggan</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-8">
                                <select class="form-control show-tick" name="pelanggan" id="pelanggan">
                                    <option disabled selected>Pelanggan</option>
                                        <?php
                                            foreach($data->get_data_pelanggan_user() as $row){
                                                $id_pelanggan = base64_encode(base64_encode($row['id_pelanggan']));
                                                ?>
                                                    <option value="<?php echo $id_pelanggan ?>"><?php echo $row['id_pelanggan']." | ".$row['nama_pelanggan']; ?></option>
                                                <?php
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-raised waves-effect bg-red" id="btnPilih">Pilih</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
        <?php } ?>
        <div class="row clearfix" id="isiSection" style="display:<?php if($level != "Pelanggan") { ?>none <?php } else { ?>block <?php } ?>">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Daftar Pembayaran</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered <?php if($level == "Pelanggan"){ ?> dataTable js-basic-example <?php } ?>" id="tablePembayaran">
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
                                    <?php if($level == "Pelanggan"){
                                        foreach($data->get_check_pembayaran_pelanggan_only() as $row){
                                            if($row['status_pembayaran'] == "Confirmation"){
                                                $status = '<span class="label label-success">'.$row['status_pembayaran'].'</span>';
                                                $action = '<a href="../../system/Print.php?data='.$row['id_pembayaran'].'" target="_blank" class="icons"><i class="zmdi zmdi-print"></i></a>';
                                            }
                                            else if($row['status_pembayaran'] == "Refuse"){
                                                $status = '<span class="label label-danger">'.$row['status_pembayaran'].'</span>';
                                                $action = '<a href="#" class="icons" onclick="del_pembayaran(\''.$row['id_pembayaran'].'\');"><i class="zmdi zmdi-delete"></i></a>';
                                            }
                                            else{
                                                $status = '<span class="label label-warning">'.$row['status_pembayaran'].'</span>';
                                                $action = '<a href="#" class="icons" onclick="del_pembayaran(\''.$row['id_pembayaran'].'\');"><i class="zmdi zmdi-delete"></i></a>';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id_pembayaran']; ?></td>
                                                <td><?php echo $row['id_tagihan']; ?></td>
                                                <td><?php echo $row['bulan']."/".$row['tahun']; ?></td>
                                                <td><?php echo $row['tanggal_pembayaran']; ?></td>
                                                <td><?php echo "Rp ".number_format($row['total_harga'],0,',','.'); ?></td>
                                                <td><?php echo $status; ?></td>
                                                <td><?php echo $action; ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }   
                                    ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</section>
    <?php $template->js(); ?>
    <script>
    <?php if($level != "Pelanggan"){ ?>
        $(document).ready(function(){

            $("#btnPilih").click(function (e) { 
                e.preventDefault();
                var isi = $("#pelanggan").val();

                if(isi == "" || isi == null){
                    swal({
                        title : "Oops!",
                        text : "Harap memilih pelanggan",
                        type : "error"
                    });
                }
                else{
                    $.ajax({
                        type: "GET",
                        url: "../../system/Ajax.php",
                        data: {
                            pelanggan : isi,
                            request : "bayar_check_pelanggan"
                        },
                        dataType: "JSON",
                        beforeSend : function(){
                            $(".page-loader-wrapper").attr("style", "display:block");
                        },
                        success: function (data) {
                            $(".page-loader-wrapper").attr("style", "display:none");

                            //Before We Insert Data to DataTable, first we must fix datatable
                            var table = $("#tablePembayaran").DataTable();

                            var baris = "", status = "", print = "";
                            for(var i = 0; i < data.pelanggan.length; i++){
                                if(data.pelanggan[i].status_pembayaran == "Confirmation"){
                                    status = "success";
                                    print = '<a href="../../system/Print.php?data='+data.pelanggan[i].id_pembayaran+'" target="_blank" class="icons"><i class="zmdi zmdi-print"></i></a>';
                                }
                                else if(data.pelanggan[i].status_pembayaran == "Requested"){
                                    status = "warning";
                                    print = "";
                                }
                                else{
                                    status = "danger";
                                    print = "";
                                }

                                baris += '<tr><td>'+data.pelanggan[i].id_pembayaran+'</td><td>'+data.pelanggan[i].id_tagihan+'</td><td>'+data.pelanggan[i].bulan+'/'+data.pelanggan[i].tahun+'</td><td>'+data.pelanggan[i].tanggal_pembayaran+'</td><td> Rp '+number_format(data.pelanggan[i].total_harga, 0, ',', '.')+'</td><td><span class="label label-'+status+'">'+data.pelanggan[i].status_pembayaran+'</span></td><td class="action">'+print+'</td></tr>';
                            }
                            //Destroy DataTable first
                            table.destroy();
                            $("#tablePembayaran tbody").html(baris);
                            //After inserting html tag, set DataTable back again
                            table = $("#tablePembayaran").DataTable();
                            $("#tablePembayaran").attr("style", "width:100%;");

                            //Pop up Card
                            $("#isiSection").attr("style", "display:block");

                        },
                        error : function(jqXHR, textStatus, errorThrown){
                            swal({
                                title : "Oops!",
                                text : "Periksa koneksi anda",
                                type : "error"
                            })
                            $(".page-loader-wrapper").attr("style", "display:none");
                        }
                    });
                }
            });
        }); 
        <?php } ?>

        function number_format(number, decimals, dec_point, thousands_point) {
            if (number == null || !isFinite(number)) {
                throw new TypeError("number is not valid");
            }

            if (!decimals) {
                var len = number.toString().split('.').length;
                decimals = len > 1 ? len : 0;
            }

            if (!dec_point) {
                dec_point = '.';
            }

            if (!thousands_point) {
                thousands_point = ',';
            }

            number = parseFloat(number).toFixed(decimals);

            number = number.replace(".", dec_point);

            var splitNum = number.split(dec_point);
            splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
            number = splitNum.join(dec_point);

            return number;
        }

        <?php if($level == "Pelanggan"){ ?>
        function del_pembayaran(kode_pembayaran){
            swal({
                title: "Perhatian!",
                text: "Jika anda menghapus permintaan pembayaran ini, tagihan anda tidak akan terlunaskan",
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
                            request : "del_pembayaran"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "Permintaan pembayaran telah dihapus",
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
                                    text : "Gagal menghapus permintaan pembayaran",
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
    <?php } ?>
    </script>
</body>
</html>