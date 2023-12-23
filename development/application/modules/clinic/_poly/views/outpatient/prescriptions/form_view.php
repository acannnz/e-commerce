<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>	
	.zoom {
			display:inline-block;
			position: relative;
		}
	.zoomImg {
			display:block;
		}
	/* magnifying glass icon */
	.zoom:after {
		content:'';
		display:block; 
		width:33px; 
		height:33px; 
		position:absolute; 
		top:0;
		right:0;
		background:url(icon.png);
	}
	.zoom img {
		display: block;
	}
	.zoom img::selection { background-color: transparent; }
</style>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><i class="fa fa-search"></i> Lihat Resep</h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                    	<label class="control-label col-md-3">No Resep</label>
                        <div class="col-md-9">
                        	<input type="text" id="NoResep" name="NoResep" class="form-control" value="<?php echo @$item->NoResep?>"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Section </label>
                        <div class="col-md-9">
                        	<select id="Farmasi_SectionID" name="Farmasi_SectionID" class="form-control" disabled="disabled">
                            	<?php if (!empty($option_pharmacy)): foreach($option_pharmacy as $row):?>
                           		<option value="<?php echo $row->SectionID?>" <?php echo $row->SectionID == @$item->Farmasi_SectionID ? "selected" : NULL ?>><?php echo $row->SectionName?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>
         
                </div>
				<div class="col-md-6">
                    <input type="hidden" id="prescriptionDokterID" name="DokterID" class="form-control" value="<?php echo @$item->DokterID ?>" readonly />
                    <div class="form-group">
                    	<label class="control-label col-md-3">Dokter</label>
                        <div class="col-md-9">
                        	<input type="text" id="prescriptionDoctorName" name="Nama_Supplier" value="<?php echo @$doctor->Nama_Supplier?>" class="form-control" readonly />
                        </div>
                    </div>
					<div class="form-group">
						<label class="control-label col-md-3"></label>
                        <div class="col-md-9">
                            <div class="checkbox">
                                <input type="hidden" name="Cyto" value="0" >
                                <input type="checkbox" id="Cyto" name="Cyto" value="1" <?php echo @$item->Cyto == 1 ? "Checked" : NULL ?> class="" disabled="disabled"><label for="Cyto">Cyto</label>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
			<?php if(empty($item->PrescriptionPicture)):?>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="dt_products" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Satuan</th>                        
									<th>Dosis</th>   
                                    <th>Aturan Pakai</th>                        
                                    <th>Qty</th>                                               
                                    <th>Stok</th>                               
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
			<?php endif?>
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
<script type="text/javascript" src="<?php echo base_url('themes/default/assets/js/zoom.min.js')?>" ></script>
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
											data: "Kode_Barang", 
											className: "text-center", 
										},
										{ data: "Nama_Barang", className: "" },
										{ data: "Satuan", className: "" },
										{ data: "Dosis", className: "" },
										{ data: "Dosis2", className: "" },
										{ data: "Qty", className: "text-center" },
										{ data: "Stok", className: "text-center" },
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
				$('#PrescriptionPicture').zoom({on:'mouseover'});
			});

	})( jQuery );
//]]>
</script>