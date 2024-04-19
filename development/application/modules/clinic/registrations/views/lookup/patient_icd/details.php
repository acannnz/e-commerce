<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-2 col-sm-12">
    </div>
    <div class="col-md-10 col-sm-12">
        <h5 class="chart-details-title text-success"><b><?php echo 'Diagnosa Pasien' ?></b></h5>
        <dl class="chart-details-list">
            <div class="table-responsive">
                <table id="dt_history" class="table table-sm table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th><?php echo 'Kode ICD' ?></th>      
                            <th><?php echo 'Nama ICD' ?></th>                                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if( $item ): foreach( $item as $row ): ?>
                        <tr>
							<td><?php echo @$row->KodeICD ?></td>                        
                            <td><?php echo @$row->Descriptions; ?></td>                                             
                        </tr>
                        <?php endforeach; 
                        else: ?>
                        <tr>
                            <td colspan="2" align="center"><?php echo'Tidak terdapat data Diagnosa'?></th>                                         
                        </tr>
                        <?php endif;?>
                    </tbody>
                </table>
            </div>
		</dl>
    </div>
</div>


