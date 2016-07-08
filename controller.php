<?php 
	session_start();

	use Stringy\Stringy as S;
	use Imagine\Gd as Imagine;

	class Controller{

		public function createImage($foto, $username){

			$photo_name = "users/" . S::create($username) -> slugify() . ".jpg";


			$ch = curl_init();
			$source = $foto;
			curl_setopt($ch, CURLOPT_URL, $source);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec ($ch);
			curl_close ($ch);
			$destination = $photo_name;
			$file = fopen($destination, "w+");
			fputs($file, $data);
			fclose($file);

			$imagine = new Imagine\Imagine();
			$image1 = $imagine -> open($photo_name);
			$image2 = $imagine -> open("./image/mask200x200.png");

			$image1->paste($image2, new \Imagine\Image\Point(0, 0));
			$image1 -> save($photo_name);

			$_SESSION['photo'] = $photo_name;

			if(!$data){
				return json_encode([
					"error" => "Não foi possível fazer o download da foto"
				]);
			}

			return json_encode([
				'new_photo' => $photo_name
			]);			
		}

	}

	$controller = new Controller();

	if($_POST['photo']){
		header("Content-Type: application/json");

		echo $controller -> createImage($_POST['photo'], $_POST['username']);
		exit;
	}
