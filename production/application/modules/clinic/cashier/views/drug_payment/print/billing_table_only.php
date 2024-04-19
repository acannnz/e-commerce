<table width="34%"  style="font-size:10px !important">
    <tbody>	           
        <tr>              
            <td colspan="4" align="center">
                <span style="font-size:16px"><?php echo $this->config->item( "company_name" ) ?></span>
            </td>
            <td></td>
        </tr>
        <tr>              
            <td colspan="5" align="center">
                <p style="font-size:10px; margin:0 !important;">
                    <?php echo sprintf("%s, %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ) ) ?>
                </p>
            </td>
        </tr>
        <tr>              
            <td colspan="5" align="center" style="border-bottom:1px dashed #000000;">
                <p style="font-size:10px;">
                    <strong><?php echo lang( "drug_payment:phone_label" ) ?> :</strong> <span><?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?></span>
                </p>                                    
            </td>
        </tr>
        <tr>              
            <td colspan="5" align="center" style="padding-top:2px;">
                <span style="font-size:16px;"><?php echo lang('drug_payment:billing_subtitle'); ?></span>
            </td>
        </tr>
        <tr style="border-bottom:1px dashed #000000;">
            <td align="left" style="padding:5px 2px;" colspan="2"><p  style="font-size:10px;"><?php echo lang('drug_payment:no_label'); ?> : <?php echo @$item->NoBukti ?></p></td>
            <td align="right" style="padding:5px 2px;" colspan="2"><p  style="font-size:10px;"><?php echo $item->Jam ?></p></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding:8px 2px;border-bottom:1px dashed #000000"><?php echo lang('drug_payment:qty_label'); ?></td>
            <td style="padding:8px 2px;border-bottom:1px dashed #000000; font: bold;"><?php echo lang('drug_payment:item_name_label'); ?></td>
            <td align="center" style="padding:8px 2px;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:value_label'); ?></td>
            <td align="right" style="padding:8px 2px;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:disc_label'); ?></td>
            <td align="right" style="padding:8px 2px;border-bottom:1px dashed #000000; font:bold;"><?php echo lang('drug_payment:total_label'); ?></td>
        </tr> 

        <?php $i = 1 ;  if(!empty($collection)) : foreach ($collection as $row) :   ?>
        <tr>
            <td style="padding:5px 3px;"><?php echo $row->Qty; ?></td>
            <td style="padding:5px 3px;"><?php echo @$row->Nama_Barang ?></td>
            <td align="center" style="padding:5px 3px;"><?php echo number_format(@$row->Harga, 2, ".", ",") ?></td>
            <td style="padding:5px 3px;"><?php echo @$row->Disc ?></td>
            <td align="right" style="padding:5px 3px;"><?php echo number_format(@$row->SubTotal, 2, ".", ",") ?></td>
        </tr>
        <?php $i++; endforeach; endif;?>
        <tr>
            <td colspan="3" align="right" style="padding:3px 3px 1px;border-bottom:1px dashed #000000;"><?php echo lang('drug_payment:grand_total_label')?></td>
            <td colspan="2" align="right" style="padding:3px 3px 1px;border-top:1px dashed #000000;border-bottom:1px dashed #000000;"><?php echo sprintf("%s %s", "Rp.", number_format(@$grand_total, 2, ".", ",")) ?></td>
        </tr>                                
        <tr>
            <td colspan="5" style="padding:1px;border-bottom:1px dashed #000000;"></td>
        </tr>
        <tr>
            <td colspan="5" align="center" style="padding:8px 3px;border-bottom:1px dashed #000000;">THANK YOU</td>
        </tr>
    </tbody>
</table>