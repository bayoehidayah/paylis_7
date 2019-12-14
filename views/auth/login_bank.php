<?php
    require_once("../../system/config.php");
    require_once("../../system/template.php");
    $template = new Template();
    $data_login = $template->login();
    $config = new Config();
    //Checking connection
    if($config->connect_to_server() == "failed"){
        header("location:../../index.php");
    }
    //Check jika sudah login
    if(isset($_SESSION['status'])){
        if($_SESSION['status'] == "TRUE"){
            header("location:../../index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $template->head(); ?>
</head>
<body>
<body class="theme-red">
<div class="authentication">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-lg-8 col-md-12 col-sm-12">
				<div class="l-detail">
                    <h5>Welcome</h5>
                    <h1>Pay<span>Lis</span></h1>
                    <h3>Sign In To Start Your Payment</h3>
                    <p>PayLis is an application that improve your payment method that is easily to use.<br/> Contact our development for more information : <br/><?php echo $data_login['information']; ?>
                    </p>                            
                    <!-- <ul class="list-unstyled l-social">
                        <li><a href="#"><i class="zmdi zmdi-facebook-box"></i></a></li>                                
                        <li><a href="#"><i class="zmdi zmdi-linkedin-box"></i></a></li>
                        <li><a href="#"><i class="zmdi zmdi-pinterest-box"></i></a></li>
                        <li><a href="#"><i class="zmdi zmdi-twitter"></i></a></li>                       
                        <li><a href="#"><i class="zmdi zmdi-instagram"></i></a></li>
                    </ul>
                    <ul class="list-unstyled l-menu">
                        <li><a href="#">Contact Us</a></li>                                
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul> -->
                </div>
			</div>
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="card">
				    <h4 class="l-login">Bank Login</h4>
                    <form class="" id="#" method="post" action="#" enctype="multipart/form-data">
                        <input type="hidden" name="request" value="login" id="request">
                        <input type="hidden" name="sebagai" value="Bank" id="sebagai">
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
                        <button type="submit" class="btn btn-raised waves-effect bg-red">SIGN IN</button>
                        <!-- <a href="sign-up.html" class="btn btn-raised waves-effect">SIGN UP</a>
                        <div class="text-left">
                            <a href="javascript:void(0);">Forgot Password?</a>
                        </div> -->
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $template->js(); ?>
<script>
    $(function(){
        $("form").submit(function (e) { 
            e.preventDefault();
            
            var username = $("#username").val();
            var password = $("#password").val();
            var sebagai = $("#sebagai").val();
            var request = $("#request").val();

            if(username == "" || password == "" || sebagai == ""){
                swal({
                    title : "Oops!",
                    text : "Harap memasukkan semua data",
                    type : "error"
                })
            }
            else{
                $.ajax({
                    type: "GET",
                    url: "../../system/Ajax.php",
                    data: {
                        username : username,
                        password : password,
                        sebagai : sebagai,
                        request : request
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.checking == "Yes"){
                            swal({
                                title: "Berhasil!",
                                text: "Anda akan diarahkan ke halaman selanjutnya dalam beberapa detik",
                                timer: 2000,
                                type : "success",
                                showConfirmButton: false
                            }, function(){
                                document.location.href = "../../index.php";
                            });
                        }
                        else if(data.checking == "No"){
                            swal({
                                title: "Gagal!",
                                text: "Gagal melakukan login",
                                type : "error"
                            }, function(){
                                location.reload();
                            });
                        }
                    },
                    error : function(jqXHR, textStatus, errorThrown){
                        swal({
                            title : "Gagal!",
                            text : "Periksa koneksi anda",
                            type : "error"
                        });
                    }
                });
            }
        });
    })
</script>
</body>
</html>