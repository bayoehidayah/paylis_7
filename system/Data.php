<?php
    class Data{

        private $con;

        public function __construct($request = ""){
            if($request == "view"){
                require_once("../system/config.php");
            }
            else{
                require_once("../../system/config.php");
            }
            
            $this->connection = new Config();
            $this->connection->connect_to_server();

            $db = Config::getInstance();
            $this->con = $db->connect();
        }

        //Data For Sidebar 
        public function data_user(){
            $id_user = $_SESSION['id_user'];
            $level = $_SESSION['level'];

            //Get Data Admin atau Bank
            if($level == "Admin" || $level == "Bank"){
                $get_data = $this->con->query("SELECT * FROM admin WHERE id_admin='$id_user'");
                if(mysqli_num_rows($get_data) > 0){
                    $data = mysqli_fetch_array($get_data);
                    $nama = $data['nama_admin'];
                }
                else{
                    $nama = "Unkown User";
                }
            }
            //Get Data Pelanggan
            else{
                $get_data = $this->con->query("SELECT * FROM pelanggan WHERE id_pelanggan='$id_user'");
                if(mysqli_num_rows($get_data) > 0){
                    $data = mysqli_fetch_array($get_data);
                    $nama = $data['nama_pelanggan'];
                }
                else{
                    $nama = "Unkown User";
                }
            }
            $return_data = array(
                "nama" => $nama,
                "level" => $level
            );

            return $return_data;
        }

        //Data For Dashboard
        public function dashboard_data(){
            //Get All Data
            if($_SESSION['level'] == "Pelanggan"){
                $id_pelanggan = $_SESSION['id_user'];
                $tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_pelanggan='$id_pelanggan' AND status='Belum Lunas'");
                $penggunaan = $this->con->query("SELECT SUM(jumlah_meter) AS jumlah FROM tagihan WHERE id_pelanggan='$id_pelanggan'");
                $data_penggunaan = mysqli_fetch_array($penggunaan);
                $pembayaran = $this->con->query("SELECT * FROM pembayaran WHERE id_pelanggan='$id_pelanggan' AND status_pembayaran='Requested'");
                $tarif = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id_pelanggan'");
                $data_tarif = mysqli_fetch_array($tarif);
                $tarif =  $data_tarif['tarifperkwh'];
                $data = array(
                    "total_tagihan" => mysqli_num_rows($tagihan),
                    "total_penggunaan" => $data_penggunaan['jumlah'],
                    "tarif_kwh" => $tarif,
                    "total_pembayaran" => mysqli_num_rows($pembayaran)
                );
            }
            else{
                $pelanggan = $this->con->query("SELECT * FROM pelanggan");
                $tagihan = $this->con->query("SELECT * FROM tagihan WHERE status='Belum Lunas'");
                $bank = $this->con->query("SELECT * FROM admin JOIN level ON admin.id_level=level.id_level WHERE level.nama_level='Bank'");
                $pembayaran = $this->con->query("SELECT * FROM pembayaran WHERE status_pembayaran='Requested'");
    
                //Take them to array
                $data = array(
                    "total_pelanggan" => mysqli_num_rows($pelanggan),
                    "total_tagihan" => mysqli_num_rows($tagihan),
                    "total_bank" => mysqli_num_rows($bank),
                    "total_pembayaran" => mysqli_num_rows($pembayaran)
                );
            }
            

            //Return back value
            return $data;
        }

        //Data For User -> Bank --------------------------------------
        public function bank_user_add($nama, $username, $password){
            $check_username = $this->con->query("SELECT * FROM admin WHERE username='$username'");
            //Jika username sudah dipake maka lakukan penolakan
            if(mysqli_num_rows($check_username) > 0){
                $checking = "No";
            }
            //JIka belum ada maka melakukan insert
            else{
                require_once("../library/Encryption.php");
                $encrypt = new Encryption();

                $pass = $encrypt->encrypt($password);
                $insert = $this->con->query("INSERT INTO `admin`(`id_admin`, `nama_admin`, `username`, `password`, `id_level`) VALUES ('','$nama','$username','$pass','1184212')");

                if($insert){
                    $checking = "Yes";
                }
                else{
                    $checking = "No";
                }
            }

            return $checking;
        }

        public function get_data_bank_user(){
            $get_all = $this->con->query("SELECT * FROM admin JOIN level ON admin.id_level=level.id_level WHERE level.nama_level='Bank'");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        public function bank_user_edit($id_admin){
            $id = base64_decode(base64_decode($id_admin));
            $get = $this->con->query("SELECT * FROM admin WHERE id_admin='$id'");
            if($get){
                require_once("../library/Encryption.php");
                $encrypt = new Encryption();

                $checking = "Yes";
                $data = mysqli_fetch_array($get);
                $password = $encrypt->decrypt($data['password']);
            }
            else{
                $checking = "No";
                $data = "";
                $password = "";
            }
            $array = array(
                "checking" => $checking,
                "bank" => $data,
                "password" => $password
            );

            return $array;
        }

        public function bank_user_update($id_admin, $nama, $username, $password){
            $id = base64_decode(base64_decode($id_admin));
            require_once("../library/Encryption.php");
            $encrypt = new Encryption();
            $pass = $encrypt->encrypt($password);

            $update = $this->con->query("UPDATE `admin` SET `nama_admin`='$nama',`username`='$username',`password`='$pass' WHERE id_admin='$id'");
            if($update){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function bank_user_del($id_admin){
            $id = base64_decode(base64_decode($id_admin));

            $delete = $this->con->query("DELETE FROM `admin` WHERE id_admin='$id'");
            if($delete){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }
            
            return $checking;
        }
        //End Data For Bank ----------------------------------------------
        
        //Data For User -> Bank ------------------------------------------
        public function get_data_pelanggan_user(){
            $get_all = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }
        //End Data For User -> Pelanggan ---------------------------------
        public function tarif_data_selected($id_tarif){
            $id = base64_decode(base64_decode($id_tarif));
            $get_all = $this->con->query("SELECT * FROM tarif JOIN tarif_gol ON tarif.id_tarif_gol=tarif_gol.id_tarif_gol WHERE tarif.id_tarif='$id'");

            // $array = array();
            $row = mysqli_fetch_array($get_all);
            $tarif = number_format($row['tarifperkwh'],0,',','.');
            // $array[] = $row;
            $data = array(
                "nominal" => $tarif,
                "tarif" => $row
            );
            return $data;
        }

        public function pelanggan_user_add($nama, $username, $password, $alamat, $id_tarif){
            $check_username = $this->con->query("SELECT * FROM pelanggan WHERE username='$username'");
            //Jika username sudah dipake maka lakukan penolakan
            if(mysqli_num_rows($check_username) > 0){
                $checking = "No";
            }
            //JIka belum ada maka melakukan insert
            else{
                require_once("../library/Encryption.php");
                require_once("../library/Generate.php");
                $encrypt = new Encryption();
                $generate = new Generate();

                $pass = $encrypt->encrypt($password);
                $id_pelanggan = $generate->number();
                $id = base64_decode(base64_decode($id_tarif));

                $insert = $this->con->query("INSERT INTO `pelanggan`(`id_pelanggan`, `id_tarif`, `nama_pelanggan`, `username`, `password`, `alamat`) VALUES ('$id_pelanggan','$id','$nama','$username','$pass','$alamat')");

                if($insert){
                    $checking = "Yes";
                }
                else{
                    $checking = "No";
                }
            }

            return $checking;
        }

        public function pelanggan_user_edit($id_pelanggan){
            $id = base64_decode(base64_decode($id_pelanggan));
            $get = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif JOIN tarif_gol ON tarif.id_tarif_gol=tarif_gol.id_tarif_gol WHERE id_pelanggan='$id'");
            if($get){
                require_once("../library/Encryption.php");
                $encrypt = new Encryption();

                $checking = "Yes";
                $data = mysqli_fetch_array($get);
                $password = $encrypt->decrypt($data['password']);
                $id_tarif = base64_encode(base64_encode($data['id_tarif']));
                $tarifperkwh = number_format($data['tarifperkwh'],0,',','.');
            }
            else{
                $checking = "No";
                $data = "";
                $password = "";
            }
            $array = array(
                "checking" => $checking,
                "pelanggan" => $data,
                "password" => $password,
                "id_tarif" => $id_tarif,
                "tarifperkwh" => $tarifperkwh
            );

            return $array;
        }

        public function pelanggan_user_update($id_pelanggan, $nama, $username, $password, $id_tarif, $alamat){
            $id_p = base64_decode(base64_decode($id_pelanggan));

            require_once("../library/Encryption.php");
            $encrypt = new Encryption();

            $pass = $encrypt->encrypt($password);
            $id_t = base64_decode(base64_decode($id_tarif));

            $update = $this->con->query("UPDATE `pelanggan` SET `id_tarif`='$id_t',`nama_pelanggan`='$nama',`username`='$username',`password`='$pass',`alamat`='$alamat' WHERE id_pelanggan='$id_p'");
            if($update){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }
        
        public function pelanggan_user_del($id_pelanggan){
            $id = base64_decode(base64_decode($id_pelanggan));

            $delete = $this->con->query("DELETE FROM `pelanggan` WHERE id_pelanggan='$id'");
            if($delete){
                $checking = "Yes";

                //Deleting Other Data
                $this->con->query("DELETE FROM `penggunaan` WHERE id_pelanggan='$id'");
                $this->con->query("DELETE FROM `tagihan` WHERE id_pelanggan='$id'");
                $this->con->query("DELETE FROM `pembayaran` WHERE id_pelanggan='$id'");
            }
            else{
                $checking = "No";
            }
            
            return $checking;
        }
        //End Data For Pelangan User ----------------------------------------------

        //Data For Tarif ------------------------------------------------------
        public function tarif_data(){
            $get_all = $this->con->query("SELECT * FROM tarif JOIN tarif_gol ON tarif.id_tarif_gol=tarif_gol.id_tarif_gol");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        public function tarif_gol(){
            $get_all = $this->con->query("SELECT * FROM tarif_gol");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        public function tarif_add($id_tarif_gol, $tarif, $daya, $tarif_kwh, $keterangan){
            $id = base64_decode(base64_decode($id_tarif_gol));
            $insert = $this->con->query("INSERT INTO `tarif`(`id_tarif`, `id_tarif_gol`, `gol_tarif`, `daya`, `tarifperkwh`, `keterangan`) VALUES ('','$id','$tarif','$daya','$tarif_kwh','$keterangan')");
            if($insert){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function tarif_edit($id_tarif){
            $id = base64_decode(base64_decode($id_tarif));
            $get = $this->con->query("SELECT * FROM tarif JOIN tarif_gol ON tarif.id_tarif_gol=tarif_gol.id_tarif_gol WHERE tarif.id_tarif='$id'");
            if($get){
                $checking = "Yes";
                $data = mysqli_fetch_array($get);
                $id_tarif_gol = base64_encode(base64_encode($data['id_tarif_gol']));
            }
            else{
                $checking = "No";
                $data = "";
            }
            $array = array(
                "checking" => $checking,
                "tarif" => $data,
                "id_tarif_gol" => $id_tarif_gol
            );

            return $array;
        }

        public function tarif_update($id_tarif, $tarif_gol, $tarif, $daya, $tarif_kwh, $keterangan){
            $id_t = base64_decode(base64_decode($id_tarif));
            $id_tg = base64_decode(base64_decode($tarif_gol));

            $update = $this->con->query("UPDATE `tarif` SET `id_tarif_gol`='$id_tg',`gol_tarif`='$tarif',`daya`='$daya',`tarifperkwh`='$tarif_kwh',`keterangan`='$keterangan' WHERE id_tarif='$id_t'");
            if($update){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function tarif_del($id_tarif){
            $id = base64_decode(base64_decode($id_tarif));

            $delete = $this->con->query("DELETE FROM `tarif` WHERE id_tarif='$id'");

            //Deleting For Pelanggan Data
            $pelanggan = $this->con->query("SELECT * FROM `pelanggan` WHERE id_tarif='$id'");

            //Perulangan
            while($row = mysqli_fetch_array($pelanggan)){
                $id_pelanggan = $row['id_pelanggan'];

                $this->con->query("DELETE FROM `pembayaran` WHERE id_pelanggan='$id_pelanggan'");
                $this->con->query("DELETE FROM `penggunaan` WHERE id_pelanggan='$id_pelanggan'");
                $this->con->query("DELETE FROM `tagihan` WHERE id_pelanggan='$id_pelanggan'");
            }

            if($delete){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }
            
            return $checking;
        }

        //Data For Tarif > Golongan ------------------------------------------------
        public function tarif_gol_add($tarif_gol){
            $insert = $this->con->query("INSERT INTO `tarif_gol`(`id_tarif_gol`, `nama_gol`) VALUES ('','$tarif_gol')");
            if($insert){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function tarif_gol_edit($id_tarif_gol){
            $id = base64_decode(base64_decode($id_tarif_gol));
            $get = $this->con->query("SELECT * FROM tarif_gol WHERE id_tarif_gol='$id'");
            if($get){
                $checking = "Yes";
                $data = mysqli_fetch_array($get);
            }
            else{
                $checking = "No";
                $data = "";
            }
            $array = array(
                "checking" => $checking,
                "tarif_gol" => $data
            );

            return $array;
        }

        public function tarif_gol_update($id_tarif_gol, $tarif_gol){
            $id = base64_decode(base64_decode($id_tarif_gol));

            $update = $this->con->query("UPDATE `tarif_gol` SET `nama_gol`='$tarif_gol' WHERE id_tarif_gol='$id'");
            if($update){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function tarif_gol_del($id_tarif_gol){
            $id = base64_decode(base64_decode($id_tarif_gol));

            //Deleting Data For Tarif_gol
            $delete = $this->con->query("DELETE FROM `tarif_gol` WHERE id_tarif_gol='$id'");
            
            //Get All Data who is used for this golongan
            $tarif = $this->con->query("SELECT * FROM `tarif` WHERE id_tarif_gol='$id'");
            //After Get All Data and take them to $tarif, start deleting tarif data
            $this->con->query("DELETE FROM `tarif` WHERE id_tarif_gol='$id'");

            //Looping for all tarif
            while($row = mysqli_fetch_array($tarif)){
                //Get Primary Key
                $id_tarif = $row['id_tarif'];

                //Get Data For Pelanggan
                $pelanggan = $this->con->query("SELECT * FROM `pelanggan` WHERE id_tarif='$id_tarif'");

                //Set Looping again to pelanggan
                while($row2 = mysqli_fetch_array($pelanggan)){
                    //Get Primary Key
                    $id_pelanggan = $row2['id_pelanggan'];

                    //Start Deleting All data
                    $this->con->query("DELETE FROM `pembayaran` WHERE id_pelanggan='$id_pelanggan'");
                    $this->con->query("DELETE FROM `penggunaan` WHERE id_pelanggan='$id_pelanggan'");
                    $this->con->query("DELETE FROM `tagihan` WHERE id_pelanggan='$id_pelanggan'");
                }
            }

            if($delete){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }
            
            return $checking;
        }

        //Data For Penggunaan > Listrik --------------------------------------
        public function peng_listrik_data(){
            if($_SESSION['level'] == "Pelanggan"){
                $id_pelanggan = $_SESSION['id_user'];
                $get_all = $this->con->query("SELECT * FROM penggunaan JOIN pelanggan ON penggunaan.id_pelanggan=pelanggan.id_pelanggan WHERE penggunaan.id_pelanggan='$id_pelanggan'");
            }
            else{
                $get_all = $this->con->query("SELECT * FROM penggunaan JOIN pelanggan ON penggunaan.id_pelanggan=pelanggan.id_pelanggan");
            }

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        public function peng_listrik_add($id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir){
            $id = base64_decode(base64_decode($id_pelanggan));
            //Before we insert the data, we will check the data first if that following data is already exist
            $data = $this->con->query("SELECT * FROM penggunaan WHERE id_pelanggan='$id' AND bulan='$bulan' AND tahun='$tahun' AND meter_awal='$meter_awal' AND meter_akhir='$meter_akhir'");

            //If exist sending back request to refuse inserting
            if(mysqli_num_rows($data) > 0){
                $checking = "No";
            }
            else{
                $insert = $this->con->query("INSERT INTO `penggunaan`(`id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `meter_awal`, `meter_akhir`) VALUES ('','$id','$bulan','$tahun','$meter_awal','$meter_akhir')");
                
                //Get back data after we inserting
                $data = $this->con->query("SELECT * FROM penggunaan WHERE id_pelanggan='$id' AND bulan='$bulan' AND tahun='$tahun' AND meter_awal='$meter_awal' AND meter_akhir='$meter_akhir'");

                $get = mysqli_fetch_array($data);
                $id_penggunaan = $get['id_penggunaan'];

                //After that we insert for tagihan data
                $jumlah_meter = $meter_akhir - $meter_awal;
                $insert_2 = $this->con->query("INSERT INTO `tagihan`(`id_tagihan`, `id_penggunaan`, `id_pelanggan`, `bulan`, `tahun`, `jumlah_meter`, `status`) VALUES ('','$id_penggunaan','$id','$bulan','$tahun','$jumlah_meter','Belum Lunas')");

                if($insert && $insert_2){
                    $checking = "Yes";
                }
                else{
                    $checking = "No";
                }
            }
        
            return $checking;
        }

        public function peng_listrik_edit($id_penggunaan){
            $id = base64_decode(base64_decode($id_penggunaan));
            $get = $this->con->query("SELECT * FROM penggunaan WHERE id_penggunaan='$id'");
            if($get){
                $checking = "Yes";
                $data = mysqli_fetch_array($get);
                $pelanggan = base64_encode(base64_encode($data['id_pelanggan']));
            }
            else{
                $checking = "No";
                $data = "";
                $pelanggan = "";
            }
            $array = array(
                "checking" => $checking,
                "penggunaan" => $data,
                "pelanggan" => $pelanggan
            );

            return $array;
        }

        public function peng_listrik_update($id_penggunaan, $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir){
            $id_pen = base64_decode(base64_decode($id_penggunaan));
            $id_pel = base64_decode(base64_decode($id_pelanggan));

            $update = $this->con->query("UPDATE `penggunaan` SET `id_pelanggan`='$id_pel',`bulan`='$bulan',`tahun`='$tahun',`meter_awal`='$meter_awal',`meter_akhir`='$meter_akhir' WHERE id_penggunaan='$id_pen'");
            
            //Also updating for tagihan
            $jumlah_meter = $meter_akhir - $meter_awal;
            $update_2 = $this->con->query("UPDATE `tagihan` SET `id_pelanggan`='$id_pel',`bulan`='$bulan',`tahun`='$tahun',`jumlah_meter`='$jumlah_meter' WHERE id_penggunaan='$id_pen'");

            if($update){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            return $checking;
        }

        public function peng_listrik_del($id_penggunaan){
            $id = base64_decode(base64_decode($id_penggunaan));
            
            //Deleting Data For penggunaan
            $delete = $this->con->query("DELETE FROM `penggunaan` WHERE id_penggunaan='$id'");

            //Get Data For tagihan
            $tagihan = $this->con->query("SELECT * FROM `tagihan` WHERE id_penggunaan='$id'");

            $row2 = mysqli_fetch_array($tagihan);
            //Get Primary Key
            $id_tagihan = $row2['id_tagihan'];

            //Start Deleting All data
            $this->con->query("DELETE FROM `tagihan` WHERE id_tagihan='$id_tagihan'");
            $this->con->query("DELETE FROM `pembayaran` WHERE id_tagihan='$id_tagihan'");
            if($delete){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }
            
            return $checking;
        }

        //This is for Penggunaan > Tagihan ------------------------------------------
        public function peng_tagihan_data(){
            if($_SESSION['level'] == "Pelanggan"){
                $id_pelanggan = $_SESSION['id_user'];
                $get_all = $this->con->query("SELECT * FROM tagihan JOIN pelanggan ON tagihan.id_pelanggan=pelanggan.id_pelanggan WHERE tagihan.id_pelanggan='$id_pelanggan'");
            }
            else{
                $get_all = $this->con->query("SELECT * FROM tagihan JOIN pelanggan ON tagihan.id_pelanggan=pelanggan.id_pelanggan");
            }

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        //Thisi is for Pembayaran > Bayar -------------------------------------------
        public function bayar_pelanggan_data($id_pelanggan){
            $id = base64_decode(base64_decode($id_pelanggan));
            $get_all = $this->con->query("SELECT * FROM tagihan WHERE id_pelanggan='$id' AND status='Belum Lunas'");

            $data_pelanggan = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id'");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            $data = mysqli_fetch_array($data_pelanggan);

            $return_data = array(
                "tagihan" => $array,
                "pelanggan" => $data,
                "tarif_kwh" => number_format($data['tarifperkwh'],0,',','.')
            );

            return $return_data;
        }

        //Function for pelanggan only
        public function bayar_pelanggan_data_only(){
            $id_pelanggan = $_SESSION['id_user'];
            $get_all = $this->con->query("SELECT * FROM tagihan JOIN pelanggan ON tagihan.id_pelanggan=pelanggan.id_pelanggan WHERE tagihan.id_pelanggan='$id_pelanggan' AND tagihan.status='Belum Lunas'");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }
            
            return $array;
        }

        public function get_detail_tagihan($id_tagihan){
            $tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_tagihan='$id_tagihan' AND status='Belum Lunas'");
            
            if(mysqli_num_rows($tagihan) > 0){
                $data_tagihan = mysqli_fetch_array($tagihan);

                $id_pelanggan = $data_tagihan['id_pelanggan'];
                $tarif = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id_pelanggan'");

                $data_tarif = mysqli_fetch_array($tarif);

                //Process Data
                $jumlah_meter = $data_tagihan['jumlah_meter'];
                $tarif_kwh = $data_tarif['tarifperkwh'];
                
                $bulan_bayar = $data_tagihan['bulan'];
                $biaya_admin = 2500;
                $harga = $jumlah_meter * $tarif_kwh;
                $ppj = 3/100 * $harga;
                $ppn = 10/100 * $harga;
                $total = $harga + $ppj + $ppn + $biaya_admin;
                $checking = "Yes";
            }
            else{
                $checking = "No";
                $bulan_bayar = "";
                $harga = "";
                $ppj = "";
                $ppn = "";
                $total = "";
                $jumlah_meter = "";
            }

            //Check Jika pelanggan sudah meminta request
            $pembayaran = $this->con->query("SELECT * FROM pembayaran WHERE id_tagihan='$id_tagihan'");
            if(mysqli_num_rows($pembayaran) > 0){
                $data_pembayaran = mysqli_fetch_array($pembayaran);
                $id_pembayaran = $data_pembayaran['id_pembayaran'];
                $status_pembayaran = $data_pembayaran['status_pembayaran'];
            }
            else{
                $id_pembayaran = "";
                $status_pembayaran = "";
            }

            $array = array(
                "checking" => $checking,
                "bulan_bayar" => $bulan_bayar,
                "jumlah_meter" => number_format($jumlah_meter,0,',','.'),
                "harga" => number_format($harga,0,',','.'),
                "harga_real" => $harga,
                "ppj" => number_format($ppj,0,',','.'),
                "ppj_real" => $ppj,
                "ppn" => number_format($ppn,0,',','.'),
                "ppn_real" => $ppn,
                "total" => number_format($total,0,',','.'),
                "total_real" => ceil($total),
                "id_pembayaran" => $id_pembayaran,
                "status_pembayaran" => $status_pembayaran
            );

            return $array;
        }

        public function pembayaran_insert($id_pembayaran, $tgl_bayar, $id_pelanggan, $id_tagihan, $harga_bayar, $total_harga, $harga_pemakaian, $ppn, $ppj, $bulan_bayar){
            //ID Pelanggan
            $id_pel = base64_decode(base64_decode($id_pelanggan));
            $kembalian = $harga_bayar - $total_harga;
            if($_SESSION['level'] == "Pelanggan"){
                $id_admin = "";
                $status_pembayaran = "Requested";
                $status_tagihan = "Belum Lunas";
            }
            else{
                $id_admin = $_SESSION['id_user'];
                $status_pembayaran = "Confirmation";
                $status_tagihan = "Lunas";
            }
            

            $insert_pembayaran = $this->con->query("INSERT INTO `pembayaran`(`id_pembayaran`, `id_tagihan`, `id_pelanggan`, `tanggal_pembayaran`, `bulan_bayar`, `biaya_admin`, `ppj`, `ppn`, `harga_pemakaian`, `total_harga`, `total_bayar`, `total_kembalian`, `id_admin`, `status_pembayaran`) VALUES ('$id_pembayaran','$id_tagihan','$id_pel','$tgl_bayar','$bulan_bayar','2500','$ppj','$ppn','$harga_pemakaian','$total_harga','$harga_bayar','$kembalian','$id_admin','$status_pembayaran')");

            $update_tagihan = $this->con->query("UPDATE `tagihan` SET `status`='$status_tagihan' WHERE id_tagihan='$id_tagihan'");

            if($insert_pembayaran && $update_tagihan){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            $return_data = array("checking" => $checking);

            return $return_data;
        }

        //This is for Pembayaran > Konfirmasi Pembayaran ------------------------
        public function get_konfirmasi_pembayaran_data(){
            $get_all = $this->con->query("SELECT * FROM pembayaran JOIN tagihan ON pembayaran.id_tagihan=tagihan.id_tagihan WHERE pembayaran.status_pembayaran<>'Confirmation' AND tagihan.status='Belum Lunas'");

            $array = array();
            while($row = mysqli_fetch_array($get_all)){
                $array[] = $row;
            }

            return $array;
        }

        public function pelanggan_konfirm_bayar_data($id_pembayaran){
            $data_pembayaran = $this->con->query("SELECT * FROM pembayaran WHERE id_pembayaran='$id_pembayaran'");
            if(mysqli_num_rows($data_pembayaran) > 0){
                $pembayaran = mysqli_fetch_array($data_pembayaran);
                $id_pelanggan = $pembayaran['id_pelanggan'];
                $id_tagihan = $pembayaran['id_tagihan'];

                $array_pembayaran = array(
                    "id_pembayaran" => $id_pembayaran,
                    "tanggal_pembayaran" => $pembayaran['tanggal_pembayaran'],
                    "harga_pemakaian" => "Rp ".number_format($pembayaran['harga_pemakaian'],0,',','.'),
                    "ppj" => "Rp ".number_format($pembayaran['ppj'],0,',','.'),
                    "ppn" => "Rp ".number_format($pembayaran['ppn'],0,',','.'),
                    "total_harga" => "Rp ".number_format($pembayaran['total_harga'],0,',','.'),
                    "total_bayar" => "Rp ".number_format($pembayaran['total_bayar'],0,',','.'),
                    "kembalian" => "Rp ".number_format($pembayaran['total_kembalian'],0,',','.'),
                    "status_pembayaran" => $pembayaran['status_pembayaran']
                );
            }
            else{
                $array_pembayaran = "";
                $id_pelanggan = "";
                $id_tagihan = "";
            }

            $data_pelanggan = $this->con->query("SELECT * FROM pelanggan JOIN tarif ON pelanggan.id_tarif=tarif.id_tarif WHERE pelanggan.id_pelanggan='$id_pelanggan'");
            if(mysqli_num_rows($data_pelanggan) > 0){
                $pelanggan = mysqli_fetch_array($data_pelanggan);

                $array_pelanggan = array(
                    "id_pelanggan" => $id_pelanggan,
                    "nama" => $pelanggan['nama_pelanggan'],
                    "tarif_daya" => $pelanggan['gol_tarif']."/".$pelanggan['daya'],
                    "tarif_kwh" => "Rp ".number_format($pelanggan['tarifperkwh'],0,',','.'),
                    "keterangan" => $pelanggan['keterangan'],
                    "alamat" => $pelanggan['alamat']
                );
            }
            else{
                $array_pelanggan = "";
            }

            $data_tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_tagihan='$id_tagihan'");
            if(mysqli_num_rows($data_tagihan) > 0){
                $tagihan = mysqli_fetch_array($data_tagihan);

                $array_tagihan = array(
                    "penggunaan_listrik" => number_format($tagihan['jumlah_meter'],0,',','.')." KwH"
                );
            }
            else{
                $array_tagihan = "";
            }

            $return_data = array(
                "pembayaran" => $array_pembayaran,
                "pelanggan" => $array_pelanggan,
                "tagihan" => $array_tagihan
            );

            return $return_data;
        }
        
        public function konfirm_bayar($id_pembayaran){
            $id_user = $_SESSION['id_user'];
            $data_pembayaran = $this->con->query("SELECT * FROM pembayaran JOIN tagihan ON pembayaran.id_tagihan=tagihan.id_tagihan WHERE pembayaran.id_pembayaran='$id_pembayaran' AND pembayaran.status_pembayaran<>'Confirmation'");

            if(mysqli_num_rows($data_pembayaran) > 0){
                $checking = "Yes";
                $pembayaran = mysqli_fetch_array($data_pembayaran);
                $id_tagihan = $pembayaran['id_tagihan'];

                //Update Pembayaran
                $this->con->query("UPDATE `pembayaran` SET `id_admin`='$id_user',`status_pembayaran`='Confirmation' WHERE id_pembayaran='$id_pembayaran'");
                //Update Tagihan
                $this->con->query("UPDATE `tagihan` SET `status`='Lunas' WHERE id_tagihan='$id_tagihan'");
            }
            else{
                $checking = "No";
            }

            $return_data = array("checking" => $checking);
            return $return_data;
        }

        public function refuse_bayar($id_pembayaran){
            $id_user = $_SESSION['id_user'];
            $data_pembayaran = $this->con->query("SELECT * FROM pembayaran JOIN tagihan ON pembayaran.id_tagihan=tagihan.id_tagihan WHERE pembayaran.id_pembayaran='$id_pembayaran'");

            if(mysqli_num_rows($data_pembayaran) > 0){
                $checking = "Yes";
                $pembayaran = mysqli_fetch_array($data_pembayaran);
                $id_tagihan = $pembayaran['id_tagihan'];

                //Update Pembayaran
                $this->con->query("UPDATE `pembayaran` SET `id_admin`='$id_user',`status_pembayaran`='Refuse' WHERE id_pembayaran='$id_pembayaran'");
                //Update Tagihan
                $this->con->query("UPDATE `tagihan` SET `status`='Belum Lunas' WHERE id_tagihan='$id_tagihan'");
            }
            else{
                $checking = "No";
            }

            $return_data = array("checking" => $checking);
            return $return_data;
        }

        public function del_pembayaran($id_pembayaran){
            $del = $this->con->query("DELETE FROM `pembayaran` WHERE id_pembayaran='$id_pembayaran' AND status_pembayaran<>'Confirmation'");
            if($del){
                $checking = "Yes";
            }
            else{
                $checking = "No";
            }

            $data = array("checking" => $checking);

            return $data;
        }

        
        //This is for Pembayaran > Check Pembayaran -----------------------------
        public function get_check_pembayaran($id_pelanggan){
            $id_pel = base64_decode(base64_decode($id_pelanggan));

            $all_data = $this->con->query("SELECT * FROM pembayaran JOIN tagihan ON pembayaran.id_tagihan=tagihan.id_tagihan WHERE pembayaran.id_pelanggan='$id_pel'");

            if(mysqli_num_rows($all_data) > 0){

                $array = array();
                while($row = mysqli_fetch_array($all_data)){
                    $array[] = $row;
                }

            }
            else{
                $array = "";
            }

            $data = array("pelanggan" => $array);

            return $data;
        }

        //This is for Pelanggan Only
        public function get_check_pembayaran_pelanggan_only(){
            $id_pel = $_SESSION['id_user'];

            $all_data = $this->con->query("SELECT * FROM pembayaran JOIN tagihan ON pembayaran.id_tagihan=tagihan.id_tagihan WHERE pembayaran.id_pelanggan='$id_pel'");

            $array = array();
            while($row = mysqli_fetch_array($all_data)){
                $array[] = $row;
            }

            return $array;
        }

        //This is for Laporan > Tunggakan ------------------------------------------
        public function lap_tunggakan(){
            $get_data = $this->con->query("SELECT tagihan.*, COUNT(tagihan.bulan) AS tunggakan, pelanggan.id_pelanggan, pelanggan.nama_pelanggan, tarif.* FROM `tagihan`, pelanggan, tarif WHERE tagihan.id_pelanggan=pelanggan.id_pelanggan AND tagihan.status='Belum Lunas' AND pelanggan.id_tarif=tarif.id_tarif GROUP BY tagihan.id_pelanggan");

            $array = array();

            while($row = mysqli_fetch_array($get_data)){
                $array[] = $row;
            }

            return $array;
        }

        public function lap_tunggakan_harga($id_pelanggan, $tarif_kwh){
            $get_tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_pelanggan='$id_pelanggan' AND status='Belum Lunas'");

            $total_harga = 0;
            $biaya_admin = 2500;
            while($row = mysqli_fetch_array($get_tagihan)){
                $harga = $row['jumlah_meter'] * $tarif_kwh;
                $ppj = 0.03 * $harga;
                $ppn = 0.10 * $harga;
                $total = $harga + $ppj + $ppn + $biaya_admin;

                $total_harga += $total;
            }

            return $total_harga;
        }

        //This is for Laporan > Penggunaan ---------------------------------
        public function lap_penggunaan(){
            $get_data = $this->con->query("SELECT tagihan.*, COUNT(tagihan.bulan) AS penggunaan, pelanggan.id_pelanggan, pelanggan.nama_pelanggan FROM `tagihan`, pelanggan WHERE tagihan.id_pelanggan=pelanggan.id_pelanggan GROUP BY tagihan.id_pelanggan");

            $array = array();

            while($row = mysqli_fetch_array($get_data)){
                $array[] = $row;
            }

            return $array;
        }

        public function lap_penggunaan_meter($id_pelanggan){
            $get_tagihan = $this->con->query("SELECT * FROM tagihan WHERE id_pelanggan='$id_pelanggan'");

            $total_meter = 0;
            while($row = mysqli_fetch_array($get_tagihan)){
                $total_meter += $row['jumlah_meter'];
            }

            return $total_meter;
        }
    }
?>