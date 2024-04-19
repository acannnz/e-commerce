<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-body">
    <script type="text/javascript">//<![CDATA[
    function lookupbox_row_selected( response ){
        var _response = JSON.parse(response)
        if( _response ){
            //console.log(_response);
            
            try {
                                                                
                var add_data = {
                    "Barang_ID"	: _response.Barang_ID || 0,
                    "Kode_Barang" : _response.Kode_Barang || 0,
                    "Nama_Barang" : _response.Nama_Barang || 0,
                    "Kode_Satuan" : _response.Kode_Satuan || 0,
                    "Kode_Beli" : _response.Kode_Beli || 0,
                    // "Qty_PO" : _response.Qty_Order || 0,
                    // "Qty_Penerimaan" : _response.Qty_Order - _response.Qty_Tlh_Dibeli || 0,
                    "Qty_Penerimaan" : _response.Qty_Penerimaan || 1,
                    "Harga_Beli" : _response.Harga_Beli || 0,
                    "Diskon_1" : 0,
                    "Diskon_Rp" : 0,
                    "Exp_Date" : "1990-01-01",
                    "sub_total" : _response.Harga_Beli  || 0,
                    "JenisBarangID" : _response.JenisBarangID || 0,
                    "Konversi" : _response.Konversi || 0,
                };
                
                $("#dt_trans_goods_receipt_detail").DataTable().row.add( add_data ).draw(true);
                $("#ajaxModal").modal('hide');
            
            } catch (e){console.log();}
        }
    }
    //]]></script>
    <?php echo $this->load->view( "references/item/modal/lookup_goods_receipt_konsinyasi", true ) ?>
</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>
<!-- /.modal-dialog -->

