<?php
/**
 * Função para gerar senhas aleatórias
 *
 * @author    Thiago Belem <contato@thiagobelem.net>
 *
 * @param integer $tamanho Tamanho da senha a ser gerada
 * @param boolean $maiusculas Se terá letras maiúsculas
 * @param boolean $numeros Se terá números
 * @param boolean $simbolos Se terá símbolos
 *
 * @return string A senha gerada
 */
function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand - 1];
	}
	return $retorno;
}

function devolvePaginacao($pagina, $numero, $quantidade)
{
	$parse_url = parse_url($_SERVER['REQUEST_URI']);
	parse_str($parse_url['query'], $params);
	unset($params['p']);

	$url = $parse_url['path']."?".http_build_query($params);

	if ($pagina == 0) {
		$pagina = 1;
	}

	$max_page = ceil($numero / $quantidade);
	if($max_page > 1){

		$primeira_pag = $pagina - 2;

		if ($primeira_pag < 1) {
			$primeira_pag = 1;
		}

		$ultima_pag = $primeira_pag + 5;

		if ($ultima_pag >= $max_page) {
			$ultima_pag = $max_page;
		}

		$html = '<div class="paginacao">';

		if ($pagina > 1) {
			$html .= '<a href="'.$url."&p=".($pagina-1).'" class="anterior"></a>';
		}
		if ($primeira_pag != 1) {
			$html .= '<a href="' . $url . "&p=" . (1) . '" class="primeira">1</a>';
		}
		if ($primeira_pag > 1) {
			$html .= '<a href="#">...</a>';
		}
		for ($i = $primeira_pag; $i <= $ultima_pag; $i++) {
			$class = "";
			if ($pagina == $i) {
				$class .= " active ";
			}
			if ($i == 1) {
				$class .= " primeira ";
			}
			if ($i == $max_page) {
				$class .= " ultimo ";
			}

			$html .= '<a href="' . $url . "&p=" . ($i) . '" class="' . $class . '">' . $i . '</a>';
		}
		if ($ultima_pag < $max_page) {
			$html .= '<a href="' . $url . "&p=" . ($i) . '">...</a>';
		}
		if ($ultima_pag != $max_page) {
			$html .= '<a href="' . $url . "&p=" . ($max_page) . '" class="ultimo">' . $max_page . '</a>';
		}
		if ($pagina < $max_page) {
			$html .= '<a href="' . $url . "&p=" . ($pagina+1) . '" class="proxima"></a>';
		}
		$html .= '</div>';
		return $html;
	}
}

function devolveLimit($paginacao)
{
	if (intval($paginacao['pagina']) == 0) {
		$pagina = 1;
	} else {
		$pagina = $paginacao['pagina'];
	}

	$numero = $paginacao['numero'];

	$de = ($pagina * $numero) - $numero;
	$ate = ($pagina * $numero);

	return "LIMIT " . $de . ", " . $numero;
}

// NOVO UPLOAD
// $file - nome da variavel do POST
// $path - apenas o nome da subpasta do directorio /fotos/
// $modo - file ou foto
function doUpload($file, $path, $modo = "file")
{
	$erro = $nome = "";
	$size["foto"] = "10000000";
	$size["file"] = "10000000";
	$tipo["foto"] = array('jpg', 'jpeg', "gif", "png", "jfif");
	$tipo["file"] = "pdf, doc";
	// Verifica tamanho do ficheiro
	if ($file["size"] > $size[$modo]) {
		$erro = "Por favor escolha um ficheiro com tamanho menor.";
	}
	// Verifica tipo de ficheiro
	$extensao = pathinfo($file["name"], PATHINFO_EXTENSION);
	$extensao = strtolower($extensao);
	$titulo = substr($file["name"], 0, -(strlen($extensao) + 1));

	if (!in_array($extensao, $tipo[$modo])) {
		$erro = "A extensão do ficheiro não é válida";
	}
	if ($erro == "") {
		$dir_name = $_SERVER['DOCUMENT_ROOT'] . "/fotos" . $path;

		if (!is_dir($dir_name)) {

			$oldumask = umask(0);
			mkdir($dir_name, 0777, true);
			umask($oldumask);
			chmod($dir_name, 0777);
		}
		if (!is_dir($dir_name)) {
			$erro = "Diretório incorrecto" . $dir_name;
		} else {
			$titulo = forceFilename($titulo);
			$nome = $titulo . "_" . uniqid(rand()) . "." . $extensao;
			$res = move_uploaded_file($file['tmp_name'], $dir_name . $nome);
			if ($res === FALSE) {
				echo $file['tmp_name'] . "<br>" . $dir_name . $nome;
				$erro = "Existe um problema ao fazer upload da imagem.";
			} else {
				@chmod($dir_name . $nome, 0777);
			}
		}
	}
	$_SESSION['erro'] = $erro;
	return $nome;
}

