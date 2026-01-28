<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
 .vakata-context { z-index: 1000; }
 .jstree-default .jstree-anchor { width:85%}
</style>

<?php echo form_open( base_url("general-ledger/income-loss/export") )?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('income_loss:page') . " Tahunan";  ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3"><?php echo lang('income_loss:period') ?> <span class="text-danger">*</span></label>
					<div class="col-md-3">
						<input type="hidden" name="annual" value="1"/>
						<input type="text" id="date" name="date" class="datepicker form-control" value="<?php echo date("Y") ?>" data-date-format="YYYY" required="required"/>
					</div>
					<div class="col-md-3">
						<a href="javascitp:;" id="tree_refresh" class="btn btn-success"><i class="fa fa-refresh fa-lg"></i> <b><?php echo lang('buttons:refresh')?></b></a>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<div class="col-md-12 text-right">
						<button type="submit" formtarget="_blank" class="btn btn-primary"><b><i class="fa fa-print"></i> <?php echo lang("buttons:print")?></b></button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-8">
				<h3><?php echo lang('beginning_balances:tree_heading'); ?> Details</h3>
				<div id="income-loss-tree" class="col-md-12 row">
				</div>
			</div>
			<div class="col-md-4">
				<h3 class="text-center">Sumary</h3>
				<div class="form-group">
					<div class="col-md-7"><b>Total Pendapatan</b></div>
					<div class="col-md-5"><h4><b id="income" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7"><b>Total HPP</b></div>
					<div class="col-md-5"><h4><b id="hpp" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7">&nbsp;</div>
					<div class="col-md-5" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7 text-danger"><b>GROSS PROFIT</b></div>
					<div class="col-md-5"><h4><b id="gross_profit" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7"><b>Biaya Operasional</b></div>
					<div class="col-md-5"><h4><b id="operating_cost" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7">&nbsp;</div>
					<div class="col-md-5" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7 text-danger"><b>EBITDA</b></div>
					<div class="col-md-5"><h4><b id="ebitda" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7"><b>PEND/BIAYA NON OPERASIONAL</b></div>
					<div class="col-md-5"><h4><b id="non_operating_cost" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7"><b>Penyusutan, Amortasi &amp; cadangan</b></div>
					<div class="col-md-5"><h4><b id="pac" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7">&nbsp;</div>
					<div class="col-md-5" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7 text-danger"><b>EBIT</b></div>
					<div class="col-md-5"><h4><b id="ebit" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7"><b>BUNGA DAN PAJAK</b></div>
					<div class="col-md-5"><h4><b id="interest_taxes" class="pull-right"></b></h4></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7">&nbsp;</div>
					<div class="col-md-5" style="border-bottom:solid 1px #000000;"><span class="pull-right">( - )</span></div>
				</div>	      	
				<div class="form-group">
					<div class="col-md-7 text-danger"><b>EAT</b></div>
					<div class="col-md-5"><h4><b id="eat" class="pull-right"></b></h4></div>
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
							url: "<?php echo base_url("general_ledger/income_loss/get_annual_summary") ?>",
							dataType: "json",       
							data: { date: $("#date").val() },
							success: function(response) {

								$("#income").html(response.income);
								$("#hpp").html(response.hpp);
								
								$("#gross_profit").html(response.gross_profit);
								$("#operating_cost").html(response.operating_cost);
								
								$("#ebitda").html(response.ebitda);
								$("#non_operating_cost").html(response.non_operating_cost);
								$("#pac").html(response.pac);
								
								$("#ebit").html(response.ebit);
								$("#interest_taxes").html(response.interest_taxes);
								
								$("#eat").html(response.eat);								
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
								core : {
									data : {
										url : '<?php echo $tree_collection ?>',
										dataType: 'JSON',
										type: 'POST',
										data : function (node) {
											return {
												'date': $("#date").val()
											};
										}
									},
									check_callback : true,
									themes : {
										responsive : true,
										stripes : true,
										dots : true
									}
								},
								types: {
									folder: { icon : "fa fa-folder-o" },
									file: { icon : "fa fa-file-o" },
								},
								force_text: true,
								plugins : ["contextmenu", "dnd", "search","state", "types", ],
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