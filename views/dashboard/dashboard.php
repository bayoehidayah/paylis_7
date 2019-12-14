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
    $set = $data->dashboard_data();
    if(!isset($_SESSION['status'])){
        header("location:../../index.php");
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
                    <h2>Dashboard</h2>                    
                    <ul class="breadcrumb">                        
                        <li class="breadcrumb-item"><a href="../../index.php">Dashboard</a></li>
                    </ul>
                </div>            
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=peng_tagihan">
                        <div class="icon"> <i class="zmdi zmdi-flash-off col-orange"></i> </div>
                        <div class="content">
                            <div class="text">Jumlah Tagihan</div>
                            <div class="number"><?php echo number_format($set['total_tagihan'],0,',',','); ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <?php $level = $_SESSION['level']; 
                if($level != "Pelanggan"){ ?>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=pelanggan_user">
                        <div class="icon"> <i class="zmdi zmdi-account-box col-blue"></i> </div>
                        <div class="content">
                            <div class="text">Jumlah Pelanggan</div>
                            <div class="number"><?php echo number_format($set['total_pelanggan'],0,',',','); ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=bayar_confirmation">
                        <div class="icon"> <i class="zmdi zmdi-spellcheck col-green"></i> </div>
                        <div class="content">
                            <div class="text">Konfirmasi Pembayaran</div>
                            <div class="number"><?php echo number_format($set['total_pembayaran'],0,',',','); ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=bank_user">
                        <div class="icon"> <i class="zmdi zmdi-accounts col-blue"></i> </div>
                        <div class="content">
                            <div class="text">Jumlah Bank</div>
                            <div class="number"><?php echo number_format($set['total_bank'],0,',',','); ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <?php } 
           else if($level == "Pelanggan"){ ?>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=peng_listrik">
                        <div class="icon"> <i class="zmdi zmdi-flash col-blue"></i> </div>
                        <div class="content">
                            <div class="text">Jumlah Penggunaan</div>
                            <div class="number"><?php echo number_format($set['total_penggunaan'],0,',',','). " KwH"; ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <a href="../../system/control.php?control=bayar_check">
                        <div class="icon"> <i class="zmdi zmdi-spellcheck col-green"></i> </div>
                        <div class="content">
                            <div class="text">Meminta Pembayaran</div>
                            <div class="number"><?php echo number_format($set['total_pembayaran'],0,',',','); ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="card info-box-4 hover-zoom-effect">
                    <div class="icon"> <i class="zmdi zmdi-accounts col-blue"></i> </div>
                    <div class="content">
                        <div class="text">Tarif/KwH</div>
                        <div class="number"><?php echo "Rp ".number_format($set['tarif_kwh'],0,',',','); ?></div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>             
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12">
                <div class="card cpu-usage">
                    <div class="header">
                        <h2>CPU USAGE (%)</h2>                        
                    </div>
                    <div class="body">
                        <div class="switch panel-switch-btn"> <span class="m-r-10 font-12">REAL TIME</span>
                            <label>OFF
                                <input type="checkbox" id="realtime" checked>
                                <span class="lever switch-col-cyan"></span>ON</label>
                        </div>
                        <div id="real_time_chart" class="dashboard-flot-chart"></div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
</section>

    <?php $template->js(); ?>
    
    <script src="../../assets/js/pages/index.js"></script>
</body>
</html>