function doResize($path_from, $img_from, $path_to, $img_to, $tipo = 1, $crop = 0, $img_to_wid = 0, $img_to_hei = 0)
{
	global $conf_compressao, $cfg_foto_tipos, $dir_site;

	$conf_compressao = 100;
	$dir_site = $_SERVER['DOCUMENT_ROOT'];
	$cfg_foto_tipos = array("jpg", "png", "jpeg", "gif");

	$arrResize = array();
	$arrResize["success"] = false;

	# defaults
	$tipo = (is_null($tipo) || $tipo < 1) ? 1 : (int)$tipo;

	# file paths
	if ($path_from) {
		$path_from = $dir_site . $path_from;
	}
	if ($path_to) {
		$path_to = $dir_site . $path_to;
	}
	$img_path_from = $path_from . $img_from;
	$img_path_to = $path_to . $img_to;

	# check if file source exists
	if (!file_exists($img_path_from) || !is_readable($img_path_from)) {
		$arrResize["errors"]["admin"][] = "The file <b>" . $img_path_from . "</b> was not found or there are no read permissions.";
	} else {
		# gets image info
		$size_from = getimagesize($img_path_from);
		$img_from_wid = $size_from[0];
		$img_from_hei = $size_from[1];
		$img_from_type = strtolower(str_replace("image/", "", $size_from['mime']));

		# check file extension
		if (!in_array($img_from_type, $cfg_foto_tipos)) {
			$arrResize["errors"]["user"][] = "Formato invalido";
		}

		# create destination folder
		if (!is_dir($path_to)) {
			$oldumask = umask(0);
			mkdir($path_to, 0777, true);
			umask($oldumask);
			chmod($path_to, 0777);
			if (!is_dir($path_to)) {
				$arrResize["errors"]["admin"][] = "Sem permissões para criar diretorio";
			}
		}

		# check if folder is writable
		if (is_dir($path_to) && !is_writable($path_to)) {
			$arrResize["errors"]["admin"][] = "Sem permissões para escrever no diretorio";
		}

		# check 'snapshot' library
		if ($crop) {
			if (file_exists($dir_site . "/lib/snapshot.obj.php")) {
				require_once($dir_site . "/lib/snapshot.obj.php");
			} else {
				$arrResize["errors"]["admin"][] = "Can't load class /lib/snapshot.obj.php";
			}
			# dimensions must be greater than 0
			if ($img_to_wid == 0 || $img_to_hei == 0) {
				$arrResize["errors"]["admin"][] = "Invalid crop dimensions: " . (float)$img_to_wid . "px × " . (float)$img_to_hei . "px";
			}
		}

		# check if GD is loaded
		if (!extension_loaded('gd') || !function_exists('gd_info')) {
			$arrResize["errors"]["admin"][] = "Missing PHP GD library on web server";
		}

		# check image dimensions
		switch ((int)$tipo) {
			case 1: # resize only the width
				if ($img_from_wid < $img_to_wid) {
					$arrResize["errors"]["user"][] = "A imagem tem de ter uma largura minima de " . $img_to_wid;
				} else {
					$img_to_tmp_hei = round(($img_to_wid * $img_from_hei) / $img_from_wid);
					$img_to_tmp_wid = $img_to_wid;
				}
				break;
			case 2: # resize only the height
				if ($img_from_hei < $img_to_hei) {
					$arrResize["errors"]["user"][] = "A imagem tem de ter uma altura minima de " . $img_to_hei;
				} else {
					$img_to_tmp_wid = round(($img_to_hei * $img_from_wid) / $img_from_hei);
					$img_to_tmp_hei = $img_to_hei;
				}
				break;
			case 3: # resize by width and height
				if ($img_from_wid < $img_to_wid || $img_from_hei < $img_to_hei) {
					$arrResize["errors"]["user"][] = "A imagem tem de ter uma largura minima de " . $img_to_wid . " e uma altura minima de " . $img_to_hei;
				} else {
					$img_from_tmp_div = round($img_from_wid / $img_from_hei, 4);
					$img_to_tmp_div = round($img_to_wid / $img_to_hei, 4);
					if ($img_from_tmp_div >= $img_to_tmp_div) {
						# portrait
						$img_to_tmp_wid = $img_to_wid;
						$x = ceil(100 * $img_to_tmp_wid / $img_from_wid);
						$img_to_tmp_hei = ceil($x * $img_from_hei / 100);
					} else {
						# landscape
						$img_to_tmp_hei = $img_to_hei;
						$y = ceil(100 * $img_to_tmp_hei / $img_from_hei);
						$img_to_tmp_wid = ceil($y * $img_from_wid / 100);
					}
				}

				break;
			case 4: # resize width and height, but only the smallest of them, so crop
				if ($img_from_wid < $img_to_wid || $img_from_hei < $img_to_hei) {
					$arrResize["errors"]["user"][] = "A imagem tem de ter uma largura minima de " . $img_to_wid . " e uma altura minima de " . $img_to_hei;
				} else {
					$img_from_tmp_div = round($img_from_wid / $img_from_hei, 4);
					$img_to_tmp_div = round($img_to_wid / $img_to_hei, 4);
					if ($img_from_tmp_div >= $img_to_tmp_div) {
						# portrait
						$img_to_tmp_hei = $img_to_hei;
						$x = 100 * $img_to_tmp_hei / $img_from_hei;
						$img_to_tmp_wid = $x * $img_from_wid / 100;
					} else {
						# landscape
						$img_to_tmp_wid = $img_to_wid;
						$x = 100 * $img_to_tmp_wid / $img_from_wid;
						$img_to_tmp_hei = $x * $img_from_hei / 100;
					}
				}
				break;
		}

		if (empty($arrResize["errors"])) {

			if (!$img_to_tmp_wid) {
				$img_to_tmp_wid = $img_from_wid;
			}
			if (!$img_to_tmp_hei) {
				$img_to_tmp_hei = $img_from_hei;
			}

			# calc required memory to edit original image
			$originalBytes = calcRequiredMemoryToEdit($img_path_from, null, null, 1.7);

			# calc required memory to create new image
			$resizeBytes = calcRequiredMemoryToEdit($img_path_from, $img_to_tmp_wid, $img_to_tmp_hei, 1.7);

			# total bytes required
			$totalBytes = $originalBytes + $resizeBytes;

			# memory usage
			$arrMemory = getMemoryUsage();

			# remaining memory
			#echo formatBytes(abs($arrMemory["bytes"]["remaining"] - $totalBytes));die;
			if (($arrMemory["bytes"]["remaining"] - $totalBytes) < 0) {
				$arrResize["errors"]["admin"][] = "There is no memory available to process this image. " . formatBytes(abs($arrMemory["bytes"]["remaining"] - $totalBytes)) . " left";
				$arrResize["errors"]["user"][] = "Não foi possivel processar a imagem";
			}
		}

		if (empty($arrResize["errors"])) {
			# temp file
			switch ($img_from_type) {
				case "png":
					$img_to_tmp_resize = imagecreatetruecolor($img_to_tmp_wid, $img_to_tmp_hei);
					imagealphablending($img_to_tmp_resize, false);
					imagesavealpha($img_to_tmp_resize, true);
					$img_to_tmp = imagecreatefrompng($img_path_from);
					imagealphablending($img_to_tmp, true);
					break;
				case "gif":
					$img_to_tmp = imagecreatefromgif($img_path_from);
					$img_to_tmp_resize = imagecreatetruecolor($img_to_tmp_wid, $img_to_tmp_hei);
					imagealphablending($img_to_tmp_resize, false);
					$transindex = imagecolortransparent($img_to_tmp);
					if ($transindex >= 0) {
						$transcol = imagecolorsforindex($img_to_tmp, $transindex);
						$transindex = imagecolorallocatealpha($img_to_tmp_resize, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
						imagefill($img_to_tmp_resize, 0, 0, $transindex);
					}
					break;
				default:
					# jpg | jpeg
					$img_to_tmp_resize = imagecreatetruecolor($img_to_tmp_wid, $img_to_tmp_hei);
					$img_to_tmp = imagecreatefromjpeg($img_path_from);
			}

			# merge images
			imagecopyresampled($img_to_tmp_resize, $img_to_tmp, 0, 0, 0, 0, $img_to_tmp_wid, $img_to_tmp_hei, $img_from_wid, $img_from_hei);

			# final file
			switch ($img_from_type) {
				case "png":
					if (!imagePNG($img_to_tmp_resize, $img_path_to, round(($conf_compressao / 100) * 9))) {
						$arrResize["errors"]["user"][] = "Não foi possivel processar a imagem";
					}
					break;
				case "gif":
					# restore transparency if exists
					if ($transindex >= 0) {
						imagecolortransparent($img_to_tmp_resize, $transindex);
						for ($y = 0; $y < $img_to_tmp_hei; ++$y) {
							for ($x = 0; $x < $img_to_tmp_wid; ++$x) {
								if (((imagecolorat($img_to_tmp_resize, $x, $y) >> 24) & 0x7F) >= 100) {
									imagesetpixel($img_to_tmp_resize, $x, $y, $transindex);
								}
							}
						}
					}
					imagetruecolortopalette($img_to_tmp_resize, true, 255);
					imagesavealpha($img_to_tmp_resize, false);
					if (!imageGif($img_to_tmp_resize, $img_path_to, $conf_compressao)) {
						$arrResize["errors"]["user"][] = "Não foi possivel processar a imagem";
					}
					break;
				default:
					# jpg | jpeg
					if (!imageJPEG($img_to_tmp_resize, $img_path_to, $conf_compressao)) {
						$arrResize["errors"]["user"][] = "Não foi possivel processar a imagem";
					}
					break;
			}

			# !important! clear memory
			unset($img_to_tmp);
			unset($img_to_tmp_resize);

			if (empty($arrResize["errors"])) {
				# crop
				if ($crop) {
					$img_crop = new ImageSnapshot();
					$img_crop->ImageFile = $img_path_to;
					$img_crop->Width = $img_to_wid;
					$img_crop->Height = $img_to_hei;
					$img_crop->Resize = false;
					$img_crop->ResizeScale = 100;
					$img_crop->Position = $crop;
					$img_crop->Compression = $conf_compressao;
					if (!$img_crop->SaveImageAs($img_path_to)) {
						$arrResize["errors"]["admin"][] =  "Não foi possivel cortar a imagem";
					}
				}

				# return full resize information
				$arrResize = doResizeResult($img_path_from, $img_path_to);
			}
		}
	}

	if (!empty($arrResize["errors"]["admin"]) && empty($arrResize["errors"]["user"])) {
		$arrResize["errors"]["user"][] = "Não foi possivel processar a imagem";
	}

	# Retornamos o nome do ficheiro final
	return $arrResize;
}

function doResizeResult($img_path_from, $img_path_to)
{
	global $dir_site;

	$arrResize["success"] = true;
	$arrResize["file"]["from"] = pathinfo($img_path_from);
	$arrResize["file"]["from"]["size"] = filesize($img_path_from);
	$arrResize["file"]["to"] = pathinfo($img_path_to);
	$arrResize["file"]["to"]["size"] = filesize($img_path_to);

	$arrResize["path"]["from"]["absolute"] = dirname($img_path_from) . '/';
	$arrResize["path"]["from"]["relative"] = dirname(str_replace($dir_site, '', $img_path_from)) . '/';
	$arrResize["path"]["to"]["absolute"] = dirname($img_path_to) . '/';
	$arrResize["path"]["to"]["relative"] = dirname(str_replace($dir_site, '', $img_path_to)) . '/';
	return $arrResize;
}
function formatBytes($bytes, $precision = 2)
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	// Uncomment one of the following alternatives
	$bytes /= pow(1024, $pow);
	//$bytes /= (1 << (10 * $pow));

	return round($bytes, $precision) . ' ' . $units[$pow];
}
function calcRequiredMemoryToEdit($sImagePath, $width = null, $height = null, $tweakFactor = 1.67470)
{
	$result = false;
	$aImageInfo = getimagesize($sImagePath);
	if (is_array($aImageInfo)) {
		if (!($width > 0 && $height > 0)) {
			$width = $aImageInfo[0];
			$height = $aImageInfo[1];
		}
		$result = round((($width * $height * $aImageInfo['bits'] * $aImageInfo['channels'] / 8 + Pow(2, 16)) * $tweakFactor));
	}
	return $result;
}

