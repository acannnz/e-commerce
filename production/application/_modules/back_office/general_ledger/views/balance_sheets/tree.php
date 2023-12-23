<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
 .vakata-context { z-index: 1000; }
 .jstree-default .jstree-anchor { width:75%}
</style>
<?php echo form_open( base_url("general-ledger/balance-sheet/export") )?>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('balance_sheets:page'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="col-md-3"><?php echo lang('balance_sheets:period') ?> <span class="text-danger">*</span></label>
					<div class="col-md-3">
						<input type="text" id="date" name="date" class="datepicker form-control" value="<?php echo date("Y-m") ?>" data-date-min-date="<?php echo config_item("Tanggal Mulai System") ?>" data-date-format="YYYY-MM"  required="required"/>
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
			<div class="col-md-6">
				<div class="form-group">
					<div class="input-group">
						<input type="text" id="activa_summary" name="activa" value="0" class="form-control text-danger text-right" />
						<span class="input-group-addon"><?php echo lang('balance_sheets:activa_label')?></span>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><?php echo lang('balance_sheets:pasiva_label')?></span>
						<input type="text" id="pasiva_summary" name="pasiva" value="0" class="form-control text-danger" />
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<input type="text" id="balance" name="balance" value="0" class="form-control text-center text-danger" />
				</div>
			</div>
		</div>
		<hr />
		<div class="row form-group">
			<div class="col-md-6">
				<h2 class="text-center"><?php echo strtoupper(lang('balance_sheets:activa_label')); ?></h2>
				<div id="activa-tree">
				</div>
			</div>
			<div class="col-md-6">
				<h2 class="text-center"><?php echo strtoupper(lang('balance_sheets:pasiva_label')); ?></h2>
				<div id="pasiva-tree">
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
							url: "<?php echo base_url("general-ledger/balance-sheet/get_summary") ?>",
							dataType: "json",       
							data: { date: $("#date").val() },
							success: function(response) {

								$("#activa_summary").val( response.activa );								
								$("#pasiva_summary").val( response.pasiva );								
								$("#balance").val( response.balance );								
							}
					});
				}
			}

		var _jsTree;
		var _jsTreeActions = {
				refresh: function(){
					var timer = 0;
					$("#tree_refresh").on("click", function(e){
	
						if (timer) {
							clearTimeout(timer);
						}
						
						timer = setTimeout( _summaryActions.refresh(), 500); 
						timer = setTimeout( $("#activa-tree").jstree(true).refresh(), 500); 
						timer = setTimeout( $("#pasiva-tree").jstree(true).refresh(), 500); 
					});
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
				jsTreeActiva: function(){
						var _this = this;
						
						var _jsTree = _this.jstree({
								core : {
									data : {
										url : '<?php echo $activa_tree_collection ?>',
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
					
					return _this
				},

				jsTreePasiva: function(){
						var _this = this;
						
						var _jsTree = _this.jstree({
								core : {
									data : {
										url : '<?php echo $pasiva_tree_collection ?>',
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
					
					return _this
				}				

			});
						
		$( document ).ready(function(e) {
				_jsTreeActions.refresh();
				$('#activa-tree').jsTreeActiva();		
				$('#pasiva-tree').jsTreePasiva();		
						
			});
			
	})( jQuery );
//]]>
</script>