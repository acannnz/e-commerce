<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('heading:clerk') ?></h3>
		<ul class="panel-btn">
			<?php if(!empty($this->session->userdata('KodeClerk'))): ?>
			<li><a href="<?php echo base_url("clerk/end") ?>" class="btn btn-info" title="<?php echo lang('label:clerk_end')?>"><b><i class="fa fa-dot-circle-o fa-spin"></i> <?php echo lang('label:clerk_end')?></a></b></li>
			<?php else: ?>
			<li><a href="<?php echo base_url("clerk/start") ?>" class="btn btn-info" title="<?php echo lang('label:clerk_start')?>"><b><i class="fa fa-dot-circle-o fa-spin"></i> <?php echo lang('label:clerk_start')?></a></b></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="panel-body">
		<table id="dt_clerk" class="table table-bordered table-hover" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('label:code') ?></th>
					<th><?php echo lang('label:date') ?></th>
					<th><?php echo lang('label:name') ?></th>
					<th><?php echo lang('label:qty_sales') ?></th>
					<th><?php echo 'Uang Awal' ?></th>
					<th><?php echo lang('label:amount_system') ?></th>
					<th><?php echo lang('label:amount_clerk') ?></th>
					<th><?php echo lang('label:amount_diff') ?></th>
					<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
				</tr>
			</thead>        
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script>
(function( $ ){
	
		$.fn.extend({
				DataTableInit: function(){
					
						var _this = this;
						//function code for custom search
						var _datatable = _this.DataTable( {		
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 10, 25, 50, 75],
							order: [[1, 'desc']],
							searching: true,
							info: true,
							responsive: true,
							ajax: {
									url: "<?php echo site_url("{$nameroutes}/datatable_collection") ?>",
									type: "POST",
									data: function(params){
									}
								},
							columns: [
									{ 
										data: 'KodeClerk',
										className: 'text-center',
										render: function ( val, type, row ) {
											return "<b>"+ val +"</b>";
										  }
									},
									{
										data: 'WaktuMulaiClerk',
										className: 'text-center',
										width: "180px",
										render: function( val, type, row ){
											if(row.WaktuAkhirClerk == null)
											return row.WaktuMulaiClerk.substr(0,19);
											
											return row.WaktuMulaiClerk.substr(0,19) +'<br/> s/d <br/> '+ row.WaktuAkhirClerk.substr(0,19)
										}
									},
									{data: 'Nama_Singkat'},
									{
										data: 'JumlahTransaksi',
										className: 'text-right',
									},
									{
										data: 'JumlahAwalUangKasir',
										className: 'text-right',
										render: function( val ){
											return mask_number.currency_add(val);
										}
									},
									{
										data: 'JumlahTotalSystem',
										className: 'text-right',
										render: function( val, type, row ){
											var _val = Number(val) + Number(row.JumlahAwalUangKasir);
											return mask_number.currency_add(_val);
										}
									},
									{
										data: 'JumlahTotalClerk',
										className: 'text-right',
										render: function( val, type, row ){
											
											return mask_number.currency_add(val);
										}
									},
									{
										data: 'JumlahTotalSelisih',
										className: 'text-right text-danger',
										render: function( val ){
											return mask_number.currency_add(val);
										}
									},
									{ 
										data: 'KodeClerk',
										className: "text-center",
										orderable: false,
										width: "140px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
														if(row.StatusClerk == 0){
															buttons += '<button class="btn btn-danger btn-sm" type="button"><?php echo 'start' ?> </button>';
														} else {
															buttons += '<button class="btn btn-success btn-sm" type="button"><?php echo 'end' ?> </button>';
														}	
														buttons += '<button data-toggle="dropdown" class="btn btn-danger dropdown-toggle btn-sm" type="button" aria-expanded="true"><span class="caret"></span></button>';
														buttons += '<ul role="menu" class="dropdown-menu">';
															if(row.editable == 1)
															buttons += "<li><a href=\"<?php echo base_url("{$nameroutes}/end") ?>/" + val + "\" title=\"<?php echo lang('buttons:view') ?>\" class=\"\"> <i class=\"fa fa-file-text-o\"></i> <?php echo lang('buttons:view') ?> </a></li>";
														buttons += '</ul>';
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						});
					
					$( "#dt_clerk_length select, #dt_clerk_filter input" )
						.addClass( "form-control" );
							
					return _this;
				}

			});
							
		$( document ).ready(function(e) {
					
				$("#dt_clerk").DataTableInit();
				
			});
	})( jQuery );
</script>
