<?php
/*
	IMAGE SNAPSHOT CLASS - www.jc21.com
	VERSION 1.3
	------------------------------------------------------
	
	Inspired by Backpackit.coms image management, Image 
	Snapshot can take a portion of an image (crop) to 
	specific dimensions, from different areas of the original 
	image.

	This class has been created for the purpose of manipulating 
	an uploaded image, and creating a Thumbnail image that 
	does not alter the aspect ratio, and yet represents 
	the contents of the original image. Im sure other 
	uses can be found for the Image Snapshot class, but 
	I'll leave that to you. I use it for Photo Galleries, 
	and Shopping Cart systems.
	
	There are 3 methods for input, and 2 methods of output.
	This allows for maximum flexibility.

	Can accept JPG, GIF and PNG files.
	
	
	WARNING
	------------------------------------------------------
	
	Will save all images as JPG.
	
	USAGE:
	------------------------------------------------------
	
	include('snapshot.class.php'); //this file
	$myimage = new ImageSnapshot; //new instance
	//if using this as an action to a form:
	$myimage->ImageField = $_FILES['userfile']; //uploaded file array
	//OR if you have the contents of the image as a variable:
	$myimage->ImageContents = $the_image_contents; //image data
	//OR if you want to use an image that is already saved:
	$myimage->ImageFile = $_SERVER['DOCUMENT_ROOT'] . '/images/myimage.jpg'; //image file, as an example. Path can be relative or absolute.
	
	$myimage->Width = 100; //width of output image
	$myimage->Height = 100; //height of output image
	$myimage->Resize = true; // resize image before crop
	$myimage->ResizeScale = 50;	// between 1 and 100, 0 for no resizing before crop, 100 to shrink image completely before crop
	$myimage->Position = 'center'; //can be 'center' (default), 'random', 'topleft', 'topcenter', 'topright', 'bottomleft', 'bottomcenter', 'bottomright','custom'
	$myimage->CustomPositionX = 25; //0 to 100. Only needed it Position is 'custom'. This specifies the position of the Snapshot along the X axis, in percentage. 25, is 25% from the left.
	$myimage->CustomPositiony = 35; //0 to 100. Only needed it Position is 'custom'. This specifies the position of the Snapshot along the Y axis, in percentage. 35, is 35% from the top.
	$myimage->Compression = 80; //jpg compression level
	
	//if you want to save as a file:
	if ($myimage->SaveImageAs('temp.jpg')) {
		echo '<img src="temp.jpg" border="0" width="' . $myimage->ReturnedWidth . '" height="' . $myimage->ReturnedHeight . '" alt="test" />';
	} else {
		echo $myimage->Err;
	}
	
	//OR if you want to get the contents into a variable:
	if ($myimage->ProcessImage() !== false) {
		$img = $myimage->GetImageContents();
	} else {
		echo $myimage->Err;
	}

	
	
	
	CHANGE LOG:
	------------------------------------------------------
	05-12-2005		1.3 Release
	05-12-2005		Added: ImageFile field, can now load image from saved file.
	05-12-2005		Added: custom position type, specify by percentages. Read updated manual for help with this.
	28-11-2005		1.2 Release
	28-11-2005		Added: ReturnedWidth and ReturnedHeight variables, for more feedback.
	28-11-2005		Fixed: if crop area was bigger than original image, mixed results occured.
	28-11-2005		1.1 Release
	28-11-2005		Added: support for Image input with a variable
	27-11-2005		1.0 Release

*/




class ImageSnapshot {

	var $ImageField;		// should be: $_FILES['imagefield'] array, OR set the next var.
	var $ImageFile;			// location of an image file saved on the server.
	var $ImageContents;		// Contents of an image in a variable. 
	var $Compression;		// JPG compression. Default is 75%
	
	var $Resize;			//either true or false. wether to resize image, if true the uses the next 3 vars
	var $ResizeScale;		//scale to resize the image to. 100 is as small as possible, 0 is effectively no resizing at all.
	
	var $Position;			//position of the snapshot: random, center (default), topleft, topright, bottomleft, bottomright
	
	var $Width;      		// width (or max width) of image to output
	var $Height;      		// height (or max height) of image to output
	
