<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php echo form_open( base_url("payable/reports/print_payable_card"), array("id" => "form_report_payable", "target" => "_blank") ) ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('reports:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-12">
						<h3><?php echo lang("reports:report_type_sub") ?></h3> 
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="payable_card" class="report_type" data-url="<?php echo base_url("{$nameroutes}/card-payable") ?>" value="payable_card" checked />
							<label for="payable_card">
								<b><?php echo lang('reports:card_payable_report') ?></b>
							</label>
						</div>
					</div>
				</div>    
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="payable_recap" class="report_type" data-url="<?php echo base_url("{$nameroutes}/recap-payable") ?>" value="payable_recap" />
							<label for="payable_recap">
								<b><?php echo lang('reports:recap_payable_report') ?></b>
							</label>
						</div>
					</div>
				</div>    
				<div class="form-group">
					<div class="col-md-offset-1 col-md-11">
						<div class="radio radio-inline">
							<input type="radio" name="f[report_type]" id="group_payable_recap" class="report_type" data-url="<?php echo base_url("{$nameroutes}/group_recap_payable") ?>" value="payable_recap" />
							<label for="group_payable_recap">
								<b>Group <?php echo lang('reports:recap_payable_report') ?></b>
							</label>
						</div>
					</div>
				</div>    
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('reports:periode_label') ?></label>
					<div class="col-lg-4">
						<input type="text" id="date_start" name="f[date_start]" value="<?php echo date("Y-m-01") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
					<label class="col-lg-2 control-label text-center"><?php echo lang('reports:till_label') ?></label>
					<div class="col-lg-4">
						<input type="text" id="date_end" name="f[date_end]" value="<?php echo date("Y-m-t") ?>" data-date-min-date="<?php echo $beginning_balance_date ?>" class="form-control datepicker">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('types:type_label')?></label>
					<div class="col-lg-10">
						<select id="type_id" name="f[type_id]" class="form-control">
							<option value="0"><?php echo lang('global:select-all') ?></option>
							<?php if( !empty($options_type)): foreach( $options_type as $k => $v ): ?>
							<option value="<?php echo $k ?>"><?php echo $v ?></option>
							<?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"><?php echo lang('reports:supplier_label') ?></label>
					<div class="col-lg-3">
						<input type="hidden" id="supplier_id" name="f[supplier_id]" class="form-control" value="" />
						<input type="text" id="supplier_code" name="f[supplier_code]" class="form-control" readonly />
					</div>
					<div class="col-md-7 input-group">
						<input type="text" id="supplier_name" name="f[supplier_name]" class="form-control" readonly />
						<div class="input-group-btn">
							<a href="<?php echo @$lookup_suppliers ?>" id="lookup-supplier" title="" data-toggle="lookup-ajax-modal" class="btn btn-info tip" ><i class="fa fa-gear"></i></a>
							<a href="javascript:;" title="" id="btn-clear-supplier"  class="btn btn-danger" ><i class="fa fa-times"></i></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"></label>
					<div class="col-md-10">
						<button type="submit" href="javascript:;" id="btn-pdf" class="btn btn-danger col-md-6"><b><i class="fa fa-file-pdf-o"></i> <?php echo lang("reports:button_pdf") ?></b></button>
						<a href="javascript:;" id="btn-excel" class="btn btn-success col-md-6"><b><i class="fa fa-file-excel-o"></i> <?php echo lang("reports:button_excel") ?></b></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		
		$( document ).ready(function(e) {
			
			$("#type_id option").attr('disabled','disabled');
			
			
			var data = $('.report_type:checked').data();
			$("form[id=\"form_report_payable\"]").attr( "action", data.url );

			$(".report_type").change( function(){
				
				if ( $(this).val() == 'payable_card' )
				{
					$("#type_id option").attr('disabled','disabled');
					$("#lookup-supplier").removeAttr('disabled');
				} else if ( $(this).val() == 'payable_recap' ) {
					$("#type_id option").removeAttr('disabled');
					$("#lookup-supplier").attr('disabled','disabled');
				}
				
				var data = $(this).data();
				$("form[id=\"form_report_payable\"]").attr( "action", data.url );
			});
						
			$("#btn-reset").on("click", function( e ){
				e.preventDefault();
				$("#form_report_payable").trigger("reset");
			});
			
			$("#btn-clear-supplier").on("click", function(e){
				e.preventDefault();
				
				$("#supplier_id, #supplier_code, #supplier_name").val( "" );
			});		
			
		});
	})( jQuery );
//]]>
</script>