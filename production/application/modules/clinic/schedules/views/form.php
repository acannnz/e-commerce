<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<?php echo form_open( current_url(), array("id" => "form_schedules", "name" => "form_schedules") ); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{heading}}</h3>
			</div>
            <div class="panel-body">
                <br>
            <div class="page-subtitle">
                <h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo lang('schedules:dokter_label') ?></h3>
            </div>
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('schedules:doctor_number_label') ?></label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" id="DokterID" name="f[DokterID]" value="<?php echo @$item->DokterID ?>" placeholder="" class="form-control" required>
                    <span class="input-group-btn">
	                    <a href="<?php echo $url_lookup_suppliers ?>" data-toggle="lookup-ajax-modal" class="btn btn-default" ><i class="fa fa-search"></i></a>
	                    <a href="javascript:;" id="clear_patient" class="btn btn-default" ><i class="fa fa-times"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('schedules:doctor_name_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Nama_Supplier" name="f[Nama_Supplier]" placeholder="" class="form-control" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('schedules:specialist_label') ?></label>
            <div class="col-lg-9">
                <input type="text" id="Specialist" name="f[Specialist]" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?php echo lang('schedules:section_label') ?></label>
            <div class="col-lg-9">
            	<select id="SectionID" name="f[SectionID]" class="form-control">
                	<?php if ($option_section): foreach($option_section as $row ): ?>
                	<option value="<?php echo $row->SectionID ?>" <?php echo @$row->SectionID == @$item->SectionID  ? "selected" : NULL  ?>><?php echo $row->SectionName ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="col-lg-12"><br /><br /></div>
    <div class="col-lg-12">
        <div class="page-subtitle">
            <h3 class="text-primary"><i class="fa fa-calendar pull-left text-primary"></i><?php echo lang('schedules:hari_label') ?></h3>
        </div>

    	<div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Senin" name="Mon[Senin]" value="1"><label style="color:#FFF" for="Senin"><?php echo lang('schedules:monday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="SeninRuangan" name="Mon[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Senin_WaktuPagiID" name="Mon[Senin_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Senin_WaktuSoreID" name="Mon[Senin_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Selasa" name="Thu[Selasa]" value="1"><label style="color:#FFF" for="Selasa"><?php echo lang('schedules:tuesday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="SelasaRuangan" name="Thu[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Selasa_WaktuPagiID" name="Thu[Selasa_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Selasa_WaktuSoreID" name="Thu[Selasa_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Rabu" name="Wed[Rabu]" value="1"><label style="color:#FFF" for="Rabu"><?php echo lang('schedules:wednesday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="RabuRuangan" name="Wed[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Rabu_WaktuPagiID" name="Wed[Rabu_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Rabu_WaktuSoreID" name="Wed[Rabu_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Kamis" name="Thi[Kamis]" value="1"><label style="color:#FFF" for="Kamis"><?php echo lang('schedules:thursday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="KamisRuangan" name="Thi[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Kamis_WaktuPagiID" name="Thi[Kamis_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Kamis_WaktuSoreID" name="Thi[Kamis_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Jumat" name="Fri[Jumat]" value="1"><label style="color:#FFF" for="Jumat"><?php echo lang('schedules:friday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="JumatRuangan" name="Fri[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Jumat_WaktuPagiID" name="Fri[Jumat_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Jumat_WaktuSoreID" name="Fri[Jumat_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Sabtu" name="Sat[Sabtu]" value="1"><label style="color:#FFF" for="Sabtu"><?php echo lang('schedules:saturday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="SabtuRuangan" name="Sat[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Sabtu_WaktuPagiID" name="Sat[Sabtu_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Sabtu_WaktuSoreID" name="Sat[Sabtu_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-4">
            <div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<div class="col-md-3">
                        	<div class="checkbox">
                              <input type="checkbox" id="Minggu" name="Sun[Minggu]" value="1"><label style="color:#FFF" for="Minggu"><?php echo lang('schedules:sunday_label') ?></label>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:room_label') ?></label>
                                    <div class="col-lg-9">
                                        <select id="MingguRuangan" name="Sun[Ruangan]" class="form-control">
											<?php for($i = 1; $i <= 6 ; $i++):?>
                                            <option value="<?php echo $i ?>" <?php echo $i == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>        
                                </div>        
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"><?php echo lang('schedules:morning_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Minggu_WaktuPagiID" name="Sun[Minggu_WaktuPagiID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                    <label class="col-lg-3 control-label text-center"><?php echo lang('schedules:afternoon_label') ?></label>
                                    <div class="col-lg-3">
                                        <select id="Minggu_WaktuSoreID" name="Sun[Minggu_WaktuSoreID]" class="form-control">
                                        	<option value="1">NONE</option>
											<?php if(!empty($option_times)): foreach($option_times as $row):?>
                                            <option value="<?php echo $row->WaktuID ?>" <?php echo $row->WaktuID == @$item->WaktuID ? "selected" : NULL  ?>><?php echo $row->Keterangan ?></option>
                                            <?php endforeach; endif;?>
                                        </select>
                                    </div>        
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
        <div class="col-lg-8">
        	<div class="form-group"> 
                <div class="panel panel-danger">
                    <div class="panel-heading bg-default">
                    	<h1 class="panel-title"><?php echo lang('schedules:generate_label') ?></h1>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-2"><?php echo lang('schedules:datestart_label') ?></label>
                                <div class="col-lg-2">
                                    <input type="text" id="date_start" class="form-control datepicker" value="" name="date_start" required="required" />
                                </div>
                                <label class="col-lg-2 text-center"><?php echo lang('schedules:dateend_label') ?></label>
                                <div class="col-lg-2">
                                    <input type="text"  id="date_end" class="form-control datepicker" value="" name="date_end" required="required" />
                                </div>
                                <div class="col-lg-4">
                                	<a href="javascript:;" class="btn btn-primary btn-block" id="generate"><b><i class="fa fa-spinner"></i> Generate</b></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   	</div>  
    <div class="col-md-12">
    	<div class="page-subtitle">
            <h3 class="text-primary"><i class="fa fa-calendar pull-left text-primary"></i><?php echo lang('schedules:schedule_label') ?></h3>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="dt_schedules" class="table table-sm table-bordered" width="100%">
                        <thead>
                            <tr>
                                <!-- <th>No</th> -->
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Pukul</th>                        
                                <th>Batal</th>                        
                                <th>ID Dokter Pengganti</th>                        
                                <th>Nama Dokter Pengganti</th>                        
                                <th>Ruangan</th>                        
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
<div class="panel-body">
    <div class="form-group">
        <div class="col-lg-12 text-right">
            <a href="<?php echo base_url("schedules/create")?>" class="btn btn-info"><b><i class="fa fa-file"></i> Buat Baru</b></a>
            <button type="submit" class="btn btn-primary"><b><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo lang( 'buttons:submit' ) ?></b></button>
            <?php /*?><button type="button" onclick="(function(e){window.history.go(-1);})(this)" class="btn btn-default"><?php echo lang( 'buttons:cancel' ) ?></button><?php */?>
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
		var _datatable;
		
		var _datatable_populate;
		var _datatable_actions = {
				edit: function( row, data, index ){
						
						switch( this.index() ){
														
							case 3:
								if ( data.Cancel == 0 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\" selected>Tidak</option>\n<option value=\"1\">Ya</option>\n</select>" );
								} else if ( data.Cancel == 1 ) {
									var _input = $( "<select style=\"width:100%\" class=\"form-control\">\n<option value=\"0\">Tidak</option>\n<option value=\"1\" selected>Ya</option>\n</select>" );
								}
								
								this.empty().append( _input );
								_input.trigger( "focus" );
								_input.on( "blur", function( e ){
										e.preventDefault();
										try{
											$( e.target ).remove();
											_datatable.row( row ).data( data );
										} catch(ex){}
									});
								
								_input.on( "change", function( e ){
										e.preventDefault();
																				
										try{

											data.Cancel =  $( e.target ).find( "option:selected" ).val() || 0;
											
											_datatable.row( row ).data( data );
										} catch(ex){console.log(ex);}
									});
							break;

							case 4:
								try{
									index = _datatable.row( row ).index();
									
									lookup_ajax_modal.show("<?php echo $url_lookup_replacement_suppliers ?>/"+ index)
								} catch(ex){}
							break;

							case 5:
								try{
									if ( data.DokterPenggantiID != "" )
									{	
										data.DokterPenggantiID= "";
										data.Nama_Supplier = "";
										_datatable.row( row ).data( data );
									}
								} catch(ex){}
							break;
							
						}
					},
				remove: function( params, scope ){
						
						_datatable.row( scope )
								.remove()
								.draw(false);						
					},
                remove_replacement_supplier: function( params, scope ){
                        params.Nama_Supplier = '';
                        params.DokterPenggantiID = '';
                        _datatable.row( scope ).data( params );						
					},
				calculate_balance: function(params, fn, scope){
						
						var _form = $( "form[name=\"form_general_ledger\"]" );
						var _form_debit = _form.find( "input[id=\"debit\"]" );
						var _form_credit = _form.find( "input[id=\"credit\"]" );
						var _form_balance = _form.find( "input[id=\"balance\"]" );
						var _form_submit = _form.find( "button[id=\"btn-submit\"]" );
						
						var tol_debit = 0, 
							tol_credit = 0, 
							tol_balance = 0;
						
						var rows = _datatable.rows().nodes();
						
						for( var i=0; i<rows.length; i++ )
						{
							
							tol_debit = tol_debit + Number($(rows[i]).find("td:eq(3)").html());
							tol_credit = tol_credit + Number($(rows[i]).find("td:eq(4)").html());
							
						}
						
						tol_balance = tol_debit - tol_credit;

						_form_debit.val(tol_debit);	
						_form_credit.val(tol_credit);
						_form_balance.val(tol_balance);
						
						if (tol_balance == 0)
						{
							_form_balance.removeClass("text-danger");
							_form_submit.removeAttr("disabled");
						} else {
							_form_balance.addClass("text-danger");
							_form_submit.attr("disabled");
						}			
						
					},
				add_row: function( params, fn, scope ){
						_datatable.row.add(
							{
							}
						).draw(false);
						
						
					}
					
					
			};
		
		$.fn.extend({
				dt_schedules: function(){
						var _this = this;
						
						if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
							return _this
						}
						
						_datatable = _this.DataTable( {
								processing: true,
								serverSide: false,								
								paginate: true,
								ordering: false,
								lengthMenu: [ 20, 50, 100 ],
								searching: true,
								info: false,
								autoWidth: false,
								responsive: true,
								data: [],
								columns: [
										// { data: "Tanggal", className: "text-center", },
										{ data: "Tanggal", className: "text-center", 
                                            render: function( val ){
												return moment(val).format('DD MMM YYYY')
											}
                                        },
										{ data: "Hari", className: "", },
										{ data: "Keterangan", className: "text-left" },
										{ 
											data: "Cancel", className: "text-center", 
											render: function( val ){
												return val ? "Ya" : "Tidak"
											}
										},
										{ data: "DokterPenggantiID", className: "text-center" },
										{ data: "Nama_Supplier", className: "text-left", },
										{ data: "Ruangan", className: "text-center", },
									
										
									],
								columnDefs  : [
										{
											"targets": ["WaktuID"],
											"visible": false,
											"searchable": false
										}
									],
								drawCallback: function( settings ) {
									dev_layout_alpha_content.init(dev_layout_alpha_settings);
								},
								// fnRowCallback : function( nRow, aData, iDisplayIndex , iDisplayIndexFull ) {
								// 		var index = iDisplayIndexFull + 1;
								// 		$('td:eq(0)',nRow).html(index);
								// 		return nRow;					
								// 	},
								createdRow: function ( row, data, index ){
										$( row ).on( "dblclick", "td", function(e){
											e.preventDefault();												
											var elem = $( e.target );
											_datatable_actions.edit.call( elem, row, data, index );
										});

                                        $( row ).on( "click", "a.btn-remove-replacement-supplier", function(e){
												e.preventDefault();												
												var elem = $( e.target );
												
												if( confirm( "<?php echo lang('global:delete_confirm') ?>, data yang dihapus akan hilang" ) ){
													_datatable_actions.remove_replacement_supplier( data, row)
												}
											})
									}
							} );
							
						$( "#dt_schedules_length select, #dt_schedules_filter input" )
						.addClass( "form-control" );
						
						return _this
					},
			});
		

		
		$( document ).ready(function(e) {
            	$( "#dt_schedules" ).dt_schedules();
				
				$("#generate").on("click", function(e){
					
					if ( $("#date_start").val() == '' || $("#date_start").val() == '')
					{
						alert("Tanggal Jadwal Praktek Dibutuhkan!");
						return false;
					}
					
					var data_post  = {};
						data_post['Day'] = {};
						data_post['Day']['Mon'] = {};
						data_post['Day']['Tue'] = {};
						data_post['Day']['Wed'] = {};
						data_post['Day']['Thu'] = {};
						data_post['Day']['Fri'] = {};
						data_post['Day']['Sat'] = {};
						data_post['Day']['Sun'] = {};
					
					data_post['DokterID'] = $("#DokterID").val();
					data_post['SectionID'] = $("#SectionID").val();
					data_post['date_start'] = $("#date_start").val();
					data_post['date_end'] = $("#date_end").val();
					
					if ( $("#Senin").is(":checked") )
					{
						data_post['Day']['Mon'] = {
							"Ruangan" : $("#SeninRuangan").val(),
							"WaktuPagiID" : $("#Senin_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Senin_WaktuSoreID").val(),
						}
					}

					if ( $("#Selasa").is(":checked") )
					{
						data_post['Day']['Tue'] = {
							"Ruangan" : $("#SelasaRuangan").val(),
							"WaktuPagiID" : $("#Selasa_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Selasa_WaktuSoreID").val(),
						}					
					}

					if ( $("#Rabu").is(":checked") )
					{
						data_post['Day']['Wed'] = {
							"Ruangan" : $("#RabuRuangan").val(),
							"WaktuPagiID" : $("#Rabu_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Rabu_WaktuSoreID").val(),
						}					
					}

					if ( $("#Kamis").is(":checked") )
					{
						data_post['Day']['Thu'] = {
							"Ruangan" : $("#KamisRuangan").val(),
							"WaktuPagiID" : $("#Kamis_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Kamis_WaktuSoreID").val(),
						}					
					}

					if ( $("#Jumat").is(":checked") )
					{
						data_post['Day']['Fri'] = {
							"Ruangan" : $("#JumatRuangan").val(),
							"WaktuPagiID" : $("#Jumat_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Jumat_WaktuSoreID").val(),
						}					
					}

					if ( $("#Sabtu").is(":checked") )
					{
						data_post['Day']['Sat'] = {
							"Ruangan" : $("#SabtuRuangan").val(),
							"WaktuPagiID" : $("#Sabtu_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Sabtu_WaktuSoreID").val(),
						}					
					}

					if ( $("#Minggu").is(":checked") )
					{
						data_post['Day']['Sun'] = {
							"Ruangan" : $("#MingguRuangan").val(),
							"WaktuPagiID" : $("#Minggu_WaktuPagiID").val(),
							"WaktuSoreID" : $("#Minggu_WaktuSoreID").val(),
						}					
					}
																	
					$.post('<?php echo $url_schedule_generate ?>', data_post, function( response, status, xhr ){
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);
						
						$( "#dt_schedules" ).DataTable().clear().draw(true);
						$( "#dt_schedules" ).DataTable().rows.add( response.generate_schedules ).draw(true);
						
					});	

				});			
				
				$("#form_schedules").on("submit", function(e){
					e.preventDefault();
					
					if ( !confirm("Apakah Anda Yakin Ingin Menyimpan Data ini ?"))
					{
						return false;
					}
					
					var data_post  = {};
						data_post['header'] = {};
						data_post['detail'] = {};
					
					data_post['header'] = {
						"DokterID" : $("#DokterID").val(),
						"SectionID" : $("#SectionID").val(),
						"Senin" : $("#Senin:checked").val() || 0,
						"Selasa" : $("#Selasa:checked").val() || 0,
						"Rabu" : $("#Rabu:checked").val() || 0,
						"Kamis" : $("#Kamis:checked").val() || 0,
						"Jumat" : $("#Jumat:checked").val() || 0,
						"Sabtu" : $("#Sabtu:checked").val() || 0,
						"Minggu" : $("#Minggu:checked").val() || 0,
						"Senin_WaktuPagiID" : $("#Senin_WaktuPagiID").val() || "",
						"Senin_WaktuSoreID" : $("#Senin_WaktuSoreID").val() || "",
						"Selasa_WaktuPagiID" : $("#Selasa_WaktuPagiID").val() || "",
						"Selasa_WaktuSoreID" : $("#Selasa_WaktuSoreID").val() || "",
						"Rabu_WaktuPagiID" : $("#Rabu_WaktuPagiID").val() || "",
						"Rabu_WaktuSoreID" : $("#Rabu_WaktuSoreID").val() || "",
						"Kamis_WaktuPagiID" : $("#Kamis_WaktuPagiID").val() || "",
						"Kamis_WaktuSoreID" : $("#Kamis_WaktuSoreID").val() || "",
						"Jumat_WaktuPagiID" : $("#Jumat_WaktuPagiID").val() || "",
						"Jumat_WaktuSoreID" : $("#Jumat_WaktuSoreID").val() || "",
						"Sabtu_WaktuPagiID" : $("#Sabtu_WaktuPagiID").val() || "",
						"Sabtu_WaktuSoreID" : $("#Sabtu_WaktuSoreID").val() || "",
						"Minggu_WaktuPagiID" : $("#Minggu_WaktuPagiID").val() || "",
						"Minggu_WaktuSoreID" : $("#Minggu_WaktuSoreID").val() || "",
						
					}
						
					var table_detail = $( "#dt_schedules" ).DataTable().rows().data();
					if ( $.isEmptyObject( table_detail ))
					{
						alert("Anda Belum Generate Jadwal Praktek! Silahkan Generate Terlebih Dahulu.");
						return false;
					}
					
					table_detail.each( function(value, index){
						var detail = {
							"Tanggal" : value.Tanggal,
							"WaktuID" : value.WaktuID,
							"Hari" : value.Hari,
							"Cancel" : value.Cancel,
							"DokterPenggantiID" : value.DokterPenggantiID,
						}
						data_post['detail'][index] = detail;
					});
					
					$.post('<?php echo current_url() ?>', data_post, function( response, status, xhr ){
						var response = $.parseJSON(response);

						if( "error" == response.status ){
							$.alert_error(response.message);
							return false
						}
						
						$.alert_success(response.message);

						setTimeout(function(){
													
							document.location.href = "<?php echo base_url("schedules/edit"); ?>/"+ response.DokterID +"/"+ response.SectionID;
							
						}, 3000 );						
					});	


				});

			});

	})( jQuery );
//]]>
</script>