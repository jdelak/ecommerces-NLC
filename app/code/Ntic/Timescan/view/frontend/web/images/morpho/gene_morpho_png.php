<?php

   function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1) {
   /* de cette manière, ca ne marche bien que pour les lignes orthogonales
   imagesetthickness($image, $thick);
   return imageline($image, $x1, $y1, $x2, $y2, $color);
   */
    if ($thick == 1) {
      return imageline($image, $x1, $y1, $x2, $y2, $color);
    }
    $t = $thick / 2 - 0.5;
    if ($x1 == $x2 || $y1 == $y2) {
      return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
    }
    $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
    $a = $t / sqrt(1 + pow($k, 2));
    $points = array(
      round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
      round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
      round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
      round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
    );    
    imagefilledpolygon($image, $points, 4, $color);
    return imagepolygon($image, $points, 4, $color);
  }

  $P = intval($_GET["P"])*5;
  $T = intval($_GET["T"])*5;
  $H = intval($_GET["H"])*5;
  
  $p = intval($_GET["P"]);
  $t = intval($_GET["T"]);
  $h = intval($_GET["H"]);
  
  $detailZoom_torse = $_GET["zoomtorse"];
  $detailZoom_ventre = $_GET["zoomventre"];
  $detailZoom_hanches = $_GET["zoomhanches"];
  $detailZoom_cuisses = $_GET["zoomcuisses"];
  $detendance_silhouette = $_GET["detendancesilh"];
  
  $p_g = explode('-',intval($_GET["P"]));
  $t_g = explode('-',intval($_GET["T"]));
  $h_g = explode('-',intval($_GET["H"]));
  
  $genre = $_GET["genre"];
  $morphotype = $_GET["morphotype"];
  
  $date = $_GET["date"];
  
  $id_diag = $_GET["id_diag"];
  
  $image = imagecreatetruecolor(380,410);
			  
	  $blanc = imagecolorallocate($image,255,255,255);
	  $noir = imagecolorallocate($image,0,0,0);
	  $rouge = imagecolorallocate($image,189,0,24);
	  $vert = imagecolorallocate($image,132,186,16);
	  $bleue = imagecolorallocate($image,0,158,206);
		  
	  $ligne = imagecolorallocate($image,49,101,156);
	  $grille = imagecolorallocate($image,200,200,200);
  
	  imagefill($image,0,0,$blanc);
  
  //Echelle de l'image
	  $echelle = 1;
	  if (((101-$P)<0) || ((101-$T)<0) || ((101-$H)<0)) $echelle = 1;
  
	  //Traçage de la grille
	  /*for ($i = 0; $i < 430; $i+=(10/$echelle)) {
		imageline($image, ($i+1), 0, ($i+1), 300, $grille);
	  }*/
   
  //Background image
	  //Homme - Femme - Type
	  if($genre == 'H'){
		//Silhouettes
		if($morphotype == 'T'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/T/silhouette-homme-t-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/T/silhouette-homme-t.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/T/silhouette-homme-t.jpg");
			}
		}
		else if($morphotype == 'I'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/I/silhouette-homme-i-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/I/silhouette-homme-i.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/I/silhouette-homme-i.jpg");
			}
		}
		else if($morphotype == 'M'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/M/silhouette-homme-m-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/M/silhouette-homme-m.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/M/silhouette-homme-m.jpg");
			}
		}
		else if($morphotype == 'E'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/E/silhouette-homme-e-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-homme-e.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-homme-e.jpg");
			}
		}
		else if($morphotype == ''){
			$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-homme-e.jpg");
		}
		//Zoom
		//Torse
		if($detailZoom_torse == '1'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/torse-homme-1.jpg");
		}else if($detailZoom_torse == '2'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/torse-homme-2.jpg");
		}else if($detailZoom_torse == '3'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/torse-homme-3.jpg");
		}
		//Ventre
		if($detailZoom_ventre == '1'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-homme-1.jpg");
		}else if($detailZoom_ventre == '2'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-homme-2.jpg");
		}else if($detailZoom_ventre == '3'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-homme-3.jpg");
		}
		//Hanches
		if($detailZoom_hanches == '1'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-homme-1.jpg");
		}else if($detailZoom_hanches == '2'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-homme-2.jpg");
		}else if($detailZoom_hanches == '3'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-homme-3.jpg");
		}
		//Cuisses
		if($detailZoom_cuisses == '1'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-homme-1.jpg");
		}else if($detailZoom_cuisses == '2'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-homme-2.jpg");
		}else if($detailZoom_cuisses == '3'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-homme-3.jpg");
		}
	 }
	 else if($genre == 'F'){
		if($morphotype == 'T'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/T/silhouette-femme-t-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/T/silhouette-femme-t.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/T/silhouette-femme-t.jpg");
			}
		}
		else if($morphotype == 'I'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/I/silhouette-femme-i-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/I/silhouette-femme-i.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/I/silhouette-femme-i.jpg");
			}
		}
		else if($morphotype == 'M'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/M/silhouette-femme-m-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/M/silhouette-femme-m.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/M/silhouette-femme-m.jpg");
			}
		}
		else if($morphotype == 'E'){
			if(!empty($detendance_silhouette)){
				$img_sil = "silhouettes/E/silhouette-femme-e-$detendance_silhouette.jpg";
				if(file_exists($img_sil)){
					$insert_image = imagecreatefromjpeg($img_sil);
				}else{
					$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-femme-e.jpg");
				}
			}else{
				$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-femme-e.jpg");
			}
		}
		else if($morphotype == ''){
			$insert_image = imagecreatefromjpeg("silhouettes/E/silhouette-femme-e.jpg");
		}
		//Zoom
		//Poitrine
		if($detailZoom_torse == '1'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/poitrine-femme-1.jpg");
		}else if($detailZoom_torse == '2'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/poitrine-femme-2.jpg");
		}else if($detailZoom_torse == '3'){
			$insert_image_detail_torse = imagecreatefromjpeg("silhouettes/DETAILS/poitrine-femme-3.jpg");
		}
		//Ventre
		if($detailZoom_ventre == '1'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-femme-1.jpg");
		}else if($detailZoom_ventre == '2'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-femme-2.jpg");
		}else if($detailZoom_ventre == '3'){
			$insert_image_detail_ventre = imagecreatefromjpeg("silhouettes/DETAILS/ventre-femme-3.jpg");
		}
		//Hanches
		if($detailZoom_hanches == '1'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-femme-1.jpg");
		}else if($detailZoom_hanches == '2'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-femme-2.jpg");
		}else if($detailZoom_hanches == '3'){
			$insert_image_detail_hanches = imagecreatefromjpeg("silhouettes/DETAILS/hanches-femme-3.jpg");
		}
		//Cuisses
		if($detailZoom_cuisses == '1'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-femme-1.jpg");
		}else if($detailZoom_cuisses == '2'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-femme-2.jpg");
		}else if($detailZoom_cuisses == '3'){
			$insert_image_detail_cuisses = imagecreatefromjpeg("silhouettes/DETAILS/cuisses-femme-3.jpg");
		}
	 }
	  
	  $background_image = imagecolortransparent($insert_image,imagecolorat($insert_image,0,0));
	  
	  $insert_imagex = imagesx($insert_image);
	  $insert_imagey = imagesy($insert_image); 
	  
	  imagecopymerge($image,$insert_image,5,5,0,0,$insert_imagex,$insert_imagey,100);
	  
	  //Image detail
	  imagecopymerge($image,$insert_image_detail_torse,205,10,0,0,167,90,100);
	  imagecopymerge($image,$insert_image_detail_ventre,205,107,0,0,167,89,100);
	  imagecopymerge($image,$insert_image_detail_hanches,205,202,0,0,167,95,100);
	  imagecopymerge($image,$insert_image_detail_cuisses,205,303,0,0,167,100,100);
	  
	  //Image fleches
	  if($genre == 'H'){
		  $img_fleche_1 = imagecreatefromgif("elements/fleche-illus-silh-1.gif");
		  imagecolortransparent($img_fleche_1,imagecolorat($img_fleche_1,0,0));
		  imagecopymerge($image,$img_fleche_1,120,50,0,0,125,72,100);
		  
		  $img_fleche_2 = imagecreatefromgif("elements/fleche-illus-silh-2.gif");
		  imagecolortransparent($img_fleche_2,imagecolorat($img_fleche_2,0,0));
		  imagecopymerge($image,$img_fleche_2,120,145,0,0,125,41,100);
		  
		  $img_fleche_3 = imagecreatefromgif("elements/fleche-illus-silh-3.gif");
		  imagecolortransparent($img_fleche_3,imagecolorat($img_fleche_3,0,0));
		  imagecopymerge($image,$img_fleche_3,115,190,0,0,125,71,100);
		  
		  $img_fleche_4 = imagecreatefromgif("elements/fleche-illus-silh-4.gif");
		  imagecolortransparent($img_fleche_4,imagecolorat($img_fleche_4,0,0));
		  imagecopymerge($image,$img_fleche_4,110,225,0,0,125,116,100);
		  
	  }else if($genre == 'F'){
		  $img_fleche_1 = imagecreatefromgif("elements/fleche-illus-silh-1.gif");
		  imagecolortransparent($img_fleche_1,imagecolorat($img_fleche_1,0,0));
		  imagecopymerge($image,$img_fleche_1,120,50,0,0,125,72,100);
		  
		  $img_fleche_2 = imagecreatefromgif("elements/fleche-illus-silh-2.gif");
		  imagecolortransparent($img_fleche_2,imagecolorat($img_fleche_2,0,0));
		  imagecopymerge($image,$img_fleche_2,120,130,0,0,125,41,100);
		  
		  $img_fleche_3 = imagecreatefromgif("elements/fleche-illus-silh-3.gif");
		  imagecolortransparent($img_fleche_3,imagecolorat($img_fleche_3,0,0));
		  imagecopymerge($image,$img_fleche_3,115,180,0,0,125,71,100);
		  
		  $img_fleche_4 = imagecreatefromgif("elements/fleche-illus-silh-4.gif");
		  imagecolortransparent($img_fleche_4,imagecolorat($img_fleche_4,0,0));
		  imagecopymerge($image,$img_fleche_4,115,220,0,0,125,116,100);
	  }

  header("Content-Type: image/png");
  
  imagepng($image);
  // imagepng($image,"/var/virtual_www/time-nutrition/time-nutrition.com/iscomming.time-nutrition.com/htdocs/k2d512z2a345/calculateur/morpho/diagimg/$id_diag.png");
  imagepng($image,"/var/virtual_www/pa-finance/time-nutrition/time-nutrition.com/htdocs/k2d512z2a345/calculateur/morpho/diagimg/$id_diag.png");
 
 ?>