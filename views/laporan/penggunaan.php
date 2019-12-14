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
                        <li class="breadcrumb-item">Laporan</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=lap_penggunaan">Penggunaan</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar Penggunaan
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTable js-exportable">
                                <thead>
                                    <tr>
                                        <th>ID Pelanggan</th>
                                        <th>Nama</th>
                                        <th>Penggunaan</th>
                                        <th>Total Meter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($data->lap_penggunaan() as $row){
                                            $total_meter = $data->lap_penggunaan_meter($row['id_pelanggan']);

                                            ?>
                                                <tr>
                                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                                    <td><?php echo $row['nama_pelanggan']; ?></td>
                                                    <td><?php echo $row['penggunaan']. " Bulan"; ?></td>
                                                    <td><?php echo number_format($total_meter,0,',','.'). " KwH"; ?></td>
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
    </div>
</section>
<?php $template->js(); ?>
</body>
</html>