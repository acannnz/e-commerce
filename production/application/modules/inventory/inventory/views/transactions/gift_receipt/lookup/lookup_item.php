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
                        Barang_ID : _response.Barang_ID || 0,
                        Kode_Barang : _response.Kode_Barang || 0,
                        Nama_Barang : _response.Nama_Barang || 0,
                        Kode_Satuan : _response.Kode_Satuan || "",
                        Qty : 1,
                        Harga :  0,
                        Diskon_Persen: 0,
                        Diskon_Rp: 0,
                        sub_total: 0,
                        TglED: '',
                        NoBatch: ''
                    };
                
                $("#dt_trans_gift_receipt").DataTable().row.add( add_data ).draw(true);
                $("#ajaxModal").modal('hide');
            
            } catch (e){console.log();}
        }
    }
    //]]></script>
    <?php echo $this->load->view( "references/item/modal/lookup", true ) ?>
</div>
<div class="modal-footer">
    <?php echo lang('patients:referrer_lookup_helper') ?>
</div>
<!-- /.modal-dialog -->

