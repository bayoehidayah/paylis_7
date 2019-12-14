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
                    <h2>Tagihan</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item">Penggunaan</li>
                        <li class="breadcrumb-item active"><a href="../../system/control.php?control=peng_tagihan">Tagihan</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Daftar Tagihan
                            <!-- <div class="float-right">
                                <button class="btn btn-raised waves-effect bg-red" style="margin-top:-5px;" id="btnAdd">Add Penggunaan</button>
                            </div>     -->
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
                                        <th>Jumlah Meter</th>
                                        <th>Status</th>
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // $i = 1;
                                        foreach($data->peng_tagihan_data() as $row){
                                            $id_tagihan = base64_encode(base64_encode($row['id_tagihan']));

                                            if($row['status'] == "Belum Lunas"){
                                                $status = '<span class="label label-warning">'.$row['status'].'</span>';
                                            }
                                            else{
                                                $status = '<span class="label label-success">'.$row['status'].'</span>';    
                                            }

                                            ?>
                                                <tr>
                                                    <!-- <td><?php echo $i; ?></td> -->
                                                    <td><?php echo $row['id_pelanggan']; ?></td>
                                                    <td><?php echo $row['nama_pelanggan']; ?></td>
                                                    <td><?php echo $row['bulan']; ?></td>
                                                    <td><?php echo $row['tahun']; ?></td>
                                                    <td><?php echo number_format($row['jumlah_meter'],0,',','.'); ?></td>
                                                    <td><?php echo $status; ?></td>
                                                    <!-- <td class="action">
                                                        <?php
                                                            // if($row['status'] == "Belum Lunas"){
                                                        ?>
                                                       <a href="#" class="icons" onclick="edit('<?php echo $id_tagihan; ?>');" title="Bayar">
                                                        <i class="zmdi zmdi-money"></i>
                                                       </a>
                                                       <?php
                                                            // }
                                                       ?>
                                                    </td> -->
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
<?php $template->js(); ?>
</body>
</html>