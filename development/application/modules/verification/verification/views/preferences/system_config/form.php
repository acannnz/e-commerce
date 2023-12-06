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
                <h3 class="panel-title">PENDAPATAN</h3>
            </div>
            <div class="panel-body">
				<h4 class="subtitle"><?php echo 'Back Office 1'; ?></h4>
                <div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (UMUM) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanUMUM]', set_value('f[AkunLawanPendatanUMUM]', @$config->AkunLawanPendatanUMUM, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanUMUM]', set_value('t[NamaAkunLawanPendatanUMUM]', @$acc_umum->Akun_No.' '.@$acc_umum->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (IKS) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanIKS]', set_value('f[AkunLawanPendatanIKS]', @$config->AkunLawanPendatanIKS, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanIKS]', set_value('t[NamaAkunLawanPendatanIKS]', @$acc_iks->Akun_No .' '. @$acc_iks->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (EXE) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanEXECUTIVE]', set_value('f[AkunLawanPendatanEXECUTIVE]', @$config->AkunLawanPendatanEXECUTIVE, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanEXECUTIVE]', set_value('t[NamaAkunLawanPendatanEXECUTIVE]', @$acc_exe->Akun_No .' '. @$acc_exe->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (BPJS) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendapatanBPJS]', set_value('f[AkunLawanPendapatanBPJS]', @$config->AkunLawanPendapatanBPJS, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendapatanBPJS]', set_value('t[NamaAkunLawanPendapatanBPJS]', @$acc_bpjs->Akun_No .' '. @$acc_bpjs->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (HC) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanHC]', set_value('f[AkunLawanPendatanHC]', @$config->AkunLawanPendatanHC, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanHC]', set_value('t[NamaAkunLawanPendatanHC]', @$acc_hc->Akun_No .' '. @$acc_hc->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (MA) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanMA]', set_value('f[AkunLawanPendatanMA]', @$config->AkunLawanPendatanMA, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanMA]', set_value('t[NamaAkunLawanPendatanMA]', @$acc_ma->Akun_No .' '. @$acc_ma->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				
				<?php if(config_item('multi_bo') == 'TRUE'): ?>
				<hr/>
				<h4 class="subtitle"><?php echo 'Back Office 2'; ?></h4>
                <div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (UMUM) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanUMUM_2]', set_value('f[AkunLawanPendatanUMUM_2]', @$config->AkunLawanPendatanUMUM_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanUMUM_2]', set_value('t[NamaAkunLawanPendatanUMUM_2]', @$acc_umum_2->Akun_No .' '. @$acc_umum_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (IKS) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanIKS_2]', set_value('f[AkunLawanPendatanIKS_2]', @$config->AkunLawanPendatanIKS_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanIKS_2]', set_value('t[NamaAkunLawanPendatanIKS_2]', @$acc_iks_2->Akun_No .' '. @$acc_iks_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (EXE) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanEXECUTIVE_2]', set_value('f[AkunLawanPendatanEXECUTIVE_2]', @$config->AkunLawanPendatanEXECUTIVE_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanEXECUTIVE_2]', set_value('t[NamaAkunLawanPendatanEXECUTIVE_2]', @$acc_exe_2->Akun_No .' '. @$acc_exe_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (BPJS) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendapatanBPJS_2]', set_value('f[AkunLawanPendapatanBPJS_2]', @$config->AkunLawanPendapatanBPJS_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendapatanBPJS_2]', set_value('t[NamaAkunLawanPendapatanBPJS_2]', @$acc_bpjs_2->Akun_No .' '. @$acc_bpjs_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (HC) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanHC_2]', set_value('f[AkunLawanPendatanHC_2]', @$config->AkunLawanPendatanHC_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanHC_2]', set_value('t[NamaAkunLawanPendatanHC_2]', @$acc_hc_2->Akun_No .' '. @$acc_hc_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Lawan Pendapatan (MA) *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunLawanPendatanMA_2]', set_value('f[AkunLawanPendatanMA_2]', @$config->AkunLawanPendatanMA_2, TRUE)); ?>
								<?php echo form_input('t[NamaAkunLawanPendatanMA_2]', set_value('t[NamaAkunLawanPendatanMA_2]', @$acc_ma_2->Akun_No .' '. @$acc_ma_2->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif;?>
                
                <hr>
				<div class="form-group">
					<?php echo form_label('Akun Pos Pembayaran Outstanding Potong Honor', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoPotongHonor]', set_value('f[AkunNoPotongHonor]', @$config->AkunNoPotongHonor, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoPotongHonor]', set_value('t[NamaAkunNoPotongHonor]', @$acc_cuthonor->Akun_No .' '. @$acc_cuthonor->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Pos Pembayaran Outstanding Others', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoOthers]', set_value('f[AkunNoOthers]', @$config->AkunNoOthers, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoOthers]', set_value('t[NamaAkunNoOthers]', @$acc_others->Akun_No .' '. @$acc_others->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
                <div class="form-group">
					<?php echo form_label('Opsi', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
                    <div class="col-sm-8 col-xs-12">
						<div class="row">
							<div class="col-xs-12">
								<?php echo form_hidden('f[ModelHPPDirect]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[ModelHPPDirect]',
										'value' => 1,
										'checked' => (1 == @$config->ModelHPPDirect),
										'class' => 'checkbox'
									]).' Model HPP Obat dan BHP Direct'; ?></label>
							</div>
							<div class="col-xs-12">
								<?php echo form_hidden('f[DiskonTdkLangsungMulai]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[DiskonTdkLangsungMulai]',
										'value' => 1,
										'checked' => (1 == @$config->DiskonTdkLangsungMulai),
										'class' => 'checkbox'
									]).' <b>MENGGUNAKAN DISKON TIDAK LANGSUNG</b>'; ?></label>
							</div>
						</div>
                    </div>
            	</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
    	<div class="panel panel-default">
            <div class="panel-heading">      
				<div class="panel-btns"> 
					<a href="javascript:;" class="minimize maximize">+</a>
				</div>          
                <h3 class="panel-title">PIUTANG</h3>
            </div>
            <div class="panel-body" style="display: none;">
				<div class="form-group">
                    <?php echo form_label('Type Piutang Asuransi (MA)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangAsuransi]',
                                'value' => set_value('f[TypePiutangAsuransi]', @$config->TypePiutangAsuransi, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangAsuransiPusat]',
                                'value' => set_value('f[TypePiutangAsuransiPusat]', @$config->TypePiutangAsuransiPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun ID Piutang Asuransi (MA)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangAsuransi]',
                                'value' => set_value('f[IDAkunPiutangAsuransi]', @$config->IDAkunPiutangAsuransi, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangAsuransiPusat]',
                                'value' => set_value('f[IDAkunPiutangAsuransiPusat]', @$config->IDAkunPiutangAsuransiPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Type Piutang IKS', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangIKS]',
                                'value' => set_value('f[TypePiutangIKS]', @$config->TypePiutangIKS, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangIKSPusat]',
                                'value' => set_value('f[TypePiutangIKSPusat]', @$config->TypePiutangIKSPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun ID Piutang IKS', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangIKS]',
                                'value' => set_value('f[IDAkunPiutangIKS]', @$config->IDAkunPiutangIKS, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangIKSPusat]',
                                'value' => set_value('f[IDAkunPiutangIKSPusat]', @$config->IDAkunPiutangIKSPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Type Piutang HC', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangHC]',
                                'value' => set_value('f[TypePiutangHC]', @$config->TypePiutangHC, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangHCPusat]',
                                'value' => set_value('f[TypePiutangHCPusat]', @$config->TypePiutangHCPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun ID Piutang HC', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangHC]',
                                'value' => set_value('f[IDAkunPiutangHC]', @$config->IDAkunPiutangHC, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Di Unit') ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDAkunPiutangHCPusat]',
                                'value' => set_value('f[IDAkunPiutangHCPusat]', @$config->IDAkunPiutangHCPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
						<?php echo form_label('Di Pusat') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun RAK Hospital (Akun_No)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[RAK_Hospital]',
                                'value' => set_value('f[RAK_Hospital]', @$config->RAK_Hospital, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Untuk di Pendapatan MA Non Billing') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun RAK Corporate (Akun_No)', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
                        <?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[RAK_Corporate]',
                                'value' => set_value('f[RAK_Corporate]', @$config->RAK_Corporate, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Untuk di Piutang Pasien, Piutang dan Hutang MA') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
        	<div class="panel-heading">      
				<div class="panel-btns"> 
					<a href="javascript:;" class="minimize maximize">+</a>
				</div>          
                <h3 class="panel-title">HUTANG</h3>
            </div>
            <div class="panel-body" style="display: none;">
				<div class="form-group">
					<div class="col-xs-6 text-center"><b>Tipe Hutang</b></div>
					<div class="col-xs-6 text-center"><b>Akun ID HPP</b></div>
				</div>
                <div class="form-group">
                    <?php echo form_label('Honor RI', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorRI]',
                                'value' => set_value('f[TypeHutangHonorRI]', @$config->TypeHutangHonorRI, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorRI]',
                                'value' => set_value('f[AkunIDHPPHonorRI]', @$config->AkunIDHPPHonorRI, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Honor RJ', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorRJ]',
                                'value' => set_value('f[TypeHutangHonorRJ]', @$config->TypeHutangHonorRJ, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorRJ]',
                                'value' => set_value('f[AkunIDHPPHonorRJ]', @$config->AkunIDHPPHonorRJ, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Honor OK', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorOK]',
                                'value' => set_value('f[TypeHutangHonorOK]', @$config->TypeHutangHonorOK, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorOK]',
                                'value' => set_value('f[AkunIDHPPHonorOK]', @$config->AkunIDHPPHonorOK, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Lab', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorLab]',
                                'value' => set_value('f[TypeHutangHonorLab]', @$config->TypeHutangHonorLab, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorLab]',
                                'value' => set_value('f[AkunIDHPPHonorLab]', @$config->AkunIDHPPHonorLab, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Jasa Kirim', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorJasaKirim]',
                                'value' => set_value('f[TypeHutangHonorJasaKirim]', @$config->TypeHutangHonorJasaKirim, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorJasaKirim]',
                                'value' => set_value('f[AkunIDHPPHonorJasaKirim]', @$config->AkunIDHPPHonorJasaKirim, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Komisi Obat', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorKomisiObat]',
                                'value' => set_value('f[TypeHutangHonorKomisiObat]', @$config->TypeHutangHonorKomisiObat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPHonorKomisiObat]',
                                'value' => set_value('f[AkunIDHPPHonorKomisiObat]', @$config->AkunIDHPPHonorKomisiObat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Insentif', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangInsentif]',
                                'value' => set_value('f[TypeHutangInsentif]', @$config->TypeHutangInsentif, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPInsentif]',
                                'value' => set_value('f[AkunIDHPPInsentif]', @$config->AkunIDHPPInsentif, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Baca Rontg', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangHonorBaca]',
                                'value' => set_value('f[TypeHutangHonorBaca]', @$config->TypeHutangHonorBaca, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPBaca]',
                                'value' => set_value('f[AkunIDHPPBaca]', @$config->AkunIDHPPBaca, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Petugas Rontg', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangPetugasRntg]',
                                'value' => set_value('f[TypeHutangPetugasRntg]', @$config->TypeHutangPetugasRntg, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
                    </div>
                    <div class="col-sm-4 col-xs-6">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[AkunIDHPPPetugasRntg]',
                                'value' => set_value('f[AkunIDHPPPetugasRntg]', @$config->AkunIDHPPPetugasRntg, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?> 
                    </div>
                </div>
				<hr/>
				<div class="form-group">
                    <?php echo form_label('Type Hutang Unit', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[TypeHutangAsuransi]',
                                'value' => set_value('f[TypeHutangAsuransi]', @$config->TypeHutangAsuransi, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Untuk di AP Hutang Unit') ?>
                    </div>
                </div>
				<div class="form-group">
                    <?php echo form_label('Akun ID Hutang Unit', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
                        <?php echo form_input([
                                'type' => 'number',
                                'id' => '', 
                                'name' => 'f[RAK_Hospital]',
                                'value' => set_value('f[IDAkunHutangAsuransi]', @$config->IDAkunHutangAsuransi, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:70px;text-align:center;'
                            ]); ?>
						<?php echo form_label('Untuk di AP Hutang Unit') ?>
                    </div>
                </div>
				<div class="form-group">
					<?php echo form_label('Opsi', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
						<div class="row">
							<div class="col-xs-12">
								<?php echo form_hidden('f[VerifikatorHitungHonor]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[VerifikatorHitungHonor]',
										'value' => 1,
										'checked' => (1 == @$config->VerifikatorHitungHonor),
										'class' => 'checkbox'
									]).' Verifikator Hitung Honor'; ?></label>
							</div>
						</div>
                    </div>
            	</div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">      
				<div class="panel-btns"> 
					<a href="javascript:;" class="minimize maximize">+</a>
				</div>          
                <h3 class="panel-title">POSTINGAN</h3>
            </div>
            <div class="panel-body" style="display: none;">
                <div class="form-group">
                    <?php echo form_label('Kode Proyek', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[KodeProyek]',
                                'value' => set_value('f[KodeProyek]', @$config->KodeProyek, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
                    <?php echo form_label('ID Divisi', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDDivisi]',
                                'value' => set_value('f[IDDivisi]', @$config->IDDivisi, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
				</div>
				<div class="form-group">
                    <?php echo form_label('Kode Proyek Pusat', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[KodeProyekPusat]',
                                'value' => set_value('f[KodeProyekPusat]', @$config->KodeProyekPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
                    <?php echo form_label('ID Divisi Pusat', '', ['class' => 'col-md-3 col-xs-12 control-label']) ?>
                    <div class="col-md-3 col-xs-12">
                    	<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[IDDivisiPusat]',
                                'value' => set_value('f[IDDivisiPusat]', @$config->IDDivisiPusat, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
                    </div>
            	</div>
				<div class="form-group">
					<?php echo form_label('Opsi', '', ['class' => 'col-sm-3 col-xs-12 control-label']) ?>
                    <div class="col-sm-9 col-xs-12">
						<div class="row">
							<div class="col-sm-6 col-xs-12">
								<?php echo form_hidden('f[PostingIKSAR]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[PostingIKSAR]',
										'value' => 1,
										'checked' => (1 == @$config->PostingIKSAR),
										'class' => 'checkbox'
									]).' Postingan IKS &amp; HC ke AR'; ?></label>
							</div>
							<div class="col-sm-6 col-xs-12">
								<?php echo form_hidden('f[PostingMAAR]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[PostingMAAR]',
										'value' => 1,
										'checked' => (1 == @$config->PostingMAAR),
										'class' => 'checkbox'
									]).' Postingan MA Ke AR'; ?></label>
							</div>
							<div class="col-sm-6 col-xs-12">
								<?php echo form_hidden('f[chkPostingAP]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[chkPostingAP]',
										'value' => 1,
										'checked' => (1 == @$config->chkPostingAP),
										'class' => 'checkbox'
									]).' Postingan ke AP'; ?></label>
							</div>
							<div class="col-sm-6 col-xs-12">
								<?php echo form_hidden('f[AdaVerifikasiPiutang]',0); ?>
								<label><?php echo form_checkbox([
										'name' => 'f[AdaVerifikasiPiutang]',
										'value' => 1,
										'checked' => (1 == @$config->AdaVerifikasiPiutang),
										'class' => 'checkbox'
									]).' Ada Verifkasi Piutang'; ?></label>
							</div>
						</div>
                    </div>
            	</div>
            </div>
        </div>
		<div class="panel panel-default">
            <div class="panel-heading">      
				<div class="panel-btns"> 
					<a href="javascript:;" class="minimize maximize">+</a>
				</div>          
                <h3 class="panel-title">OBAT BEBAS</h3>
            </div>
            <div class="panel-body" style="display: none;">
				<div class="form-group">
					<?php echo form_label('Akun Pendapatan Obat Bebas *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunPendapatanObatBebas]', set_value('f[AkunPendapatanObatBebas]', @$config->AkunPendapatanObatBebas, TRUE)); ?>
								<?php echo form_input('t[NamaAkunPendapatanObatBebas]', set_value('t[NamaAkunPendapatanObatBebas]', @$acc_otc_income->Akun_No .' '. @$acc_otc_income->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<h4 class="subtitle">Akun Pembayaran</h4>
				<div class="form-group">
					<?php echo form_label('Tunai *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunBayar_OB_Tunai]', set_value('f[AkunBayar_OB_Tunai]', @$config->AkunBayar_OB_Tunai, TRUE)); ?>
								<?php echo form_input('t[NamaAkunBayar_OB_Tunai]', set_value('t[NamaAkunBayar_OB_Tunai]', @$acc_otc_tunai->Akun_No .' '. @$acc_otc_tunai->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Dijamin Asuransi *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunBayar_OB_Asuransi]', set_value('f[AkunBayar_OB_Asuransi]', @$config->AkunBayar_OB_Asuransi, TRUE)); ?>
								<?php echo form_input('t[NamaAkunBayar_OB_Asuransi]', set_value('t[NamaAkunBayar_OB_Asuransi]', @$acc_otc_insurence->Akun_No .' '. @$acc_otc_insurence->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Ditagihkan Perusahaan *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunBayar_OB_Perusahaan]', set_value('f[AkunBayar_OB_Perusahaan]', @$config->AkunBayar_OB_Perusahaan, TRUE)); ?>
								<?php echo form_input('t[NamaAkunBayar_OB_Perusahaan]', set_value('t[NamaAkunBayar_OB_Perusahaan]', @$acc_otc_company->Akun_No .' '. @$acc_otc_company->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Kredit *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunBayar_OB_Kredit]', set_value('f[AkunBayar_OB_Kredit]', @$config->AkunBayar_OB_Kredit, TRUE)); ?>
								<?php echo form_input('t[NamaAkunBayar_OB_Kredit]', set_value('t[NamaAkunBayar_OB_Kredit]', @$acc_otc_credit->Akun_No .' '. @$acc_otc_credit->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('L.O.G *', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunBayar_OB_LOG]', set_value('f[AkunBayar_OB_LOG]', @$config->AkunBayar_OB_LOG, TRUE)); ?>
								<?php echo form_input('t[NamaAkunBayar_OB_LOG]', set_value('t[NamaAkunBayar_OB_LOG]', @$acc_otc_log->Akun_No .' '. @$acc_otc_log->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
            <div class="panel-heading">      
				<div class="panel-btns"> 
					<a href="javascript:;" class="minimize maximize">+</a>
				</div>          
                <h3 class="panel-title">BPJS, L.O.G &amp; BIAYA</h3>
            </div>
            <div class="panel-body" style="display: none;">
				<h4 class="subtitle">BPJS</h4>
				<div class="form-group">
					<?php echo form_label('Type Piutang BPJS', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangBPJS]',
                                'value' => set_value('f[TypePiutangBPJS]', @$config->TypePiutangBPJS, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun No Keuntungan BPJS', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoKeuntunganBPJS]', set_value('f[AkunNoKeuntunganBPJS]', @$config->AkunNoKeuntunganBPJS, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoKeuntunganBPJS]', set_value('t[NamaAkunNoKeuntunganBPJS]', @$acc_bpjs_income->Akun_No .' '. @$acc_bpjs_income->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun No Kerugian BPJS', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoKerugianBPJS]', set_value('f[AkunNoKerugianBPJS]', @$config->AkunNoKerugianBPJS, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoKerugianBPJS]', set_value('t[NamaAkunNoKerugianBPJS]', @$acc_bpjs_loss->Akun_No .' '. @$acc_bpjs_loss->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Piutang BPJS', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[IDAkunPiutangBPJS]', set_value('f[IDAkunPiutangBPJS]', @$config->IDAkunPiutangBPJS, TRUE)); ?>
								<?php echo form_input('t[NamaIDAkunPiutangBPJS]', set_value('t[IDAkunPiutangBPJS]', @$acc_bpjs_receivable->Akun_No .' '. @$acc_bpjs_receivable->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<h4 class="subtitle">L.O.G</h4>
				<div class="form-group">
					<?php echo form_label('Type Piutang L.O.G', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<?php echo form_input([
                                'type' => 'text',
                                'id' => '', 
                                'name' => 'f[TypePiutangLOG]',
                                'value' => set_value('f[TypePiutangLOG]', @$config->TypePiutangLOG, TRUE),
                                'placeholder' => '', 
                                'class' => 'form-control inline',
                                'style' => 'width:100px;text-align:center;'
                            ]); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun Piutang L.O.G', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[IDAkunPiutangLOG]', set_value('f[IDAkunPiutangLOG]', @$config->IDAkunPiutangLOG, TRUE)); ?>
								<?php echo form_input('t[NamaIDAkunPiutangLOG]', set_value('t[NamaIDAkunPiutangLOG]', @$acc_log_receivable->Akun_No .' '. @$acc_log_receivable->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<hr/>
				<h4 class="subtitle">BIAYA</h4>
				<div class="form-group">
					<?php echo form_label('Akun No Biaya Insentif', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoBiayaInsentif]', set_value('f[AkunNoBiayaInsentif]', @$config->AkunNoBiayaInsentif, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoBiayaInsentif]', set_value('t[NamaAkunNoBiayaInsentif]', @$acc_biaya_insentif->Akun_No .' '. @$acc_biaya_insentif->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?php echo form_label('Akun No Hutang Insentif', '', ['class' => 'col-sm-4 col-xs-12 control-label']) ?>
					<div class="col-sm-8 col-xs-12">
						<div class="row lookupbox7-form-control">
							<div class="col-sm-8 col-xs-12">
								<?php echo form_hidden('f[AkunNoHutangInsentif]', set_value('f[AkunNoHutangInsentif]', @$config->AkunNoHutangInsentif, TRUE)); ?>
								<?php echo form_input('t[NamaAkunNoHutangInsentif]', set_value('t[NamaAkunNoHutangInsentif]', @$acc_payable_insentif->Akun_No .' '. @$acc_payable_insentif->Akun_Name, TRUE), [
										'placeholder' => '', 
										'class' => 'form-control lookupbox7-input-search'
									]); ?>
							</div>
							<div class="col-sm-4 col-xs-12">
								<?php echo form_button([
										'type' => 'button',
										'content' => '<i class="fa fa-search"></i>',
										'class' => 'btn btn-block btn-primary lookupbox7-btn-popup'
									]); ?>
							</div>
						</div>
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
<script src="<?php echo site_url("themes/bracketadmin/vendor/lookupbox7/jquery.lookupbox7.js"); ?>"></script>
<script>
(function( $ ){
		$( document ).ready(function(e) {
            	var _form = $('form[name="form_config"]');
				
				// PT. Apik
				_form.find('input[name="t[NamaAkunLawanPendatanUMUM]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanUMUM]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanUMUM]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanIKS]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanIKS]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanIKS]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanEXECUTIVE]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanEXECUTIVE]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanEXECUTIVE]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendapatanBPJS]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendapatanBPJS]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendapatanBPJS]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanHC]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanHC]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanHC]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanMA]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanMA]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanMA]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				
				<?php if(config_item('multi_bo') == 'TRUE'): ?>
				// Dokter Spesialis
				_form.find('input[name="t[NamaAkunLawanPendatanUMUM_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanUMUM_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanUMUM_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanIKS_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanIKS_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanIKS_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanEXECUTIVE_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanEXECUTIVE_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanEXECUTIVE_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendapatanBPJS_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendapatanBPJS_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendapatanBPJS_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanHC_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanHC_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanHC_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunLawanPendatanMA_2]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_2'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunLawanPendatanMA_2]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunLawanPendatanMA_2]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				<?php endif;?>
				
				_form.find('input[name="t[NamaAkunNoPotongHonor]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoPotongHonor]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoPotongHonor]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunNoOthers]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoOthers]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoOthers]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				
				// OBAT BEBAS
				_form.find('input[name="t[NamaAkunPendapatanObatBebas]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunPendapatanObatBebas]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunPendapatanObatBebas]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunBayar_OB_Tunai]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunBayar_OB_Tunai]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunBayar_OB_Tunai]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunBayar_OB_Asuransi]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunBayar_OB_Asuransi]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunBayar_OB_Asuransi]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunBayar_OB_Perusahaan]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunBayar_OB_Perusahaan]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunBayar_OB_Perusahaan]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunBayar_OB_Kredit]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunBayar_OB_Kredit]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunBayar_OB_Kredit]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunBayar_OB_LOG]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunBayar_OB_LOG]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunBayar_OB_LOG]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				
				// BPJS
				_form.find('input[name="t[NamaAkunNoKeuntunganBPJS]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoKeuntunganBPJS]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoKeuntunganBPJS]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunNoKerugianBPJS]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoKerugianBPJS]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoKerugianBPJS]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaIDAkunPiutangBPJS]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[IDAkunPiutangBPJS]"]').val(v.Akun_ID);
								_form.find('input[name="t[NamaIDAkunPiutangBPJS]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				
				// L.O.G
				_form.find('input[name="t[NamaIDAkunPiutangLOG]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[IDAkunPiutangLOG]"]').val(v.Akun_ID);
								_form.find('input[name="t[NamaIDAkunPiutangLOG]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				
				// BIAYA
				_form.find('input[name="t[NamaAkunNoBiayaInsentif]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoBiayaInsentif]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoBiayaInsentif]"]').val(v.Kode +' '+ v.Nama);
							}
					});
				_form.find('input[name="t[NamaAkunNoHutangInsentif]"]').lookupbox7({
						remote: '<?php echo site_url('verification/preferences/account/lookup/BO_1'); ?>',
						title: 'Daftar Pilihan Rekening',
						columns: [
								{data: "Akun_No", orderable: true, searchable: true, className: 'text-center', width: "150px"},
								{data: "Akun_Name", orderable: true, searchable: true}
							],
						headings: ['Kode','Nama Rekening'],
						order: [],
						placeholder: 'Ketik cari rekening',
						btnApplyText: 'Terapkan Pilihan',
						btnCloseText: 'Tutup',
						onSelected: function(v){
								_form.find('input[name="f[AkunNoHutangInsentif]"]').val(v.Kode);
								_form.find('input[name="t[NamaAkunNoHutangInsentif]"]').val(v.Kode +' '+ v.Nama);
							}
					});
					
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


