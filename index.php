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

</head>
<body>
	
	<div class="container">

		<div class="row">
			<div class="col-lg-12 text-center">
				<div class="block-center">
					
					<img src="http://in9web.com/blog/wp-content/uploads/2015/09/logo-in9web.png" alt="Logo da [in9]web" style="margin-top: 30px; max-width: 100px" />

					<h1 id="fb-welcome"></h1>
					<p> Diga a todos que você é mais [in9]Web </p>
					<img src="" alt="" id="fb-perfil" class="hide">

					<p>
						Baixe sua foto e adicione em seu perfil
					</p>		
					
					<a href="/index.php?donwload=1" class="btn btn-default" id="download-photo"> Baixar sua foto </a> 

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

		// Place following code after FB.init call.
		function onLogin(response) {
		  if (response.status == 'connected') {
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
		    	});


		    });
		  }
		}

		FB.getLoginStatus(function(response) {
		  // Check login status on load, and if the user is
		  // already logged in, go directly to the welcome message.
		  if (response.status == 'connected') {
		    onLogin(response);
		  } else {
		    // Otherwise, show Login dialog first.
		    FB.login(function(response) {
		      onLogin(response);
		    });
		  }
		});


	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>


	<script   src="https://code.jquery.com/jquery-1.12.4.min.js"   integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="   crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>	

</body>
</html>
