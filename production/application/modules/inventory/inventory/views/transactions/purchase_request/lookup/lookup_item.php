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
                        "Kode_Barang" : _response.Kode_Barang || 0,
                        "Nama_Barang" : _response.Nama_Barang || 0,
                        "Konversi" :  _response.Konversi || 1,
                        "Nama_Kategori" : _response.Nama_Kategori || "",
                        "Nama_Satuan" : _response.Kode_Satuan || "",
                        "Min_Stok" : _response.Min_Stok || 0,
                        "Max_Stok" : _response.Max_Stok || 0,
                        "Qty_Stok" : _response.Qty_Stok || 0,
                        "Qty_Permintaan" : 1,
                        "Harga_Beli" : _response.Harga_Beli || 0,
                        "Jumlah_Total": _response.Harga_Beli || 0,
                        "Barang_ID" : _response.Barang_ID || 0,
                        "JenisBarangID" : _response.JenisBarangID || 0,
                    };
                
                $("#dt_trans_purchase_request_detail").DataTable().row.add( add_data ).draw(true);
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

