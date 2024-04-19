<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-6 col-sm-12">
    </div>
    <div class="col-md-6 col-sm-12">
        <h4 class="chart-details-title text-success"><b><?php echo 'Detail Resep' ?></b></h4>
        <dl class="chart-details-list">
            <div class="table-responsive">
                <table id="dt_registration_section" class="table table-sm table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo 'Nama Obat' ?></th>      
                            <th><?php echo 'Satuan' ?></th>                    
                            <th><?php echo 'Jumlah' ?></th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if( $item ): foreach( $item as $row ): ?>
                        <tr>
                            <td><?php echo $i++;?></th>
							<td><?php echo @$row->NamaResepObat ?></td>                        
                            <td><?php echo @$row->Satuan; ?></td>                        
                            <td><?php echo @$row->JmlPemakaian ?></td>                        
                        </tr>
                        <?php endforeach; endif;?>
                    </tbody>
                </table>
            </div>
		</dl>
    </div>
</div>


