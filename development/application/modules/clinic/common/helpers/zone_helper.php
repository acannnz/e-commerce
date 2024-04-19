<?php

if( ! function_exists('format_address') )
{
	function format_address( $address, $area=null, $district=null, $county=null, $province=null, $country=null )
	{
		$addresses = array();
		array_push( $addresses, $address );
		
		if( ! is_null($area) && ! empty($area) ){ array_push( $addresses, $area ); }
		if( ! is_null($district) && ! empty($district) ){ array_push( $addresses, $district ); }
		if( ! is_null($county) && ! empty($county) ){ array_push( $addresses, $county ); }
		if( ! is_null($province) && ! empty($province) ){ array_push( $addresses, $province ); }
		if( ! is_null($country) && ! empty($country) ){ array_push( $addresses, $country ); }
		
		return (string) @implode( ", ", $addresses );
	}
}

if( ! function_exists('format_areas') )
{
	function format_areas( $country=null, $province=null, $county=null, $district=null, $area=null  )
	{
		$areas = array();
		
		//if( ! is_null($country) && ! empty($country) ){ array_push( $areas, $country ); }
		//if( ! is_null($province) && ! empty($province) ){ array_push( $areas, $province ); }
		if( ! is_null($county) && ! empty($county) ){ array_push( $areas, $county ); }
		if( ! is_null($district) && ! empty($district) ){ array_push( $areas, $district ); }
		if( ! is_null($area) && ! empty($area) ){ array_push( $areas, $area ); }
		
		return (string) @implode( " &rsaquo; ", $areas );
	}
}

