d<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url(), array("id" => "form_item_usage", "name" => "form_item_usage") ); ?>
<div class="row">
	<div class="col-md-6">
        <div class="page-subtitle">
            <h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('item_usage:dokter_label') ?></h3>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:evidence_number_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NoBukti" name="f[NoBukt]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:date_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Tanggal" name="f[Tanggal]" placeholder="" class="form-control" value="<?php echo @$item->Jam ?>"  readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:section_label') ?></label>
            <div class="col-lg-9">
                <input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo @$item->SectionID ?>" class="form-control">
                <input type="text" id="SectionName" name="f[SectionName]" value="<?php echo @$section->SectionName ?>" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:description_label') ?></label>
            <div class="col-lg-9">
            	<textarea id="Keterangan" name="f[Keterangan]" class="form-control" required="required"><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
    </div>
 
    <div class="col-md-12">
    	<div class="page-subtitle">
            <h3 class="text-primary"><i class="fa fa-calendar pull-left text-primary"></i> Detail Barang</h3>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="dt_item_usages" class="table table-sm table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Deskripsi</th>
                                <th>Satuan</th>
                                <th>Stok</th>                        
                                <th>Pemakaian</th>                        
                                <th>Harga</th>                        
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12">
        <a href="<?php echo $lookup_item ?>" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Pilih Barang</b></a>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
        <a href="<?php echo base_url("item_usage/create")?>" class="btn btn-default"><b><?php echo lang("buttons:create") ?></b></a>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 0:
								
								try{
									if( confirm( "<?php echo lang('global:dialog:delete_message') ?>" ) ){
													_datatable_actions.remove( data, row )
												}
								} catch(ex){}
								
							break;
														
							case 6:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.QtyPemakaian  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											
											data.QtyPemakaian = this.value || "";
											_datatable.row( row ).data( data );
											
										} catch(ex){}
									});
							break;							
						}
					},
				remove: function( params, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);						
					},
					
			};
		
		$.fn.extend({
				dt_item_usages: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								lengthMenu: [ 20, 50, 100 ],
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ 
											data: "BarangID", 
											className: "actions text-center", 
											render: function( val, type, row, meta ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "BarangID", className: "text-center", }, // kolom penomoran
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang", },
										{ data: "Satuan", className: "text-center", },
										{ data: "QtyStok", className: "text-right" },
										{ data: "QtyPemakaian", className: "text-right" },
										{ data: "Harga", className: "text-right", },
									],
								columnDefs  : [
										{
											"targets": ["BarangID","Keterangan"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								fnRowCallback : function( nRow, aData, iDisplayIndex , iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(1)',nRow).html(index);
										return nRow;					
									},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											_datatable_actions.edit.call( elem, row, data, index );
										});
									}
							} );
							
						$( "#dt_item_usages_length select, #dt_item_usages_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_item_usages" ).dt_item_usages();
				
				$("#form_item_usage").on("submit", function(e){
					e.preventDefault();
					
					if ( !confirm("Apakah Anda Yakin Ingin Menyimpan Data ini ?"))
					{
						return false;
					}
					
					var data_post  = {"f":{}, "d":{}};
										
					data_post['f'] = {
						"Keterangan" : $("#Keterangan").val(),
					}
							
					var table_detail = $( "#dt_item_usages" ).DataTable().rows().data();
					
					if ( $.isEmptyObject( table_detail ))
					{
						alert("Anda Belum Memilih Barang! Silahkan Pilih Terlebih Dahulu.");
						return false;
					}
					table_detail.each( function(value, index){
						var detail = {
							"QtyPemakaian" : value.QtyPemakaian,
							"Keterangan" : value.Keterangan,
							"BarangID" : value.BarangID,
							"Satuan" : value.Satuan,
							"QtyStok" : value.QtyStok,
							"Harga" : parseFloat(value.Harga.replace(/[^0-9\.-]+/g,"")),
						}
						data_post['d'][index] = detail;

						data_post['Kode_Barang'] = value.Kode_Barang;
						data_post['Nama_Barang'] = value.Nama_Barang;
					});

					$.post( $(this).prop("action"), data_post, function( response, status, xhr ){
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);

						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("pharmacy/item-usage/view"); ?>/"+ response.NoBukti;
							
						}, 2500 );						
					});	


				});

			});

	})( jQuery );
//]]>
</script>