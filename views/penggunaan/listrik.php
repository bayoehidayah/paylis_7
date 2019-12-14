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
                    <h2>Penggunaan</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">Penggunaan</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=peng_listrik">Listrik</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar Penggunaan Listrik
                            <?php $level = $_SESSION['level']; if($level == "Admin"){ ?>
                            <div class="float-right">
                                <button class="btn btn-raised waves-effect bg-red" style="margin-top:-5px;" id="btnAdd">Add Penggunaan</button>
                            </div>    
                            <?php } ?>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTable js-basic-example">
                                <thead>
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>ID Pelanggan</th>
                                        <th>Nama</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Meter Awal</th>
                                        <th>Meter Akhir</th>
                                        <?php if($level == "Admin"){ ?>
                                        <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // $i = 1;
                                        foreach($data->peng_listrik_data() as $row){
                                            $id_penggunaan = base64_encode(base64_encode($row['id_penggunaan']));
                                            ?>
                                                <tr>
                                                    <!-- <td><?php echo $i; ?></td> -->
                                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                                    <td><?php echo $row['nama_pelanggan']; ?></td>
                                                    <td><?php echo $row['bulan']; ?></td>
                                                    <td><?php echo $row['tahun']; ?></td>
                                                    <td><?php echo number_format($row['meter_awal'],0,',','.'). " KwH"; ?></td>
                                                    <td><?php echo number_format($row['meter_akhir'],0,',','.'). " KwH"; ?></td>
                                                    <?php if($level == "Admin"){ ?>
                                                    <td class="action">
                                                       <a href="#" class="icons" onclick="edit('<?php echo $id_penggunaan; ?>');">
                                                        <i class="zmdi zmdi-edit"></i>
                                                       </a>
                                                       <a href="#" class="icons" onclick="del('<?php echo $id_penggunaan; ?>');">
                                                        <i class="zmdi zmdi-delete"></i>
                                                       </a>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php
                                            // $i++;
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
<?php if($level == "Admin"){ ?>
<form action="tambah" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Adding bulan</h4>
                </div>
                <div class="modal-body"> 
                    <div id="hidden_input"></div>
                    <div class="form-group">
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
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="bulan" id="bulan">
                                <label class="form-label">Bulan</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="tahun" id="tahun">
                                <label class="form-label">Tahun</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="meter_awal" id="meter_awal">
                                <label class="form-label">Meter Awal</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="meter_akhir" id="meter_akhir">
                                    <label class="form-label">Meter Akhir</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link waves-effect">Save</button>
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } $template->js(); if($level == "Admin"){ ?>
    <script>
        $(document).ready(function(){
            $("#btnAdd").click(function (e) { 
                e.preventDefault();
                $("form").attr("action", "tambah");
                $(".modal-title").text("Adding Penggunaan");
                $("#hidden_input").html("");
                $("#pelanggan").val("").selectpicker("refresh");
                $("#bulan").val("");
                $("#tahun").val("");
                $("#meter_awal").val("");
                $("#meter_akhir").val("");
                $(".form-line").removeClass("focused");
                $("#addModal").modal("show");
            });

            $("form").submit(function (e) { 
                e.preventDefault();
                
                var action = $(this).attr("action");

                if(action == "tambah"){
                    adding();
                }
                else if(action == "update"){
                    update();
                }
            });
        });

        function adding(){
            var pelanggan = $("#pelanggan").val();
            var bulan = $("#bulan").val()
            var tahun = $("#tahun").val()
            var meter_awal = $("#meter_awal").val();
            var meter_akhir = $("#meter_akhir").val()

            if(pelanggan == "" || bulan == "" || tahun == "" || meter_awal == "" || meter_akhir == ""){
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
                        pelanggan : pelanggan,
                        bulan : bulan,
                        tahun : tahun,
                        meter_awal : meter_awal,
                        meter_akhir : meter_akhir,
                        request : "peng_listrik_adding"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Penggunaan listrik telah berhasil ditambah. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal menambah penggunaan listrik",
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

        function edit(id_peng_listrik){
            $.ajax({
                type: "GET",
                url: "../../system/Ajax.php",
                data: {
                    id_peng_listrik : id_peng_listrik,
                    request : "peng_listrik_edit"
                },
                dataType: "JSON",
                success: function (data) {
                    if(data.checking == "Yes"){
                        $("form").attr("action", "update");
                        var input = '<input type="hidden" name="id_peng_listrik" id="id_peng_listrik" value="'+id_peng_listrik+'">';

                        $(".modal-title").text("Editing Penggunaan");
                        $("#hidden_input").html(input);
                        $("#pelanggan").val(data.pelanggan).selectpicker("refresh");
                        $("#bulan").val(data.penggunaan.bulan);
                        $("#tahun").val(data.penggunaan.tahun);
                        $("#meter_awal").val(data.penggunaan.meter_awal);
                        $("#meter_akhir").val(data.penggunaan.meter_akhir);
                        $(".form-line").addClass("focused");
                        $("#addModal").modal("show");
                    }
                    else if(data.checking == "No"){
                        swal({
                            title : "Gagal!",
                            text : "Gagal mengambil data",
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

        function update(){
            var id_peng_listrik = $("#id_peng_listrik").val();
            var pelanggan = $("#pelanggan").val();
            var bulan = $("#bulan").val()
            var tahun = $("#tahun").val()
            var meter_awal = $("#meter_awal").val();
            var meter_akhir = $("#meter_akhir").val()

            if(pelanggan == "" || bulan == "" || tahun == "" || meter_awal == "" || meter_akhir == ""){
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
                        id_peng_listrik : id_peng_listrik,
                        pelanggan : pelanggan,
                        bulan : bulan,
                        tahun : tahun,
                        meter_awal : meter_awal,
                        meter_akhir : meter_akhir,
                        request : "peng_listrik_updating"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Penggunaan listrik telah berhasil diupdate. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal mengupdate penggunaan listrik",
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

        function del(id_peng_listrik){
            swal({
                title: "Peringatan!",
                text: "Jika anda menghapus data ini, data untuk tagihan akan terhapus beserta pembayaran yang menggunakan data ini. Apakah anda yakin?",
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
                            id_peng_listrik : id_peng_listrik,
                            request : "peng_listrik_deleting"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "Penggunaan listrik telah dihapus. Halaman ini akan dimuat ulang",
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
                                    text : "Gagal menghapus penggunaan listrik",
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
<?php } ?>
</body>
</html>