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
							<label class="col-md-3 control-label"><?php echo lang('search:section_label') ?></label>
							<div class="col-md-9">
								<select id="section_id" name="f[section_id]" class="form-control">
									<?php foreach($option_section as $key => $val):?>
									<option value="<?php echo $key ?>" <?php echo $key == $item->SectionID ? 'selected' : ''?>><?php echo $val ?></option>
									<?php endforeach ?>
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
											<div class="checkbox">
											<?php echo form_checkbox([
													'id' => 'check-all',
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
												<label for="check-all">&nbsp;</label>
											</div>
										</th>
										<th><?php echo 'No Bukti' ?></th>
										<th><?php echo 'Tanggal' ?></th>
										<th><?php echo 'Nilai' ?></th>
										<th><?php echo 'Keterangan' ?></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;"></th>
										<th><?php echo 'No Bukti' ?></th>
										<th><?php echo 'Tanggal' ?></th>
										<th><?php echo 'Nilai' ?></th>
										<th><?php echo 'Keterangan' ?></th>
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
				
				$("#check-all").on("change", function(e){
					
					$(".post-check").prop('checked', $(this).prop("checked"));
					
					$(this).prop("checked") 
						? $(".post-check").closest( 'tr' ).addClass('danger')
						: $(".post-check").closest( 'tr' ).removeClass('danger');
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
									params.section_id = $("#section_id").val();
									<?php if ( @$is_cancel ): ?>
									params.is_cancel = 1;
									<?php endif; ?>
								}
							},
						fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
						columns: [
									{
										data: "NoBukti", 
										className: "text-center",
										orderable: false, 
										searchable: false, 
										render: function ( val, type, row, meta ){
												return 	'<div class="checkbox">' +
															'<input type="checkbox"  id="row'+ meta.row +'" name="postings[][No_Bukti]" data class=\"post-check\" value ="'+ val +'" >' +
															'<label for="row'+ meta.row +'">&nbsp;</label>' +
														'</div>';
											}
										
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tanggal", 
										class: "text-center",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},	
									{ 
										data: "Nilai", 
										class: "text-right",
										render: function ( val, type, row ){
												return mask_number.currency_add( val )
											}
									},					
									{ data: "Keterangan", }						
							]
					});
			});
		
	})( jQuery );
</script>


