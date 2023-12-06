<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }

class Settings_model extends CI_Model
{
    
    public function get_all_records( $table, $where, $join_table, $join_criteria )
    {
        $this->db->where( $where );
        if ( $join_table )
        {
            $this->db->join( $join_table, $join_criteria );
        }
        
		$query = $this->db->get( $table );
        if ( $query->num_rows() > 0 )
        {
            return $query->result();
        }
        else
        {
            return NULL;
        }
    }
	
    public function countries()
    {
        return $this->db->get( 'countries' )->result();
    }
    
    public function save_translation( $post = array() )
    {
        $data = '';
        $this->load->helper( 'file' );
        $language = $post[ '_language' ];
        $lang     = $this->db->get_where( 'languages', array(
             'name' => $language 
        ) )->result();
        $lang     = $lang[ 0 ];
        $bundled  = FALSE;
        if ( $lang->bundled == 1 )
        {
            $bundled = TRUE;
        }
        $file    = $post[ '_file' ];
        $altpath = $post[ '_path' ];
        $groups  = $this->language_comments();
        if ( $language == 'english' )
        {
            $fullpath = $altpath . $language . "/" . $file . "_lang.php";
        }
        else
        {
            if ( $file == 'fx' && $bundled )
            {
                $fullpath = "./application/language/" . $language . "/custom_language.php";
            }
            else
            {
                $fullpath = "./application/language/" . $language . "/" . $file . "_lang.php";
            }
        }
        $path    = "./application/language/" . $language . "/" . $language . '-original-' . config_item( 'version' ) . '.json';
        $strjson = read_file( $path );
        $strings = json_decode( $strjson, true );
        $added   = 0;
        foreach ( $strings[ $file ] as $key => $value )
        {
            $newvalue = $post[ $key ];
            if ( $bundled && $file == 'fx' && $newvalue == $value )
            {
            }
            else
            {
                $newvalue = str_replace( "'", "\'", $newvalue );
                $data .= '$lang[\'' . $key . '\'] = \'' . $newvalue . '\';' . "\r\n";
                $added += 1;
            }
            if ( isset( $groups[ $key ] ) && !$bundled )
            {
                $data .= "\r\n" . "// " . $groups[ $key ] . "\r\n";
            }
        }
        $data .= "\r\n" . "\r\n";
        if ( $file == 'fx' && !$bundled )
        {
            $data .= "if(file_exists(APPPATH.'/language/" . $language . "/custom_language.php')){" . "\r\n";
            $data .= "\t" . "include APPPATH.'/language/" . $language . "/custom_language.php';" . "\r\n";
            $data .= "}" . "\r\n" . "\r\n" . "\r\n";
            $data .= "/* End of file fx_lang.php */";
        }
        else
        {
            if ( $file == 'fx' && $bundled )
            {
                $data .= "/* End of file custom_language.php */" . "\r\n";
                $data .= "/* Location: ./application/language/" . $language . "/custom_language.php */" . "\r\n";
            }
            else
            {
                $data .= "/* End of file " . $file . "_lang.php */" . "\r\n";
                $data .= "/* Location: ./application/language/" . $language . "/" . $file . "_lang.php */" . "\r\n";
            }
        }
        $data = '<?php' . "\r\n" . "\r\n" . $data;
        
        if ( $bundled && $file == 'fx' && $added == 0 )
        {
        }
        else
        {
            write_file( $fullpath, $data );
        }
        
        if ( $file == 'fx' )
        {
            $data2 = '';
            $keys  = array(
                 'reference_no',
                'date_issued',
                'due_date',
                'from',
                'to',
                'item_name',
                'amount',
                'vat',
                'tax',
                'price',
                'discount',
                'total',
                'paid',
                'balance_due',
                'payment_information',
                'notes',
                'partially_paid',
                'fully_paid',
                'not_paid',
                'draft',
                'accepted',
                'declined',
                'pending',
                'page',
                'page_of' 
            );
            foreach ( $keys as $key )
            {
                $value = $post[ $key ];
                $value = str_replace( "'", "\'", $value );
                $data2 .= '$l[\'' . $key . '\'] = \'' . $value . '\';' . "\r\n";
            }
            $data2 = '<?php' . "\r\n" . $data2;
            write_file( './application/modules/fopdf/helpers/languages/' . $lang->code . '.inc', $data2 );
        }
        return TRUE;
    }
	
    public function backup_translation( $language, $files, $original = FALSE )
    {
        $this->load->helper( 'file' );
        $path = "./application/language/" . $language . "/" . $language . '-' . ( $original ? 'original-' . config_item( 'version' ) : 'backup' ) . '.json';
        foreach ( $files as $file => $altpath )
        {
            if ( $language !== 'english' )
            {
                $altpath = "./application/language/";
            }
            $file             = str_replace( "_lang.php", "", $file );
            $strings[ $file ] = $this->lang->load( $file, $language, TRUE, TRUE, $altpath );
        }
        
        return write_file( $path, json_encode( $strings, JSON_UNESCAPED_UNICODE ) );
        
    }
	
    public function restore_translation( $language, $files )
    {
        $this->load->helper( 'file' );
        $json = read_file( "./application/language/" . $language . "/" . $language . '-backup.json' );
        $str  = json_decode( $json, TRUE );
        
        foreach ( $files as $file => $altpath )
        {
            $file     = str_replace( "_lang.php", "", $file );
            $opath    = "./application/language/" . $language . "/" . $language . '-original-' . config_item( 'version' ) . '.json';
            $origjson = read_file( $opath );
            $orig     = json_decode( $origjson, true );
            foreach ( $orig[ $file ] as $key => $value )
            {
                if ( isset( $str[ $file ][ $key ] ) )
                {
                    $lang[ $key ] = $str[ $file ][ $key ];
                }
                else
                {
                    $lang[ $key ] = $value;
                }
            }
            $lang[ '_language' ] = $language;
            $lang[ '_file' ]     = $file;
            $lang[ '_path' ]     = $altpath;
            $this->save_translation( $lang );
        }
        return TRUE;
    }
    
