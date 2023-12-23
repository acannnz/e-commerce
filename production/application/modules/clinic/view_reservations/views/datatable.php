<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<?php echo form_open( $lookup_reservations, array("id" => "form_reservations", "name" => "form_reservations") ); ?>
<div class="panel panel-info panel-collapsed">
	<div class="panel-heading panel-collapse">
		<h3 class="panel-title"><?php echo 'Info Dokter' ?></h3>
		<ul class="panel-btn">
			<li><a href="javascript:;" class="btn btn-info panel-collapse" title="Tampilkan"><i class="fa fa-angle-down"></i></a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3 class="text-primary"><i class="fa fa-user pull-left text-primary"></i><?php echo 'Dokter' ?></h3>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Kode Dokter' ?></label>
					<div class="col-lg-9">
						<input type="text" id="NRM" name="f[NRM]" value="<?php echo @$item->NRM ?>" placeholder="" class="form-control" maxlength="8" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Nama Dokter' ?></label>
					<div class="col-lg-9">
						<input type="text" id="NamaPasien" name="p[NamaPasien]" value="<?php echo @$item->NamaPasien ?>" placeholder="" class="form-control patient" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Alamat' ?></label>
					<div class="col-lg-9">
						<input type="text" id="Alamat" name="p[Alamat]" value="<?php echo @$item->Alamat ?>" class="form-control patient" disabled>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'DOB' ?></label>
					<div class="col-lg-3">
						<input type="text" id="TglLahir" name="p[TglLahir]" value="<?php echo @$item->TglLahir ?>" placeholder="" class="form-control datepicker patient" disabled>
					</div>
					<div class="col-lg-1">
						<input type="text" id="UmurThn" name="f[UmurThn]" value="<?php echo @$item->UmurThn ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo 'Tahun' ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurBln" name="f[UmurBln]" value="<?php echo @$item->UmurBln ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo 'Bulan' ?></label>
					<div class="col-lg-1">
						<input type="text" id="UmurHr" name="f[UmurHr]" value="<?php echo @$item->UmurHr ?>" placeholder="" class="form-control" readonly>
					</div>
					<label class="col-lg-1 control-label"><?php echo 'Hari' ?></label>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Phone' ?></label>
					<div class="col-lg-9">
						<input type="text" id="Phone" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Email' ?></label>
					<div class="col-lg-9">
						<input type="text" id="Phone" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?php echo 'Spesialis' ?></label>
					<div class="col-lg-9">
						<input type="text" id="Phone" value="<?php echo @$item->Phone ?>" placeholder="" class="form-control cooperation" disabled="disabled">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="page-subtitle">
					<h3 class="text-primary"><i class="fa fa-calendar pull-left text-primary"></i><?php echo 'Jadwal' ?></h3>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<div class="table-responsive">
							<table id="dt_jadwal_dok" class="table table-sm table-bordered" width="100%">
								<thead>
									<tr>
										<th></th>
										<th>Hari</th>
										<th>Waktu / Jam</th>                        
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
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo 'View Reservasi' ?></h3>
			</div>
            <div class="panel-body">  
				<div class="row">
					<div class="col-md-6">
						<div class="col-md-3">
							<div class="form-group">
								<label class="control-label"><?php echo 'Periode' ?></label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<input type="text" id="month" name="f[month]" data-type="month" class="form-control searchable datepicker" data-date-format="YYYY-MM" value="<?= date('Y-m') ?>" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-6">
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>1" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 1</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>2" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 2</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>3" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 3</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>4" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 4</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>5" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 9</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>6" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 10</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>7" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 11</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 12</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 17</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 18</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 19</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 20</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 25</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 26</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 27</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 28</b></a>
						</div>
					</div>
					<div class="col-md-6">
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 5</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 6</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger btn-block"><b><i class="fa fa-calendar"></i> 7</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 8</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 13</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 14</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger btn-block"><b><i class="fa fa-calendar"></i> 15</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 16</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 21</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 22</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger btn-block"><b><i class="fa fa-calendar"></i> 23</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-success btn-block"><b><i class="fa fa-calendar"></i> 24</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-info btn-block"><b><i class="fa fa-calendar"></i> 29</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-warning btn-block"><b><i class="fa fa-calendar"></i> 30</b></a>
						</div>
						<div class="col-md-3">
							<a href="<?php echo @$lookup_reservations ?>" data-toggle="lookup-ajax-modal" class="btn btn-danger btn-block"><b><i class="fa fa-calendar"></i> 31</b></a>
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

	$.fn.extend({
		dt_jadwal_dok: function(){
			var _this = this;
			
			if( $.fn.dataTable.isDataTable( _this.attr("id") ) ){
				return _this
			}
			
			_datatable = _this.DataTable( {
					processing: true,
					serverSide: false,								
					paginate: false,
					ordering: false,
					searching: false,
					info: false,
					autoWidth: false,
					responsive: true,
					<?php if (!empty($collection)):?>
					data: <?php print_r(json_encode(@$collection, JSON_NUMERIC_CHECK));?>,
					<?php endif; ?>
					columns: [
							{ 
								data: "Konsul_SectionID", 
								className: "actions text-center", 
								render: function( val, type, row, meta ){
										return String("<a href=\"javascript:;\" title=\"<?php echo lang( "buttons:remove" ) ?>\" class=\"btn btn-danger btn-remove\"><i class=\"fa fa-times\"></i></a>")
									} 
							},
							{ 
								data: "SectionName", 
								className: "", 
							},
							{ data: "Nama_Supplier", className: "" },
						],
					drawCallback: function( settings ) {
						dev_layout_alpha_content.init(dev_layout_alpha_settings);
					},
					createdRow: function ( row, data, index ){
							$( row ).on( "dblclick", "td", function(e){
									e.preventDefault();												
									var elem = $( e.target );
									_datatable_actions.edit.call( elem, row, data, index );
								});
								
							$( row ).on( "click", "a.btn-remove", function(e){
									e.preventDefault();												
									var elem = $( e.target );
									
									if( confirm( "<?php echo lang('global:delete_confirm') ?>" ) ){
										_datatable_actions.remove( data, function(){ _datatable.ajax.reload() }, row )
									}
								})
						}
				} );
				
			$( "#dt_jadwal_dok_length select, #dt_jadwal_dok_filter input" )
			.addClass( "form-control" );
			
			return _this
		},
	});

	$( document ).ready(function(e) {
			$( "#dt_jadwal_dok" ).dt_jadwal_dok();
			
	});

	$('#month').on('dp.hide', function(ev) {
			
			params = {type: $(this).data('type'), date: $(this).val() };

			console.log(params);			
		});

})( jQuery );
//]]>
</script>