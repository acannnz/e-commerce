<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class en_helper
{
	public static function numb_format( $numbers, $decimals=2, $dec_point=',', $thousands_sep='.' )
	{
		return @number_format( $numbers, $decimals, $dec_point, $thousands_sep );
	}
	
	public static function numb_to_words( $numbers )
	{
	   list($numbers, $decimal) = explode(".", $numbers);
	
	   $output = "";
	
	   if($numbers{0} == "-")
	   {
		  $output = "negative ";
		  $numbers = ltrim($numbers, "-");
	   }
	   else if($numbers{0} == "+")
	   {
		  $output = "positive ";
		  $numbers = ltrim($numbers, "+");
	   }
	   
	   if($numbers{0} == "0")
	   {
		  $output .= "zero";
	   }
	   else
	   {
		  $numbers = str_pad($numbers, 36, "0", STR_PAD_LEFT);
		  $group = rtrim(chunk_split($numbers, 3, " "), " ");
		  $groups = explode(" ", $group);
	
		  $groups2 = array();
		  foreach($groups as $g) $groups2[] = self::_convert_three_digit($g{0}, $g{1}, $g{2});
	
		  for($z = 0; $z < count($groups2); $z++)
		  {
			 if($groups2[$z] != "")
			 {
				$output .= $groups2[$z].self::_convert_group(11 - $z).($z < 11 && !array_search('', array_slice($groups2, $z + 1, -1))
				 && $groups2[11] != '' && $groups[11]{0} == '0' ? " and " : ", ");
			 }
		  }
	
		  $output = rtrim($output, ", ");
	   }
	
	   if($decimal > 0)
	   {
		  $output .= " point";
		  for($i = 0; $i < strlen($decimal); $i++) $output .= " ".self::_convert_digit($decimal{$i});
	   }
	
	   return $output;
	}
	
	public static function words_to_number($data) 
	{
		// Replace all number words with an equivalent numeric value
		$data = strtr(
			$data,
			array(
				'zero'      => '0',
				'a'         => '1',
				'one'       => '1',
				'two'       => '2',
				'three'     => '3',
				'four'      => '4',
				'five'      => '5',
				'six'       => '6',
				'seven'     => '7',
				'eight'     => '8',
				'nine'      => '9',
				'ten'       => '10',
				'eleven'    => '11',
				'twelve'    => '12',
				'thirteen'  => '13',
				'fourteen'  => '14',
				'fifteen'   => '15',
				'sixteen'   => '16',
				'seventeen' => '17',
				'eighteen'  => '18',
				'nineteen'  => '19',
				'twenty'    => '20',
				'thirty'    => '30',
				'forty'     => '40',
				'fourty'    => '40', // common misspelling
				'fifty'     => '50',
				'sixty'     => '60',
				'seventy'   => '70',
				'eighty'    => '80',
				'ninety'    => '90',
				'hundred'   => '100',
				'thousand'  => '1000',
				'million'   => '1000000',
				'billion'   => '1000000000',
				'and'       => '',
			)
		);
	
		// Coerce all tokens to numbers
		$parts = array_map(
			function ($val) {
				return floatval($val);
			},
			preg_split('/[\s-]+/', $data)
		);
	
		$stack = new SplStack; // Current work stack
		$sum   = 0; // Running total
		$last  = null;
	
		foreach ($parts as $part) {
			if (!$stack->isEmpty()) {
				// We're part way through a phrase
				if ($stack->top() > $part) {
					// Decreasing step, e.g. from hundreds to ones
					if ($last >= 1000) {
						// If we drop from more than 1000 then we've finished the phrase
						$sum += $stack->pop();
						// This is the first element of a new phrase
						$stack->push($part);
					} else {
						// Drop down from less than 1000, just addition
						// e.g. "seventy one" -> "70 1" -> "70 + 1"
						$stack->push($stack->pop() + $part);
					}
				} else {
					// Increasing step, e.g ones to hundreds
					$stack->push($stack->pop() * $part);
				}
			} else {
				// This is the first element of a new phrase
				$stack->push($part);
			}
	
			// Store the last processed part
			$last = $part;
		}
	
		return $sum + $stack->pop();
	}
	
	public static function ago( $datetime )
	{
		$interval = date_create('now')->diff( date_create(date("Y-m-d H:i:s", $datetime)) );
		$suffix = ( $interval->invert ? '' : '' );
		if ( $v = $interval->y >= 1 ) return self::_pluralize( $interval->y, 'year' ) . $suffix;
		if ( $v = $interval->m >= 1 ) return self::_pluralize( $interval->m, 'month' ) . $suffix;
		if ( $v = $interval->d >= 1 ) return self::_pluralize( $interval->d, 'day' ) . $suffix;
		if ( $v = $interval->h >= 1 ) return self::_pluralize( $interval->h, 'hour' ) . $suffix;
		if ( $v = $interval->i >= 1 ) return self::_pluralize( $interval->i, 'minute' ) . $suffix;
		return self::_pluralize( $interval->s, 'second' ) . $suffix;
	}
	
	private static function _convert_group($index)
	{
	   switch($index)
	   {
		  case 11: return " decillion";
		  case 10: return " nonillion";
		  case 9: return " octillion";
		  case 8: return " septillion";
		  case 7: return " sextillion";
		  case 6: return " quintrillion";
		  case 5: return " quadrillion";
		  case 4: return " trillion";
		  case 3: return " billion";
		  case 2: return " million";
		  case 1: return " thousand";
		  case 0: return "";
	   }
	}
	
	private static function _convert_three_digit($dig1, $dig2, $dig3)
	{
	   $output = "";
	
	   if($dig1 == "0" && $dig2 == "0" && $dig3 == "0") return "";
	
	   if($dig1 != "0")
	   {
		  $output .= self::_convert_digit($dig1)." hundred";
		  if($dig2 != "0" || $dig3 != "0") $output .= " and ";
	   }
	
	   if($dig2 != "0") $output .= self::_convert_two_digit($dig2, $dig3);
	   else if($dig3 != "0") $output .= self::_convert_digit($dig3);
	
	   return $output;
	}
	
	private static function _convert_two_digit($dig1, $dig2)
	{
	   if($dig2 == "0")
	   {
		  switch($dig1)
		  {
			 case "1": return "ten";
			 case "2": return "twenty";
			 case "3": return "thirty";
			 case "4": return "forty";
			 case "5": return "fifty";
			 case "6": return "sixty";
			 case "7": return "seventy";
			 case "8": return "eighty";
			 case "9": return "ninety";
		  }
	   }
	   else if($dig1 == "1")
	   {
		  switch($dig2)
		  {
			 case "1": return "eleven";
			 case "2": return "twelve";
			 case "3": return "thirteen";
			 case "4": return "fourteen";
			 case "5": return "fifteen";
			 case "6": return "sixteen";
			 case "7": return "seventeen";
			 case "8": return "eighteen";
			 case "9": return "nineteen";
		  }
	   }
	   else
	   {
		  $temp = self::_convert_digit($dig2);
		  switch($dig1)
		  {
			 case "2": return "twenty-$temp";
			 case "3": return "thirty-$temp";
			 case "4": return "forty-$temp";
			 case "5": return "fifty-$temp";
			 case "6": return "sixty-$temp";
			 case "7": return "seventy-$temp";
			 case "8": return "eighty-$temp";
			 case "9": return "ninety-$temp";
		  }
	   }
	}
		  
	private static function _convert_digit($digit)
	{
	   switch($digit)
	   {
		  case "0": return "zero";
		  case "1": return "one";
		  case "2": return "two";
		  case "3": return "three";
		  case "4": return "four";
		  case "5": return "five";
		  case "6": return "six";
		  case "7": return "seven";
		  case "8": return "eight";
		  case "9": return "nine";
	   }
	}
	
	private static function _pluralize( $count, $text )
	{
		return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
	}
}


