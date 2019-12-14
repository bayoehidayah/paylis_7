<?php
    //This is Ajax Controller not a Class
    if(isset($_GET['request'])){
        $request = $_GET['request'];

        if($request == "server_connection"){
            require_once("config.php");

            $connection = new Config();
            $check_status = $connection->connect_to_server();

            $callback = array("checking" => $check_status);

            echo json_encode($callback);
        }
        else if($request == "login"){
            require_once("auth.php");
            $username = $_GET['username'];
            $password = $_GET['password'];
            $sebagai = $_GET['sebagai'];
            $auth = new Auth();
            $checking = $auth->login_action($username, $password, $sebagai);

            $json = array(
                "checking" => $checking
            );

            echo json_encode($json);
        }

        //This is For User -> Bank Ajax Controller-------------------------------
        else if($request == "bank_user_adding"){
            $username = $_GET['username'];
            $password = $_GET['password'];
            $nama = $_GET['nama'];

            require_once("data.php");
            
            $insert = new Data("view");
            $checking = $insert->bank_user_add($nama, $username, $password);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "bank_user_edit"){
            require_once("data.php");
            $id_admin = $_GET['id_admin'];
            $data = new Data("view");

            $check = $data->bank_user_edit($id_admin);

            echo json_encode($check);
        }
        else if($request == "bank_user_updating"){
            $id_admin = $_GET['id_admin'];
            $username = $_GET['username'];
            $password = $_GET['password'];
            $nama = $_GET['nama'];

            require_once("data.php");
            
            $insert = new Data("view");
            $checking = $insert->bank_user_update($id_admin, $nama, $username, $password);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "bank_user_deleting"){
            require_once("data.php");
            $id_admin = $_GET['id_admin'];
            $data = new Data("view");

            $check = $data->bank_user_del($id_admin);

            $data = array("checking" => $check);

            echo json_encode($data);
        }
        //End for User -> Bank Ajax Controller

        //This is for User -> Pelanggan Ajax Controller ---------------------------------
        else if($request == "pelanggan_tarif_data"){
            require_once("data.php");
            $id_tarif = $_GET['tarif'];
            $data = new Data("view");

            $check = $data->tarif_data_selected($id_tarif);

            echo json_encode($check);
        }
        else if($request == "pelanggan_user_adding"){
            require_once("data.php");
            $id_tarif = $_GET['tarif'];
            $nama = $_GET['nama'];
            $username = $_GET['username'];
            $password = $_GET['password'];
            $alamat = $_GET['alamat'];

            $data = new Data("view");
            $check = $data->pelanggan_user_add($nama, $username, $password, $alamat, $id_tarif);
            
            $checking = array("checking" => $check);

            echo json_encode($checking);
        }
        else if($request == "pelanggan_user_edit"){
            require_once("data.php");
            $id_pelanggan = $_GET['id_pelanggan'];
            $data = new Data("view");

            $check = $data->pelanggan_user_edit($id_pelanggan);

            echo json_encode($check);
        }
        else if($request == "pelanggan_user_updating"){
            $id_pelanggan = $_GET['id_pelanggan'];
            $id_tarif = $_GET['tarif'];
            $nama = $_GET['nama'];
            $username = $_GET['username'];
            $password = $_GET['password'];
            $alamat = $_GET['alamat'];

            require_once("data.php");
            
            $insert = new Data("view");
            $checking = $insert->pelanggan_user_update($id_pelanggan, $nama, $username, $password, $id_tarif, $alamat);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "pelanggan_user_deleting"){
            require_once("data.php");
            $id_pelanggan = $_GET['id_pelanggan'];
            $data = new Data("view");

            $check = $data->pelanggan_user_del($id_pelanggan);

            $data = array("checking" => $check);

            echo json_encode($data);
        }
        //End for User -> Pelanggan Ajax Controller

        //This is for Tarif -> Tarif AJax Controller -----------------------------------------
        else if($request == "tarif_adding"){
            require_once("data.php");
            $id_tarif_gol = $_GET['tarif_gol'];
            $tarif = $_GET['tarif'];
            $daya = $_GET['daya'];
            $tarif_kwh = $_GET['tarif_kwh'];
            $keterangan = $_GET['keterangan'];

            $data = new Data("view");
            $check = $data->tarif_add($id_tarif_gol, $tarif, $daya, $tarif_kwh, $keterangan);

            $checking = array("checking" => $check);

            echo json_encode($checking);
        }
        else if($request == "tarif_edit"){
            require_once("data.php");
            $id_tarif = $_GET['id_tarif'];
            $data = new Data("view");

            $check = $data->tarif_edit($id_tarif);

            echo json_encode($check);
        }
        else if($request == "tarif_updating"){
            $id_tarif = $_GET['id_tarif'];
            $tarif_gol = $_GET['tarif_gol'];
            $tarif = $_GET['tarif'];
            $daya = $_GET['daya'];
            $tarif_kwh = $_GET['tarif_kwh'];
            $keterangan = $_GET['keterangan'];

            require_once("data.php");
            
            $update = new Data("view");
            $checking = $update->tarif_update($id_tarif, $tarif_gol, $tarif, $daya, $tarif_kwh, $keterangan);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "tarif_deleting"){
            require_once("data.php");
            $id_tarif = $_GET['id_tarif'];
            $data = new Data("view");

            $check = $data->tarif_del($id_tarif);

            $data = array("checking" => $check);

            echo json_encode($data);
        }
        //ENd For Tarif > Tarif Ajax Request

        //This is for Tarif > Golongan AJax Request
        else if($request == "tarif_gol_adding"){
            require_once("data.php");
            $tarif_gol = $_GET['tarif_gol'];

            $data = new Data("view");
            $check = $data->tarif_gol_add($tarif_gol);

            $checking = array("checking" => $check);

            echo json_encode($checking);
        }
        else if($request == "tarif_gol_edit"){
            require_once("data.php");
            $id_tarif_gol = $_GET['id_tarif_gol'];
            $data = new Data("view");

            $check = $data->tarif_gol_edit($id_tarif_gol);

            echo json_encode($check);
        }
        else if($request == "tarif_gol_updating"){
            $id_tarif_gol = $_GET['id_tarif_gol'];
            $tarif_gol = $_GET['tarif_gol'];

            require_once("data.php");
            
            $update = new Data("view");
            $checking = $update->tarif_gol_update($id_tarif_gol, $tarif_gol);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "tarif_gol_deleting"){
            require_once("data.php");
            $id_tarif_gol = $_GET['id_tarif_gol'];
            $data = new Data("view");

            $check = $data->tarif_gol_del($id_tarif_gol);

            $data = array("checking" => $check);

            echo json_encode($data);
        }

        //This is for Penggunaan -> Listrik AJax Controller -----------------------------------------
        else if($request == "peng_listrik_adding"){
            require_once("data.php");
            $id_pelanggan = $_GET['pelanggan'];
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $meter_awal = $_GET['meter_awal'];
            $meter_akhir = $_GET['meter_akhir'];

            $data = new Data("view");
            $check = $data->peng_listrik_add($id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir);

            $checking = array("checking" => $check);

            echo json_encode($checking);
        }
        else if($request == "peng_listrik_edit"){
            require_once("data.php");
            $id_penggunaan = $_GET['id_peng_listrik'];
            $data = new Data("view");

            $check = $data->peng_listrik_edit($id_penggunaan);

            echo json_encode($check);
        }
        else if($request == "peng_listrik_updating"){
            $id_penggunaan = $_GET['id_peng_listrik'];
            $id_pelanggan = $_GET['pelanggan'];
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $meter_awal = $_GET['meter_awal'];
            $meter_akhir = $_GET['meter_akhir'];

            require_once("data.php");
            
            $update = new Data("view");
            $checking = $update->peng_listrik_update($id_penggunaan, $id_pelanggan, $bulan, $tahun, $meter_awal, $meter_akhir);

            $data = array("checking" => $checking);

            echo json_encode($data);
        }
        else if($request == "peng_listrik_deleting"){
            require_once("data.php");
            $id_penggunaan = $_GET['id_peng_listrik'];
            $data = new Data("view");

            $check = $data->peng_listrik_del($id_penggunaan);

            $data = array("checking" => $check);

            echo json_encode($data);
        }

        //This is for Pembayaran > Bayar AJax Request --------------------
        else if($request == "bayar_select_pelanggan"){
            $pelanggan = $_GET['pelanggan'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->bayar_pelanggan_data($pelanggan);

            echo json_encode($check);
        }
        else if($request == "get_detail_tagihan"){
            $tagihan = $_GET['tagihan'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->get_detail_tagihan($tagihan);

            echo json_encode($check);
        }
        else if($request == "bayar_action"){
            $id_pembayaran = $_GET['id_pembayaran'];
            $tgl_bayar = $_GET['tgl_bayar'];
            $id_pelanggan = $_GET['id_pelanggan'];
            $id_tagihan = $_GET['id_tagihan'];
            $harga_bayar = $_GET['harga_bayar'];
            $total_harga = $_GET['total_harga'];
            $harga_pemakaian = $_GET['harga_pemakaian'];
            $ppn = $_GET['ppn'];
            $ppj = $_GET['ppj'];
            $bulan_bayar = $_GET['bulan_bayar'];

            require_once("data.php");

            $insert = new Data("view");

            $check = $insert->pembayaran_insert($id_pembayaran, $tgl_bayar, $id_pelanggan, $id_tagihan, $harga_bayar, $total_harga, $harga_pemakaian, $ppn, $ppj, $bulan_bayar);
            
            echo json_encode($check);
        }

        //This is for Pembayaran > Konfirmasi Pembayaran
        else if($request == "pelanggan_konfirm_bayar_data"){
            $id_pembayaran = $_GET['kode_pembayaran'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->pelanggan_konfirm_bayar_data($id_pembayaran);

            echo json_encode($check);
        }
        else if($request == "konfirm_bayar"){
            $id_pembayaran = $_GET['kode_pembayaran'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->konfirm_bayar($id_pembayaran);

            echo json_encode($check);
        }
        else if($request == "refuse_bayar"){
            $id_pembayaran = $_GET['kode_pembayaran'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->refuse_bayar($id_pembayaran);

            echo json_encode($check);
        }

        else if($request == "del_pembayaran"){
            $id_pembayaran = $_GET['kode_pembayaran'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->del_pembayaran($id_pembayaran);

            echo json_encode($check);
        }

        //This is for Pembayaran > Check Pembayaran
        else if($request == "bayar_check_pelanggan"){
            $pelanggan = $_GET['pelanggan'];

            require_once("data.php");

            $data = new Data("view");
            $check = $data->get_check_pembayaran($pelanggan);

            echo json_encode($check);
        }
    }
?>