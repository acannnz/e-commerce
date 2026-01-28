<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="col-md-offset-1 col-md-10">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('cash_flow:account_heading'); ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<a href="javascript:;" class="js-btn-refresh btn btn-block btn-info"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></a>
				</div>
				<div class="col-md-6">
					<a href="javascript:;" class="js-btn-save btn btn-block btn-success"><b><i class="fa fa-save"></i> <?php echo lang("buttons:save")?></b></a>
				</div>
			</div>
			<div class="row">
				<div class="table-responsive">
					<table id="dt-table" class="table table-sm" width="100%">
						<thead>
							<tr>
								<th><?php echo lang("cash_flow:account_number_label") ?></th>
								<th><?php echo lang("cash_flow:account_name_label") ?></th>
								<th><?php echo lang("cash_flow:debt_label") ?></th>
								<th><?php echo lang("cash_flow:credit_label") ?></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<a href="javascript:;" class="js-btn-refresh btn btn-block btn-info"><b><i class="fa fa-refresh"></i> <?php echo lang("buttons:refresh")?></b></a>
				</div>
				<div class="col-md-6">
					<a href="javascript:;" class="js-btn-save btn btn-block btn-success"><b><i class="fa fa-save"></i> <?php echo lang("buttons:save")?></b></a>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _option_subgroup = '';
		<?php foreach($option_subgroup as $k => $v ):?>
			_option_subgroup += '<option value="<?php echo $k?>"><?php echo $v?></option>\n'
		<?php endforeach;?>;
		var _datatable;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
							case 2:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">"+ _option_subgroup +"</select>" );
									_input.val(data.D);								
								
								this.empty().append( _input );		
								
								_input.focus();						
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.D = _input.val() || '';
											data.D_Name = $( e.target ).find( "option:selected" ).html() || '';
											
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
							break;
							case 3:
								var _input = $( "<select style=\"width:100%\" class=\"form-control\">"+ _option_subgroup +"</select>" );
									_input.val(data.K);								
								
								this.empty().append( _input );								
								
								_input.focus();
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											_datatable.row( row ).data( data ).draw();
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();																				
										try{
											var _selected = $( e.target ).find( "option:selected" ).data() || {};
											data.K = _input.val() || '';
											data.K_Name = $( e.target ).find( "option:selected" ).html() || '';
											
											_datatable.row( row ).data( data ).draw();
										} catch(ex){console.log(ex);}
									});
							break;
							
						}
					},	
			};
		
		$.fn.extend({
				dtTableInit: function(){
						var _this = this;						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								paging: false,
								ordering: false,
								searching: false,
								info: false,
								autoWidth: true,
								ajax: {
										url: "<?php echo base_url("general-ledger/cash-flow/datatable_account_collection") ?>",
										type: "POST",
										data: function( params ){},
									},
								columns:[{ 
											data: "Akun_No",
											render: function( val, type, row, meta ){ 
												return "<b>"+ val +"</b>";
											} 
										},
										{ data: "Akun_Name"},
										{ data: "D_Name",},
										{ data: "K_Name"},
									],
								createdRow: function ( row, data, index ){
										if(data.Induk == 1)
										{
											switch(data.Level_Ke)
											{
												case 1:
													$(row).addClass('danger');
													break;
												case 2:
													$(row).addClass('info');
													break;
												case 3:
													$(row).addClass('success');
													break;
												case 4:
													$(row).addClass('warning');
													break;
											}	
										}
										
										$( row ).on( "click", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											if(data.Induk == 0)
												_datatable_actions.edit.call( elem, row, data, index );	
										});	
									},
								drawCallback: function( settings ) {
										dev_layout_alpha_content.init(dev_layout_alpha_settings);
									}
							});
							
						$( "#dt-table-activa_length select, #dt-table-activa_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		
		$( document ).ready(function(e) {
				$("#dt-table").dtTableInit();
				$(".js-btn-refresh").on("click", function(){
					$.post('<?php echo base_url("general-ledger/cash-flow/validate_account")?>', function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$("#dt-table").DataTable().ajax.reload();
					})	
				});
			
				$(".js-btn-save").on("click", function(e){
					e.preventDefault();	
					_return = false;
					_account_warning = {};
					
					var data_post = { 
							"detail": [{ }]
						};

					var _table = $( "#dt-table" ).DataTable().rows().data();
					_table.each(function (value, index) {
						if( value.Induk == 1 ) return false;
						if( _return == false && (value.K == '' || value.D == '') )
						{
							_return = true;
							_account_warning = value.Akun_No +" --> "+ value.Akun_Name;
							return false;
						}
						
						var _debit = {
							"Akun_id": value.Akun_ID,
							"Normal_Pos": 'D',
							"GroupII_id": value.D,
						}
						var _credit = {
							"Akun_id": value.Akun_ID,
							"Normal_Pos": 'K',
							"GroupII_id": value.K,
						}						
						data_post.detail.push(_debit);
						data_post.detail.push(_credit);
					});

					if ( _return )
					{
						message = "Rekening "+ _account_warning +" Belum di Setup";
						$.alert_error( message );
						return false;
					}
					
					$.post('<?php echo current_url() ?>', data_post, function( response, status, xhr ){
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
						$("#dt-table").DataTable().ajax.reload();						
					})	
				});
		
			});
	})( jQuery );
//]]>
</script>