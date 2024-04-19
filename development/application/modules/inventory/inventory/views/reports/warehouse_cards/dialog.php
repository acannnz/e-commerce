<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
?>

<?php echo form_open( base_url("{$nameroutes}/export"), array("target" => "_blank") ); ?>
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
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                	<a href="<?php echo site_url("{$nameroutes}/create") ?>"><i class="fa fa-plus"></i> <?php echo lang('action:add') ?></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('reports:recap_stock_heading'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:date_label')?></label>
						<div class="col-md-3">
							<input id="date_start" name="f[date_start]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-d"); ?>" />
						</div>
						<label class="col-md-1 control-label text-center"><?php echo lang('reports:till_label')?></label>
						<div class="col-md-3">
						   <input id="date_end" name="f[date_end]" type="text" class="form-control datepicker" value="<?php echo date("Y-m-t"); ?>" />
						</div>
					</div>   
				</div>
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:item_label')?></label>
						<div class="col-md-7">
							<div class="input-group">
								<input type="hidden" id="Lokasi_ID" name="f[Lokasi_ID]" value="<?php echo @$item->Lokasi_ID ?>">
								<input type="hidden" id="Barang_ID" name="f[Barang_ID]" value="<?php echo @$item->Barang_ID ?>">
								<input type="text" id="Kode_Barang" name="f[Kode_Barang]" value="<?php echo @$item->Kode_Barang ?>" placeholder="" class="form-control">
								<span class="input-group-btn">
									<a href="<?php echo @$lookup_products ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo lang('reports:export_to_label')?></label>
						<div class="col-md-3">
							<select id="export_to" name="export_to" class="form-control">
								<option value="pdf">PDF</option>
								<option value="excel">EXCEL</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3  form-group">
						<button type="submit" class="btn btn-primary"><b><i class="fa fa-file"></i> <?php echo lang( 'buttons:export' ) ?></b></button>
						<button type="reset" class="btn btn-default"><?php echo lang( 'buttons:reset' ) ?></button>
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