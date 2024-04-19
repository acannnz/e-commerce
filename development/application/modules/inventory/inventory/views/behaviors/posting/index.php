<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open( $form_action, [
		'id' => 'form_crud__list', 
		'name' => 'form_crud__list', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo @$is_cancel ?  lang('heading:cancel_posting') : lang('heading:posting'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('search:for_date_from_label') ?></label>
							<div class="col-md-3">
								<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-01")?>" />
							</div>
							<label class="col-md-3 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
							<div class="col-md-3">
								<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-t") ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:warehouse') ?></label>
							<div class="col-lg-9">
								<?php 
								echo form_dropdown('location_id', $dropdown_section, '', ['class'=>'form-control select','id'=>'location_id']);
								?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">
								<button id="btn-refresh" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<div class="col-md-12">
								<button id="btn-posting" data-action-url="<?php echo @$form_action ?>" data-act="ajax-modal" data-title="<?php echo (@$is_cancel) ? lang('confirm:cancel_posting_title'): lang('confirm:posting_title'); ?>" type="button" class="btn btn-danger btn-block"><b><i class="fa fa-exchange"></i> <?php echo (@$is_cancel) ? lang('process:cancel_posting'): lang('process:posting'); ?></b></button>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="cl-md-12">
						<div class="form-group">
							<table id="dt_trans_posting_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:no_evidence') ?></th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:section') ?></th>
										<?php /*?><th><?php echo lang('label:transaction_type') ?></th><?php */?>
										<th><?php echo lang('label:amount') ?></th>
										<th><?php echo lang('label:description') ?></th>
										<th><?php echo lang('label:supplier') ?></th>
										<th><?php echo lang('label:cn') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;"></th>
										<th><?php echo lang('label:no_evidence') ?></th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:section') ?></th>
										<?php /*?><th><?php echo lang('label:transaction_type') ?></th><?php */?>
										<th><?php echo lang('label:amount') ?></th>
										<th><?php echo lang('label:description') ?></th>
										<th><?php echo lang('label:supplier') ?></th>
										<th><?php echo lang('label:cn') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</tfoot>
							</table>
            			</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<?php echo form_hidden('mass_action', ''); ?>
<?php echo form_close() ?>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				$( "#btn-refresh" ).on("click", function(e){
						$( "#dt_trans_posting_list" ).DataTable().ajax.reload()
				});
				//function code for custom search
				
				$( "#dt_trans_posting_list" ).DataTable({
						processing: true,
						serverSide: false,								
						paginate: false,
						ordering: false,
						order: [[1, 'desc']],
						searching: true,
						info: true,
						responsive: true,
						lengthMenu: [ 30, 75, 100 ],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
								type: "POST",
								data: function(params){
									params.date_from = $("#for_date_from").val();
									params.date_till = $("#for_date_till").val();
									params.location_id = $("#location_id").val();
									<?php if ( @$is_cancel ): ?>
									params.is_cancel = 1;
									<?php endif; ?>
								}
							},
						fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
						columns: [
									{
										data: "No_Penerimaan", 
										orderable: false, 
										searchable: false, 
										render: function( val ){
											return checkbox( val )
										}
										
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										width: "110px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tgl_Penerimaan", 
										class: "text-center",
										width: "100px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ data: "SectionName", },							
									/*{ data: "JenisTransaksi",},*/							
									{ 
										data: "Total_Nilai", 
										className: "text-right",
										render: function( val ){
											return mask_number.currency_add( val );
										}
									},
									{ data: "Keterangan", },
									{ data: "Nama_Supplier", },
									{ 
										data: "CN", 
										render: function( val ){
											return mask_number.currency_add( val );
										}
									},
									{ 
										data: "Penerimaan_ID",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"javascript:;\" data-act=\"ajax-modal\" data-action-url=\"<?php echo base_url("inventory/transactions/goods_receipt/view") ?>/" + val + "\" data-title=\"Detail Transaksi\" data-modal-lg='1' title=\"View\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-eye\"></i> <?php echo lang('buttons:view')?></a>";
												buttons += "</div>";
												
												return buttons;
											}
									}								
							]
					});
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>


