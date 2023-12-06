<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
 .vakata-context { z-index: 1000; }
</style>
<div class="row">
	<div class="col-md-6">
    	<h2><?php echo lang('accounts:tree_heading'); ?></h2>
    </div>
	<div class="col-md-6">
    	<div class="col-md-9">
			<input type="text" class="search-input form-control" placeholder="<?php echo lang('buttons:search') ?>" />
        </div>
    	<div class="col-md-3">
    		<button id="tree_refresh" class="btn btn-info pull-right"><i class="fa fa-refresh fa-lg"></i> <b><?php echo lang('buttons:refresh')?></b></button>
		</div>
    </div>
</div>

<div class="row" style="padding: 20px 0px!important;overflow: hidden;">
	<div class="col-md-offset-1 col-md-11">
    	<div id="account_tree"></div>
   </div>
</div>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _jsTree;
		var _jsTreeActions = {
				refresh: function(){
					$("#account_tree").jstree(true).refresh();
				},
				search: function( searchString ){
					$('#account_tree').jstree( true ).search( searchString );
				},
				contextMenu: function( node ){
					var contextMenu;
					switch (node.original.type){
						case "root": contextMenu = _jsTreeActions.menuRoot( node ); break;
						case "header": contextMenu = _jsTreeActions.menuHeader( node ); break;
						case "child": contextMenu = _jsTreeActions.menuChild( node ); break;
					}
					return contextMenu;
				},
				menuRoot: function( node ){
					
					if( node.original.Akun_ID == 0 ){
						return {
							Create: {
								separator_before: false,
								separator_after: false,
								label: "<?php echo lang("buttons:create")?>",
								action: function (obj) { 
									form_ajax_modal.show('<?php echo $create_url ?>/0');
								},
							},
						}
					}
					
					var _menu =  {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:create")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $create_url ?>/0');//+ node.original.Akun_ID);
							},
						},
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:edit")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $edit_url ?>/'+ node.original.Akun_ID);
							}
						},     
						AddChild: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:add_child")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $add_child_url ?>/'+ node.original.Akun_ID);
							}
						},
					}
					
					// jika root tidak memiliki child, maka bisa dihapus
					if( $("#account_tree").jstree().get_node( node ).children.length === 0 ){
						_menu['Delete'] = {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:delete")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $delete_url ?>/'+ node.original.Akun_ID);
							}
						}
					}
					
					return _menu;
				},
				menuHeader: function( node ){
					return {
						Create: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:create")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $create_url ?>/'+ node.original.parent_id);
							},
						},
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:edit")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $edit_url ?>/'+ node.original.Akun_ID);
							}
						},                         
						AddChild: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:add_child")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $add_child_url ?>/'+ node.original.Akun_ID);
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
								form_ajax_modal.show('<?php echo $create_url ?>/'+ node.original.parent_id);
							},
						},
						Edit: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:edit")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $edit_url ?>/'+ node.original.Akun_ID);
							}
						},                         
						AddChild: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:add_child")?>",
							action: function (obj) { 
								if (node.original.add_child)
								{
									form_ajax_modal.show('<?php echo $add_child_url ?>/'+ node.original.Akun_ID);
								} else {
									$.alert_error("<?php echo lang('accounts:cannot_add_child')?>");
								}
							}
						},
						Delete: {
							separator_before: false,
							separator_after: false,
							label: "<?php echo lang("buttons:delete")?>",
							action: function (obj) { 
								form_ajax_modal.show('<?php echo $delete_url ?>/'+ node.original.Akun_ID);
							}
						},
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
							}).bind("dblclick.jstree", function (e, data) {
								var data = $(_this).jstree().get_selected(true);
								console.log(data);
							   	form_ajax_modal.show("<?php echo @$edit_url ?>/"+ data[0]['original']['Akun_ID'] );
							});
							
							$("#tree_refresh").on("click", function(e){
								_jsTreeActions.refresh()
							});
							
							var timer = 0;
							$(".search-input").on("keyup", function(e) {
								e.preventDefault();				
								
								if (timer) {
									clearTimeout(timer);
								}
								
								var searchString = $(this).val();
								timer = setTimeout( _jsTreeActions.search( searchString ), 500); 
								
							});

					
					return _this
				}
			});
					
		
		$( document ).ready(function(e) {
						
			$('#account_tree').jsTree();								          

			});
	})( jQuery );
//]]>
</script>