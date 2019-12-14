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
                    <h2>Tarif</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">Tarif</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=tarif">Tarif</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar Tarif
                            <div class="float-right">
                                <button class="btn btn-raised waves-effect bg-red" style="margin-top:-5px;" id="btnAdd">Add Tarif</button>
                            </div>    
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTable js-basic-example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tarif/Daya</th>
                                        <th>Tarif/KwH (Rp)</th>
                                        <th>Golongan</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($data->tarif_data() as $row){
                                            $id_tarif = base64_encode(base64_encode($row['id_tarif']));
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $row['gol_tarif']."/".$row['daya']; ?></td>
                                                    <td><?php echo number_format($row['tarifperkwh'],0,',','.'); ?></td>
                                                    <td><?php echo $row['nama_gol']; ?></td>
                                                    <td><?php echo $row['keterangan']; ?></td>
                                                    <td class="action">
                                                       <a href="#" class="icons" onclick="edit('<?php echo $id_tarif; ?>');">
                                                        <i class="zmdi zmdi-edit"></i>
                                                       </a>
                                                       <a href="#" class="icons" onclick="del('<?php echo $id_tarif; ?>');">
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
                    <h4 class="modal-title" id="defaultModalLabel">Adding Tarif</h4>
                </div>
                <div class="modal-body"> 
                    <div id="hidden_input"></div>
                    <div class="form-group">
                        <select class="form-control show-tick" name="tarif_gol" id="tarif_gol">
                            <option disabled selected>Golongan</option>
                                <?php
                                    foreach($data->tarif_gol() as $row){
                                        $id_tarif_gol = base64_encode(base64_encode($row['id_tarif_gol']));
                                        ?>
                                            <option value="<?php echo $id_tarif_gol ?>"><?php echo $row['nama_gol']; ?></option>
                                        <?php
                                    }
                                ?>
                        </select>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" class="form-control" name="tarif" id="tarif">
                                <label class="form-label">Tarif (Ex : R-1, R-2, B-1)</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" class="form-control" name="daya" id="daya">
                                <label class="form-label">Daya (Ex : 450 VA)</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="number" class="form-control" name="tarif_kwh" id="tarif_kwh">
                                <label class="form-label">Tarif/KwH (Rp)</label>
                            </div>
                        </div>
                        <div class="form-group form-float">
                            <div class="form-line">
                                <input type="text" class="form-control" name="keterangan" id="keterangan">
                                    <label class="form-label">Keterangan</label>
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
                $(".modal-title").text("Adding Tarif");
                $("#hidden_input").html("");
                $("#tarif_gol").val("").selectpicker("refresh");
                $("#tarif").val("");
                $("#daya").val("");
                $("#tarif_kwh").val("");
                $("#keterangan").val("");
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
            var tarif_gol = $("#tarif_gol").val();
            var tarif = $("#tarif").val()
            var daya = $("#daya").val()
            var tarif_kwh = $("#tarif_kwh").val();
            var keterangan = $("#keterangan").val()

            if(tarif_gol == "" || tarif == "" || daya == "" || tarif_kwh == "" || keterangan == ""){
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
                        tarif_gol : tarif_gol,
                        tarif : tarif,
                        daya : daya,
                        tarif_kwh : tarif_kwh,
                        keterangan : keterangan,
                        request : "tarif_adding"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Tarif telah berhasil ditambah. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal menambah tarif",
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

        function edit(id_tarif){
            $.ajax({
                type: "GET",
                url: "../../system/Ajax.php",
                data: {
                    id_tarif : id_tarif,
                    request : "tarif_edit"
                },
                dataType: "JSON",
                success: function (data) {
                    if(data.checking == "Yes"){
                        $("form").attr("action", "update");
                        var input = '<input type="hidden" name="id_tarif" id="id_tarif" value="'+id_tarif+'">';

                        $(".modal-title").text("Editing Tarif");
                        $("#hidden_input").html(input);
                        $("#tarif_gol").val(data.id_tarif_gol).selectpicker("refresh");
                        $("#tarif").val(data.tarif.gol_tarif);
                        $("#daya").val(data.tarif.daya);
                        $("#tarif_kwh").val(data.tarif.tarifperkwh);
                        $("#keterangan").val(data.tarif.keterangan);
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
            var id_tarif = $("#id_tarif").val();
            var tarif_gol = $("#tarif_gol").val();
            var tarif = $("#tarif").val()
            var daya = $("#daya").val()
            var tarif_kwh = $("#tarif_kwh").val();
            var keterangan = $("#keterangan").val()

            if(tarif_gol == "" || tarif == "" || daya == "" || tarif_kwh == "" || keterangan == ""){
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
                        id_tarif : id_tarif,
                        tarif_gol : tarif_gol,
                        tarif : tarif,
                        daya : daya,
                        tarif_kwh : tarif_kwh,
                        keterangan : keterangan,
                        request : "tarif_updating"
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title : "Berhasil!",
                                text : "Tarif telah berhasil diupdate. Halaman ini akan segera dimuat ulang",
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
                                text : "Gagal mengupdate tarif",
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

        function del(id_tarif){
            swal({
                title: "Peringatan!",
                text: "Jika anda menghapus data ini, data untuk pelanggan juga akan terhapus beserta penggunaan, tagihan dan pembayaran yang menggunakan tarif ini. Apakah anda yakin?",
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
                            id_tarif : id_tarif,
                            request : "tarif_deleting"
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if(data.checking == "Yes"){
                                swal({
                                    title : "Berhasil!",
                                    text : "Tarif telah dihapus. Halaman ini akan dimuat ulang",
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
                                    text : "Gagal menghapus tarif",
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