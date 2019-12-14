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
    <style>
        /* Custom Style */
        .form-group .btn-group{
            position: relative;
            margin: 3px 5px;
        }

        .margin-top{
            margin-top:32px;
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
?>
<!-- Main Content -->
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row">
                <div class="col-lg-12 col-md-6 col-sm-7">
                    <h2>Pelanggan</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=pelanggan_user">Pelanggan</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar User Pelanggan
                            <?php $level = $_SESSION['level']; if($level == "Admin"){ ?>
                            <div class="float-right">
                                <button class="btn btn-raised waves-effect bg-red" style="margin-top:-5px;" id="btnAdd">Add User</button>
                            </div>    
                            <?php } ?>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTable js-basic-example">
                                <thead>
                                    <tr>
                                        <th>ID Pelanggan</th>
                                        <th>Nama</th>
                                        <th>Tarif</th>
                                        <th>Tarif/Kwh</th>
                                        <th>Alamat</th>
                            <?php if($level == "Admin"){?><th>Action</th><?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($data->get_data_pelanggan_user() as $row){
                                            $id_pelanggan = base64_encode(base64_encode($row['id_pelanggan']));
                                            ?>
                                                <tr>
                                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                                    <td><?php echo $row['nama_pelanggan']; ?></td>
                                                    <td><?php echo $row['gol_tarif']."/".$row['daya']; ?></td>
                                                    <td><?php echo number_format($row['tarifperkwh'],0,',','.'); ?></td>
                                                    <td><?php echo $row['alamat']; ?></td>
                                                    <?php if($level == "Admin"){ ?>
                                                    <td class="action">
                                                       <a href="#" class="icons" onclick="edit('<?php echo $id_pelanggan; ?>');">
                                                        <i class="zmdi zmdi-edit"></i>
                                                       </a>
                                                       <a href="#" class="icons" onclick="del('<?php echo $id_pelanggan; ?>');">
                                                        <i class="zmdi zmdi-delete"></i>
                                                       </a>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php
                                            $i++;
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Adding User</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" id="alert_info">ID Pelanggan akan digenerate automatis</div>
                    <div id="hidden_input"></div>
                    <div class="row clearfix">
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control show-tick" name="tarif" id="tarif">
                                    <option disabled selected>Tarif</option>
                                    <?php
                                        foreach($data->tarif_data() as $row){
                                            $id_tarif = base64_encode(base64_encode($row['id_tarif']));
                                            ?>
                                                <option value="<?php echo $id_tarif ?>"><?php echo $row['gol_tarif']."/".$row['daya']." (".$row['keterangan'].")"; ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line tarif_kwh">
                                    <input type="text" class="form-control" name="tarif_kwh" id="tarif_kwh" readonly>
                                        <label class="form-label">Tarif / Kwh</label>
                                </div>
                            </div>      
                        </div>
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line tarif_gol">
                                    <input type="text" class="form-control" name="tarif_gol" id="tarif_gol" readonly>
                                    <label class="form-label">Golongan Tarif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="nama" id="nama">
                                        <label class="form-label">Nama</label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="username" id="username">
                                        <label class="form-label">Username</label>
                                </div>
                            </div>  
                        </div>
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="password" class="form-control" name="password" id="password">
                                        <label class="form-label">Password</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="alamat" id="alamat">
                                        <label class="form-label">Alamat</label>
                                </div>
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
<?php } $template->js(); if($level == "Admin") {?>
    <script>
        $(document).ready(function(){
            $("#btnAdd").click(function (e) { 
                e.preventDefault();
                $("form").attr("action", "tambah");
                $(".modal-title").text("Adding User");
                $("#alert_info").removeAttr("style");
                $("#hidden_input").html("");
                $("#tarif").val("").selectpicker("refresh");
                $("#tarif_kwh").val("");
                $("#tarif_gol").val("");
                $("#nama").val("");
                $("#username").val("");
                $("#password").val("");
                $("#alamat").val("");
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

            $("#tarif").change(function (e) { 
                e.preventDefault();
                
                var tarif = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "../../system/Ajax.php",
                    data: {
                        tarif : tarif,
                        request : "pelanggan_tarif_data"
                    },
                    dataType: "JSON",
                    beforeSend : function(){
                        $(".tarif_gol").removeClass("focused");
                        $(".tarif_kwh").removeClass("focused");
                        $("#tarif_kwh").val("")
                        $("#tarif_gol").val("");
                    },
                    success: function (data) {
                        $(".tarif_gol").addClass("focused");
                        $(".tarif_kwh").addClass("focused");
                        $("#tarif_kwh").val(data.nominal)
                        $("#tarif_gol").val(data.tarif.nama_gol);
                    },
                    error : function(jqXHR, textStatus, errorThrown){
                        swal({
                            title : "Oops!",
                            text : "Gagal mengambil data tarif",
                            type : "error"
                        });
                    }
                });
            });
        });

        function adding(){
            var nama = $("#nama").val();
            var tarif = $("#tarif").val();
            var username = $("#username").val();
            var password = $("#password").val();
            var alamat = $("#alamat").val();

            if(nama == "" || username == "" || password == "" || tarif == "" || alamat == ""){
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
                        nama : nama,
                        username : username,
                        password : password,
                        tarif : tarif,
                        alamat : alamat,
                        request : "pelanggan_user_adding"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Pelanggan user telah berhasil ditambah. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal menambah pelanggan user",
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

        function edit(id_pelanggan){
            $.ajax({
                type: "GET",
                url: "../../system/Ajax.php",
                data: {
                    id_pelanggan : id_pelanggan,
                    request : "pelanggan_user_edit"
                },
                dataType: "JSON",
                success: function (data) {
                    if(data.checking == "Yes"){
                        $("form").attr("action", "update");
                        var input = '<input type="hidden" name="id_pelanggan" id="id_pelanggan" value="'+id_pelanggan+'">';
                        $("#alert_info").attr("style", "display:none;");
                        $(".modal-title").text("Editing User");
                        $("#hidden_input").html(input);
                        $("#tarif").val(data.id_tarif).selectpicker("refresh");
                        $("#tarif_kwh").val(data.tarifperkwh);
                        $("#tarif_gol").val(data.pelanggan.nama_gol);
                        $("#nama").val(data.pelanggan.nama_pelanggan);
                        $("#username").val(data.pelanggan.username);
                        $("#password").val(data.password);
                        $("#alamat").val(data.pelanggan.alamat);
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
            var id_pelanggan = $("#id_pelanggan").val();
            var nama = $("#nama").val();
            var tarif = $("#tarif").val();
            var username = $("#username").val();
            var password = $("#password").val();
            var alamat = $("#alamat").val();

            if(id_pelanggan == "" || nama == "" || username == "" || password == "" || tarif == "" || alamat == ""){
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
                        id_pelanggan : id_pelanggan,
                        nama : nama,
                        username : username,
                        password : password,
                        tarif : tarif,
                        alamat : alamat,
                        request : "pelanggan_user_updating"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Pelanggan user telah berhasil diupdate. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal mengupdate pelanggan user",
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

        function del(id_pelanggan){
            swal({
                title: "Peringatan!",
                text: "Jika anda menghapus data ini, data untuk penggunaan akan terhapus beserta tagihan dan pembayaran yang menggunakan data ini. Apakah anda yakin?",
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
                            id_pelanggan : id_pelanggan,
                            request : "pelanggan_user_deleting"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "User telah dihapus. Halaman ini akan dimuat ulang",
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
                                    text : "Gagal menghapus user",
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
<?php }?>
</body>
</html>