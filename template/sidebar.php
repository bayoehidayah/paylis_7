<?php
    require_once("../../system/Data.php");
    require_once("../../library/Encryption.php");
    $data = new Data("sidebar");
    $encrypt = new Encryption("sidebar");
    $akun = $data->data_user();
    $level = $akun['level'];

    if($level == "Admin"){
        $nama = $encrypt->decrypt("b89d7efa53997b7745af5682d01adca1613661353031666132626436373561666666663637616165373838653261666239663765353730636563653362353237616230313134623866346636373436350ba60d7d654eebb736d74c51327699");
    }
    else{
        $nama = $akun['nama'];
    }
?>
<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar"> 
    <!-- User Info -->
    <div class="user-info">
        <div class="image"> <img src="../../assets/images/random-avatar1.jpg" width="48" height="48" alt="User" /> </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown"><?php echo $nama; ?></div>
            <div class="email"><?php echo $level; ?></div>
            <!-- <div class="btn-group user-helper-dropdown"> <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li><a href="profile_2.html"><i class="material-icons">person</i>Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                    <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                    <li class="divider"></li>
                    <li><a href="sign-in.html"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div> -->
        </div>
    </div>
    <!-- #User Info --> 
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>
            <li <?php if($_SESSION['current_menu'] == "dashboard"){ echo 'class="active open"'; } ?>><a href="../../system/control.php?control=dashboard"><i class="zmdi zmdi-home"></i><span>Dashboard</span></a></li>
            <?php 
                // Menu ini hanya admin yang dapat mengaksesnya
                if($level == "Admin" || $level == "Bank"){ 
            ?>
            <li <?php if($_SESSION['current_menu'] == "bank_user" || $_SESSION['current_menu'] == "pelanggan_user"){ echo 'class="active open"'; } ?>> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-assignment-account"></i><span>User</span> </a>
                <ul class="ml-menu">
                    <?php if($level == "Admin"){ ?>
                    <li <?php if($_SESSION['current_menu'] == "bank_user"){ echo 'class="active"'; } ?>><a href="../../system/control.php?control=bank_user">Bank</a></li>
                    <?php } ?>
                    <li <?php if($_SESSION['current_menu'] == "pelanggan_user"){ echo 'class="active"'; } ?>><a href="../../system/control.php?control=pelanggan_user">Pelanggan</a></li>
                </ul>
            </li>
            <?php if($level == "Admin"){ ?>
            <li <?php if($_SESSION['current_menu'] == "tarif" || $_SESSION['current_menu'] == "tarif_gol"){ echo 'class="active open"'; } ?>> <a href="#" class="menu-toggle"><i class="zmdi zmdi-assignment"></i><span>Tarif</span> </a>
                <ul class="ml-menu">
                    <li <?php if($_SESSION['current_menu'] == "tarif"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=tarif">Tarif</a> </li>
                    <li <?php if($_SESSION['current_menu'] == "tarif_gol"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=tarif_gol">Golongan</a> </li>
                </ul>
            </li>
            <?php 
                } }
            ?>
            <li <?php if($_SESSION['current_menu'] == "peng_listrik" || $_SESSION['current_menu'] == "peng_tagihan"){ echo 'class="active open"'; } ?>> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-flash"></i><span>Penggunaan</span> </a>
                <ul class="ml-menu">
                    <li <?php if($_SESSION['current_menu'] == "peng_listrik"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=peng_listrik">Listrik</a> </li>
                    <li <?php if($_SESSION['current_menu'] == "peng_tagihan"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=peng_tagihan">Tagihan</a> </li>
                </ul>
            </li>
            <li <?php if($_SESSION['current_menu'] == "bayar" || $_SESSION['current_menu'] == "bayar_check" || $_SESSION['current_menu'] == "bayar_confirmation"){ echo 'class="active open"'; } ?>> <a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-money"></i><span>Pembayaran</span> </a>
                <ul class="ml-menu">
                    <li <?php if($_SESSION['current_menu'] == "bayar"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=bayar">Bayar</a> </li>
                    <?php if($level != "Pelanggan"){ ?>
                    <li <?php if($_SESSION['current_menu'] == "bayar_confirmation"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=bayar_confirmation">Konfirmasi Pembayaran</a> </li>
                    <?php } ?>
                    <li <?php if($_SESSION['current_menu'] == "bayar_check"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=bayar_check">Check Pembayaran</a> </li>
                </ul>
            </li>
            <?php if($level == "Admin" || $level == "Bank"){ ?>
            <li <?php if($_SESSION['current_menu'] == "lap_tunggakan" || $_SESSION['current_menu'] == "lap_penggunaan"){ echo 'class="active open"'; } ?>><a href="javascript:void(0);" class="menu-toggle"><i class="zmdi zmdi-file-text"></i><span>Laporan</span> </a>
                <ul class="ml-menu">
                    <li <?php if($_SESSION['current_menu'] == "lap_tunggakan"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=lap_tunggakan">Tunggakan</a></li>
                    <li <?php if($_SESSION['current_menu'] == "lap_penggunaan"){ echo 'class="active"'; } ?>> <a href="../../system/control.php?control=lap_penggunaan">Penggunaan</a></li>
                </ul>
            </li>
            <?php } ?>
        </ul>
    </div>
    <!-- #Menu --> 
</aside>
