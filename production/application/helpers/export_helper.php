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

    public static function generate_pdf_batch_proccess($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        $PDF = new \Mpdf\Mpdf('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $PDF->debug = false;
        $PDF->autoScriptToLang = true;
        $PDF->autoLangToFont = true;
        $PDF->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$PDF->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $PDF->SetTitle(self::ci()->config->item("company_name"));
        $PDF->SetAuthor(self::ci()->config->item("company_name"));
        $PDF->SetCreator(self::ci()->config->item("company_name"));
        $PDF->useSubstitutions = false;
        $PDF->simpleTables = true;
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

    public static function print_pdf_rincian_biaya($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if (!$margin_bottom) {
            $margin_bottom = 3;
        }
        if (!$margin_top) {
            $margin_top = 3;
        }

        $PDF = new \Mpdf\Mpdf('utf-8', 'A4-' . $orientation, '13', '', 3, 3, $margin_top, $margin_bottom, 9, 9);
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

        $PDF->SetJS("this.print();");

        $PDF->Output($name, "I");
    }

    public static function print_pdf_dialog($html_content, $name = 'download.pdf', $footer = '', $margin_bottom = '', $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 10, $margin_right = 10)
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

        return $PDF->Output($name, "S");
        // $PDF->Output($name, 'I');

    }

    public static function save_pdf($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 5, $margin_right = 5)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        //$PDF = new \Mpdf\Mpdf('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
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

        $PDF->Output("$name", 'F');
    }

    public static function attach_pdf($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P', $margin_left = 5, $margin_right = 5)
    {
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }

        //$PDF = new \Mpdf\Mpdf('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
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

    public static function print_pdf_string($html_content, $name = 'download.pdf', $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if (!$margin_bottom) {
            $margin_bottom = 3;
        }
        if (!$margin_top) {
            $margin_top = 3;
        }
        // $PDF = new \Mpdf\Mpdf([
        //     'mode' => 'utf-8', 
        //     'format' => [96, 200],
        //     'font_size' => 13,
        //     'margin_top' => $margin_top,
        //     'margin_bottom' => $margin_bottom,
        //     'margin_left' => 8,
        //     'margin_right' => 8,
        //     'margin_header' => 9,
        //     'margin_footer' => 9,
        // ]);
        //$PDF = new \Mpdf\Mpdf('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $PDF = new \Mpdf\Mpdf(['utf-8', ['76', '297'], '13', '', 8, 8, $margin_top, $margin_bottom, 2, 9]);
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


        return $PDF->Output($name, "S");
    }

    public static function generate_rpttopdf($my_report, $params = NULL)
    {
        //- Variables - Server Information 
        // $my_server = "27.54.118.164"; 
        // $my_user = "metta"; 
        // $my_password = "qwerty678"; 
        // $my_database = "Klinik_Kulhen"; 

        $my_pdf     = APPPATH . ("../../public/Report/inventory/temporary/RptToPdf.pdf"); // RPT export to pdf file
        $ObjectFactory = new COM("CrystalRuntime.Application.8.5") or die("Error on load"); // call COM port
        $creport = $ObjectFactory->OpenReport($my_report, 1); // call rpt report

        //- Set database logon info - must have 
        // $creport->Database->Tables(1)->SetLogOnInfo($my_server, $my_database, $my_user, $my_password);

        $creport->EnableParameterPrompting = 0;
        $creport->DiscardSavedData;
        $creport->ReadRecords();

        // ======== Pass formula fields ========
        // $creport->FormulaFields->Item(1)->Text = ("'My Report Title'");
        // ======== Pass Parameters =========
        if (!empty($params)) {
            foreach ($params as $row) {
                $creport->ParameterFields($row['params'])->SetCurrentValue($row['value']);
            }
        }
        //export to PDF process
        $creport->ExportOptions->DiskFileName = $my_pdf; //export to pdf
        $creport->ExportOptions->PDFExportAllPages = true;
        $creport->ExportOptions->DestinationType = 1; // export to file
        $creport->ExportOptions->FormatType = 31; // PDF type
        $creport->Export(false);

        //------ Release the variables ------
        $creport = null;
        $crapp = null;
        $ObjectFactory = null;

        header('Content-Length: ' . filesize($my_pdf));
        header("Content-Type: application/pdf");
        header('Content-Disposition: inline; filename="downloaded.pdf"'); // feel free to change the suggested filename
        readfile($my_pdf);

        exit;

        // $pdf_preview = file_get_contents($my_pdf);
        // header('Content-Type: application/pdf');
        // header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        // header('Pragma: public');
        // header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        // header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        // header('Content-Length: '.strlen($pdf_preview));
        // header('Content-Disposition: inline; filename="'.basename($my_pdf).'";');
        // ob_clean(); 
        // flush(); 

        // echo $pdf_preview;

    }

    public static function generate_rpttoxls($my_report, $xls_name, $params = [])
    {
        $my_xls     = APPPATH . ("../../public/Report/inventory/temporary/RptToXls.xls"); // RPT export to pdf file
        $ObjectFactory = new COM("CrystalRuntime.Application") or die("Error on load"); // call COM port
        $creport = $ObjectFactory->OpenReport($my_report, 1); // call rpt report

        $creport->EnableParameterPrompting = 0;
        $creport->DiscardSavedData;
        $creport->ReadRecords();

        // ======== Pass formula fields ========
        // $creport->FormulaFields->Item(1)->Text = ("'My Report Title'");
        // ======== Pass Parameters =========
        if (!empty($params)) {
            foreach ($params as $row) {
                $creport->ParameterFields($row['params'])->SetCurrentValue($row['value']);
            }
        }

        //export to EXCEL process
        $creport->ExportOptions->DiskFileName = $my_xls; //export to pdf
        $creport->ExportOptions->PDFExportAllPages = true;
        $creport->ExportOptions->DestinationType = 1; // export to file
        $creport->ExportOptions->FormatType = 29; // EXCEL type
        $creport->Export(false);

        //------ Release the variables ------
        $creport = null;
        $crapp = null;
        $ObjectFactory = null;

        $xls_file = file_get_contents($my_xls);
        header('Content-type: application/vnd-ms-excel');
        header('Content-Disposition: attachment; filename="' . basename($xls_name) . '.xls";');

        echo $xls_file;
    }

    private static function &ci()
    {
        return get_instance();
    }
}