/*


function _parser_zones()
{
	$this->load->model( "zone_m", "country_m" );
	$this->load->model( "zone_m", "province_m" );
	$this->load->model( "zone_m", "county_m" );
	$this->load->model( "zone_m", "district_m" );
	$this->load->model( "zone_m", "village_m" );
	
	$this->province_m->table = "zone";
	$this->county_m->table = "zone__cities";
	$this->district_m->table = "zone__districts";
	$this->village_m->table = "zone__areas";
	
	set_time_limit(0);
	
	
	$_country_id = 100;
	
	$r_country = array(
			'code' => 'ID',
			'parent_id' => 0,
			'zone_type' => 'COUNTRY',
			'zone_name' => "Indonesia",
			'zone_description' => "Indonesia Raya",
			'state' => 1,
		);
	
	$country_id = $this->zone_m->insert( $r_country );
	
	$provinces = $this->province_m->get_all( array("country_id" => $_country_id, 'id' => 1508) );
	if( $provinces )
	{
		foreach( $provinces as $province )
		{
			//sleep(2);
			
			$r_province = array(
					'code' => $province->code,
					'parent_id' => $country_id,
					'zone_type' => 'PROVINCE',
					'zone_name' => $province->name,
					'zone_description' => $province->name,
					'state' => 1,
				);
				
			$province_id = $this->zone_m->insert( $r_province );
				
			// Render kabupaten
			$counties = $this->county_m->get_all( array("zone_id" => $province->id) );
			if( $counties )
			{
				foreach( $counties as $county )
				{
					$r_county = array(
							'code' => @$county->code,
							'parent_id' => $province_id,
							'zone_type' => 'COUNTY',
							'zone_name' => $county->city_name,
							'zone_description' => $county->city_name,
							'zone_island' => $county->city_island,
							'state' => 1,
						);
					
					$county_id = $this->zone_m->insert( $r_county );	
					
					// Render kecamatan
					$districts = $this->district_m->get_all( array("city_id" => $county->id) );
					if( $districts )
					{
						foreach( $districts as $district )
						{
							$r_district = array(
									'code' => @$district->code,
									'parent_id' => $county_id,
									'zone_type' => 'DISTRICT',
									'zone_name' => $district->district_name,
									'zone_description' => $district->district_name,
									'zone_island' => $county->city_island,
									'state' => 1,
								);
								
							$district_id = $this->zone_m->insert( $r_district );
								
							// Render desa
							$villages = $this->village_m->get_all( array("district_id" => $district->id) );
							if( $villages )
							{
								foreach( $villages as $village )
								{
									$r_village = array(
											'code' => $village->code,
											'parent_id' => $district_id,
											'zone_type' => 'VILLAGE',
											'zone_name' => $village->area_name,
											'zone_description' => $village->area_name,
											'zone_postcode' => $village->area_postcode,
											'zone_island' => $county->city_island,
											'state' => 1,
										);
										
									$this->zone_m->insert( $r_village );
								}
							}
						}
					}
				}
			}
		}
	}
	
	exit();
}

function _parse_nationality()
{
	$rr_nationality = array(
				'Afghan',
				'Albanian',
				'Algerian',
				'American',
				'Andorran',
				'Angolan',
				'Antiguans',
				'Argentinean',
				'Armenian',
				'Australian',
				'Austrian',
				'Azerbaijani',
				'Bahamian',
				'Bahraini',
				'Bangladeshi',
				'Barbadian',
				'Barbudans',
				'Batswana',
				'Belarusian',
				'Belgian',
				'Belizean',
				'Beninese',
				'Bhutanese',
				'Bolivian',
				'Bosnian',
				'Brazilian',
				'British',
				'Bruneian',
				'Bulgarian',
				'Burkinabe',
				'Burmese',
				'Burundian',
				'Cambodian',
				'Cameroonian',
				'Canadian',
				'Cape Verdean',
				'Central African',
				'Chadian',
				'Chilean',
				'Chinese',
				'Colombian',
				'Comoran',
				'Congolese',
				'Congolese',
				'Costa Rican',
				'Croatian',
				'Cuban',
				'Cypriot',
				'Czech',
				'Danish',
				'Djibouti',
				'Dominican',
				'Dominican',
				'Dutch',
				'Dutchman',
				'Dutchwoman',
				'East Timorese',
				'Ecuadorean',
				'Egyptian',
				'Emirian',
				'Equatorial Guinean',
				'Eritrean',
				'Estonian',
				'Ethiopian',
				'Fijian',
				'Filipino',
				'Finnish',
				'French',
				'Gabonese',
				'Gambian',
				'Georgian',
				'German',
				'Ghanaian',
				'Greek',
				'Grenadian',
				'Guatemalan',
				'Guinea-Bissauan',
				'Guinean',
				'Guyanese',
				'Haitian',
				'Herzegovinian',
				'Honduran',
				'Hungarian',
				'I-Kiribati',
				'Icelander',
				'Indian',
				'Indonesian',
				'Iranian',
				'Iraqi',
				'Irish',
				'Irish',
				'Israeli',
				'Italian',
				'Ivorian',
				'Jamaican',
				'Japanese',
				'Jordanian',
				'Kazakhstani',
				'Kenyan',
				'Kittian and Nevisian',
				'Kuwaiti',
				'Kyrgyz',
				'Laotian',
				'Latvian',
				'Lebanese',
				'Liberian',
				'Libyan',
				'Liechtensteiner',
				'Lithuanian',
				'Luxembourger',
				'Macedonian',
				'Malagasy',
				'Malawian',
				'Malaysian',
				'Maldivan',
				'Malian',
				'Maltese',
				'Marshallese',
				'Mauritanian',
				'Mauritian',
				'Mexican',
				'Micronesian',
				'Moldovan',
				'Monacan',
				'Mongolian',
				'Moroccan',
				'Mosotho',
				'Motswana',
				'Mozambican',
				'Namibian',
				'Nauruan',
				'Nepalese',
				'Netherlander',
				'New Zealander',
				'Ni-Vanuatu',
				'Nicaraguan',
				'Nigerian',
				'Nigerien',
				'North Korean',
				'Northern Irish',
				'Norwegian',
				'Omani',
				'Pakistani',
				'Palauan',
				'Panamanian',
				'Papua New Guinean',
				'Paraguayan',
				'Peruvian',
				'Polish',
				'Portuguese',
				'Qatari',
				'Romanian',
				'Russian',
				'Rwandan',
				'Saint Lucian',
				'Salvadoran',
				'Samoan',
				'San Marinese',
				'Sao Tomean',
				'Saudi',
				'Scottish',
				'Senegalese',
				'Serbian',
				'Seychellois',
				'Sierra Leonean',
				'Singaporean',
				'Slovakian',
				'Slovenian',
				'Solomon Islander',
				'Somali',
				'South African',
				'South Korean',
				'Spanish',
				'Sri Lankan',
				'Sudanese',
				'Surinamer',
				'Swazi',
				'Swedish',
				'Swiss',
				'Syrian',
				'Taiwanese',
				'Tajik',
				'Tanzanian',
				'Thai',
				'Togolese',
				'Tongan',
				'Trinidadian or Tobagonian',
				'Tunisian',
				'Turkish',
				'Tuvaluan',
				'Ugandan',
				'Ukrainian',
				'Uruguayan',
				'Uzbekistani',
				'Venezuelan',
				'Vietnamese',
				'Welsh',
				'Welsh',
				'Yemenite',
				'Zambian',
				'Zimbabwean',
				'Afghan',
				'Albanian',
				'Algerian',
				'American',
				'Andorran',
				'Angolan',
				'Antiguans',
				'Argentinean',
				'Armenian',
				'Australian',
				'Austrian',
				'Azerbaijani',
				'Bahamian',
				'Bahraini',
				'Bangladeshi',
				'Barbadian',
				'Barbudans',
				'Batswana',
				'Belarusian',
				'Belgian',
				'Belizean',
				'Beninese',
				'Bhutanese',
				'Bolivian',
				'Bosnian',
				'Brazilian',
				'British',
				'Bruneian',
				'Bulgarian',
				'Burkinabe',
				'Burmese',
				'Burundian',
				'Cambodian',
				'Cameroonian',
				'Canadian',
				'Cape Verdean',
				'Central African',
				'Chadian',
				'Chilean',
				'Chinese',
				'Colombian',
				'Comoran',
				'Congolese',
				'Congolese',
				'Costa Rican',
				'Croatian',
				'Cuban',
				'Cypriot',
				'Czech',
				'Danish',
				'Djibouti',
				'Dominican',
				'Dominican',
				'Dutch',
				'Dutchman',
				'Dutchwoman',
				'East Timorese',
				'Ecuadorean',
				'Egyptian',
				'Emirian',
				'Equatorial Guinean',
				'Eritrean',
				'Estonian',
				'Ethiopian',
				'Fijian',
				'Filipino',
				'Finnish',
				'French',
				'Gabonese',
				'Gambian',
				'Georgian',
				'German',
				'Ghanaian',
				'Greek',
				'Grenadian',
				'Guatemalan',
				'Guinea-Bissauan',
				'Guinean',
				'Guyanese',
				'Haitian',
				'Herzegovinian',
				'Honduran',
				'Hungarian',
				'I-Kiribati',
				'Icelander',
				'Indian',
				'Indonesian',
				'Iranian',
				'Iraqi',
				'Irish',
				'Irish',
				'Israeli',
				'Italian',
				'Ivorian',
				'Jamaican',
				'Japanese',
				'Jordanian',
				'Kazakhstani',
				'Kenyan',
				'Kittian and Nevisian',
				'Kuwaiti',
				'Kyrgyz',
				'Laotian',
				'Latvian',
				'Lebanese',
				'Liberian',
				'Libyan',
				'Liechtensteiner',
				'Lithuanian',
				'Luxembourger',
				'Macedonian',
				'Malagasy',
				'Malawian',
				'Malaysian',
				'Maldivan',
				'Malian',
				'Maltese',
				'Marshallese',
				'Mauritanian',
				'Mauritian',
				'Mexican',
				'Micronesian',
				'Moldovan',
				'Monacan',
				'Mongolian',
				'Moroccan',
				'Mosotho',
				'Motswana',
				'Mozambican',
				'Namibian',
				'Nauruan',
				'Nepalese',
				'Netherlander',
				'New Zealander',
				'Ni-Vanuatu',
				'Nicaraguan',
				'Nigerian',
				'Nigerien',
				'North Korean',
				'Northern Irish',
				'Norwegian',
				'Omani',
				'Pakistani',
				'Palauan',
				'Panamanian',
				'Papua New Guinean',
				'Paraguayan',
				'Peruvian',
				'Polish',
				'Portuguese',
				'Qatari',
				'Romanian',
				'Russian',
				'Rwandan',
				'Saint Lucian',
				'Salvadoran',
				'Samoan',
				'San Marinese',
				'Sao Tomean',
				'Saudi',
				'Scottish',
				'Senegalese',
				'Serbian',
				'Seychellois',
				'Sierra Leonean',
				'Singaporean',
				'Slovakian',
				'Slovenian',
				'Solomon Islander',
				'Somali',
				'South African',
				'South Korean',
				'Spanish',
				'Sri Lankan',
				'Sudanese',
				'Surinamer',
				'Swazi',
				'Swedish',
				'Swiss',
				'Syrian',
				'Taiwanese',
				'Tajik',
				'Tanzanian',
				'Thai',
				'Togolese',
				'Tongan',
				'Trinidadian or Tobagonian',
				'Tunisian',
				'Turkish',
				'Tuvaluan',
				'Ugandan',
				'Ukrainian',
				'Uruguayan',
				'Uzbekistani',
				'Venezuelan',
				'Vietnamese',
				'Welsh',
				'Welsh',
				'Yemenite',
				'Zambian',
				'Zimbabwean'
			);
		
		$this->load->model( "nationality_m" );
		
		foreach( $rr_nationality as $nationality )
		{
			$this->nationality_m->insert(array(
					"alias" => str_replace(' ', '-', strtolower($nationality)),
					"nationality" => $nationality,
					"state" => 1
				));	
		}
		
		exit;
}

*/
