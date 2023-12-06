<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
?>
<?php echo form_open($form_actions, [
		"id" => "form_crud", 
		"name" => "form_crud", 
		"role" => "form"
	]); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-body table-responsive">
            	<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:no_evidence')?></label>
							<div class="col-md-9">
								<input type="text" name="NoBukti" value="<?php echo @$item->NoBukti ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:date')?></label>
							<div class="col-md-9">
								<input type="text" name="Jam" value="<?php echo @$item->Jam ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:date_closing')?></label>
							<div class="col-md-9">
								<input type="text" name="TglTransaksi" value="<?php echo @$item->TglTransaksi ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:user')?></label>
							<div class="col-md-9">
								<input type="text" name="Username" value="<?php echo @$item->Nama_Asli ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:note')?></label>
							<div class="col-md-9">
								<textarea name="f[Catatan]" class="form-control"><?php echo @$item->Catatan ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<?php if($item->Batal): ?>
								<h4><strong><?php echo lang('message:cancel_data')?></strong></h4>
								<?php endif; ?>
								<?php if($item->Posting): ?>
								<h4><strong><?php echo lang('message:posted_data')?></strong></h4>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:no_reg')?></label>
							<div class="col-md-9">
								<input type="text" name="NoReg" value="<?php echo @$item->NoReg ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:date_reg')?></label>
							<div class="col-md-9">
								<input type="text" name="TglTransaksi" value="<?php echo @$item->TglTransaksi ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:nrm')?></label>
							<div class="col-md-9">
								<input type="text" name="NRM" value="<?php echo @$item->NRM ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:patient_name')?></label>
							<div class="col-md-9">
								<input type="text" name="NamaPasien" value="<?php echo @$item->NamaPasien ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:cooperation_type')?></label>
							<div class="col-md-9">
								<input type="text" name="KerjaSama" value="<?php echo @$item->JenisKerjasama ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:customer_name')?></label>
							<div class="col-md-9">
								<input type="text" name="Nama_Customer" value="<?php echo @$item->Nama_Customer ?>" class="form-control" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"><?php echo lang('label:no_card')?></label>
							<div class="col-md-9">
								<input type="text" name="NoKartu" value="<?php echo @$item->NoKartu ?>" class="form-control" readonly>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="row">
					<div class="form-group">
						<ul class="nav nav-tabs nav-justified">
							<li class="active"><a href="#detail-revenue" data-toggle="tab"><strong><?php echo lang("subtitle:revenue")?></strong></a></li>
							<li class=""><a href="#detail-cash" data-toggle="tab"><strong><?php echo lang("subtitle:cash")?></strong></a></li>
							<li class=""><a href="#detail-receivable" data-toggle="tab"><strong><?php echo lang("subtitle:receivable")?></strong></a></li>
							<li class=""><a href="#detail-honor" data-toggle="tab"><strong><?php echo lang("subtitle:honor")?></strong></a></li>
							<li class=""><a href="#detail-discount" data-toggle="tab"><strong><?php echo lang("subtitle:discount")?></strong></a></li>
							<?php /*?><li class=""><a href="#detail-cost" data-toggle="tab"><strong><?php echo lang("subtitle:cost")?></strong></a></li>
							<li class=""><a href="#detail-coefficient" title="<?php echo lang('subtitle:coefficient_weight')?>" data-toggle="tab"><strong><?php echo lang("subtitle:coefficient")?></strong></a></li><?php */?>
						</ul>
						<div class="tab-content">
							<div id="detail-revenue" class="tab-pane active">
								<?php echo $view_detail_revenue ?>
							</div>
							<div id="detail-cash" class="tab-pane">
								<?php echo $view_detail_cash ?>
							</div>
							<div id="detail-receivable" class="tab-pane">
								<?php echo $view_detail_receivable ?>
							</div>
							<div id="detail-honor" class="tab-pane">
								<?php echo $view_detail_honor ?>
							</div>
							<div id="detail-discount" class="tab-pane">
								<?php echo $view_detail_discount ?>
							</div>
							<div id="detail-cost" class="tab-pane">
								<?php echo $view_detail_cost ?>
							</div>
							<div id="detail-coefficient" class="tab-pane">
								<?php echo $view_detail_coefficient ?>
							</div>
						</div>	
					</div>
               	</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12 text-right">
							<button type="submit" id="btn-submit" class="btn btn-primary <?php echo (@$item->Batal || @$item->Posting || @$is_modal) ? 'disabled' : NULL?>"><?php echo lang('buttons:save') ?></button>
							<button type="button" data-act="ajax-modal" data-title="<?php echo lang('global:cancel_confirm'); ?>" data-action-url="<?php echo $cancel_audit_url ?>" class="btn btn-danger <?php echo (@$item->Batal || @$item->Posting) ? 'disabled' : NULL?>"><?php echo sprintf("%s %s", lang('buttons:cancel'), lang('label:audit'))?></button>
							<button type="button" data-act="ajax-modal" data-title="<?php echo lang('confirm:cancel_posting_title'); ?>" data-action-url="<?php echo $cancel_posting_url ?>" class="btn btn-danger <?php echo (@$item->Batal || ! @$item->Posting) ? 'disabled' : NULL?>"><?php echo sprintf("%s %s", lang('buttons:cancel'), lang('label:posting')) ?></button>
						<?php if( @$is_modal ): ?>
							<button type="button" data-dismiss="modal" class="btn btn-danger"><?php echo lang('buttons:close') ?></button>
						<?php endif; ?>
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
;(function( $ ){
		$( document ).ready(function(){
				var _form = $( 'form[name="form_crud"]' );
				
				_form.on("submit", function(e){
					e.preventDefault();	
					
					$.post($(this).attr("action"), $(this).serializeArray(), function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error( response.message );
							return false
						}
						
						$.alert_success( response.message );
						
						location.reload(); 
					});
				});
				
			});
	})( jQuery );
//]]>
</script>