function isResizable($imagePath = '')
{
	$arrAllowed = array('png', 'gif', 'jpg', 'jpeg', 'x-ms-bmp');
	$imageType = null;
	if (is_file($imagePath)) {
		# gets image info
		$arrImage = getimagesize($imagePath);
		$imageType = strtolower(str_replace('image/', '', $arrImage['mime']));
	}
	return (in_array($imageType, $arrAllowed)) ? true : false;
}
function getMemoryUsage()
{
	$arrResult = array();
	$arrResult["bytes"]["using"] = memory_get_usage();
	$arrResult["bytes"]["remaining"] = return_bytes(ini_get('memory_limit')) - memory_get_usage();

	$arrResult["human"]["using"] = formatBytes($arrResult["bytes"]["using"]);
	$arrResult["human"]["remaining"] = formatBytes($arrResult["bytes"]["remaining"]);
	return $arrResult;
}
function return_bytes($size_str)
{
	switch (strtolower(substr($size_str, -1))) {
		case 'm':
			return (int)$size_str * 1048576;
		case 'k':
			return (int)$size_str * 1024;
		case 'g':
			return (int)$size_str * 1073741824;
		default:
			return $size_str;
	}
}
function isValidNif($nif)
{
	// Verificar se e' um numero e se e' composto exactamente por 9 digitos
	if (!is_numeric($nif) || strlen($nif) != 9) return false;

	$narray = str_split($nif);

	// verificar se o primeiro digito e' valido. O primeiro digito indica o tipo de contribuinte.
	if ($narray[0] != 1 && $narray[0] != 2 &&  $narray[0] != 5 && $narray[0] != 6 && $narray[0] != 8 && $narray[0] != 9)
		return false;

	$checkbit = $narray[0] * 9;

	for ($i = 2; $i <= 8; $i++) {
		$checkbit += $nif[$i - 1] * (10 - $i);
	}

	$checkbit = 11 - ($checkbit % 11);

	if ($checkbit >= 10) $checkbit = 0;

	if ($nif[8] == $checkbit) return true;
	return false;
}
function grava_ficheiro($origem, $destino, $maxlargura = 100, $maxaltura = 100, $qualidade = 80, $params, $copy)
{
	$imagem = $params['name']; // Nome originai da imagem
	$nome_imagem = md5(uniqid(time())) . $imagem;
	$dir = $destino; // Diretório das imagens
	$salva = $dir . $nome_imagem; // Caminho onde vai ficar a imagem no servidor
	if ($copy) {
		copy($params['tmp_name'], $salva);
	} else {
		move_uploaded_file($params['tmp_name'], $salva); // Este comando move o arquivo do diretório temporário para o caminho especificado acima
	}

	return $nome_imagem;
}
function euro($preco)
{
	$preco = "&#8364; " . number_format($preco, 2, ',', '') . " ";
	return $preco;
}

