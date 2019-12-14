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
                    <h2>Bank</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">User</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=bank_user">Bank</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar User Bank
                            <div class="float-right">
                                <button class="btn btn-raised waves-effect bg-red" style="margin-top:-5px;" id="btnAdd">Add User</button>
                            </div>    
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTable js-basic-example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($data->get_data_bank_user() as $row){
                                            $id_admin = base64_encode(base64_encode($row['id_admin']));
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['nama_admin']; ?></td>
                                                    <td><?php echo $row['username']; ?></td>
                                                    <td><?php echo $row['nama_level']; ?></td>
                                                    <td class="action">
                                                       <a href="#" class="icons" onclick="edit('<?php echo $id_admin; ?>');">
                                                        <i class="zmdi zmdi-edit"></i>
                                                       </a>
                                                       <a href="#" class="icons" onclick="del('<?php echo $id_admin; ?>');">
                                                        <i class="zmdi zmdi-delete"></i>
                                                       </a>
                                                    </td>
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
<form action="tambah" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Adding User</h4>
                </div>
                <div class="modal-body"> 
                    <div id="hidden_input"></div>
                    <div class="form-group">
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" class="form-control" name="nama" id="nama">
                                <label class="form-label">Nama</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" class="form-control" name="username" id="username">
                                <label class="form-label">Username</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="password" class="form-control" name="password" id="password">
                                <label class="form-label">Password</label>
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
    <?php $template->js(); ?>
    <script>
        $(document).ready(function(){
            $("#btnAdd").click(function (e) { 
                e.preventDefault();
                $("form").attr("action", "tambah");
                $(".modal-title").text("Adding User");
                $("#hidden_input").html("");
                $("#nama").val("");
                $("#username").val("");
                $("#password").val("");
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
            var nama = $("#nama").val();
            var username = $("#username").val();
            var password = $("#password").val();

            if(nama == "" || username == "" || password == ""){
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
                        request : "bank_user_adding"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Bank user telah berhasil ditambah. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal menambah bank user",
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

        function edit(id_admin){
            $.ajax({
                type: "GET",
                url: "../../system/Ajax.php",
                data: {
                    id_admin : id_admin,
                    request : "bank_user_edit"
                },
                dataType: "JSON",
                success: function (data) {
                    if(data.checking == "Yes"){
                        $("form").attr("action", "update");
                        var input = '<input type="hidden" name="id_admin" id="id_admin" value="'+id_admin+'">';

                        $(".modal-title").text("Editing User");
                        $("#hidden_input").html(input);
                        $("#nama").val(data.bank.nama_admin);
                        $("#username").val(data.bank.username);
                        $("#password").val(data.password);
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
            var id_admin = $("#id_admin").val();
            var nama = $("#nama").val();
            var username = $("#username").val();
            var password = $("#password").val();

            if(nama == "" || username == "" || password == ""){
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
                        id_admin : id_admin,
                        nama : nama,
                        username : username,
                        password : password,
                        request : "bank_user_updating"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Bank user telah berhasil diupdate. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal mengupdate bank user",
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

        function del(id_admin){
            swal({
                title: "Apakah anda yakin?",
                text: "Data yang telah terhapus tidak dapat dikembalikan",
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
                            id_admin : id_admin,
                            request : "bank_user_deleting"
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
</body>
</html>