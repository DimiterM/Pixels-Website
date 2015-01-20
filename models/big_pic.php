<?php

require_once "ads.php";

class BigPic
{
	public $big_pic_template;
	public $big_pic_filename;
	public $big_pic_shadows_filename;

	public function __construct()
	{
		$this->big_pic_template = $_SERVER['DOCUMENT_ROOT'] . "images/big_pic_template.png";
		$this->big_pic_filename = $_SERVER['DOCUMENT_ROOT'] . "images/big_pic.png";
		$this->big_pic_shadows_filename = $_SERVER['DOCUMENT_ROOT'] . "images/big_pic_shadows.png";
	}

	public function build_big_pic($ads)
	{
		// build the big picture from all polygons in ads table
		$big_pic = imagecreatefrompng($this->big_pic_template);
		$this->edit_image("copy_image", $ads, $big_pic);
		imagepng($big_pic, $this->big_pic_filename);
	}

	public function build_shadow_pic($ads)
	{
		// get big picture and add all reserved (newads table) and new (ads table) polygons as shadows
		$big_pic_shadows = imagecreatefrompng($this->big_pic_filename);
		$this->edit_image("copy_shadow", $ads, $big_pic);
		imagepng($big_pic_shadows, $this->big_pic_shadows_filename);
	}


	private function edit_image($function, $ads, &$big_img)
	{
		foreach ($ads as $ad)
		{
			$this->$function($ad, $big_img);
		}
	}

	private function copy_image($ad, $big_img)
	{
		$ad_pic = imagecreatefrompng($ad['filename']);
		imagecopy($big_img, $ad_pic, 
			$ad['x0'], $ad['y0'], 0, 0, $ad['width'], $ad['height']);
	}

	private function copy_shadow($ad, $big_img)
	{
		$poly = text_polygon_to_array($ad['coords']);
		imagefilledpolygon($big_img, 
			$poly, 4, 
			imagecolorallocate($big_img, 0, 0, 0));
	}
}


?>