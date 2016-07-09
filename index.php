<?php require "config.php"; ?>
<?php require "controller.php"; ?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Sou mais in9web</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<style>

		body{
			background-size: cover; 			
			background: -moz-linear-gradient(124deg, rgb(82, 172, 255) 0%, rgb(204, 255, 157) 100%); /* ff3.6+ */
			background: -webkit-gradient(linear, left top, right bottom, color-stop(0%, rgb(204, 255, 157)), color-stop(100%, rgb(82, 172, 255))); /* safari4+,chrome */
			background: -webkit-linear-gradient(124deg, rgb(82, 172, 255) 0%, rgb(204, 255, 157) 100%); /* safari5.1+,chrome10+ */
			background: -o-linear-gradient(124deg, rgb(82, 172, 255) 0%, rgb(204, 255, 157) 100%); /* opera 11.10+ */
			background: -ms-linear-gradient(124deg, rgb(82, 172, 255) 0%, rgb(204, 255, 157) 100%); /* ie10+ */
			background: linear-gradient(326deg, rgb(82, 172, 255) 0%, rgb(204, 255, 157) 100%); /* w3c */
			overflow: hidden;
		}

		.box{
			background-color:#fff;
			min-height: 800px;
			width: 500px;
			margin: 0 auto;
			padding-top: 50px;
			box-shadow: 0px 0px 25px rgba(0,0,0,.3);
		}


	</style>
	<script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->

</head>
<body>
	
	<div class="container">

		<div class="row">
			<div class="col-lg-12 text-center">
				<div class="block-center box">
					
					<img src="http://in9web.com/blog/wp-content/uploads/2015/09/logo-in9web.png" alt="Logo da [in9]web" style="margin-top: 30px; max-width: 150px" />

					<h1 id="fb-welcome"></h1>
					<p> Diga a todos que você é mais [in9]Web </p>
					<img src="" alt="" id="fb-perfil" class="hide">

					<div class="alert hide" id="alert"></div>
					
					<form action="update.php" style="margin-top: 20px" id="form-update" method="post">
						<div class="form-group">
							<label for="photo-description">
								Fale por que você é mais [in9]web
							</label>
							<input type="hidden" name="photo" id="photo" value="" />
							<input type="hidden" name="accessToken" id="accessToken" value="" />
							<input type="text" required="required" name="photo-description" value="Por que eu amo a [in9]web" class="form-control" style="max-width: 300px; margin: 0 auto 0;"/>
						</div>
						<button class="btn btn-primary" style="padding: 5px 50px; font-size: 18px" id="button-send"> postar agora </button> 						
					</form>

				</div>				
			</div>
		</div>		
	</div>	

	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '<?php echo FACEBOOK_APP_ID; ?>',
	      xfbml      : true,
	      version    : 'v2.6'
	    });

		function onLogin(response) {
		  if (response.status == 'connected') {


		  	FB.api('/me/permissions', function(data){
		  		var has_permission = false;

		  		console.log(data);

		  		for(var i =0; i < data.data.length; i++){
		  			if(data.data[i].permission == "publish_actions" && data.data[i].status == "granted"){
		  				has_permission = true;
		  				break;
		  			}
		  		}

		  		if(!has_permission){
				    FB.login(function(response) {
				      onLogin(response);
				    }, {scope: 'publish_actions'});		  			

				    console.log("Não há permissão para postagem");
		  		}
		  	});


		    FB.api('/me?fields=name,first_name,picture.type(large){url,width,height,is_silhouette}', function(data) {

		    	welcome = document.getElementById("fb-welcome");
		    	welcome.innerHTML =  "Ola " + data.first_name;

		    	if(data.picture){
		    		url = data.picture.data.url;
		    		img_perfil = document.getElementById("fb-perfil");
		    		img_perfil.src=url;
		    		img_perfil.className = "thumbnail center-block";
		    		img_perfil.style.minWidth="150px";
		    	}

		    	// modifier picture
		    	$.post("/index.php",{'photo': url, 'username': data.name},function(data){
		    		img_perfil = document.getElementById("fb-perfil");
		    		img_perfil.src=data.new_photo;
		    		$("#photo").val(data.new_photo);
	
					var response = FB.getAuthResponse();
					document.getElementById("accessToken").value = response.accessToken;
		    	});

		    });
		  }
		}

		$("#form-update").submit(function(e){
			e.preventDefault();

			$("#button-send")[0].disabled=true;
			$("#button-send").text("Aguarde, enviando!");

			$.post(this.action, $(this).serialize(), function(data){
				data = JSON.parse(data);

				if(data.status == "ok"){
					$("#alert").html("Obrigado por sua postagem!");
					$("#alert").removeClass("hide").addClass("alert-success").fadeIn();

					$("#button-send").text("Indo para o facebook");

					setTimeout(function(){
						var url = 'https://www.facebook.com/photo.php?fbid=' +data.response.photo_id + '&set=ms.c.eJw9kMkRBDEIAzPa4gbln9iCbebbJXTAqiANOMwzU358QdQBwAKOYDHnesBRkmAhXVB5FJYPaEZ7FIEXBA1g%7E_hSKOUn4BZIubSpkWCAT60a1gOMoak%7E_ieEAXWRAntogXeB2F6QI5HpyfRwfOSazCcT1yPdxzTsTsAYMO0K%7E_Y4iiMVqG3en0KwaSA86VQ2qw18reWoqaYMZ9iBfTyBv5%7E_2oBytnQZvaBnVqd0mXggKw6w%7E_gOQGmBy.bps.a.1338987862796015.1073741827.100000544435251&type=3&makeprofile=1&profile_id=100000544435251&pp_source=photo_view';
						location.href=url;
					}, 3000);

				}
				else if(data.status == "fail"){
					$("#alert").removeClass("hide").addClass("alert-error").fadeIn();
					$("#alert").html("Desculpe, houve um erro! Tente novamente.");
				}
				
			});

		});

		//publish_actions

		FB.getLoginStatus(function(response) {
		  // Check login status on load, and if the user is
		  // already logged in, go directly to the welcome message.
		  if (response.status == 'connected') {
		    onLogin(response);
		  } else {
		    // Otherwise, show Login dialog first.
		    FB.login(function(response) {
		      onLogin(response);
		    }, {scope: 'publish_actions'});
		  }
		}, true);

	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>


	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>	

</body>
</html>
