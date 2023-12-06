<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?php echo lang('heading:goods_receipt_list'); ?></h3>
					</div>
					<div class="col-md-6">
						<div class="panel-bars">
							<ul class="btn-bars">
								<li class="dropdown">
									<a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
										<i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
									</a>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>
											<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<?php echo form_open(site_url("{$nameroutes}/mass_action"), [
						'id' => 'form_crud__list_open', 
						'name' => 'form_crud__list_open', 
						'rule' => 'form' , 
						'class' => ''
					]); ?>
				<div class="row">
					<div class="col-md-12">
						<div class="row form-group">
							<label class="col-md-1 control-label"><?php echo lang('search:for_date_from_label') ?></label>
							<div class="col-md-2">
								<input type="text" name="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
							</div>
							<label class="col-md-1 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
							<div class="col-md-2">
								<input type="text" name="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
							</div>
							<div class="col-lg-3">
								<?php 
								echo form_dropdown('Gudang_ID', $dropdown_section, '', ['class'=>'form-control select','id'=>'Gudang_ID']);
								?>
							</div>
							<div class="col-md-3">
								<button name="btn_search" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
							</div>
						</div>
						<div class="row form-group">
							<table id="dt_trans_goods_receipt_detail" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:evidence_number') ?></th>
										<th><?php echo lang('label:no_do') ?></th>
										<th><?php echo lang('label:item_name') ?></th>
										<th><?php echo lang('label:item_qty') ?></th>
										<th><?php echo lang('label:exp_date') ?></th>
										<th><?php echo lang('label:no_batch') ?></th>
										<th style="width:65px;text-align:center;">Stok <i class="fa fa-cog"></i></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:evidence_number') ?></th>
										<th><?php echo lang('label:no_do') ?></th>
										<th><?php echo lang('label:item_name') ?></th>
										<th><?php echo lang('label:item_qty') ?></th>
										<th><?php echo lang('label:exp_date') ?></th>
										<th><?php echo lang('label:no_batch') ?></th>
										<th style="width:65px;text-align:center;">Stok <i class="fa fa-cog"></i></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<?php echo form_hidden('mass_action', ''); ?>
				<?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>

<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list_open"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_trans_goods_receipt_detail" ).DataTable().ajax.reload();
				});
				
				var _datatable = $( "#dt_trans_goods_receipt_detail" );
				//function code for custom search
				$( "#dt_trans_goods_receipt_detail" ).DataTable({
					processing: true,
					serverSide: true,								
					paginate: true,
					ordering: true,
					//lengthMenu: [ 50, 75, 100, 150 ],
					order: [[1, 'desc']],
					searching: true,
					info: true,
					responsive: true,
					ajax: {
							url: "<?php echo site_url("{$nameroutes}/datatable_detail") ?>",
							type: "POST",
							data: function(params){
								params.date_from   = _form.find("input[name=\"for_date_from\"]").val();
								params.date_till   = _form.find("input[name=\"for_date_till\"]").val();
								params.Gudang_ID  = _form.find("select[name=\"Gudang_ID\"]").val();
							}
						},
					columns: [
							{orderable: false, searchable: false, render: checkbox},
							{ 
								data: 'Tgl_Penerimaan',
								className: 'text-center',
							
							},
							{data: 'No_Penerimaan', className: 'details-control'},
							{data: 'No_DO'},
							{data: 'Nama_Barang', width:'300px'},
							{data: 'Qty_Penerimaan', className: 'text-right'},
							{ 
								data: 'Tgl_Expired',
								className: 'text-center',
							},
							{data: 'NoBatch'},
							{ 
								data: 'No_Penerimaan',
								className: "",
								orderable: false,
								width: "150px",
								render: function ( val, type, row ){
										var buttons = '<div class="btn-group">';
															if(row.OutStok == 0){
																buttons += '<button type="button" class="btn btn-success btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tersisa <span class="caret"></span></button>';
															} else if(row.OutStok == 1){
																buttons += '<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Habis <span class="caret"></span></button>';
															}
															buttons += '<ul class="dropdown-menu">';
																if(row.OutStok == 0){
																	buttons += '<li><a href="javascript:;" data-action-url="<?php echo @$update_state ?>/'+ row.Penerimaan_ID +'/'+ row.Barang_ID +'/1" data-title="Update Status Stok Habis" data-act="ajax-modal" data-modal-lg="1"><i class="fa fa-pencil"></i> Ubah Stok Habis</a></li>';
																} else if(row.OutStok == 1){
																	buttons += '<li><a href="javascript:;" data-action-url="<?php echo @$update_state ?>/'+ row.Penerimaan_ID +'/'+ row.Barang_ID +'/0" data-title="Update Status Stok Tersisa" data-act="ajax-modal" data-modal-lg="1"><i class="fa fa-pencil"></i> Ubah Stok Sisa</a></li>';
																}															
															buttons += '</ul>';
														buttons += '</div>';
										return buttons
									}
							}
						]
				});
				
				
				var _detail_rows = [];
				$( "#dt_trans_goods_receipt_detail" ).find( 'tbody' ).on( 'click', 'tr td.details-control', function(e){
						var _tr = $( this ).closest( 'tr' );
						var _rw = _datatable.DataTable().row( _tr );
						
						var _dt = _rw.data();
						var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
				 
						if( _rw.child.isShown() ){
							_tr.removeClass( 'details' );
							
							_rw.child.hide();
				 
							// Remove from the 'open' array
							_detail_rows.splice( _ids, 1 );
						} else {
							_tr.addClass( 'details' );
							
							if( _rw.child() == undefined ){
								var _details = $( "<div class=\"details-loader\"></div>" );
								_rw.child( _details ).show();
								_details.html('\
									<div class="row">\
										<div class="col-md-4">\
											<div class="form-group">\
												<label class="col-md-3">Tanggal Expired</label>\
												<div class="col-md-9">
													<input type="text" name="Tgl_Expired" class="form-control datepicker" autocomplete="off">
												</div>
											</div>\
										</div>\
										<div class="col-md-4">\
											<div class="form-group">\
												<label class="col-md-3">NoBatch</label>\
												<div class="col-md-9">
													<input type="text" name="NoBatch" class="form-control" autocomplete="off">
												</div>
											</div>\
										</div>\
									</div>\
								');
								$( window ).trigger( "resize" );
							} else {
								_rw.child.show();
							}
							
							// Add to the 'open' array
							if( _ids === -1 ){
								_detail_rows.push( _tr.attr( 'id' ) );
							}
						}
						
						$( window ).trigger( "resize" );
					});
					
				// On each draw, loop over the `_detail_rows` array and show any child rows
				_datatable.DataTable().on('draw', function (){
						$.each(_detail_rows, function ( i, id ){
								$( '#' + id + ' td.details-control').trigger( 'click' );
							});
					});
				
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>


