<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo 'Daftar Pemakaian Barang Section' ?></h3>
		<ul class="panel-btn">
			<li><a href="<?php echo $create_url ?>" class="btn btn-info" title="<?php echo lang('buttons:create') ?>"><b><i class="fa fa-plus"></i> <?php echo 'Buat Pemakain Baru' ?></b></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-lg-2 control-label text-center">From</label>
					<div class="col-lg-4">
						<input type="text" id="date_from" name="date_from" value="<?php echo date("Y-m-d") ?>" class="form-control datepicker">
					</div>
					<label class="col-lg-2 control-label text-center">Till</label>
					<div class="col-lg-4">
						<input type="text" id="date_till" name="date_till" value="<?php echo date("Y-m-d") ?>" class="form-control datepicker">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<a href="javascript:;" id="refresh-datatable" class="btn btn-success"><b><i class="fa fa-search"></i> <?php echo lang('buttons:search')?> </b></a>
				</div>    	
			</div>
		</div>
		<div class="table-responsive">
			<table id="dt-item-usage" class="table table-sm table-bordered table-striped" width="100%">
				<thead>
					<tr>
						<th><?php echo lang("item_usage:evidence_number_label")?></th>
						<th><?php echo lang("item_usage:date_label")?></th>
						<th><?php echo lang("item_usage:section_label")?></th>
						<th><?php echo lang("item_usage:description_label")?></th>
						<th><?php echo lang("item_usage:state_label")?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var datatable_searchable = {
				init: function(){

				$('#dt-item-usage_filter input').unbind();

					var timer = 0;
					$( "#dt-item-usage_filter input" ).on("keypress", function(e){
						if ( (e.which || e.keyCode) == 13 ) {
							e.preventDefault();
							if (timer) {
								clearTimeout(timer);
							}
							timer = setTimeout(searchWord, 400); 
						}
					});	
					
					$( "#dt-item-usage_filter input" ).on("keyup paste", function(e){
						e.preventDefault();
		
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(searchWord, 400); 
					});		
					
					$("#refresh-datatable").on("click", function(e){
						
						if (timer) {
							clearTimeout(timer);
						}
						timer = setTimeout(searchWord, 500); 
					});

			
					function searchWord(){
						var words = $.trim( $("#dt-item-usage_filter input").val() || "" );
						$( "#dt-item-usage" ).DataTable().search( words );
						$( "#dt-item-usage" ).DataTable().draw(true);	
					}
					
					function refreshTable(){
						$( "#dt-item-usage" ).DataTable().ajax.reload();
					}
				}
			}
			
		$.fn.extend({
				DataTable_ItemUsage: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'DESC']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo base_url("poly/item-usage/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.section_id = '<?php echo $SectionID ?>';	
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							columns: [
									{ 
										data: "NoBukti", 
										className: "text-center",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},
									{ 
										data: "Jam", 
										className: "text-center",
										render: function( val ){
											return val.substr(0, 19);
										}
									},
									{ 
										data: "SectionName", 
									},
									{ 
										data: "Keterangan", 
									},
									{ 
										data: "StatusBatal",
										className: "text-right",
										orderable: false,
										searchable: false,
										render: function (val){
											if ( val == 1)
											{
												return "<span class=\"label label-danger label-sm\"><?php echo lang( "buttons:cancel" ) ?> </span>";
											}
											return ''
										}
									},
									{ 
										data: "NoBukti", 
										className: "text-center",
										orderable: false,
										searchable: false,
										render: function ( val, type, row ){
											var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
												buttons += "<a href=\"<?php echo base_url("poly/item-usage/view/{$type}") ?>/" + row.NoBukti + "\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"label label-default label-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
											<?php /*?>	buttons += "<a href=\"<?php echo base_url("registrations/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";<?php */?>
											buttons += "</div>";
											
											return buttons
										}
									},
										
								]
						} );
						
					$( "#dt-item-usage_length select, #dt-item-usage_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-item-usage" ).DataTable_ItemUsage();
				datatable_searchable.init();
			});
	})( jQuery );
//]]>
</script>