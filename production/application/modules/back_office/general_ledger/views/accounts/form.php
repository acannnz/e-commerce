<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url(), array("id" => "form-account", "name" => "form-account") ); ?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:component_label') ?> <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <select name="f[Kelompok]" id="Kelompok" data-target="#Group_ID" data-populate="groupAccount" class="form-control" <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <option value="1" <?php echo (@$item->Kelompok == 1) ? "selected" : null ?> > Neraca </option>
                    <option value="2" <?php echo (@$item->Kelompok == 2) ? "selected" : null ?> > Laba - Rugi</option> 
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:group_label') ?></label>
            <div class="col-md-8">
                <select name="f[Group_ID]" id="Group_ID" data-target="#GroupAkunDetailID" data-populate="groupAccountDetail" class="form-control" required <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <option value="0"><?php echo lang('global:select-pick') ?></option>
                    <?php if (!empty($option_group[$item->Kelompok])) : foreach($option_group[$item->Kelompok] as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->Group_ID ) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:sub_group_label') ?></label>
            <div class="col-md-8">
                <select name="f[GroupAkunDetailID]" id="GroupAkunDetailID" class="form-control" required <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <option value="0"><?php echo lang('global:select-pick') ?></option>
                    <?php if (!empty($option_group_detail[$item->Group_ID])) : foreach($option_group_detail[$item->Group_ID] as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->GroupAkunDetailID) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:normal_pos_label') ?> <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <select name="f[Normal_Pos]" class="form-control" <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <option value="D" <?php echo (@$item->Normal_Pos == 'D') ? "selected" : null ?> > Debit </option>
                    <option value="K" <?php echo (@$item->Normal_Pos == 'K') ? "selected" : null ?> > Kredit</option> 
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:level_label') ?></label>
            <div class="col-md-8">
                <select name="f[Level_Ke]" class="form-control" <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <?php for($i = 1; $i <= $max_concept->Jumlah_Level; $i++) : ?>
                    <option value="<?php echo @$i ?>" <?php echo (@$i == @$item->Level_Ke ) ? "selected" : null ?> > <?php echo @$i ?></option> 
                    <?php endfor; ?>
                </select>
            </div>
        </div>
	</div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:account_number_label')?></label>
            <div class="col-md-8">
                <input type="text" id="Akun_No" name="f[Akun_No]" value="<?php echo @$item->Akun_No ?>" class="form-control"  <?php echo @$is_trans ? 'disabled' : NULL?>/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:account_name_label')?></label>
            <div class="col-md-8">
                <input type="text" id="Akun_Name" name="f[Akun_Name]" value="<?php echo @$item->Akun_Name ?>" class="form-control" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-4 control-label"><?php echo lang('accounts:currency_label') ?></label>
            <div class="col-md-8">
                <select name="f[Currency_id]" class="form-control" required <?php echo @$is_trans ? 'disabled' : NULL?>>
                    <?php if (!empty($option_currency)) : foreach($option_currency as $k => $v) : ?>
                    <option value="<?php echo @$k ?>" <?php echo (@$k == @$item->Currency_id) ? "selected" : null ?> > <?php echo @$v ?></option> 
                    <?php endforeach; endif;?>
                </select>
            </div>
        </div>
        <div class="form-group row">
        	<div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:integration_label')?></label>
                <div class="col-md-4">
                    <label class="switch">
                        <input type="hidden" value="0" name="f[Integrasi]" />
                        <input type="checkbox" <?php if(@$item->Integrasi == 1){ echo "checked=\"checked\""; } ?> name="f[Integrasi]" value="1" <?php echo @$is_trans ? 'disabled' : NULL?>>
                        <span></span>
                    </label>
                </div>
			</div>
            <div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:integration_source_label')?></label>
                <div class="col-md-4">
                    <select name="f[SumberIntegrasi]" class="form-control" <?php echo @$is_trans ? 'disabled' : NULL?>>
                        <option value="NONE"><?php echo lang('global:select-none')?></option>
                        <option value="GC" <?php echo (@$item->SumberIntegrasi == "GC") ? "selected" : null ?> > GC </option>
                        <option value="AR" <?php echo (@$item->SumberIntegrasi == "AR") ? "selected" : null ?> > AR</option> 
                        <option value="AP" <?php echo (@$item->SumberIntegrasi == "AP") ? "selected" : null ?> > AP</option> 
                        <option value="FO" <?php echo (@$item->SumberIntegrasi == "FO") ? "selected" : null ?> > FO</option> 
                    </select>
                </div>
        	</div>
        </div>
        <div class="form-group row">
        	<div class="col-md-6">
                <label class="col-md-8 control-label"><?php echo lang('accounts:convert_permanent_label')?></label>
                <div class="col-md-4">
                    <label class="switch">
                        <input type="hidden" value="0" name="f[Convert_Permanen]" />
                        <input type="checkbox" <?php if(@$item->Convert_Permanen == 1){ echo "checked=\"checked\""; } ?> name="f[Convert_Permanen]" value="1" <?php echo @$is_trans ? 'disabled' : NULL?>>
                        <span></span>
                    </label>
                </div>
			</div>
        </div>
	</div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
    	<button type="submit" class="btn btn-primary" ><?php echo lang( 'buttons:submit' ) ?></button>
        <button type="reset" class="btn btn-warning"><?php echo lang( 'buttons:reset' ) ?></button>
        <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
