<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
 .vakata-context { z-index: 1000; }
 .jstree-table-midwrapper {
 display: inline-flex;
}
</style>

<?php echo form_open( base_url("general-ledger/income-loss/export"), ['class' => 'form-horizantal'])?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('income_loss:page'). ' Triwulan'; ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?php echo lang('income_loss:quarterly') ?> <span class="text-danger">*</span></label>
							<div class="col-md-12">
								<select id="quarterly" name="quarterly" class="form-control" required>
									<option value="1" data-month1="<?php echo lang('january')?>" data-month2="<?php echo lang('february')?>" data-month3="<?php echo lang('march')?>">TRIWULAN I</option>
									<option value="2" data-month1="<?php echo lang('april')?>" data-month2="<?php echo lang('may')?>" data-month3="<?php echo lang('june')?>">TRIWULAN II</option>
									<option value="3" data-month1="<?php echo lang('july')?>" data-month2="<?php echo lang('august')?>" data-month3="<?php echo lang('september')?>">TRIWULAN III</option>
									<option value="4" data-month1="<?php echo lang('october')?>" data-month2="<?php echo lang('november')?>" data-month3="<?php echo lang('december')?>">TRIWULAN IV</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label"><?php echo lang('income_loss:period') ?> <span class="text-danger">*</span></label>
							<div class="col-md-12">
								<input type="text" id="year" name="year" class="datepicker form-control" value="<?php echo date("Y") ?>" data-date-format="YYYY" required="required"/>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="control-label">&nbsp;</label>
							<div class="col-md-12">
								<a href="javascitp:;" id="tree_refresh" class="btn btn-success"><i class="fa fa-refresh fa-lg"></i> <b><?php echo lang('buttons:refresh')?></b></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php /*?><div class="col-md-4">
				<div class="form-group">
					<div class="col-md-12 text-right">
						<button type="submit" formtarget="_blank" class="btn btn-primary"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print")?></b></button>
					</div>
				</div>
			</div><?php */?>
		</div>
		
		<!-- TAB -->
		<ul id="tab" class="nav nav-tabs nav-justified">
			<li class="active"><a href="#tab-detail" data-toggle="tab"><i class="fa fa-sitemap"></i> Details</a></li>
			<li><a href="#tab-summary" data-toggle="tab"><i class="fa fa-sort-numeric-asc"></i> Summary</a></li>
		</ul>
		<div class="tab-content">
			<div id="tab-detail" class="tab-pane tab-pane-padding active">
				<div class="row">
					<div class="col-md-12">
						<div id="income-loss-tree" class="col-md-12 row"></div>
					</div>
				</div>
			</div>
			<div id="tab-summary" class="tab-pane tab-pane-padding">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<div class="col-md-6">&nbsp;</div>
							<div class="col-md-2"><h4 class="header-month1 text-right"><b><span></span></b></h4></div>
							<div class="col-md-2"><h4 class="header-month2 text-right"><b><span></span></b></h4></div>
							<div class="col-md-2"><h4 class="header-month3 text-right"><b><span></span></b></h4></div>
						</div>	  
						<div class="form-group">
							<div class="col-md-6"><b>Total Pendapatan</b></div>
							<div class="col-md-2"><h4><b id="income1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="income2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="income3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6"><b>Total HPP</b></div>
							<div class="col-md-2"><h4><b id="hpp1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="hpp2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="hpp3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6">&nbsp;</div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6 text-danger"><b>GROSS PROFIT</b></div>
							<div class="col-md-2"><h4><b id="gross_profit1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="gross_profit2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="gross_profit3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6"><b>Biaya Operasional</b></div>
							<div class="col-md-2"><h4><b id="operating_cost1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="operating_cost2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="operating_cost3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6">&nbsp;</div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6 text-danger"><b>EBITDA</b></div>
							<div class="col-md-2"><h4><b id="ebitda1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="ebitda2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="ebitda3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6"><b>PEND/BIAYA NON OPERASIONAL</b></div>
							<div class="col-md-2"><h4><b id="non_operating_cost1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="non_operating_cost2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="non_operating_cost3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6"><b>Penyusutan, Amortasi &amp; cadangan</b></div>
							<div class="col-md-2"><h4><b id="pac1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="pac2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="pac3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6">&nbsp;</div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6 text-danger"><b>EBIT</b></div>
							<div class="col-md-2"><h4><b id="ebit1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="ebit2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="ebit3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6"><b>BUNGA DAN PAJAK</b></div>
							<div class="col-md-2"><h4><b id="interest_taxes1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="interest_taxes2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="interest_taxes3" class="pull-right"></b></h4></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6">&nbsp;</div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
							<div class="col-md-2" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
						</div>	      	
						<div class="form-group">
							<div class="col-md-6 text-danger"><b>EAT</b></div>
							<div class="col-md-2"><h4><b id="eat1" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="eat2" class="pull-right"></b></h4></div>
							<div class="col-md-2"><h4><b id="eat3" class="pull-right"></b></h4></div>
						</div>	      	
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
		
		var _summaryActions = {
				refresh: function(){
					$.ajax({
							type: "POST",  
							url: "<?php echo base_url("general_ledger/income_loss/get_quarterly_summary") ?>",
							dataType: "json",       
							data: { 'quarterly': $("#quarterly").val(), 'year': $("#year").val() },
							success: function(response) {
								var _count = 1;
								$.each(response, function(i, v){
									$("#income"+ _count).html(v.income);
									$("#hpp"+ _count).html(v.hpp);
									
									$("#gross_profit"+ _count).html(v.gross_profit);
									$("#operating_cost"+ _count).html(v.operating_cost);
									
									$("#ebitda"+ _count).html(v.ebitda);
									$("#non_operating_cost"+ _count).html(v.non_operating_cost);
									$("#pac"+ _count).html(v.pac);
									
									$("#ebit"+ _count).html(v.ebit);
									$("#interest_taxes"+ _count).html(v.interest_taxes);
									
									$("#eat"+ _count).html(v.eat);
									
									_count += 1;
								});
								
							}
					});
				}
			}
		var _jsTree;
		var _jsTreeActions = {
				refresh: function(){
					$("#income-loss-tree").jstree(true).refresh();
				},
				contextMenu: function( node ){
					var contextMenu;
					switch (node.original.type){
						//case "root": contextMenu = _jsTreeActions.menuRoot( node ); break;
						//case "header": contextMenu = _jsTreeActions.menuHeader( node ); break;
						case "child": contextMenu = _jsTreeActions.menuChild( node ); break;
					}
					return contextMenu;
				},
				menuRoot: function( node ){
					return {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:create")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$create_url ?>/'+ node.original.component);
							},
						},
						AddChild: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:add_child")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$add_child_url ?>/'+ node.original.Akun_ID);
							}
						},
					}
				},
				menuHeader: function( node ){
					return {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:create")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$create_url ?>/'+ node.original.parent_id);
							},
						},
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:edit")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$edit_url ?>/'+ node.original.Akun_ID);
							}
						},                         
						AddChild: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:add_child")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$add_child_url ?>/'+ node.original.Akun_ID);
							}
						},
					}				
				},
				menuChild: function( node ){
					return {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:create")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$create_url ?>/'+ node.original.parent_id);
							},
						},
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:edit")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo @$edit_url ?>/'+ node.original.Akun_ID);
							}
						}
					}
				}
			}
			
		$.fn.extend({
				jsTree: function(){
						var _this = this;
						
						var _jsTree = _this.jstree({
								plugins : ["table", "contextmenu", "dnd", "search","state", "types"],
								// configure tree table
								table: {
									columns: [
								  		{width: 500, header: "Rekening"},
								  		{width: 100, value: "Nilai", header: "<span>Bulan</span>", columnClass: 'text-right header-month1'}, // format: function(v) {if (v){ return '$'+v.toFixed(2) }}},
								  		{width: 100, value: "Nilai2", header: "<span>Bulan</span>", columnClass: 'text-right header-month2'},
								  		{width: 100, value: "Nilai3", header: "<span>Bulan</span>", columnClass: 'text-right header-month3'}
									],
									resizable: true,
									draggable: false,
									contextmenu: false,
									width: '99%',
								},
								core : {
									data : {
										url : '<?php echo $tree_collection ?>',
										dataType: 'JSON',
										type: 'POST',
										data : function (node) {
											return {
												'quarterly': $("#quarterly").val(),
												'year': $("#year").val()
											};
										}
									},
									check_callback : true,
									themes : {
										//responsive : true,
										stripes : true,
										dots : true
									}
								},
								types: {
									root: { icon : "" },
									folder: { icon : "fa fa-folder-o" },
									file: { icon : "fa fa-file-o" },
								},
								force_text: true,
								contextmenu :{         
									items : function($node) {
										return _jsTreeActions.contextMenu( $node );
									}
								}
			
							}).on('open_node.jstree', function (e, data) { 
								//if (data.node.parent == "#") return false;
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							}).on('close_node.jstree', function (e, data) { 							
								//if (data.node.parent == "#") return false;
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							}).bind("hover_node.jstree ", function (e, data) {
								dev_layout_alpha_content.init(dev_layout_alpha_settings);
							});
							/*.bind("dblclick.jstree", function (event) {
							   var node = $(event.target).closest("li");
							   var id = node.attr('id');
							   form_ajax_modal.show("<?php //echo @$url ?>/edit/"+ id)

							})*/;
							
							var timer = 0;
							$("#tree_refresh").on("click", function(e){

								if (timer) {
									clearTimeout(timer);
								}
								var _option = $("#quarterly option:selected").data();
								
								// Get HTML								
								$(".header-month1").find('span').text( _option.month1 );
								$(".header-month2").find('span').text( _option.month2 );
								$(".header-month3").find('span').text( _option.month3 );
								
								timer = setTimeout( _summaryActions.refresh(), 500); 
								timer = setTimeout( _jsTreeActions.refresh(), 500); 
							});
							
					
					return _this
				}
			});
			
		$( document ).ready(function(e) {
				$('#income-loss-tree').jsTree();		
						
			});
	})( jQuery );
//]]>
</script>