	var $Err;				// error if (and when) they occur
	
	var $InternalImage;		// Internal variable for working with the image
	
	var $ReturnedWidth; 	//New in 1.2: this is the actual width of the image returned, will only differ to Width if the original image was smaller.
	var $ReturnedHeight; 	//New in 1.2: this is the actual height of the image returned, will only differ to Height if the original image was smaller.	
	
	var $CustomPositionX; 	//New in 1.3: 0 to 100, this is the percentage of the custom position along the X scale. $Position must be 'custom' for this to apply.
	var $CustomPositionY; 	//New in 1.3: 0 to 100, this is the percentage of the custom position along the Y scale. $Position must be 'custom' for this to apply.

	var $Dest;
	var $DestExt;

	function ImageUploaded() {
		//set up some defaults
		$this->Width = '800';
		$this->Height = '600';
		$this->Resize = true;
		$this->ResizeScale = 100;
		$this->Position = 'center';
		$this->Err = '';
		$this->Compression = 75;
		$this->InternalImage = '';
		$this->ImageContents = '';
		$this->ReturnedWidth = 0; // New in 1.2
		$this->ReturnedHeight = 0; // New in 1.2
		$this->CustomPositionX = 50; // New in 1.3
		$this->CustomPositionY = 50; // New in 1.3
	}

	
	
	function SaveImageAs($destination) {

		$this->Dest = $destination;

		//Saves the image to the desination. Returns true if successful, or false with Err specifying the error.
		//example: $myimage->SaveImageAs("/docroot/images/newimage.jpg
		if ($this->ProcessImage()) {

			if (!$handle = fopen($destination, 'w')) {
				$this->Err = 'Cannot open file (' . $destination . ')';
				return false;
			} else {
				if (fwrite($handle, $this->InternalImage) === FALSE) {
					$this->Err = 'Cannot write to file (' . $destination . ')';
					return false;
				} else {
					return true;
				}
				fclose($handle);
			}								
		} else {
			return false;		
		}
	}
	
	function GetImageContents() {
		// calls process image, and returns the contents for other manipulations of database entry.
		//if ($this->ProcessImage()) {
			return $this->InternalImage;
		//} else {
			//return false;
		//}
	}
	
