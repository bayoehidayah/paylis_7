<?php
    require_once("system/config.php");
    require_once("system/view.php");
    require_once("library/session.php");

    $checking_status = new Config();
    $session = new Session();

    if($checking_status->connect_to_server() == "success"){
        //Check Session
        $session->status();
    }
    else{
        ?>
            <html>
                <head>
                    <title>404 Error Connection</title>
                    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
                    <!-- Sweetalert Css -->
                    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />                    
                    <!-- Custom Css -->
                    <link rel="stylesheet" href="assets/css/main.css">
                    <!-- AdminCC You can choose a theme from css/themes instead of get all themes -->
                    <link rel="stylesheet" href="assets/css/all-themes.css" />
                    </head>

                </head>
                <body>
                    <!-- Jquery Core Js --> 
                    <script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js -->
                    <script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js --> 
                    <script src="assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js -->
                    <script src="assets/bundles/mainscripts.bundle.js"></script><!-- Custom Js --> 
                    <script>
                        $(document).ready(function(){
                            load_server();
                        })

                        function load_server(){
                            swal({
                                // title: "Oops!",
                                title : "Mencoba menghubungkan ke server...",
                                
                                timer : 2000,
                                showCancelButton: false,
                                showConfirmButton : false,
                                closeOnConfirm: false
                            }, function () {
                                setTimeout(function () {
                                    $.ajax({
                                        type: "GET",
                                        url: "system/Ajax.php",
                                        data: {
                                            request : "server_connection"
                                        },
                                        dataType: "JSON",
                                        success: function (data) {
                                            if(data.checking == "success"){
                                                swal({
                                                    title : "Berhasil!",
                                                    text : "Telah terhubung ke server, halaman akan direfresh",
                                                    type : "success",
                                                    timer : 2000,
                                                    showConfirmButton : false
                                                }, function(){
                                                    location.reload();
                                                })
                                            }
                                            else if(data.checking == "failed"){
                                                swal({
                                                    title : "Gagal!",
                                                    text : "Gagal terhubung ke server, mencoba kembali...",
                                                    type : "error",
                                                    timer : 2000,
                                                    showConfirmButton : false,
                                                    showCancelButton : false,
                                                }, function(){
                                                    load_server();
                                                });
                                            }
                                        },
                                        error : function(jqXHR, textStatus, errorThrown){
                                            swal({
                                                title : "Oops!",
                                                text : "Gagal melakukan penghubungan server, mencoba kembali...",
                                                type : "error",
                                                timer : 2000,
                                                showConfirmButton : false,
                                                showCancelButton : false,
                                            }, function(){
                                                load_server();
                                            }); 
                                        }
                                    });
                                }, 2000);
                            });
                        }
                    </script>
                </body>
            </html>
        <?php
    }
?>