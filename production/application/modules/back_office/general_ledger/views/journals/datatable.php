<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open(); ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('journals:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-md-3 control-label"><?php echo lang('general:from_date_label')?></label>
					<div class="col-md-3">
						<input type="text" id="date_from" class="form-control searchable datepicker" value="<?php echo date("Y-m-d")?>" />
					</div>
					<label class="col-md-3 control-label text-center"><?php echo lang('general_ledger:till_date_label')?></label>
					<div class="col-md-3">
						<input type="text" id="date_till" class="form-control searchable datepicker" value="<?php echo date("Y-m-d") ?>" />
					</div>
				</div>
				<div class="row form-group">
					<label class="col-md-3 control-label"><?php echo lang('journals:division_label') ?></label>
					<div class="col-md-9">
						<select id="DivisiID" class="form-control searchable_option">
							<option value=""><?php echo lang('global:select-none')?></option>
							<?php if (!empty($option_division)) : foreach($option_division as $k => $v) : ?>
							<option value="<?php echo @$k ?>"> <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
				<div class="row form-group">
					<label class="col-md-3 control-label"><?php echo lang('journals:project_label') ?></label>
					<div class="col-md-9">
						<select id="Kode_Proyek" class="form-control searchable_option">
							<option value=""><?php echo lang('global:select-none')?></option>
							<?php if (!empty($option_project)) : foreach($option_project as $k => $v) : ?>
							<option value="<?php echo @$k ?>"> <?php echo @$v ?></option> 
							<?php endforeach; endif;?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row form-group">
					<label class="col-md-3 control-label"><?php echo lang('journals:notes_label') ?></label>
					<div class="col-md-9">
						<textarea id="Keterangan" class="form-control searchable"></textarea>
					</div>
				</div>
				<div class="row form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-9">
						<button id="reset" type="reset" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:reset")?></b></button>
					</div>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-general-ledger-journals" class="table table-sm" width="100%">
				<thead>
					<tr>
						<th><?php echo lang('journals:date_label') ?></th>
						<th><?php echo lang('journals:journal_number_label') ?></th>
						<th><?php echo lang('journals:debit_label') ?></th>
						<th><?php echo lang('journals:credit_label') ?></th>
						<th><?php echo lang('journals:notes_label') ?></th>
						<th><?php echo lang('journals:currency_label') ?></th>
						<th></th>
					</tr>
				</thead>        
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){

		var search_datatable = {
			init : function(){
					var timer = 0;
			
					$( ".searchable" ).on("keyup", function(e){
						e.preventDefault();
						
						var isWordCharacter = event.key.length === 1;
						var isBackspaceOrDelete = (event.keyCode == 8 || event.keyCode == 46);
					
						if (isWordCharacter || isBackspaceOrDelete) {
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout( search_datatable.reload_table , 600 ); 					
						}
							
					});
	
					$( ".searchable_option" ).on("change", function(e){
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
							
					});
					
					$("#date_from, #date_till").datetimepicker({format: "YYYY-MM-DD"}).on("dp.change", function (e) {
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
	
					});
							
					$("#reset").on("click", function(){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout( search_datatable.reload_table , 600 ); 
					});
					
				},
			reload_table : function(){
					$( "#dt-general-ledger-journals" ).DataTable().ajax.reload();
				}
		};
			
		$.fn.extend({
				DataTableGeneralLedgerJournals: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'asc']],
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("general-ledger/journals/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
										
										params.DivisiID = $("#DivisiID").val() || "";
										params.Kode_Proyek = $("#Kode_Proyek").val() || "";
										params.Keterangan = $("#Keterangan").val() || "";									
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "Transaksi_Date",
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val.substr(0, 10) + "</b>"
											}
									},
									{ 
										data: "No_Bukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<b>" + val + "</b>"
											}
									},
									{ 
										data: "Debit", 
										className: "text-right",
										render: function ( val, type, row){
											return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										}
									},
									{ 
										data: "Kredit", 
										className: "text-right",
										render: function ( val, type, row){
											return Number(val).toFixed( 2 ).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
										}
									},
									{ 
										data: "Keterangan",
										width: "20%",
										render: function( val ){
											return val.substr(0,45)
										}
									},
									{ 
										data: "Currency_Code", 
										orderable: false,
										className: "text-center",
									},
									
									{ 
										data: "No_Bukti",
										className: "a-right actions",
										orderable: false,
										render: function ( val, type, row ){			
												val = encodeURIComponent(val);									
												var buttons = "<a href=\"<?php echo base_url("general_ledger/journals/edit/?No_Bukti") ?>=" + val + "\"  title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-info\"><i class=\"fa fa-pencil\"></i></a>";
												
												buttons += "<a href=\"<?php echo base_url("general_ledger/journals/delete/?No_Bukti") ?>=" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger \"><i class=\"fa fa-times\"></i></a>";
												
												if (row.Posted == 1)
												{
													buttons = "<a href=\"<?php echo base_url("general_ledger/journals/edit/?No_Bukti") ?>=" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:view" ) ?>\" class=\"btn btn-info \"><i class=\"fa fa-eye\"></i></a>";
												}
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-general-ledger-journals_length select, #dt-general-ledger-journals_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-general-ledger-journals" ).DataTableGeneralLedgerJournals();
				search_datatable.init();
				
			});
	})( jQuery );
//]]>
</script>