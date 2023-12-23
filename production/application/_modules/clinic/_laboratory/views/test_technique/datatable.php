<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="col-md-offset-2 col-md-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">List Data Teknik Pemeriksaan</h3>
			<ul class="panel-btn">
				<li><a href="<?php echo base_url("{$nameroutes}/create") ?>" data-toggle="ajax-modal" title="<?php echo lang('buttons:create') ?>" class="btn btn-info pull-right"><i class="fa fa-plus-circle"></i> <b><?php echo lang('buttons:create') ?></b></a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="row">
		<table id="dt-reservations" class="table" width="100%">
			<thead>
				<tr>
					<th>Teknik Pemeriksaan</th>
					<th>Aktif</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		$.fn.extend({
				DataTable_reservations: function(){
						var _this = this;
						
						var _datatable = _this.DataTable( {
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							order: [[1, 'DESC']],
							searching: true,
							info: true,
							ajax: {
									url: "<?php echo base_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function( params ){
										params.date_from = $("#date_from").val();	
										params.date_till = $("#date_till").val();	
									}
								},
							fnDrawCallback: function( settings ){ $( window ).trigger( "resize" ); },
							columns: [
									{ 
										data: "TeknikPemeriksaan", 
										//className: "text-right",
										render: function ( val, type, row ){
												return "<strong class=\"text-primary\">" + val + "</strong>"
											}
									},

									{ data: "Aktif",
									  render: function ( val, type, row ){
										  if( 1 == val ){ 
										  return "<span class=\"label label-success\"><?php echo 'Aktif' ?></span>"
										  } else if( 0 == val ){ 
										  return "<span class=\"label label-warning\"><?php echo 'Nonaktif' ?></span>"
										  }
									  }
									},
									{ 
										data: "TeknikPemeriksaan",
										className: "",
										orderable: false,
										width: "130px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/edit") ?>/" + val + "\" data-toggle=\"form-ajax-modal\" title=\"<?php echo lang( "buttons:edit" ) ?>\" class=\"btn btn-default btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang( "buttons:edit" ) ?> </a>";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/delete") ?>/" + val + "\" data-toggle=\"ajax-modal\" title=\"<?php echo lang( "buttons:delete" ) ?>\" class=\"btn btn-danger btn-xs\"> <i class=\"fa fa-times\"></i> </a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						} );
						
					$( "#dt-reservations_length select, #dt-reservations_filter input" )
						.addClass( "form-control" );
					
					return _this
				}
			});
		
		$( document ).ready(function(e) {
            	$( "#dt-reservations" ).DataTable_reservations();
			});
	})( jQuery );
//]]>
</script>