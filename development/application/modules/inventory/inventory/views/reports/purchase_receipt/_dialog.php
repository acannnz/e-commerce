<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("{$nameroutes}/export"), array("target" => "_blank") ); ?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">  
			<h3 class="panel-title"><?php echo 'Laporan Penerimaan Pembelian' ?></h3>
		</div>
		<div class="panel-body">
			<div class="col-md-12">
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label">Pilihan</label>
						<div class="col-md-9">
							<select id="opsi" name="f[opsi]" class="form-control">
								<option value="by_supplier">Per Supplier</option>
								<option value="all_supplier">Semua Supplier</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:periode_label')?></label>
						<div class="col-md-3">
						   <input id="date" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off"/>
						</div>
						<label class="col-md-3 control-label text-center"><?php echo lang('reports:till_label')?></label>
						<div class="col-md-3">
						   <input id="date" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" autocomplete="off"/>
						</div>
					</div>   
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:section_label')?></label>
						<div class="col-md-9">
							<select id="location" name="f[location]" class="form-control">
								<?php foreach($option_section as $row):?>
								<option value="<?php echo $row->Lokasi_ID?>" ><?php echo $row->SectionName?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:supplier_label')?></label>
						<div class="col-md-9">
							<div class="input-group">
								<input type="hidden" id="supplier" name="f[supplier_id]">
								<input type="text" id="supplier_name" name="f[supplier_name]" class="form-control" readonly>
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_supplier ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
						<div class="col-md-9">
							<select id="export_to" name="export_to" class="form-control">
								<option value="excel">EXCEL</option>
								<option value="pdf">PDF</option>
							</select>
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
						<button type="reset" class="btn btn-default"><?php echo lang( 'buttons:reset' ) ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close()?>
<script type="text/javascript">
(function (e) {	
	$(document).ready(function(e) {

			$('body').on('focus',".datepicker", function(){
				$(this).datetimepicker({
						format: "YYYY-MM-DD", 
						widgetPositioning: {
							horizontal: 'auto', // horizontal: 'auto', 'left', 'right'
							vertical: 'auto' // vertical: 'auto', 'top', 'bottom'
						},
					});
			});				
    });
}) (jQuery);
</script>