<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
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
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:;" data-mass="delete">
                                        <i class="fa fa-trash-o"></i> <?php echo lang('action:delete') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo lang('heading:stock_opname_list'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 control-label"><?php echo lang('label:section')?></label>
							<div class="col-md-12">
								<select id="location_id" name="location_id" class="form-control">
									<?php if($dropdown_section_to): foreach($dropdown_section_to as $key => $val):?>
									<option value="<?php echo $key ?>" ><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 control-label"><?php echo lang('label:period')?></label>
							<div class="col-md-12">
								<input type="text" id="period" name="period" value="<?php echo date("Y-m") ?>" class="form-control datepicker-priod" data-date-format="YYYY-MM">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 control-label"><?php echo lang('label:item_type_group')?></label>
							<div class="col-md-12">
								<select id="type_group" name="type_group" class="form-control">
									<option value="ALL">ALL</option>
									<?php if($dropdown_type_group): foreach($dropdown_type_group as $key => $val):?>
									<option value="<?php echo $key ?>" ><?php echo $val ?></option>
									<?php endforeach; endif;?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-md-12 control-label">&nbsp;</label>
							<button id="reset" type="button" class="btn btn-warning btn-block"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></button>
						</div>    	
					</div>
				</div>
				<div class="table-responsive">
					<table id="dt_stock_opname" class="table table-sm" width="100%">
						<thead>
							<tr>
								<th><?php echo lang('label:no_evidence')?></th>
								<th><?php echo lang('label:date')?></th>
								<th><?php echo lang('label:item_type_group')?></th>
								<th><?php echo lang('label:description')?></th>
								<th><?php echo lang('global:user')?></th>
								<th><?php echo lang('global:state')?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
        </div>
    </div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
	
		$.fn.extend({
				dt_stock_opname: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							lengthMenu: [ 30, 45, 75, 100 ],
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.location_id = $("#location_id").val();	
										params.period = $("#period").val();	
										params.type_group = $("#type_group").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "No_Bukti", 
										className: "text-center",
										name: "a.No_Bukti",
										width: "150px",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Tgl_Opname", 
										class: "text-center",
										name: "a.Tgl_Opname",
										render: function ( val, type, row ){
												return ( val ) ? val : "n/a"
											}
									},
									{ 
										data: "KelompokJenis", 
										name: "a.KelompokJenis",
									},							
									{ 
										data: "Keterangan", 
										name: "a.Keterangan",
									},							
									{ 
										data: "Nama_Singkat", 
									},
									{ 
										data: "Posted", 
										name: "a.Posted",
										render: function ( val, type, row ){
											
												return ( val ) ? "<strong class=\"text-danger\">Sudah Proses</strong>" : "Belum Proses"
											}
									},									{ 
										data: "No_Bukti",
										orderable: false,
										width: "100px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"Lihat Stok Opname\" class=\"btn btn-primary btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:update')?></a>";
												buttons += "</div>";
												
												return buttons
											}
									}								]
						} );
						
					$( "#dt_stock_opname_length select, #dt_stock_opname_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
				var _form = $('form[name="form_crud__list"]');
				$( "#reset" ).on("click", function(e){
					$( "#dt_stock_opname" ).DataTable().ajax.reload()
				});
			
            	$( "#dt_stock_opname" ).dt_stock_opname();
				
				$('.datepicker-priod').datepicker({
					format: 'yyyy-mm'
				 });
				
			});
	})( jQuery );
//]]>
</script>