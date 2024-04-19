<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="modal-dialog modal-xlg">
    <div class="modal-content">
        <div class="modal-header"> 
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <h4 class="modal-title"><i class="fa fa-search"></i> Lihat Detail Jasa</h4>
        </div>
        <div class="modal-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <div class="form-group">
                    	<label class="control-label col-md-3">ID Jasa</label>
                        <div class="col-md-9">
                        	<input type="text" id="JasaID" name="JasaID" class="form-control"  readonly="readonly" />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Deskripsi</label>
                        <div class="col-md-9">
                        	<input type="text" id="JasaName" name="JasaName" class="form-control"  readonly="readonly" />
                        </div>
                    </div>

                    <div class="form-group">
                    	<label class="control-label col-md-3">DokterID</label>
                        <div class="col-md-9">
                        	<input type="text" id="DokterIDService" name="DokterIDService" class="form-control" readonly />
                        </div>
                    </div>
                    <div class="form-group">
                    	<label class="control-label col-md-3">Nama Dokter</label>
                        <div class="col-md-9">
                        	<input type="text" id="Nama_Supplier" name="Nama_Supplier" class="form-control" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-3">Tarif</label>
                        <div class="col-md-9">
                            <input type="text" id="Tariff" name="Tariff" class="form-control"  readonly="readonly" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-group">	
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul id="tab-poly" class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#service-tab1" data-toggle="tab"><i class="fa fa-stethoscope"></i> Daftar Komponen Jasa</a></li>
                            <li><a href="#service-tab2" data-toggle="tab"><i class="fa fa-medkit"></i> Daftar BHP</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="service-tab1" class="tab-pane tab-pane-padding active">
                                <?php echo modules::run("{$nameroutes}s/service_inpatient/service_component", @$indexRow ) ?>
                            </div>
                            <div id="service-tab2" class="tab-pane tab-pane-padding">
                                <?php echo modules::run("{$nameroutes}s/service_inpatient/service_consumable", @$indexRow ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
        	<div class="row form-group">
            	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<script type="text/javascript">
//<![CDATA[
(function( $ ){
		var services_index = "<?php echo $indexRow ?>";
		
		$( document ).ready(function(e) {
							
			var data_service = $("#dt_services").DataTable().row(services_index).data();
			console.log("Service:", data_service);
			$("#JasaID").val(data_service.JasaID);
			$("#JasaName").val(data_service.JasaName);
			$("#DokterIDService").val(data_service.DokterID);
			$("#Nama_Supplier").val(data_service.Nama_Supplier);
			$("#Tariff").val(data_service.Tarif);
		});

	})( jQuery );
//]]>
</script>