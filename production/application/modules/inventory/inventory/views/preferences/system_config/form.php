<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
AkunIDPenampungReturMutasi
*/
?>
<?php echo form_open(site_url("{$nameroutes}/index_post"), [
		'id' => 'form_config', 
		'name' => 'form_config', 
		'rule' => 'form' , 
		'class' => ''
	]); ?>
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title">General</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?php echo form_label('Filter death stock (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[HariDeathStok]',
                                'value' => set_value('f[HariDeathStok]', @$config['HariDeathStok'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[HariDeathStok_Farmasi]',
                                'value' => set_value('f[HariDeathStok_Farmasi]', @$config['HariDeathStok_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <div class="form-group">
					<?php echo form_label('Filter slow moving (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[HariSlowMoving]',
                                'value' => set_value('f[HariSlowMoving]', @$config['HariSlowMoving'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[HariSlowMoving_Farmasi]',
                                'value' => set_value('f[HariSlowMoving_Farmasi]', @$config['HariSlowMoving_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <hr>
                <div class="form-group">
					<?php echo form_label('Basis Perhitungan', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
                    	<?php echo form_dropdown('f[BasisPerhitungan]', ['Mingguan','Bulanan'], set_value('f[BasisPerhitungan]', @$config['BasisPerhitungan'], TRUE), [
								'id' => 'input_parent', 
								'placeholder' => '',
								'class' => 'select2'
							]); ?>
                    </div>
            	</div>
                <hr>
                <div class="form-group">
					<?php echo form_label('Stok Minimum (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMin]',
                                'value' => set_value('f[Hari_SMin]', @$config['Hari_SMin'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMin_Farmasi]',
                                'value' => set_value('f[Hari_SMin_Farmasi]', @$config['Hari_SMin_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Section') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMin_Section]',
                                'value' => set_value('f[Hari_SMin_Section]', @$config['Hari_SMin_Section'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <div class="form-group">
					<?php echo form_label('Stok Maksimum (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMax]',
                                'value' => set_value('f[Hari_SMax]', @$config['Hari_SMax'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMax_Farmasi]',
                                'value' => set_value('f[Hari_SMax_Farmasi]', @$config['Hari_SMax_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Section') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_SMax_Section]',
                                'value' => set_value('f[Hari_SMax_Section]', @$config['Hari_SMax_Section'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <div class="form-group">
					<?php echo form_label('ROP (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_ROP]',
                                'value' => set_value('f[Hari_ROP]', @$config['Hari_ROP'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_ROP_Farmasi]',
                                'value' => set_value('f[Hari_ROP_Farmasi]', @$config['Hari_ROP_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Section') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_ROP_Section]',
                                'value' => set_value('f[Hari_ROP_Section]', @$config['Hari_ROP_Section'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <div class="form-group">
					<?php echo form_label('Lead Time (Hari)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Gudang') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_LT]',
                                'value' => set_value('f[Hari_LT]', @$config['Hari_LT'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Farmasi') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_LT_Farmasi]',
                                'value' => set_value('f[Hari_LT_Farmasi]', @$config['Hari_LT_Farmasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                    <div class="col-md-3 col-xs-6">
                        <?php echo form_label('Section') ?>
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[Hari_LT_Section]',
                                'value' => set_value('f[Hari_LT_Section]', @$config['Hari_LT_Section'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
                <hr>
                <div class="form-group">
					<?php echo form_label('Metode Perhitungan', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
                    	<?php echo form_dropdown('f[MetodePerhitungan]', ['EOQ','Rata-rata'], set_value('f[MetodePerhitungan]', @$config['MetodePerhitungan'], TRUE), [
								'id' => 'input_parent', 
								'placeholder' => '',
								'class' => 'select2'
							]); ?>
                    </div>
            	</div>
                <div class="form-group">
					<?php echo form_label('Kunci Stok Minimum', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
                    	<?php echo form_hidden('f[StokMinusDikunci]',0); ?>
						<?php echo form_checkbox([
								'name' => 'f[StokMinusDikunci]',
								'value' => 1,
								'checked' => (1 == @$config['StokMinusDikunci']),
								'class' => 'checkbox'
							]); ?>
                    </div>
            	</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
    	<div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title">Pembulatan Nilai Hutang</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
					<?php echo form_label('Akun Penampung', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[Rekening_Pembulatan]',
                                'value' => set_value('f[Rekening_Pembulatan]', @$config['Rekening_Pembulatan'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
            	</div>
                <div class="form-group">
					<?php echo form_label('Pembulatan', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
                    	<?php echo form_dropdown('f[Dibulatkan_Ke]', [10,100,1000,10000], set_value('f[Dibulatkan_Ke]', @$config['Dibulatkan_Ke'], TRUE), [
								'id' => 'input_parent', 
								'placeholder' => '',
								'class' => 'select2',
								'style' => 'width:100px;'
							]); ?>
                    </div>
            	</div>
                <div class="form-group">
					<?php echo form_label('Metode Pembulatan', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
                    	<label><?php echo form_radio([
								'name' => 'f[Metode_Pembulatan]',
								'value' => 1,
								'checked' => (1 == @$config['Metode_Pembulatan']),
								'class' => 'radio'
							]); ?> Ke Atas</label>
                        <label><?php echo form_radio([
								'name' => 'f[Metode_Pembulatan]',
								'value' => 2,
								'checked' => (2 == @$config['Metode_Pembulatan']),
								'class' => 'radio'
							]); ?> Ke Bawah</label>
                    	<label><?php echo form_radio([
								'name' => 'f[Metode_Pembulatan]',
								'value' => 3,
								'checked' => (3 == @$config['Metode_Pembulatan']),
								'class' => 'radio'
							]); ?> Ke Semua</label>
                    </div>
            	</div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title">Komisi Death Stock</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?php echo form_label('Komisi Death Stock', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-9 col-xs-12">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[KomisiDeathStok]',
                                'value' => set_value('f[KomisiDeathStok]', @$config['KomisiDeathStok'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]).' '.form_label('%'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_label('Tipe Pasien', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-9 col-xs-12">
                    	<?php echo form_hidden([
								'f[KomisiDeathStokSHHC]' => 0,
								'f[KomisiDeathStokUMUM]' => 0,
								'f[KomisiDeathStokIKS]' => 0,
								'f[KomisiDeathStokEXE]' => 0,
							]); ?>
                        <label><?php echo form_checkbox([
								'name' => 'f[KomisiDeathStokSHHC]',
								'value' => 1,
								'checked' => (1 == @$config['KomisiDeathStokSHHC']),
								'class' => 'checkbox'
							]); ?> SHHC</label>
                        <label><?php echo form_checkbox([
								'name' => 'f[KomisiDeathStokUMUM]',
								'value' => 1,
								'checked' => (1 == @$config['KomisiDeathStokUMUM']),
								'class' => 'checkbox'
							]); ?> UMUM</label>
                    	<label><?php echo form_checkbox([
								'name' => 'f[KomisiDeathStokIKS]',
								'value' => 1,
								'checked' => (1 == @$config['KomisiDeathStokIKS']),
								'class' => 'checkbox'
							]); ?> IKS</label>
                    	<label><?php echo form_checkbox([
								'name' => 'f[KomisiDeathStokEXE]',
								'value' => 1,
								'checked' => (1 == @$config['KomisiDeathStokEXE']),
								'class' => 'checkbox'
							]); ?> Executive</label>
                    </div>
            	</div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">                
                <h3 class="panel-title">Lainnya</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <?php echo form_label('Akun ID Persediaan Mutasi', '', ['class' => 'col-md-5 col-xs-12 control-label']) ?>
                    <div class="col-md-7 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[AkunIDPersediaanMutasi]',
                                'value' => set_value('f[AkunIDPersediaanMutasi]', @$config['AkunIDPersediaanMutasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
            	</div>
                <div class="form-group">
                    <?php echo form_label('Akun ID Penampung Retur Mutasi', '', ['class' => 'col-md-5 col-xs-12 control-label']) ?>
                    <div class="col-md-7 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[AkunIDPenampungReturMutasi]',
                                'value' => set_value('f[AkunIDPenampungReturMutasi]', @$config['AkunIDPenampungReturMutasi'], TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
            	</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-xs-12">
    	<?php echo form_button([
				'name' => '',
				'id' => '',
				'value' => '',
				'type' => 'reset',
				'content' => lang('button:reset'),
				'class' => 'btn btn-block btn-default'
			]); ?>
    </div>
    <div class="col-sm-6 col-xs-12">
    	<?php echo form_button([
			'name' => '',
			'id' => '',
			'value' => '',
			'type' => 'submit',
			'content' => lang('button:update'),
			'class' => 'btn btn-block btn-primary'
		]); ?>
    </div>
</div>    
<?php echo form_close() ?>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_config"]');
				_form.appForm({
						isModal: false,
						beforeAjaxSubmit: function(data){},
						onError: function(result){},
						onSuccess: function(result){
								if (result['success']){
									appAlert.success(result['message']);	
								}
							}
					});
			});
	})( jQuery );
</script>


