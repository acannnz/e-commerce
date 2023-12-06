<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	//print_r($item_lookup);exit;
?>
<?php echo form_open( $form_action, [
		'id' => 'form_customer', 
		'name' => 'form_customer', 
		'rule' => 'form', 
		'class' => 'form-horizontal'
	]); ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
            <div class="panel-heading">                
                <div class="panel-bars">
					<ul class="btn-bars">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <i class="fa fa-bars fa-lg tip" data-placement="left" title="<?php echo lang("actions") ?>"></i>
                            </a>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <a href="<?php echo site_url("{$nameroutes}/create"); ?>">
                                        <i class="fa fa-plus"></i> <?php echo lang('action:add') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <h3 class="panel-title"><?php echo (@$is_edit) ? lang('heading:customer_update') : lang('heading:customer_create'); ?></h3>
            </div>
            <div class="panel-body table-responsive">
          		<div class="row">
            		<div class="col-md-6 col-xs-12">
                        <div class="form-group">
							<?php echo form_label(lang('label:code').' *', 'Kode_Customer', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Kode_Customer]', set_value('f[Kode_Customer]', @$item->Kode_Customer, TRUE), [
										'id' => 'Kode_Customer', 
										'placeholder' => '', 
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:category').' *', 'Kode_Kategori', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[Kode_Kategori]', $dropdown_category, set_value('f[Kode_Kategori]', @$item->Kode_Kategori, TRUE), [
									'id' => 'Kode_Customer', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
                        <div class="form-group">
                            <?php echo form_label(lang('label:name').' *', 'Nama_Customer', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Nama_Customer]', set_value('f[Nama_Customer]', @$item->Nama_Customer, TRUE), [
										'id' => 'Nama_Customer', 
										'placeholder' => '',
										'required' => 'required',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:alias'), 'Alias', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Alias]', set_value('f[Alias]', @$item->Alias, TRUE), [
										'id' => 'Alias', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:payment_type').' *', 'Type_Pembayaran', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[Type_Pembayaran]', $dropdown_paymenttype, set_value('f[Type_Pembayaran]', @$item->Type_Pembayaran, TRUE), [
									'id' => 'Kode_Customer', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:payment_term'), 'Term_Pembayaran', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Term_Pembayaran]', set_value('f[Term_Pembayaran]', @$item->Term_Pembayaran, TRUE), [
										'id' => 'Term_Pembayaran', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:npwp_no'), 'No_NPWP', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_NPWP]', set_value('f[No_NPWP]', @$item->No_NPWP, TRUE), [
										'id' => 'No_NPWP', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<?php echo form_label(lang('label:currency'), 'Currency_ID', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
							<?php echo form_dropdown('f[Currency_ID]', $dropdown_currency, set_value('f[Currency_ID]', @$item->Currency_ID, TRUE), [
									'id' => 'Currency_ID', 
									'placeholder' => '', 
									'class' => 'form-control'
								]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:credit_limit'), 'Batas_Kredit', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Batas_Kredit]', set_value('f[Batas_Kredit]', @$item->Batas_Kredit, TRUE), [
										'id' => 'Batas_Kredit', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-3">
								<div class="checkbox">
									<label for="Active">
										<?php echo form_checkbox('f[Active]', set_value('f[Active]', @$item->Active, TRUE), (boolean) @$item->Active, [
											'id' => 'Active', 
										]); ?>
										<?php echo lang('global:active') ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-6 col-xs-12">
                        <div class="form-group">
                            <?php echo form_label(lang('label:address'), 'Alamat_1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_textarea([
										'name' => 'f[Alamat_1]', 
										'value' => set_value('f[Alamat_1]', @$item->Alamat_1, TRUE),
										'id' => 'Alamat_1', 
										'placeholder' => '',
										'rows' => 4,
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:postalcode'), 'Kode_Pos', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Kode_Pos]', set_value('f[Kode_Pos]', @$item->Kode_Pos, TRUE), [
										'id' => 'Kode_Pos', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:claim_address_1'), 'Claim_Address1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Claim_Address1]', set_value('f[Claim_Address1]', @$item->Claim_Address1, TRUE), [
										'id' => 'Claim_Address1', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:claim_address_2'), 'Claim_Address2', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Claim_Address2]', set_value('f[Claim_Address2]', @$item->Claim_Address2, TRUE), [
										'id' => 'Claim_Address2', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:claim_address_3'), 'Claim_Address3', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Claim_Address3]', set_value('f[Claim_Address3]', @$item->Claim_Address3, TRUE), [
										'id' => 'Claim_Address3', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:phone_1'), 'No_Telepon_1', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Telepon_1]', set_value('f[No_Telepon_1]', @$item->No_Telepon_1, TRUE), [
										'id' => 'No_Telepon_1', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:phone_2'), 'No_Telepon_2', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Telepon_2]', set_value('f[No_Telepon_2]', @$item->No_Telepon_2, TRUE), [
										'id' => 'No_Telepon_2', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:phone_3'), 'No_Telepon_3', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Telepon_3]', set_value('f[No_Telepon_3]', @$item->No_Telepon_3, TRUE), [
										'id' => 'No_Telepon_3', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:fax'), 'No_Fax', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[No_Fax]', set_value('f[No_Fax]', @$item->No_Fax, TRUE), [
										'id' => 'No_Fax', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:email'), 'Alamat_Email', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Alamat_Email]', set_value('f[Alamat_Email]', @$item->Alamat_Email, TRUE), [
										'id' => 'Alamat_Email', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
						<div class="form-group">
                            <?php echo form_label(lang('label:website'), 'Alamat_Website', ['class' => 'control-label col-md-3']) ?>
							<div class="col-md-9">
								<?php echo form_input('f[Alamat_Website]', set_value('f[Alamat_Website]', @$item->Alamat_Website, TRUE), [
										'id' => 'Alamat_Website', 
										'placeholder' => '',
										'class' => 'form-control'
									]); ?>
							</div>
                        </div>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-6">
						<h4 class="subtitle"><?php echo lang('heading:contact_person')?></h4>
					</div>
					<div class="col-md-6">
						<a href="javascript:;" data-action-url="<?php echo @$add_contact_person ?>" data-act="ajax-modal"  data-title="<?php echo lang('heading:contact_person_create')?>"  data-act="ajax-modal" class="btn btn-primary btn-sm pull-right"><b><i class="fa fa-plus"></i> <?php echo lang('buttons:add')?></b></a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<table id="dt_contact_person" class="datatables table table-bordered table-hover" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th><?php echo lang('label:name') ?></th>
									<th><?php echo lang('label:address') ?></th>
									<th><?php echo lang('label:office_phone') ?></th>
									<th><?php echo lang('label:mobile') ?></th>
									<th><?php echo lang('label:email') ?></th>
									<th><?php echo lang('label:position') ?></th>
								</tr>
							</thead>        
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group text-right">
							<button id="js-btn-submit" type="button" class="btn btn-primary"><?php echo lang( 'buttons:save' ) ?></button>
							<button class="btn btn-warning" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}/create") ?>';">New</button> 
							<button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url("{$nameroutes}") ?>';">Close</button> 
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _form = $("#form_customer");
		
		$.fn.extend({
				dataTableInit: function(){
						var _this = this;
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						_datatable = _this.DataTable( {
								dom: 'tip',
								processing: false,
								serverSide: false,								
								paginate: false,
								ordering: false,
								searching: false,
								info: false,
								responsive: true,
								data: <?php print_r(json_encode(@$contact_person_collection, JSON_NUMERIC_CHECK)); ?>,
								columns: [
										{ 
											data: "KontakPerson_ID", 
											className: "actions text-center", 
											render: function( val, type, row ){
													return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
												} 
										},
										{ data: "Nama" },
										{ 
											data: "Alamat", 
											render: function( val ){
												return val.substr(0, 30);
											} 
										},
										{ data: "No_Telepon_Kantor", className: "" },
										{ data: "No_Handphone", className: "" },
										{ data: "Alamat_Email"},
										{ data: "Jabatan", className: "" },					
									],
								createdRow: function ( row, data, index ){																							
										$( row ).attr('data-action-url', '<?php echo base_url('marketing/contact_person/form') ?>/'+ index);
										$( row ).attr('data-act', 'ajax-modal');
										$( row ).attr('data-title', '<?php echo lang('heading:contact_person_create')?>');
										//$( row ).attr('data-modal-lg', 1);
										$( row ).on( "click", "a.btn-remove", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
													_datatable.row( row ).remove().draw();
												}
											})
									}
							} );
							
						$( "#dt_trans_purchase_request_detail_length select, #dt_trans_purchase_request_detail_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
				
		$( document ).ready(function(e) {
			
				$('#dt_contact_person').dataTableInit();
						
				_form.find("button#js-btn-submit").on("click", function(e){
					e.preventDefault();		
					
					var data_post = {};
					data_post['customer'] = {
							Kode_Customer : _form.find('#Kode_Customer').val(),
							Nama_Customer : _form.find('#Nama_Customer').val(),
							Alias : _form.find('#Alias').val(),
							Alamat_1 : _form.find('#Alamat_1').val(),
							Kode_Pos : _form.find('#Kode_Pos').val(),
							No_Telepon_1 : _form.find('#No_Telepon_1').val(),
							No_Telepon_2 : _form.find('#No_Telepon_1').val(),
							No_Telepon_3 : _form.find('#No_Telepon_3').val(),
							No_Fax : _form.find('#No_Fax').val(),
							Alamat_Email : _form.find('#Alamat_Email').val(),
							Alamat_Website : _form.find('#Alamat_Website').val(),
							No_NPWP : _form.find('#No_NPWP').val(),
							Type_Pembayaran : _form.find('#Type_Pembayaran').val(),
							Term_Pembayaran : _form.find('#Type_Pembayaran').val(),
							Currency_ID : _form.find('#Currency_ID').val(),
							Batas_Kredit : _form.find('#Batas_Kredit').val(),
							Active : _form.find('#Active').is(':checked') ? 1 : 0,
							Kode_Kategori : _form.find('#Kode_Kategori').val(),
							Claim_Address1 : _form.find('#Claim_Address1').val(),
							Claim_Address2 : _form.find('#Claim_Address2').val(),
							Claim_Address3 : _form.find('#Claim_Address3').val(),
						};
						
					data_post['contact_person'] = {};
					
					var contact_person_collection = $('#dt_contact_person').DataTable().rows().data();
					contact_person_collection.each(function (value, index) {
						data_post['contact_person'][index] = value;
					});
							
					$.post( _form.prop("action"), data_post, function( response, status, xhr ){
						
						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success( response.message );
						
						setTimeout(function(){
													
							document.location.href = "<?php echo base_url($nameroutes); ?>";
							
							}, 300 );
						
					});
				});
				
				

			});

	})( jQuery );
//]]>
</script>
