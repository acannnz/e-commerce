<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open(site_url("{$nameroutes}/mass_action"), [
		'id' => 'form_crud__list', 
		'name' => 'form_crud__list', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">  
                <h3 class="panel-title"><?php echo lang('heading:revenue_recognition_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="col-md-1 control-label"><?php echo lang('search:for_date_from_label') ?></label>
							<div class="col-md-2">
								<input type="text" id="for_date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
							</div>
							<label class="col-md-1 control-label text-center"><?php echo lang('search:for_date_till_label') ?></label>
							<div class="col-md-2">
								<input type="text" id="for_date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
							</div>
							<label class="col-md-1 control-label text-center"><?php echo lang('search:group_label') ?></label>
							<div class="col-md-2">
								<select id="group" class="form-control">
									<option value=""><?php echo lang('global:select-all'); ?></option>
									<option value="RAWAT JALAN">Rawat Jalan</option>
									<option value="OBAT BEBAS">Obat Bebas</option>
									<option value="RAWAT INAP">Rawat Inap</option>
									<option value="OUTSTANDING">Outstanding</option>
									<option value="DEPOSIT">Deposit</option>
								</select>
							</div>
							<div class="col-md-3">
								<button id="reset" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="cl-md-12">
						<div class="form-group">
							<table id="dt_trans_revenue_recognition_list" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><?php echo lang('label:no_evidence') ?></th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:patient_type') ?></th>
										<th><?php echo lang('label:no_invoice') ?></th>
										<th><?php echo lang('label:nrm') ?></th>
										<th><?php echo lang('label:patient_name') ?></th>
										<th><?php echo lang('label:state') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th><?php echo lang('label:no_evidence') ?></th>
										<th><?php echo lang('label:date') ?></th>
										<th><?php echo lang('label:patient_type') ?></th>
										<th><?php echo lang('label:no_invoice') ?></th>
										<th><?php echo lang('label:nrm') ?></th>
										<th><?php echo lang('label:patient_name') ?></th>
										<th><?php echo lang('label:state') ?></th>
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
				$( "#reset" ).on("click", function(e){
						$( "#dt_trans_revenue_recognition_list" ).DataTable().ajax.reload()
				});
				//function code for custom search
				
				$( "#dt_trans_revenue_recognition_list" ).DataTable({
						processing: true,
						serverSide: true,								
						paginate: true,
						ordering: true,
						order: [[0, 'desc']],
						searching: true,
						info: true,
						responsive: true,
						lengthMenu: [ 15, 30, 50, 100 ],
						ajax: {
								url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
								type: "POST",
								data: function(params){
									params.date_from = $("#for_date_from").val();
									params.date_till = $("#for_date_till").val();
									params.group = $("#group").val();
									
								}
							},
						fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
						columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										width: "180px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "TglTransaksi", 
										class: "text-center",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "JenisKerjasama", 
										className: "text-center",
									},							
									{ 
										data: "NoInvoice", 
										className: "text-center",
									},							
									{ 
										data: "NRM", 
										className:'text-success text-center'
									},
									{ 
										data: "NamaPasien", 
									},										
									{ 
										data: "Batal", 
										class: 'text-center',
										render: function( val, type, row ){
											
											if ( val == 1)
												return "<span class=\"label label-danger\"> <?php echo lang('label:cancel')?> </span>";
		
											if ( row.Posting == 1)
												return "<span class=\"label label-success\"> <?php echo lang('label:posting')?> </span>";
											
											return '';
										}
										
									},
									{ 
										data: "NoBukti",
										className: "",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"Periksa Pasien\" class=\"btn btn-primary btn-xs\"><?php echo lang('buttons:view')?></a>";
												buttons += "</div>";
												
												var buttons = '<div class="btn-group">';
														buttons += '<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>';
														buttons += '<ul class="dropdown-menu">';
															buttons += '<li><a href="<?php echo base_url("{$nameroutes}/update") ?>/' + val + '" title="<?php echo lang('heading:revenue_recognition_view'); ?>" class="btn btn-primary btn-xs"><?php echo lang('buttons:view')?></a><li>';
															buttons += '<li><a href="<?php echo base_url("{$nameroutes}/cancel_audit") ?>/' + val + '" title="<?php echo lang( "buttons:cancel" ) ?>" class="btn-remove"><i class="fa fa-times"></i> <?php echo lang('buttons:cancel')?></a></li>';
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


