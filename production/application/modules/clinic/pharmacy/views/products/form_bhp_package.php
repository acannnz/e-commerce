<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open(current_url(), array("id" => "form_product_package")) ?>
<div class="row form-group">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label col-md-3">Kode Paket</label>
            <div class="col-md-9">
                <input type="text" id="Kode" name="f[Kode]" class="form-control" value="<?php echo @$item->Kode?>"  readonly="readonly" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Nama Paket</label>
            <div class="col-md-9">
                <input type="text" id="NamaPaket" name="f[NamaPaket]" class="form-control" value="<?php echo @$item->NamaPaket ?>" required="required" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Section </label>
            <div class="col-md-9">
                <select id="SectionID" name="SectionID" class="form-control">
                    <?php if (!empty($option_section)): foreach($option_section as $row):?>
                    <option value="<?php echo $row->SectionID?>" <?php echo $row->SectionID == @$item->SectionID ? "selected" : NULL ?>><?php echo $row->SectionName?></option>
                    <?php endforeach;endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Ditagihkan </label>
            <div class="col-md-9">
                <select id="Ditagihkan" name="Ditagihkan" class="form-control">
                	<option value="T" <?php echo @$item->Ditagihkan == "T"?>>YA</option>
                	<option value="F" <?php echo @$item->Ditagihkan == "F"?>>TIDAK</option>
                </select>
            </div>
        </div>
        <?php /*?><div class="form-group">
            <div class="col-md-offset-3 col-md-9">
                <div class="checkbox">
                    <input type="hidden" name="Cyto" value="0" >
                    <input type="checkbox" id="Cyto" name="Cyto" value="1" <?php echo @$item->Cyto == 1 ? "Checked" : NULL ?> class=""><label for="Cyto">Cyto</label>
                </div>
            </div>
        </div><?php */?>
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
                        <th>Konversi</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row form-group">
    <a href="<?php echo @$lookup_product ?>" id="add_icd" data-toggle="lookup-ajax-modal" class="btn btn-primary btn-block"><b><i class="fa fa-plus"></i> Tambah Produk</b></a>
</div>
<div class="row form-group text-right">
    <button type="submit" class="btn btn-primary"><i class="fa fa-file"></i> Simpan</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
</div>
<?php echo form_close()?>

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
									if( confirm( "<?php echo lang('pharmacy:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
								} catch(ex){}
								
							break;

							case 4:
								var _input = $( "<input type=\"number\" style=\"width:100%\" value=\""+ Number(data.Qty || 0) +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.Qty = Number(this.value || 0);
											_datatable.row( row ).data( data ).draw(false);
										} catch(ex){}
									});
							break;														
							

							case 5:
								var _input = $( "<input type=\"text\" style=\"width:100%\" value=\""+ data.AturanPakai  +"\" class=\"form-control\">" );
								this.empty().append( _input );
								
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											data.AturanPakai = this.value || "";
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
							break;							

						}
					},
				remove: function( params, fn, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);
								
						_datatable_actions.calculate_balance();
						
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_general_ledger\"]" );
						var _form_debit = _form.find( "input[id=\"debit\"]" );
						var _form_credit = _form.find( "input[id=\"credit\"]" );
						var _form_balance = _form.find( "input[id=\"balance\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						var rows = _datatable.rows().nodes();
						
						for( var i=0; i<rows.length; i++ )
						{
							
							tol_debit = tol_debit + Number($(rows[i]).find("td:eq(3)").html());
							tol_credit = tol_credit + Number($(rows[i]).find("td:eq(4)").html());
							
						}
						
						tol_balance = tol_debit - tol_credit;

						_form_debit.val(tol_debit);	
						_form_credit.val(tol_credit);
						_form_balance.val(tol_balance);
						
						if (tol_balance == 0)
						{
							_form_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_form_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
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
										{ data: "QTY", className: "text-center" },
										{ data: "Konv", className: "" },
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
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												_datatable_actions.edit.call( elem, row, data, index );
											});
											
										$( "a.btn-remove", row ).on( "click dblclick", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('pharmacy:delete_confirm_message') ?>" ) ){
													_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
												}
											});
									}
							} );
							
						$( "#dt_products_length select, #dt_products_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});

			function lookupbox_row_selected( response ){
				var _response = JSON.parse(response)
				if( _response ){
					
					try {					

						$("#DokterID").val( _response.Kode_Supplier );
						$("#DocterName").val( _response.Nama_Supplier );
					
						$( '#form-ajax-modal' ).remove();
						$("body").removeClass("modal-open").removeAttr("style");
					} catch (e){console.log(e);}
				}
			}
		
		$( document ).ready(function(e) {
            	$( "#dt_products" ).dt_products();
								
				$("form[id=\"form_product_package\"]").on("submit", function(e){
					e.preventDefault();	

					var d = new Date();

					var data_post = { };
						data_post['f'] = {
							"Kode" : $("#Kode").val(),
							"NamaPaket" : $("#NamaPaket").val(),
							"SectionID" : $("#SectionID").val(),
							"Ditagihkan" : $("#Ditagihkan").val(),
						};
						
						data_post['details'] = {};
											
					var table_data = $( "#dt_products" ).DataTable().rows().data();
					
					table_data.each(function (value, index) {
						var detail = {
							"KodePaket" : $("#Kode").val(),
							"Kode_Barang"	: value.Kode_Barang,
							"QTY" : value.QTY,
							"Konv" : value.Konv,
						}
						
						data_post['details'][index] = detail;
					});
					console.log(data_post);
					
					$.post($(this).attr("action"), data_post, function( response, status, xhr ){
						
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.status);
							return false
						}
						
						$.alert_success("<?php echo lang('global:created_successfully')?>");
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("pharmacy/products/bhp_package_edit"); ?>/"+ response.KodePaket;
							
							}, 3000 );
												
					})	
				});

			});

	})( jQuery );
//]]>
</script>