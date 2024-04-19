<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="modal-body">
    <div class="form-group" style="margin: 27px 0px;">
    	<p style="line-height: 100%;font-size: 14px;"><?php echo lang('confirm:posting_split')?></p>   
        <?php echo form_hidden('confirm', 1); ?>
	</div>
</div>
<div class="modal-footer">
    <?php echo form_button([
			'name' => '',
			'id' => 'btn-dismiss',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-times" aria-hidden="true"></i> ' . lang('buttons:close'),
			'class' => 'btn btn-default',
			'data-dismiss' => 'modal'
		]); ?>
	<?php echo form_button([
			'name' => '',
			'id' => 'btn-submit',
			'value' => '',
			'type' => 'button',
			'content' => '<i class="fa fa-check" aria-hidden="true"></i> ' . lang('buttons:yes'),
			'class' => 'btn btn-danger'
		]); ?>
</div>
<script type="text/javascript">
//<![CDATA[
;(function( $ ){
		$( document ).ready(function(){
				var _form = $("form#form_crud__list");
				var _selected_data = _form.find("input[name=\"val[]\"]:checked");
				
				$("#btn-submit").on("click", function(e){
					e.preventDefault();	
					
					var _selected_val = _selected_data.map(function() {
						return this.value;
					}).get();
					
					// looping untuk adding NoBukti yg berelasi.
					var _seleted_post =  [];
					$.each( _selected_val, function(i, v){
						
						var id_split = (v.indexOf('-SPLIT') !== -1) ? v.replace('-SPLIT', '') : v +'-SPLIT';

						if(_seleted_post.indexOf(v) !== -1 || _seleted_post.indexOf(id_split) !== -1) return true;
						
						check = $("#dt_trans_posting_list").DataTable().rows( function ( idx, data, node ) {
								return data.NoBukti === id_split ?	true : false;
							} ).data();
							
						_seleted_post.push(v);
						if ( check.any() ) _seleted_post.push(id_split);						
					});
					
					var data_post = {};
						data_post['confirm'] = 1;
						data_post['selected'] = _seleted_post;
					
					$.post( _form.prop('action'), data_post, function( response, status, xhr ){

						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						$('#dt_trans_posting_list').DataTable().ajax.reload();
						$('#btn-dismiss').trigger('click');
						//location.reload();
							
					});
				});
				
			});
	})( jQuery );
//]]>
</script>