#
# Converte nome do ficheiro
#
function forceFilename($str, $spaceChar = '_')
{
	$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
	$str = utf8_decode($str);
	$str = strtolower(trim($str));
	# substitui caracteres acentuados
	$a = 'ÀÁÂÃÄÅÆCÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæcçèéêëìíîïðñòóôõöøùúûýýþÿRr';
	$b = 'aaaaaaacceeeeiiiidnoooooouuuuybsaaaaaaacceeeeiiiidnoooooouuuyybyRr';
	$str = strtr($str, utf8_decode($a), $b);
	$str = strtr($str, '"!´??+&*[]|:;?`,<>() {}°@%$æ»«?', '_______________________________');
	$str = str_replace("'", "", $str);
	$str = str_replace("#", "", $str);
	$nom = str_replace('\\', "_", $str);
	$str = str_replace("//", "/", $str);
	$str = str_replace("~", $spaceChar, $str);
	$str = str_replace(" ", $spaceChar, $str);
	$str = str_replace("{$spaceChar}{$spaceChar}", "{$spaceChar}", $str);
	$str = str_replace("{$spaceChar}-", '-', $str);
	$str = str_replace("-{$spaceChar}", '-', $str);
	$str = utf8_encode($str);
	return $str;
}

#
# Executa comando PHP em todos os itens de um array (control.obj.php)
#
function array_map_recursive($function, $data)
{
	if (is_array($data)) {
		foreach ($data as $i => $item) {
			$data[$i] = is_array($item) ? array_map_recursive($function, $item) : $function($item);
		}
	}
	return $data;
}
function escreveErroSucesso()
{
	if ($_SESSION['sucesso']) {
		echo " data-sucesso='" . $_SESSION['sucesso'] . "'";
		unset($_SESSION['sucesso']);
	}
	if ($_SESSION['erro']) {
		echo " data-erro='" . $_SESSION['erro'] . "'";
		unset($_SESSION['erro']);
	}
}
function pr($array)
{
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

function real_getip() {
	if ($_SERVER["HTTP_CLIENT_IP"]) {
		return $_SERVER["HTTP_CLIENT_IP"];
	}
	foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
		if (trim($ip)) {
			return $ip;
		}
	}
	if ($_SERVER["HTTP_X_FORWARDED"]) {
		return $_SERVER["HTTP_X_FORWARDED"];
	}
	else if ($_SERVER["HTTP_FORWARDED_FOR"]) {
		return $_SERVER["HTTP_FORWARDED_FOR"];
	}
	else if ($_SERVER["HTTP_FORWARDED"]) {
		return $_SERVER["HTTP_FORWARDED"];
	}
	else if ($_SERVER["HTTP_X_FORWARDED"]) {
		return $_SERVER["HTTP_X_FORWARDED"];
	}
	else {
		return $_SERVER["REMOTE_ADDR"];
	}
}
function formatarDataPortugues($data) {
    // Array associativo para traduzir os nomes dos dias da semana
    $dias_semana = array(
        'Sunday' => 'Domingo',
        'Monday' => 'Segunda-feira',
        'Tuesday' => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday' => 'Quinta-feira',
        'Friday' => 'Sexta-feira',
        'Saturday' => 'Sábado'
    );

    // Array associativo para traduzir os nomes dos meses
    $meses = array(
        'January' => 'Janeiro',
        'February' => 'Fevereiro',
        'March' => 'Março',
        'April' => 'Abril',
        'May' => 'Maio',
        'June' => 'Junho',
        'July' => 'Julho',
        'August' => 'Agosto',
        'September' => 'Setembro',
        'October' => 'Outubro',
        'November' => 'Novembro',
        'December' => 'Dezembro'
    );

    // Converter a data para o formato desejado
    $dataFormatada = date('l, d \d\e F \d\e Y', strtotime($data));
    $dataFormatada = strtr($dataFormatada, $dias_semana);
    $dataFormatada = strtr($dataFormatada, $meses);

    return $dataFormatada;
}