	function ProcessImage() {
		//Processes the image. Resize if needed. fills the internal image with the contents of the changed image.
		//Internal function

		# Destination Extension
		$arrDest = pathinfo($this->Dest);
		if (is_array($arrDest) && isset($arrDest["extension"])) {
			$this->DestExt = strtolower($arrDest["extension"]);
		}
		unset($arrDest);

		if (strlen($this->ImageContents) > 0) {	
			//LOAD FROM STRING!
			$tmp_image = @imagecreatefromstring($this->ImageContents);
			//END LOAD FROM STRING
			
		} elseif (strlen($this->ImageFile) > 0) {
			//LOAD FROM FILE
			//check that file exists
			if (file_exists($this->ImageFile)) {
				//load it!
				$data = getimagesize($this->ImageFile); //[0] = w, [1] = h, [2] = type, [3] = attr
				switch ($data[2]) {
					case 1:
						$tmp_image = @imagecreatefromgif($this->ImageFile);
						break;
					case 2:
						$tmp_image = @imagecreatefromjpeg($this->ImageFile);
						break;
					case 3:
						$tmp_image = @imagecreatefrompng($this->ImageFile);
						imagealphablending($tmp_image, true);
						break;
					default:
						$this->Err = 'File is not a valid image type';
						return false;
						exit;
						break;
				}					
			} else {
				$this->Err = 'File "' . $this->ImageFile . '" does not exist!';
				return false;
			}
			//END LOAD FROM FILE
			
		} elseif (count($this->ImageField) > 0) {
			//LOAD FROM FIELD FILE
			//check that file exists
			if (file_exists($this->ImageField['tmp_name'])) {
				//load it!
				$data = getimagesize($this->ImageField['tmp_name']); //[0] = w, [1] = h, [2] = type, [3] = attr
				switch ($data[2]) {
					case 1:
						$tmp_image = @imagecreatefromgif($this->ImageField['tmp_name']);
						break;
					case 2:
						$tmp_image = @imagecreatefromjpeg($this->ImageField['tmp_name']);
						break;
					case 3:
						$tmp_image = @imagecreatefrompng($this->ImageField['tmp_name']);
						imagealphablending($tmp_image, true);
						break;
					default:
						$this->Err = 'File is not a valid image type';
						return false;
						exit;
						break;
				}					
			} else {
				$this->Err = 'File "' . $this->ImageField['tmp_name'] . '" does not exist!';
				return false;
			}
			//END LOAD FROM FILE
			
		} else {
			$this->Err = 'No image was loaded, use ImageFile, ImageContents or ImageField before output';
			return false;
			exit;
		}
	
		$image_width = imagesx($tmp_image);
		$image_height = imagesy($tmp_image);

		//check a div by zero error.
		if ($this->Resize == true && $this->ResizeScale == 0) {
			//there is no point resizing, when the scale doesn't change the size anyway.
			$this->Resize = false;
		}
		
		
		//check if image is meant to be resized...
		if ($this->Resize == 'true' || $this->Resize == 1) {
			//Yes resize
			if ($image_width > $this->Width && $image_height > $this->Height) {
				//have to resize..
				//get the proportional dimensions, while allowing room for a snapshot, and according to the resizescale
				$w_ratio = $this->Width / ($image_width * ($this->ResizeScale / 100));		//width ratio.. ie: 0.1 = 80 / 800
				$h_ratio = $this->Height / ($image_height * ($this->ResizeScale / 100));	//height ratio. ie: 0.075 = 60 / 800
				$maxwidth = $this->Width;	//maxwidth is the max width of the final snapshot
				$maxheight = $this->Height;	//maxheight is the max height of the final snapshot
				if ($w_ratio < $h_ratio) {
					$maxheight = ceil($image_height * $h_ratio);
					$maxwidth = ceil($image_width * $h_ratio);
				} else {
					$maxwidth = ceil($image_width * $w_ratio);
					$maxheight = ceil($image_height * $w_ratio);
				}
				
				//now we either have a correctly sized image (compared to the thumb) or an oversized image, that has been shrink and waiting to be snaopshot'd
				
				$final_width = $maxwidth;
				$final_height = $maxheight;
				//end maintain aspect	

				//$new_photo = imagecreatetruecolor($final_width,$final_height);
				imagecopyresampled($tmp_image, $tmp_image,0,0,0,0, $final_width, $final_height, $image_width, $image_height);
				
				$image_width = $final_width;
				$image_height = $final_height;
				
				//$tmp_image = $new_photo;
				//imagedestroy($new_photo);
				

			}
			
			// end resize
		} 
		
		//SNAPSHOT! CHA_CHING!
		//at this stage, $tmp_image should contain the image ready for snapshot. No drama.
		
		if ($image_width < $this->Width) {
			$this->Width = $image_width;
		}
		if ($image_height < $this->Height) {
			$this->Height = $image_height;
		}
		$new_photo = imagecreatetruecolor($this->Width,$this->Height);

		$this->ReturnedWidth = $this->Width;
		$this->ReturnedHeight = $this->Height;
		
		//currently, the dimensions of our temp image are:<br />
		//	$image_width
		//	$image_height
		$source_y = 0;
		$source_x = 0;
		//we shall compare the snapshot dimensions to the image dimensions
		switch ($this->Position) {
			case 'random':
				//my favourite, random place on the image.
				if ($image_height > $this->Height) {
					// height is larger.
					//get the min and max room to play with
					$min = 0;
					$max = $image_height - $this->Height;							
					$source_y = rand($min,$max);
				}
				if ($image_width > $this->Width) {
					// width is larger.
					$min = 0;
					$max = $image_width - $this->Width;							
					$source_x = rand($min,$max);
				}
				break;
			
			case 'topright':
				//topright of the image
				$source_y = 0;
				if ($image_width > $this->Width) {
					// width is larger.				
					$source_x = $image_width - $this->Width;
				}
				break;
			
			case 'topleft':
				//topleft of the image
				$source_y = 0;
				$source_x = 0;
				break;
				
			case 'topcenter':
				//topleft of the image
				$source_y = 0;
				if ($image_width > $this->Width) {
					// width is larger.
					$source_x = (($image_width - $this->Width) / 2);
				}
				break;
				
			case 'bottomright':
				//bottomright of the image
				if ($image_height > $this->Height) {
					// height is larger.
					$source_y = $image_height - $this->Height;
				}
				if ($image_width > $this->Width) {
					// width is larger.				
					$source_x = $image_width - $this->Width;
				}
				break;
				
			case 'bottomleft':
				//bottom left of the image
				if ($image_height > $this->Height) {
					// height is larger.
					$source_y = $image_height - $this->Height;
				}
				$source_x = 0;
				break;
				
			case 'bottomcenter':
				//bottom left of the image
				if ($image_height > $this->Height) {
					// height is larger.
					$source_y = $image_height - $this->Height;
				}
				if ($image_width > $this->Width) {
					// width is larger.
					$source_x = (($image_width - $this->Width) / 2);
				}
				break;
				
			case 'custom':
				//new in 1.3
				//use custom positions as percentages to get the position.
					if (round($this->CustomPositionX) < 0 || round($this->CustomPositionX) > 100) {
						//x not a valid number. Reset:
						$this->CustomPositionX = 50;
					}
					if (round($this->CustomPositionY) < 0 || round($this->CustomPositionY) > 100) {
						//y not a valid number. Reset:
						$this->CustomPositionY = 50;
					}
					
					//get the digits!
					if (round($this->CustomPositionY) == 0 || $image_height <= $this->Height) {
						//does not meet credentials
						$source_y = 0;
					} else {
						//ok
						$source_y = (($image_height - $this->Height) * (round($this->CustomPositionY) / 100)) ;
					}
					if (round($this->CustomPositionX) == 0 || $image_width <= $this->Width) {
						//does not meet credentials
						$source_x = 0;
					} else {
						//ok
						$source_x = (($image_width - $this->Width) * (round($this->CustomPositionX) / 100)) ;
					}
				break;
			
			default:
				//center of the image
				if ($image_height > $this->Height) {
					// height is larger.
					$source_y = (($image_height - $this->Height) / 2);
				}
				if ($image_width > $this->Width) {
					// width is larger.
					$source_x = (($image_width - $this->Width) / 2);
				}
				break;
		}

		# Transparency
		switch ($this->DestExt) {
			case "png":
				imagealphablending($new_photo, false);
				imagesavealpha($new_photo, true);
			break;
			case "gif":
				# get and reallocate transparency-color
				$transindex = imagecolortransparent($tmp_image);
				if ($transindex >= 0) {
					$transcol = imagecolorsforindex($tmp_image, $transindex);
					$transindex = imagecolorallocatealpha(
						$new_photo,
						$transcol['red'],
						$transcol['green'],
						$transcol['blue'],
						127
					);
					imagefill($new_photo, 0, 0, $transindex);
				}
			break;
		}

		//bool imagecopyresampled ( destination_image, source_image, destination X, destination Y, source X, source Y, destination W, destination H, source W, source H)
		imagecopyresampled($new_photo,$tmp_image,0,0,$source_x,$source_y,$this->Width,$this->Height,$this->Width,$this->Height);
		//imagecopymerge($new_photo,$tmp_image,0,0,$source_x,$source_y,$this->Width,$this->Height,100);

		# restore transparency
		if ($this->DestExt == "gif" && $transindex >= 0) {
			imagecolortransparent($new_photo, $transindex);
			for ($y = 0; $y < $this->Height; ++$y) {
				for ($x = 0; $x < $this->Width; ++$x) {
					if (((imagecolorat($new_photo, $x, $y) >> 24) & 0x7F) >= 100) {
						imagesetpixel(
							$new_photo,
							$x,
							$y,
							$transindex
						);
					}
				}
			}
		}

		ob_start();
			switch ($this->DestExt) {
				case "png":
					imagepng($new_photo, null, round(($this->Compression / 100) * 9));
				break;
				case "gif":
					imagegif($new_photo, null, $this->Compression);
				break;
				default:
					imagejpeg($new_photo, null, $this->Compression);
			}
			$this->InternalImage = ob_get_contents();
		ob_end_clean();
		return true;
		//END SNAPSHOT
		
	}	
}
?>