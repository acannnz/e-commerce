<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url(), array("id" => "form_item_usage", "name" => "form_item_usage") ); ?>
<div class="page-subtitle">
    <h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('item_usage:section_label') ?></h3>
</div>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:evidence_number_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="NoBukti" name="f[NoBukt]" value="<?php echo @$item->NoBukti ?>" placeholder="" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:date_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Tanggal" name="f[Tanggal]" placeholder="" class="form-control" value="<?php echo @$item->Jam ?>"  readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:section_label') ?></label>
            <div class="col-lg-9">
                <input type="hidden" id="SectionID" name="f[SectionID]" value="<?php echo @$item->SectionID ?>" class="form-control">
                <input type="text" id="SectionName" name="f[SectionName]" value="<?php echo @$item->SectionName ?>" class="form-control" readonly="readonly">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('item_usage:description_label') ?></label>
            <div class="col-lg-9">
            	<textarea id="Keterangan" name="f[Keterangan]" class="form-control" readonly="readonly"><?php echo @$item->Keterangan ?></textarea>
            </div>
        </div>
    </div>
    <?php if ($item->StatusBatal == 1) :?>
    <div class="col-md-6">
        <div class="form-group">
        	<h2 class="text-danger">Pemakaian ini Sudah Di Batalkan</h2>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">Alasan Batal</label>
            <div class="col-lg-9">
            	<textarea id="AlasanBatal" name="f[AlasanBatal]" class="form-control" readonly="readonly"><?php echo @$item->AlasanBatal ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label">User Pembatal</label>
            <div class="col-lg-9">
            	<input type="text" id="UserBatal" name="f[UserBatal]" value="<?php echo @$item->UserBatal ?>" class="form-control" readonly="readonly">
            </div>
        </div>    
    </div>
    <?php endif; ?>
</div>

<div class="page-subtitle">
    <h3 class="text-primary"><i class="fa fa-calendar pull-left text-primary"></i> Detail Barang</h3>
</div>
<div class="row"> 
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="dt_item_usages" class="table table-sm table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Barang</th>
                                <th>Deskripsi</th>
                                <th>Satuan</th>
                                <th>Stok</th>                        
                                <th>Pemakaian</th>                        
                                <th>Harga</th>                        
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
		<?php if ($item->StatusBatal == 0) :?>
        <a href="<?php echo $cancel_link ?>" data-toggle="ajax-modal" class="btn btn-danger"><b><i class="fa fa-times"></i> <?php echo lang("buttons:cancel") ?></b></a>
        <?php endif; ?>
        <a href="<?php echo base_url("pharmacy/item-usage/create")?>" class="btn btn-default"><b><i class="fa fa-plus"></i> <?php echo lang("buttons:create") ?></b></a>
    </div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {

			};
		
		$.fn.extend({
				dt_item_usages: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: false,
								ordering: false,
								lengthMenu: [ 20, 50, 100 ],
								searching: false,
								info: false,
								autoWidth: false,
								responsive: true,
								<?php if (!empty($collection)):?>
								data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
								<?php endif; ?>
								columns: [
										{ data: "BarangID", className: "text-center", }, // kolom penomoran
										{ data: "Kode_Barang", className: "text-center", },
										{ data: "Nama_Barang", },
										{ data: "Satuan", className: "text-center", },
										{ data: "QtyStok", className: "text-right" },
										{ 
											data: "QtyPemakaian", 
											className: "text-right",
											render: function ( val ){
												return "<b>"+ val +"</b>"
											}
										},
										{ 
											data: "Harga", 
											className: "text-right", 
											render: function ( val ){
												var mask = parseFloat(val).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
												return "<b>"+ mask +"</b>";
											}
										},
									],
								columnDefs  : [
										{
											"targets": ["BarangID","Keterangan"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								fnRowCallback : function( nRow, aData, iDisplayIndex , iDisplayIndexFull ) {
										var index = iDisplayIndexFull + 1;
										$('td:eq(0)',nRow).html(index);
										return nRow;					
									},
							} );
							
						$( "#dt_item_usages_length select, #dt_item_usages_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_item_usages" ).dt_item_usages();

			});

	})( jQuery );
//]]>
</script>