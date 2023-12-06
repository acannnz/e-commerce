<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-5 col-sm-12">
        <h4 class="chart-details-title text-success"><b><?php echo lang("registrations:personal_subtitle") ?></b></h4>
        <br>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:gender_label") ?></dt>
            <dd>: <?php echo ( @$item->JenisKelamin ) ? lang("global:".strtolower(@$item->JenisKelamin)): "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:birth_date_label") ?></dt>
            <dd>: <?php echo ( @$item->TglLahir ) ? strftime(lang("global:format_date"), strtotime(@$item->TglLahir)) : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:age_label") ?></dt>
            <dd>: <?php echo (int) @$item->UmurThn ?> Tahun</dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo 'Negara' ?></dt>
            <dd>: <?php echo ( @$item->Nationality ) ? @$item->Nationality : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:address_label") ?></dt>
            <dd>: <?php echo @$item->Alamat ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:phone_label") ?></dt>
            <dd>: <?php echo ( @$item->Phone ) ? @$item->Phone : "n/a" ?></dd>
        </dl>
        <dl class="chart-details-list">
            <dt class="text-default uppercase"><?php echo lang("registrations:email_label") ?></dt>
            <dd>: <?php echo ( @$item->Email ) ? @$item->Email : "n/a" ?></dd>
        </dl>
    </div>
    <div class="col-md-7 col-sm-12">
        <h4 class="chart-details-title text-success"><b><?php echo lang("registrations:destionation_subtitle") ?></b></h4>
		
        <dl class="chart-details-list">
            <div class="table-responsive">
                <table id="dt_registration_section" class="table table-sm table-bordered" width="100%">
                    <thead>
                        <tr>
                            <!-- <th></th> -->
							<th><?php echo lang("registrations:section_label") ?></th>
                            <th><?php echo lang("registrations:time_label") ?></th>                        
                            <th><?php echo lang("registrations:no_label") ?></th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if( $section_destination ): foreach( $section_destination as $row ): ?>
                        <tr>
                            <!-- <td><?php echo $i++;?></th> -->
							<?php /* <td><?php echo @$row->SectionName .' - '. @$row->Kamar ?></td> */ ?>  
                            <td><?php echo @$row->SectionName ?></td>                  
                            <td><?php echo @$row->estimation_time; //$row->Keterangan ?></td>                        
                            <td class="text-center"><?php echo @$row->NoAntri ?></td>                        
                        </tr>
                        <?php endforeach; endif;?>
                    </tbody>
                </table>
            </div>
		</dl>
    </div>
</div>


