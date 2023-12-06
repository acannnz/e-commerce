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
				<div class="row">
					<div class="col-md-6">
		                <h3 class="panel-title"><?php echo lang('heading:family_list'); ?></h3>
					</div>
					<div class="col-md-6">
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
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<table id="dt_family" class="table table-bordered table-hover" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:no_family') ?></th>
										<th><?php echo lang('label:no_kk') ?></th>
										<th><?php echo lang('label:patriarch') ?></th>
										<th><?php echo lang('label:address') ?></th>
										<th><?php echo lang('label:note') ?></th>
										<th><?php echo lang('global:status') ?></th>
										<th style="width:65px;text-align:center;"><i class="fa fa-cog"></i></th>
									</tr>
								</thead>        
								<tbody>
								</tbody>
								<tfoot class="dtFilter">
									<tr>
										<th style="min-width:30px;width:30px;text-align:center;">
											<?php echo form_checkbox([
													'name' => 'check',
													'checked' => FALSE,
													'class' => 'checkbox checkth'
												]); ?>
										</th>
										<th><?php echo lang('label:no_family') ?></th>
										<th><?php echo lang('label:no_kk') ?></th>
										<th><?php echo lang('label:patriarch') ?></th>
										<th><?php echo lang('label:address') ?></th>
										<th><?php echo lang('label:note') ?></th>
										<th><?php echo lang('global:status') ?></th>
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
	
		$.fn.extend({
				DataTableInit: function(){
					
						var _this = this;
						//function code for custom search
						var _datatable = _this.DataTable( {		
							processing: true,
							serverSide: true,								
							paginate: true,
							ordering: true,
							lengthMenu: [ 25, 50, 75],
							order: [[1, 'ASC']],
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
									{orderable: false, searchable: false, render: checkbox},
									{ 
										data: 'NoFamily',
										className: 'text-center',
										render: function ( val, type, row ) {
											return "<b>"+ val +"</b>";
										  }
									},
									{
										data: 'NoKK',
										className: 'text-center',
									},
									{data: 'PersonalName'},
									{data: 'Address'},
									{data: 'Note'},							
									{
										data: 'Status',
										className: 'text-center',
										render: function( val ){
											return val = 1 ? '<?php echo lang('global:active') ?>' : '<?php echo lang('global:inactive') ?>';
										}
									},							
									{ 
										data: 'Id',
										className: "",
										orderable: false,
										width: "120px",
										render: function ( val, type, row ){
												var buttons = "<div class=\"btn-group pull-right\" role=\"group\">";
													buttons += "<a href=\"<?php echo base_url("{$nameroutes}/update") ?>/" + val + "\" title=\"<?php echo lang('buttons:edit') ?>\" class=\"btn btn-info btn-xs\"> <i class=\"fa fa-pencil\"></i> <?php echo lang('buttons:edit') ?> </a>";
													buttons += "<a href=\"javascript:;\" title=\"<?php echo lang('buttons:detail_family_personals') ?>\" class=\"btn btn-success btn-xs js-personal-expand\"> <i class=\"fa fa-expand\"></i></a>";
												buttons += "</div>";
												
												return buttons
											}
									}
								]
						});
						
						var _detail_rows = [];
							
						_this.find( 'tbody' ).on( 'click', 'tr td a.js-personal-expand', function(e){
								var _tr = $( this ).closest( 'tr' );
								var _rw = _datatable.row( _tr );
								
								var _dt = _rw.data();
								var _ids = $.inArray( _tr.attr( 'id' ), _detail_rows );
						 
								if( _rw.child.isShown() ){
									
									$(this).find('i').addClass( 'fa-expand' );
									$(this).find('i').removeClass( 'fa-compress' );
																		
									_rw.child.hide();
						 
									// Remove from the 'open' array
									_detail_rows.splice( _ids, 1 );
								} else {
									
									$(this).find('i').removeClass( 'fa-expand' );
									$(this).find('i').addClass( 'fa-compress' );									
									
									if( _rw.child() == undefined ){
										var _details = $( "<div class=\"details-loader\"></div>" );
										_rw.child( _details ).show();
										_details.html( "<span class=\"text-loader\"><?php echo lang("global:ajax_loading") ?></span>" );
										_details.load( "<?php echo base_url("{$nameroutes}/family_personal") ?>", {"id": _dt.Id}, function( response, status, xhr ){
												$( window ).trigger( "resize" );
											});
									} else {
										_rw.child.show();
									}
									
									// Add to the 'open' array
									if( _ids === -1 ){
										_detail_rows.push( _tr.attr( 'id' ) );
									}
								}
								
								$( window ).trigger( "resize" );
							});
						
						// On each draw, loop over the `_detail_rows` array and show any child rows
						_datatable.on('draw', function (){
								$.each(_detail_rows, function ( i, id ){
										$( '#' + id + ' td a.js-personal-expand').trigger( 'click' );
									});
							});
							
					return _this;
				}

			});
							
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_crud__list"]');
				_form.find("button[name=\"btn_search\"]").on("click", function(e){
						$( "#dt_family" ).DataTable().ajax.reload();
				});					
					
				$("#dt_family").DataTableInit();
				
				$('.panel-bars .btn-bars .dropdown-menu a[data-mass="delete"]').click(function (e) {
						e.preventDefault();
						_form.find('input[name="mass_action"]').val($(this).attr('data-mass'));
						_form.trigger('submit');
					});
			});
	})( jQuery );
</script>