    public function translation_stats( $files )
    {
        $stats     = array();
        $fstats    = array();
		
		$languages = $this->applib->languages();
		
        foreach ( $languages as $lang )
        {
            $lang       = $lang->name;
            $translated = 0;
            $total      = 0;
			
            foreach ( $files as $file => $altpath )
            {
                $diff = 0;
                $shortfile = str_replace( "_lang.php", "", $file );
                //$en = $this->lang->load( $shortfile, 'english', TRUE, TRUE, $altpath );
                $en = $this->lang->load( $shortfile, 'english', TRUE, TRUE );
				if ( $lang != 'english' )
                {
                    //$tr = $this->lang->load( $shortfile, $lang, TRUE, TRUE, './application/language/' );
                    $tr = $this->lang->load( $shortfile, $lang, TRUE, TRUE );
					
                    foreach ( $en as $key => $value )
                    {
                        $translation = isset( $tr[ $key ] ) ? $tr[ $key ] : $value;
                        if ( !empty( $translation ) && $translation != $value )
                        {
                            $diff++;
                        }
                    }
                    $fstats[ $shortfile ] = array(
							"total" => count( $en ),
							"translated" => $diff 
						);
                } else
                {
                    $diff = count( $en );
                    $fstats[ $shortfile ] = array(
							"total" => count( $en ),
							"translated" => $diff 
						);
                }
				
                $total += count( $en );
                $translated += $diff;
            }
            
			$stats[ $lang ][ 'total' ]      = $total;
            $stats[ $lang ][ 'translated' ] = $translated;
            $stats[ $lang ][ 'files' ]      = $fstats;
        }
		
        return $stats;
    }
    
    public function add_translation( $language, $files )
    {
        $this->load->helper( 'file' );
        $lang    = $this->db->get_where( 'locales', array(
             'language' => str_replace( "_", " ", $language ) 
        ) )->result();
        $l       = $lang[ 0 ];
        $slug    = strtolower( str_replace( " ", "_", $language ) );
        $dirpath = './application/language/' . $slug;
        $icon    = explode( "_", $l->locale );
        if ( isset( $icon[ 1 ] ) )
        {
            $icon = strtolower( $icon[ 1 ] );
        }
        else
        {
            $icon = strtolower( $icon[ 0 ] );
        }
        
        if ( is_dir( $dirpath ) )
        {
            return FALSE;
        }
        mkdir( $dirpath, 0755 );
        
        foreach ( $files as $file => $path )
        {
            $source = $path . 'english/' . $file;
            $destin = './application/language/' . $language . '/' . $file;
            $data   = read_file( $source );
            $data   = str_replace( '/english/', '/' . $language . '/', $data );
            $data   = str_replace( 'system/language', 'application/language', $data );
            write_file( $destin, $data );
        }
        
        $insert = array(
             'code' => $l->code,
            'name' => $slug,
            'icon' => $icon,
            'active' => '0',
            'bundled' => '0' 
        );
        
        return $this->db->insert( 'languages', $insert );
    }
    
    public function language_comments()
    {
        return array(
             "you" => "Version 1.4 Translations",
            "extras" => "Version 1.4 Translations edit Modules templates",
            "no" => "Version 1.4 Translations edit Modules invoices",
            "this_tax_will" => "Version 1.4 Translations edit Modules settings",
            "this_tax_applied" => "Version 1.4 Translations edit Modules login",
            "get_an_image_captcha" => "Missing 1.2 Untranslated words",
            "my_projects" => "1.4 Additions",
            "from_templates" => "New Language files - login - register",
            "already_have_an_account" => "New Language files - Modules project",
            "type_your_note_here" => "New Language files - Untranslated words",
            "nothing_to_display_here" => "Fixed INVOICE PDF and others",
            "username_changed_successfully" => "1.5.6",
            "view_contacts" => "1.5.7",
            "months" => "Jøran Sørbø's ADDITIONS",
            "bug_status_change" => "Added 1.6.0 language files",
            "preview_file" => "Email templates subjects",
            "change_email_subject" => "1.6.2",
            "custom_css" => "1.7" 
        );
    }
    
    public function timezones()
    {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime             = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
        
        $tempTimezones = array();
        foreach ( $timezoneIdentifiers as $timezoneIdentifier )
        {
            $currentTimezone = new DateTimeZone( $timezoneIdentifier );
            
            $tempTimezones[] = array(
                 'offset' => (int) $currentTimezone->getOffset( $utcTime ),
                'identifier' => $timezoneIdentifier 
            );
        }
        
        // Sort the array by offset,identifier ascending
        usort( $tempTimezones, function( $a, $b )
        {
            return ( $a[ 'offset' ] == $b[ 'offset' ] ) ? strcmp( $a[ 'identifier' ], $b[ 'identifier' ] ) : $a[ 'offset' ] - $b[ 'offset' ];
        } );
        
        $timezoneList = array();
        foreach ( $tempTimezones as $tz )
        {
            $sign                                = ( $tz[ 'offset' ] > 0 ) ? '+' : '-';
            $offset                              = gmdate( 'H:i', abs( $tz[ 'offset' ] ) );
            $timezoneList[ $tz[ 'identifier' ] ] = '(UTC ' . $sign . $offset . ') ' . $tz[ 'identifier' ];
        }
        
        return $timezoneList;
    }    
}

/* End of file model.php */ 

