<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><i class="fa fa-search"></i> Lihat BHP</h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                    	<label class="control-label col-md-3">No Resep</label>
                        <div class="col-md-9">
                        	<input type="text" id="NoBuktiBHP" name="NoBuktiBHP" class="form-control" value="<?php echo @$item->NoBukti?>"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Section </label>
                        <div class="col-md-9">
                        	<select id="Farmasi_SectionID" name="Farmasi_SectionID" class="form-control" disabled="disabled">
                           		<option value="<?php echo @$section->SectionID ?>" ><?php echo @$section->SectionName ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">ID Dokter</label>
                        <div class="col-md-9">
                        	<input type="text" id="prescriptionDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Nama Dokter</label>
                        <div class="col-md-9">
                        	<input type="text" id="prescriptionDoctorName" name="Nama_Supplier" value="<?php echo @$doctor->Nama_Supplier?>" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Paket Obat</label>
                        <div class="col-md-9">
                            <input type="hidden" id="Paket" name="Paket" value="<?php echo @$item->Paket ?>" />
                            <input type="text" id="Package_name" name="Package_name" class="form-control" value="<?php echo @$package->Nama_Paket ?>" disabled="disabled" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Jumlah</label>
                        <div class="col-md-9">
                            <input type="text" id="JumlahTransaksi" name="JumlahTransaksi" class="form-control" value="<?php echo number_format(@$item->JumlahTransaksi, 2, ".", ",")?>"  readonly="readonly" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="dt_products" class="table table-sm table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>BarangID</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>                        
                                    <th>Qty</th>                        
                                    <th>Stok</th>                        
                                    <th>Harga @</th>                        
                                    <th>Disc</th>                        
                                    <th>Jumlah</th>                        
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
				
		$.fn.extend({
				dt_products: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "Barang_ID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ 
											data: "Kode_Barang", 
											className: "text-center", 
										},
										{ data: "Nama_Barang", className: "" },
										{ data: "Satuan", className: "" },
										{ data: "JmlObat", className: "text-center" },
										{ data: "Stok", className: "text-center" },
										{ 
											data: "Harga", className: "text-right",
											render: function( val ) {
												return parseFloat(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
											}
										},
										{ data: "Disc", className: "text-right" },
										{ 
											data: "Harga", className: "text-right",
											render: function( val, type, row ){
												 var Jumlah = parseFloat(row.Harga) * Number(row.JmlObat)
												 return Jumlah.toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
											} 
										},
									],

								columnDefs  : [
										{
											"targets": [],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
							} );
							
						$( "#dt_products_length select, #dt_products_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
            	$( "#dt_products" ).dt_products();

			});

	})( jQuery );
//]]>
</script>