<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

final class export_helper
{
    public static function generate_pdf($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 10, $margin_right = 10)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_header' => 2,
            'margin_footer' => 2,
        ]);

        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->useSubstitutions = false;
        $PDF->simpleTables = false;
        $PDF->SetDisplayMode('fullpage');

        $css_content = @file_get_contents(str_replace("\\", "/", FCPATH) . "themes/default/js/plugins/bootstrap/css/bootstrap.min.css");
        $PDF->WriteHTML($css_content, 1);
        if (strlen($html_content) > 999999) :
            foreach (str_split($html_content, 999999) as $content) :
                $PDF->WriteHTML($content);
            endforeach;
        else :
            $PDF->WriteHTML($html_content);
        endif;

        if ($header != '') {
            $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE);
        }
        if ($footer != '') {
            $PDF->SetHTMLFooter('<p class="text-center" style="color: #778899 !important; font-size: 12px;">' . $footer . '</p>', '', TRUE);
        }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer

        $PDF->Output($name, 'I');
    }

    public static function print_pdf($html_content, $name = 'download.pdf', $footer = '', $margin_bottom = '', $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 10, $margin_right = 10)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_header' => 2,
            'margin_footer' => 2,
        ]);

        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->useSubstitutions = false;
        $PDF->simpleTables = false;
        $PDF->SetDisplayMode('fullpage');

        $css_content = @file_get_contents(str_replace("\\", "/", FCPATH) . "themes/default/js/plugins/bootstrap/css/bootstrap.min.css");
        $PDF->WriteHTML($css_content, 1);

        if (strlen($html_content) > 999999) :
            foreach (str_split($html_content, 999999) as $content) :
                $PDF->WriteHTML($content);
            endforeach;
        else :
            $PDF->WriteHTML($html_content);
        endif;

        // if( $header != '' ){ $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE); }
        // if( $footer != '' ){ $PDF->SetHTMLFooter('<p class="text-center" style="color: #778899 !important; font-size: 12px;">' . $footer . '</p>', '', TRUE); }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer


        $PDF->Output($name, 'I');
    }
    
    public static function print_review_pdf($html_content, $name = 'download.pdf', $footer = '', $margin_bottom = '', $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left, $margin_right)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_header' => 2,
            'margin_footer' => 2,
        ]);

        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->useSubstitutions = false;
        $PDF->simpleTables = false;
        $PDF->SetDisplayMode('fullpage');

        $css_content = @file_get_contents(str_replace("\\", "/", FCPATH) . "themes/default/js/plugins/bootstrap/css/bootstrap.min.css");
        $PDF->WriteHTML($css_content, 1);
        $PDF->WriteHTML( $html_content );

        // if (strlen($html_content) > 999999) :
        //     foreach (str_split($html_content, 999999) as $content) :
        //         $PDF->WriteHTML($content);
        //     endforeach;
        // else :
        //     $PDF->WriteHTML($html_content);
        // endif;

        if( $header != '' ){ $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE); }
        if( $footer != '' ){ $PDF->SetHTMLFooter('<p class="text-center" style="color: #778899 !important; font-size: 12px;">' . $footer . '</p>', '', TRUE); }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer


        return $PDF->Output( $name, "S" );
    }

    public static function save_pdf($html_content, $filename, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 5, $margin_right = 5)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_header' => 2,
            'margin_footer' => 2,
        ]);

        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->SetDisplayMode('fullpage');

        $css_content = @file_get_contents(str_replace("\\", "/", FCPATH) . "themes/default/js/plugins/bootstrap/css/bootstrap.min.css");
        $PDF->WriteHTML($css_content, 1);
        $PDF->WriteHTML($html_content);

        if ($header != '') {
            $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE);
        }
        if ($footer != '') {
            $PDF->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', TRUE);
        }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer

        $PDF->Output("$filename", 'F');
    }

    public static function attach_pdf($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 5, $margin_right = 5)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        //$PDF = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => $margin_left,
            'margin_right' => $margin_right,
            'margin_header' => 2,
            'margin_footer' => 2,
        ]);
        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->SetDisplayMode('fullpage');

        $css_content = @file_get_contents(str_replace("\\", "/", FCPATH) . "themes/default/js/plugins/bootstrap/css/bootstrap.min.css");
        $PDF->WriteHTML($css_content, 1);
        $PDF->WriteHTML($html_content);

        if ($header != '') {
            $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE);
        }
        if ($footer != '') {
            $PDF->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', TRUE);
        }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer

        return ($file_content = $PDF->Output($name, 'S'));
    }

    public static function numb_format($number, $decimals = 2, $dec_point = ',', $thousands_sep = '.')
    {
        $active_lang = self::ci()->config->item("language");

        if ("english" == $active_lang) {
            self::ci()->load->helper("en");
            return en_helper::numb_format($number, $decimals);
        } else if ("indonesian" == $active_lang) {
            self::ci()->load->helper("id");
            return id_helper::numb_format($number, $decimals);
        }

        return @number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    public static function numb_to_words($numbers)
    {
        $active_lang = self::ci()->config->item("language");

        if ("english" == $active_lang) {
            self::ci()->load->helper("en");
            return en_helper::numb_to_words($numbers);
        } else if ("indonesian" == $active_lang) {
            self::ci()->load->helper("id");
            return id_helper::numb_to_words($numbers);
        }
    }

    public static function print_pdf_string( $html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if( ! $margin_bottom){ $margin_bottom = 3; }
		if( ! $margin_top ){ $margin_top = 3; }
        $PDF = new \Mpdf\Mpdf([
            'mode' => 'utf-8', 
            'format' => [96, 200],
            'font_size' => 13,
            'margin_top' => $margin_top,
            'margin_bottom' => $margin_bottom,
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_header' => 9,
            'margin_footer' => 9,
        ]);
        // $PDF = new mPDF('utf-8', ['76','297'], '13', '', 6, 5, $margin_top, $margin_bottom, 9, 9);
        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle( self::ci()->config->item( "company_name" ) );
        $PDF->SetAuthor( self::ci()->config->item( "company_name" ) );
        $PDF->SetCreator( self::ci()->config->item( "company_name" ) );
        $PDF->SetDisplayMode('fullpage');
        
		$css_content = @file_get_contents( str_replace("\\", "/", FCPATH)."themes/default/js/plugins/bootstrap/css/bootstrap.min.css" );
		$PDF->WriteHTML( $css_content, 1 );
        $PDF->WriteHTML( $html_content );
        
		if( $header != '' ){ $PDF->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE); }
        if( $footer != '' ){ $PDF->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', TRUE); }
        //$PDF->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$PDF->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer
	
        
		return $PDF->Output( $name, "S" );
    }
    
    private static function &ci()
    {
        return get_instance();
    }
}
