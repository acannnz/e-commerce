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
                <?php /*?><div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                	<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div><?php */?>
                <h3 class="panel-title"><?php echo @$is_cancel ?  lang('heading:cancel_posting') : lang('heading:posting'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('search:for_date_from_label') ?></label>
							<div class="col-md-3">
								<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
							</div>
							<label class="col-md-3 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
							<div class="col-md-3">
								<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('label:doctor_name') ?></label>
							<div class="col-md-9">
								<select id="doctor_id" class="form-control">
									<option value="">-- Tidak Ada --</option>
									<?php foreach($option_doctor as $k => $v): ?>
									<option value="<?php echo $k ?>"><?php echo $v ?></option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"><?php echo lang('search:group_label') ?></label>
							<div class="col-md-9">
								<select id="group" class="form-control">
									<option value="">-- Tidak Ada --</option>
									<option value="RAWAT JALAN">RAWAT JALAN</option>
									<option value="RAWAT INAP">RAWAT INAP</option>
									<option value="OBAT BEBAS">OBAT BEBAS</option>
									<option value="OUTSTANDING">OUTSTANDING</option>
									<option value="DEPOSIT">DEPOSIT</option>									
								</select>
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
							<table id="dt_trans_posting_list" class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
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
										<th><?php echo 'Back Office' ?></th>
										<th><?php echo lang('label:no_invoice') ?></th>
										<th><?php echo lang('label:date_closing') ?></th>
										<th><?php echo lang('label:note') ?></th>
										<th><?php echo lang('label:patient') ?></th>
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
										<th><?php echo 'Back Office' ?></th>
										<th><?php echo lang('label:no_invoice') ?></th>
										<th><?php echo lang('label:date_closing') ?></th>
										<th><?php echo lang('label:note') ?></th>
										<th><?php echo lang('label:patient') ?></th>
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
									params.doctor_id = $("#doctor_id").val();
									params.group = $("#group").val();
									<?php if ( @$is_cancel ): ?>
									params.is_cancel = 1;
									<?php endif; ?>
								}
							},
						fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
						columns: [
									{
										data: "NoBukti", 
										orderable: false, 
										searchable: false, 
										render: function( val ){
											return checkbox( val )
										}
										
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										width: "140px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tanggal", 
										class: "text-center",
										width: "130px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "PostingKeBackOffice", 
										class: "text-center",
										width: "130px",
										render: function ( val, type, row ){
												return ( val == 'BO_1' ) ? 'Back Office 1' : 'Back Office 2'
											}
									},
									{ data: "NoInvoice", class: "text-center",},	
									{ 
										data: "TglTransaksi", 
										class: "text-center",
										width: "130px",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},					
									{ data: "Catatan", },
									{ 
										data: "NamaPasien", 
										render: function ( val, type, row ){
												return ( val != null ) ? val : row.Keterangan
											}
									},
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"javascript:;\" title=\"View\" data-act=\"ajax-modal\" data-title=\"<?php //echo lang('heading:revenue_recognition_view'); ?>\" data-action-url=\"<?php echo @$view_revenue_url?>/"+ val +"\" data-modal-lg=\"1\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-eye\"></i> <?php echo lang('buttons:view')?></a>";
											buttons += "</div>";
											
											var buttons = '<div class="btn-group">';
													buttons += '<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
													buttons += '<ul class="dropdown-menu">';
														buttons += '<li><a href="javascript:;" data-action-url="<?php echo @$view_audit_url ?>/' + val + '" data-act=\"ajax-modal\" data-title="<?php echo lang('heading:revenue_recognition_view'); ?>" title="<?php echo lang('heading:revenue_recognition_view'); ?>" data-modal-lg=\"1\" class=""><i class="fa fa-eye"></i> <?php echo lang('buttons:view')?></a><li>';
														<?php if ( ! @$is_cancel ): ?>
														buttons += '<li><a href="javascript:;" data-action-url="<?php echo @$cancel_audit_url ?>/' + val + '" data-act=\"ajax-modal\" data-title="<?php echo lang('global:cancel_confirm'); ?>" title="<?php echo lang( "buttons:cancel" ) ?>" class=""><i class="fa fa-times"></i> <?php echo lang('buttons:cancel')?></a></li>';
														<?php endif; ?>
													buttons += '</ul>';
												buttons += '</div>';
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


