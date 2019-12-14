<?php
    require_once("../../system/config.php");
    require_once("../../system/template.php");
    require_once("../../system/data.php");
    require_once("../../library/generate.php");
    //Check jika belum login
    $template = new Template();
    $data = new Data();
    $generate = new Generate();
    $config = new Config();

    $db = Config::getInstance();
    $con = $db->connect();

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
    <style>
        /* Custom Style */
        .form-group .btn-group{
            position: relative;
            margin: 3px 5px;
        }

        .margin-top{
            margin-top:-10px;
        }
        @media(max-width:576px){
            .margin-top{
                margin-top:0px;
            }
        }
    </style>
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
    $level = $_SESSION['level'];
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
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=bayar">Bayar</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <?php if($level != "Pelanggan"){ ?>
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
            <?php if($level == "Pelanggan") { ?>
            <input type="hidden" name="pelanggan" id="pelanggan" value="<?php echo base64_encode(base64_encode($_SESSION['id_user'])) ?>">
            <?php 
                $id_pelanggan = $_SESSION['id_user'];
                $data_pelanggan = $con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id_pelanggan'");
                $pelanggan = mysqli_fetch_array($data_pelanggan);
            } ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>Daftar Tagihan</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered <?php if($level == "Pelanggan"){ ?> dataTable js-basic-example <?php } ?>" id="tableTagihan">
                                <thead>
                                    <tr>
                                        <td>Tagihan</td>
                                        <td>Bulan</td>
                                        <td>Tahun</td>
                                        <td>Pemakaian Listrik</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($level == "Pelanggan"){
                                        foreach($data->bayar_pelanggan_data_only() as $row){
                                            ?>
                                                <tr>
                                                    <td><?php echo $row['id_tagihan']; ?></td>
                                                    <td><?php echo $row['bulan']; ?></td>
                                                    <td><?php echo $row['tahun']; ?></td>
                                                    <td><?php echo $row['jumlah_meter']; ?></td>
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
        <div class="row clearfix" id="bayarSection">
            <div class="col-md-4" style="display:<?php if($level != "Pelanggan") { ?>none <?php } else { ?>block <?php } ?>" id="prosesSection">
                <div class="card">
                    <div class="header">
                        <h2>Proses Pembayaran</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="bayar" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="total_harga" id="total_harga">
                                    <input type="hidden" name="bulan_bayar" id="bulan_bayar">
                                    <input type="hidden" name="ppj" id="ppj">
                                    <input type="hidden" name="ppn" id="ppn">
                                    <input type="hidden" name="harga_pemakaian" id="harga_pemakaian">
                                    <select class="form-control show-tick" name="tagihan" id="tagihan">
                                        <?php 
                                            if($level == "Pelanggan"){
                                                ?>
                                    <option disabled selected>Tagihan</option>
                                                <?php
                                                foreach($data->bayar_pelanggan_data_only() as $row){
                                                    ?>
                                                        <option value="<?php echo $row['id_tagihan'] ?>"><?php echo $row['id_tagihan']; ?></option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" class="form-control" name="id_pembayaran" id="id_pembayaran" value="<?php echo $generate->num_char(32); ?>" readonly>
                                            <label class="form-label">Kode Pembayaran</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line" id="tglForm">
                                            <input type="text" class="datepicker form-control" name="tgl_bayar" id="tgl_bayar">
                                            <label class="form-label">Tanggal Pembayaran</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line" id="hargaForm">
                                            <input type="number" class="form-control" name="harga_bayar" id="harga_bayar">
                                            <label class="form-label">Harga Bayar</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line" id="kembalianForm">
                                            <input type="number" class="form-control" name="kembalian" id="kembalian" readonly>
                                            <label class="form-label">Kembalian</label>
                                        </div>
                                    </div>
                                    <div id="btnSubmit"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8" style="display:<?php if($level != "Pelanggan") { ?>none <?php } else { ?>block <?php } ?>" id="detailSection">
                <div class="card">
                    <div class="header">
                        <h2>Detail Pembayaran</h2>
                    </div>
                    <div class="body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-hover" id="tablePelanggan">
                                    <tr>
                                        <td>ID Pelanggan</td>
                                        <td id="tPelangganColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $id_pelanggan;} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td id="tNamaColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $pelanggan['nama_pelanggan'];} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tarif/Daya</td>
                                        <td id="tTarifDayaColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $pelanggan['gol_tarif']."/".$pelanggan['daya'];} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tarif/KwH</td>
                                        <td id="tTarifKwHColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $pelanggan['tarifperkwh'];} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td id="tKeteranganColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $pelanggan['keterangan'];} ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td id="tAlamatColumn"><?php if($_SESSION['level'] == "Pelanggan"){ echo $pelanggan['alamat'];} ?></td>
                                    </tr>
                                </table>  
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-hover" id="tagihanSelect">
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
            </div>
        </div>
    </div>
</section>
    <?php $template->js(); ?>
    <script>
        $(document).ready(function(){
            $("#harga_bayar").on("change keyup focus",function (e) { 
                e.preventDefault();
                kembalian();
            });

            $('.datepicker').bootstrapMaterialDatePicker({
                format: 'YYYY-M-DD',
                clearButton: true,
                weekStart: 1,
                time: false
            }).on("change", function(){
                $("#tglForm").addClass("focused");
            });
            
            $("#tagihan").on("change", function (e) { 
                e.preventDefault();
                
                var tagihan = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "../../system/Ajax.php",
                    data: {
                        tagihan : tagihan,
                        request : "get_detail_tagihan"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            $("#tPenggunaanColumn").text(data.jumlah_meter+" KwH");
                            $("#tPemakaianColumn").text("Rp "+data.harga);
                            $("#tPpjColumn").text("Rp "+data.ppj);
                            $("#tPpnColumn").text("Rp "+data.ppn);
                            $("#tTotalColumn").text("Rp "+data.total);
                            $("#total_harga").val(data.total_real);
                            $("#harga_bayar").attr("min", data.total_real);
                            $("#harga_pemakaian").val(data.harga_real);
                            $("#ppj").val(data.ppj_real);
                            $("#ppn").val(data.ppn_real);
                            $("#bulan_bayar").val(data.bulan_bayar);

                            var btnSubmit = "";
                            if(data.status_pembayaran == "Requested" || data.status_pembayaran == "Refuse"){
                                <?php if($level == "Pelanggan"){ ?> 
                                btnSubmit = '<div class="alert alert-info">Tidak bisa meminta pembayaran untuk tagihan ini, karena anda sudah mengirimkan permintaan pembayaran sebelumya untuk tagihan ini. Kode Pembayaran : '+data.id_pembayaran+'</div>';
                                <?php } else { ?>
                                btnSubmit = '<div class="alert alert-info">Tidak bisa membayar tagihan ini, dikarenakan pelanggan sudah meminta pembayaran dengan menggunakan kode pembayaran : '+data.id_pembayaran+'</div>';
                                <?php } ?>

                            }
                            else{
                                btnSubmit = '<button type="submit" class="btn btn-raised waves-effect bg-red float-right">Submit</button>';
                            }

                            $("#btnSubmit").html(btnSubmit);
                        }
                        else if(data.checking == "No"){
                            swal({
                                title : "Gagal!",
                                text : "Gagal mengambil data detail pembayaran",
                                type : "error"
                            });
                        } 
                    },
                    error : function(jqXHR, textStatus, errorThrown){
                        swal({
                            title : "Oops!",
                            text : "Periksa koneksi anda",
                            type : "error"
                        })
                    }
                });
            });
            <?php if($level == "Admin" || $level == "Bank"){ ?>
            $("#btnPilih").click(function (e) { 
                e.preventDefault();
                var isi = $("#pelanggan").val();
                if(isi == null || isi == ""){
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
                            request : "bayar_select_pelanggan"
                        },
                        dataType: "JSON",
                        beforeSend : function(){
                            $(".page-loader-wrapper").attr("style", "display:block");
                        },
                        success: function (data) {
                            $(".page-loader-wrapper").attr("style", "display:none");

                            //Before We Insert Data to DataTable, first we must fix datatable to 
                            var table = $("#tableTagihan").DataTable();

                            var baris = "", select = "<option disabled selected>Tagihan</option>";
                            for(var i = 0; i < data.tagihan.length; i++){
                                baris += '<tr><td>'+data.tagihan[i].id_tagihan+'</td><td>'+data.tagihan[i].bulan+'</td><td>'+data.tagihan[i].tahun+'</td><td>'+data.tagihan[i].jumlah_meter+' KwH</td></tr>';

                                select += '<option value="'+data.tagihan[i].id_tagihan+'">'+data.tagihan[i].id_tagihan+'</option>';
                            }
                            //Destroy DataTable first
                            table.destroy();
                            $("#tableTagihan tbody").html(baris);
                            //After inserting html tag, set DataTable back again
                            table = $("#tableTagihan").DataTable();
                            $("#tableTagihan").attr("style", "width:100%;");

                            //Set Tagihan Selection
                            $("#tagihan").html(select).selectpicker("refresh");

                            //Set Table Pelanggan
                            $("#tPelangganColumn").text(data.pelanggan.id_pelanggan);
                            $("#tNamaColumn").text(data.pelanggan.nama_pelanggan);
                            $("#tTarifDayaColumn").text(data.pelanggan.gol_tarif+"/"+data.pelanggan.daya);
                            $("#tTarifKwHColumn").text("Rp "+data.tarif_kwh);
                            $("#tKeteranganColumn").text(data.pelanggan.keterangan);
                            $("#tAlamatColumn").text(data.pelanggan.alamat);

                            $("#tPenggunaanColumn").text("");
                            $("#tPemakaianColumn").text("");
                            $("#tPpjColumn").text("");
                            $("#tPpnColumn").text("");
                            $("#tTotalColumn").text("");
                            $("#total_harga").val("");
                            $("#harga_bayar").attr("min", "0").val("");
                            $("#harga_pemakaian").val("");
                            $("#ppj").val("");
                            $("#ppn").val("");
                            $("#tgl_bayar").val("");
                            $("#kembalian").val("");
                            $("#bulan_bayar").val("");

                            $("#tglForm").removeClass("focused");
                            $("#hargaForm").removeClass("focused");
                            $("#kembalianForm").removeClass("focused");

                            $("#btnSubmit").html("");

                            //Pop up Card
                            $("#isiSection").attr("style", "display:block");
                            $("#prosesSection").attr("style", "display:block");
                            $("#detailSection").attr("style", "display:block");

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
            <?php } ?>

            $("form").submit(function (e) { 
                e.preventDefault();
                
                var action = $(this).attr("action");

                if(action == "bayar"){
                    bayar();
                }
            });
        });

        function bayar(){
            var id_pembayaran = $("#id_pembayaran").val();
            var tgl_bayar = $("#tgl_bayar").val()
            var id_pelanggan = $("#pelanggan").val();
            var id_tagihan = $("#tagihan").val();
            var harga_bayar = $("#harga_bayar").val()
            var total_harga = $("#total_harga").val();
            var harga_pemakaian = $("#harga_pemakaian").val()
            var ppn = $("#ppn").val();
            var ppj = $("#ppj").val();
            var bulan_bayar = $("#bulan_bayar").val();

            if(id_pembayaran == "" || tgl_bayar == "" || id_pelanggan == "" || id_tagihan == "" || harga_bayar == "" || total_harga == "" || harga_pemakaian == "" || ppn == "" || ppj == "" || bulan_bayar == ""){
                swal({
                    title : "Oops!",
                    text : "Harap mengisi semua bidang",
                    type : "error"
                });
            }
            else{
                $.ajax({
                    type: "GET",
                    url: "../../system/Ajax.php",
                    data: {
                        id_pembayaran : id_pembayaran,
                        tgl_bayar : tgl_bayar,
                        id_pelanggan : id_pelanggan,
                        id_tagihan : id_tagihan,
                        harga_bayar : harga_bayar,
                        total_harga : total_harga,
                        harga_pemakaian : harga_pemakaian,
                        ppn : ppn,
                        ppj : ppj,
                        bulan_bayar : bulan_bayar,
                        request : "bayar_action"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Tagihan telah dibayar",
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
                                text : "Gagal membayar tagihan",
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
            }
        }

        function kembalian(){
			var harga = $("#harga_bayar").val();
		    var total_harga = $("#total_harga").val();
			var jumlah = Math.floor(harga - total_harga);
			$("#kembalian").val(jumlah);
            $("#kembalianForm").addClass("focused");
		}        
    </script>
</body>
</html>