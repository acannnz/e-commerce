<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
 .vakata-context { z-index: 1000; }
</style>
<div class="row">
	<div class="col-md-6">
    	<h2><?php echo lang('structures:page'); ?></h2>
    </div>
	<div class="col-md-6">
    	<?php /*?><input type="text" class="search-input form-control" /><?php */?>
    	<button id="tree_refresh" class="btn btn-info pull-right"><i class="fa fa-refresh fa-lg"></i> <b><?php echo lang('buttons:refresh')?></b></button>
    </div>
</div>

<div class="row">
	<div class="col-md-offset-1 col-md-11">
    	<div id="structure_tree"></div>
   </div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _jsTree;
		var _jsTreeActions = {
				refresh: function(){
					$("#structure_tree").jstree(true).refresh();
					dev_layout_alpha_content.init(dev_layout_alpha_settings);
				},
				contextMenu: function( node ){
					var contextMenu;
					switch (node.original.type){
						case "root": contextMenu = _jsTreeActions.menuRoot( node ); break;
						case "header": contextMenu = _jsTreeActions.menuHeader( node ); break;
						case "detail": contextMenu = _jsTreeActions.menuDetail( node ); break;
					}
					return contextMenu;
				},
				menuRoot: function( node ){
					return {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "Create",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $create_url ?>/'+ node.original.component);
							}
						}
					};				
				},
				menuHeader: function( node ){
					return {
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "Edit",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $edit_url ?>/'+ node.original.Group_ID);
							}
						},                         
						Delete: {
							separator_before: false,
							separator_after: false,
							label: "Delete",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $delete_url ?>/'+ node.original.Group_ID);
							}
						}
					};					},
				menuDetail: function( node ){
					return false;
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
										data : function (node) {
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
									folder: {
									  icon : "fa fa-folder-o"
									},
									file: {
									  icon : "fa fa-file-o"
									},
								},
								force_text: true,
								plugins : ["contextmenu", "dnd", "search","state", "types", ],
								contextmenu :{         
									items : function($node) {
										
										return _jsTreeActions.contextMenu( $node );
									}
								}
			
							}).on('open_node.jstree', function (e, data) { 
							
								dev_layout_alpha_content.init(dev_layout_alpha_settings);

								if (data.node.parent !== "#") data.instance.set_icon(data.node, "fa fa-folder-open-o"); 
							
							}).on('close_node.jstree', function (e, data) { 
							
								dev_layout_alpha_content.init(dev_layout_alpha_settings);

								if (data.node.parent !== "#") data.instance.set_icon(data.node, "fa fa-folder-o"); 
							
							});
							
							$("#tree_refresh").on("click", function(e){
								_jsTreeActions.refresh()
							});
					
					dev_layout_alpha_content.init(dev_layout_alpha_settings);
					
					return _this
				}
			});
					
		$( document ).ready(function(e) {		
				$("#structure_tree").jsTree();
			});
	})( jQuery );
//]]>
</script>