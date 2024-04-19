<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url(), array("id" => "form_retur", "name" => "form_retur") ); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">Buat Retur Barang</h3>

	</div>
	<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('retur:retur_number_label') ?></label>
						<div class="col-lg-9">
							<input type="text" id="NoRetur" name="f[NoRetur]" value="<?php echo @$item->NoRetur ?>" placeholder="" class="form-control" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('retur:date_label') ?></label>
						<div class="col-lg-9">
							<input type="text" id="Tanggal" name="f[Tanggal]" placeholder="" class="form-control" value="<?php echo @$item->Jam ?>"  readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('retur:billing_pharmacy_label') ?> <span class="text-danger">*</span></label>
						<div class="col-lg-9">
							<div class="input-group">
								<input type="text" id="NoBukti" name="f[NoBukti]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control"  readonly="readonly">
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_billing_pharmacy ?>" data-toggle="lookup-ajax-modal" class="btn btn-default <?php echo (@$is_edit) ? 'disabled' : '' ?>" ><i class="fa fa-search"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label"><?php echo lang('retur:description_label') ?></label>
						<div class="col-lg-9">
							<textarea id="Keterangan" name="f[Keterangan]" class="form-control" required><?php echo @$item->Keterangan ?></textarea>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="well well-sm">
						<h3 class="subtitle">Informasi Pasien</h3>
						<address>
							No Registrasi: <b><span id="NoReg"><?php echo @$item->NoReg ?></span></b> <br/>
							N.R.M: <b><span id="NRM"><?php echo @$item->NRM ?></span></b> <br/>
							Nama: <b><span id="NamaPasien"><?php echo @$item->NamaPasien ?></span></b><br/>
							Section Asal: <b><span id="SectionAsal"><?php echo @$item->SectionName ?></span></b><br/>
							<input type="hidden" id="SectionID" name="f[SectionID]">
						</address>
					</div>
				</div>
		 	</div>
			<hr/>
			<div class="col-md-12">
				<h3 class="subtitle">Detail Barang</h3>
				<div class="table-responsive">
					<table id="dt_returs" class="table table-bordered" width="100%">
						<thead>
							<tr>
								<th><i class="fa fa-sort-numeric-asc"></i></th>
								<th>Kode Barang</th>
								<th>Nama Barang</th>
								<th>Satuan</th>
								<th>Qty Pesan</th>                        
								<th>Qty Pemakaian</th>                        
								<th>Qty Retur</th>                        
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12 text-right">
					<a href="<?php echo base_url("pharmacy/retur/create")?>" class="btn btn-success"><b><i class="fa fa-plus"></i> <?php echo lang("buttons:create") ?></b></a>
					<?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
					<?php if(!@$is_edit):?>
					<button type="submit" class="btn btn-primary"><b><i class="fa fa-save"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
					<?php endif;?>
				</div>
			</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		var _detail_rows = [];
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
										
							case 6:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ data.Qty_Retur  +"\" class=\"form-control\" max=\""+ data.Qty_Terpakai  +"\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											
											data.Qty_Retur = this.value > data.Qty_Terpakai ? data.Qty_Terpakai : this.value;
											_datatable.row( row ).data( data ).draw();
											
										} catch(ex){}
									});
							break;							
						}
					},
				details: function( data, row, elem ){
						var _tr = $( elem ).closest( 'tr' );
						var _rw = _datatable.row( _tr );
						
						var _dt = _rw.data();
						var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
				 
						if( _rw.child.isShown() ){
							_tr.removeClass( 'details' );
							
							$(elem).find('i').addClass( 'fa-expand' );
							$(elem).find('i').removeClass( 'fa-compress' );
							
							_rw.child.hide();
				 
							// Remove from the 'open' array
							_detail_rows.splice( _ids, 1 );
						} else {
							$(elem).find('i').removeClass( 'fa-expand' );
							$(elem).find('i').addClass( 'fa-compress' );
									
							_tr.addClass( 'details' );
							
							//if( _rw.child() == undefined ){
								var _details = $( "<div class=\"details-loader\"></div>" );
								_rw.child( _details ).show();
								
								
							/*} else {
								_rw.child.show();
							}*/
							
							// Add to the 'open' array
							if( _ids === -1 ){
								_detail_rows.push( _tr.attr( 'id' ) );
							}
						}
						
						$( window ).trigger( "resize" );
					},
				remove: function( params, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);						
					},
					
			};
		
		$.fn.extend({
				dt_returs: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: true,
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
											data: "Barang_ID", 
											className: "text-center",
											render: function( val, type, row ){
												return "<a href=\"javascript:;\" title=\"<?php echo lang('buttons:detail') ?>\" class=\"btn btn-success btn-xs btn-detail\"> Detail <i class=\"fa fa-expand\"></i></a>";
											} 
										},
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang"},
										{ data: "Satuan", className: "text-center", },
										{ data: "Qty_Pesan", className: "text-right", },
										{ data: "Qty_Terpakai", className: "text-right" },
										{ data: "Qty_Retur", className: "text-right" },
									],
								fnRowCallback : function( nRow, aData, iDisplayIndex , iDisplayIndexFull ) {
										/*var index = iDisplayIndexFull + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;	*/				
									},
								createdRow: function ( row, data, index ){
										$( row ).on( "click", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											_datatable_actions.edit.call( elem, row, data, index );
										});
										
										// $( row ).on( "click", "a.btn-detail", function(e){
										// 		e.preventDefault();										
										// 		var elem = $( this );
										// 		_datatable_actions.details( data, row, elem );
										// 	});
										<?php if(! @$is_edit):?>
										$( row ).find("a.btn-detail").trigger('click');	
										<?php endif;?>
									}
							} );
							
						$( "#dt_returs_length select, #dt_returs_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_returs" ).dt_returs();
				
				$("#form_retur").on("submit", function(e){
					e.preventDefault();
					
					if ( !confirm("Apakah Anda Yakin Ingin Menyimpan Data ini ?"))
					{
						return false;
					}
					
					var data_post  = {"f":{}, "d":{}};
										
					data_post['f'] = {
						NoBukti : $('#NoBukti').val(), 
						Keterangan : $('#Keterangan').val(),
						NoReg : $('#NoReg').html() || null,
						SectionID : $('#SectionID').val(),
					}
							
					var table_detail = $( "#dt_returs" ).DataTable().rows().data();
					table_detail.each( function(v, i){
						if(v.Barang_ID == 0) return true;
						var detail = {
							Qty_Pesan : v.Qty_Pesan, 
							Qty_Terpakai : v.Qty_Terpakai, 
							Qty_Retur : v.Qty_Retur, 
							HargaPersediaan : v.HargaPersediaan, 
							Barang_ID : v.Barang_ID,
							Satuan : v.Satuan, 
							NoBill : $('#NoBukti').val(),
							JenisBarangID : v.JenisBarangID || 0,
							PemakaianOnly : 0,
							Harga : v.Harga,
							Disc : null,
						}
						data_post['d'][i] = detail;
					});

					$.post( $(this).prop("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}					
						$.alert_success(response.message);

						setTimeout(function(){
							document.location.href = "<?php echo base_url("pharmacy/retur"); ?>";
						}, 300 );						
					});	


				});

			});

	})( jQuery );
//]]>
</script>