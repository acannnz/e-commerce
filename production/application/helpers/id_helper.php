<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class id_helper
{
	public static function numb_format( $numbers, $decimals=2, $dec_point='.', $thousands_sep=',' )
	{
		return @number_format( $numbers, $decimals, $dec_point, $thousands_sep );
	}
	
	public static function numb_to_words( $numbers, $decimal=2 )
	{
		return self::_numb_to_words( $numbers, $decimal );
	}
	
	private static function _numb_to_words( $numbers, $decimal=2 )
	{
		$stext = array(
				"Nol",
				"Satu",
				"Dua",
				"Tiga",
				"Empat",
				"Lima",
				"Enam",
				"Tujuh",
				"Delapan",
				"Sembilan",
				"Sepuluh",
				"Sebelas"
			);
		
		$say  = array(
				"Ribu",
				"Juta",
				"Milyar",
				"Triliun",
				"Biliun", // remember limitation of float
				"--apaan---" ///setelah biliun namanya apa?
			);
		
		$w = "";
	
		if ($numbers <0 ) 
		{
			$w  = "Minus ";
			//make positive
			$numbers *= -1;
		}
	
		$snum = number_format($numbers,$decimal,",",".");
		//die($snum);
		$strnum =  explode(".",substr($snum,0,strrpos($snum,",")));
		
		//parse decimalnya
		$koma = substr($snum,strrpos($snum,",")+1);
	
		$isone = substr($numbers,0,1)  ==1;
		if (count($strnum)==1) 
		{
			$numbers = $strnum[0];
			switch (strlen($numbers)) 
			{
				case 1:
				case 2:
					if (!isset($stext[$strnum[0]]))
					{
						if($numbers<19)
						{
							$w .=$stext[substr($numbers,1)]." Belas";
						}else
						{
							$w .= $stext[substr($numbers,0,1)]." Puluh ".(intval(substr($numbers,1))==0 ? "" : $stext[substr($numbers,1)]);
						}
					}else
					{
						$w .= $stext[$strnum[0]];
					}
					break;
				case 3:
					$w .=  ($isone ? "Seratus" : self::_numb_to_words(substr($numbers,0,1))." Ratus")." ".(intval(substr($numbers,1))==0 ? "" : self::_numb_to_words(substr($numbers,1)));
					break;
				case 4:
					$w .=  ($isone ? "Seribu" : self::_numb_to_words(substr($numbers,0,1))." Ribu")." ".(intval(substr($numbers,1))==0 ? "" : self::_numb_to_words(substr($numbers,1)));
					break;
				default:
					break;
			}
		}else
		{
			$text = $say[count($strnum)-2];
			$w = ($isone && strlen($strnum[0])==1 && count($strnum) <= 3 ? "Se".strtolower($text) : self::_numb_to_words($strnum[0]).' '.$text);
			array_shift($strnum);
			$i = count($strnum)-2;
			foreach ($strnum as $k=>$v) 
			{
				if (intval($v)) 
				{
					$w.= ' '.self::_numb_to_words($v).' '.($i >=0 ? $say[$i] : "");
				}
				$i--;
			}
		}
		$w = trim($w);
		if ($decimal = intval($koma)) {
			$w .= " Koma ". self::_numb_to_words($koma);
		}
		
		return trim($w);
	}
}