(function($){
	
	var populateGroups = {
			groupAccount: JSON.parse('<?php print_r(json_encode($option_group, JSON_NUMERIC_CHECK)) ?>'),
			groupAccountDetail: JSON.parse('<?php print_r(json_encode($option_group_detail, JSON_NUMERIC_CHECK)) ?>')
		}
	
	$.fn.extend({
		option_chosen: function( _is_root = false ){
				var _this = this;
				if( !_this.size() ){return _this}
				
				var target = _this.data("target");
				var populate = _this.data("populate");
				var _target = jQuery( target );
				
				if( !_is_root && '<?php @$is_edit ?>' != '' ) {
					_target.option_populate( populate, _this.val() || 0 );
				}
				
				_this.on( "change", function(){
						if( selected = _this.val() || 0 ){
							_target.option_clear();
							_target.option_populate( populate, selected )
						}
					});
					
				return _this;						
			},
		option_populate: function( populate, key ){
				var _this = this;
				if( !_this.size() ){return _this}
				
				var populate = populateGroups[ populate ][ key ] || [];
				_this.option_option( populate );
				
				return _this;
			},
		option_option: function( populate ){
				var _this = this;
				if( !_this.size() ){return _this}
				
				if( !$.isEmptyObject(populate) ){
					_this.html("");
					
					jQuery( "<option></option>" )
						.val("0")
						.text( "<?php echo lang('global:select-none')?>" )
						.appendTo( _this );
					
					$.each(populate, function(index, value){
							var _option = jQuery( "<option></option>" );
							_option.val( index );
							_option.text( value );
							
							_this.append( _option );
						});
				} else {
					_this.html("");
					
					jQuery( "<option></option>" )
						.val("0")
						.text("<?php echo lang('global:select-empty')?>")
						.appendTo( _this );
				}
				
				return _this;
			},
		option_clear: function( ){
				var _this = this;
				if( !_this.size() ){return _this}

				_this.html("");
					
				jQuery( "<option></option>" )
					.val("0")
					.text("<?php echo lang('global:select-empty')?>")
					.appendTo( _this );
										
				var target = _this.data("target");
				if( target ){
					var _target = jQuery( target );				
					_target.option_clear();
				}
									
				return _this;
			},
	});
	
	$(document).ready(function(e) {
		$( "select#Kelompok" ).option_chosen( true );
		$( "select#Group_ID" ).option_chosen();
		
		$("#form-account").on("submit", function(e){
			e.preventDefault();
			
			/*if ( !confirm("Apakah Anda yakin akan menyimpan data ini ?"))
			{
				return false;
			}	*/	

			$.post($(this).attr("action"), $(this).serialize(), function( response, status, xhr ){
							
				var response = $.parseJSON(response);
				if( "error" == response.status ){
					$.alert_error(response.message);
					return false
				}
				
				$.alert_success( response.message );
				
				$("#account_tree").jstree(true).refresh();

				$("#form-ajax-modal").remove();				
				$("body").removeClass("modal-open");				
			});

		});
		
    });
})(jQuery);

</script>