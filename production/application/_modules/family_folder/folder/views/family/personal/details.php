<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="row">
    <div class="col-md-6">
        <h4 class="subtitle"><b><?php echo lang("heading:family_personal") ?></b></h4>
	</div>
	<div class="col-md-6 text-right">
        <a href="javscript:;" data-action-url="<?php echo $add_personal_url ?>" data-act="ajax-modal" data-title="<?php echo lang('heading:family_personal_add')?>" data-modal-lg="1" title="<?php echo lang( "buttons:add" ) ?>" class="btn btn-success btn-xs"><i class="fa fa-user-plus"></i> <?php echo lang( "buttons:add" ) ?></a>
    </div>
	<div class="col-md-12">
        <dl class="chart-details-list">
            <div class="table-responsive">
                <table id="dt_family_personal" class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo lang("label:name") ?></th>
                            <th><?php echo lang("label:relation") ?></th>                        
                            <th class="text-center"><?php echo lang("label:index") ?></th>                        
							<th class="text-center"><?php echo lang("label:patriarch") ?></th>                        
							<th><?php echo lang("label:gender") ?></th>                        
							<th><?php echo lang("label:mobile") ?></th>                        
                            <th><?php echo lang("label:address") ?></th>                        
                            <th><?php echo lang("global:status") ?></th>   
							<th class="text-center"><i class="fa fa-cog"></i></th>                                             
						</tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if( $collection ): foreach( $collection as $row ): ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></th>
                            <td><?php echo $row->PersonalName ?></td>
                            <td><?php echo lang( sprintf("label:%s", strtolower($row->Relation)) )?></td>
                            <td class="text-center"><?php echo $row->Index ?></td>                 
							<td class="text-center"><?php echo $item->PersonalIdKK == $row->PersonalId ? '&radic;' : NULL ?></td>
							<td><?php echo $row->PersonalGender == 'M' ? lang('global:male') : lang('global:female') ?></td>
							<td><?php echo $row->MobileNumber ?></td>
							<td><?php echo $row->PersonalAddress ?></td>
							<td><?php echo $row->Status ? lang('global:active') : lang('global:inactive') ?></td>                        
							<td><a href="javascript:;" data-action-url="<?php echo base_url("{$nameroutes}/personal_update/{$row->FamilyId}/{$row->PersonalId}") ?>" data-act="ajax-modal" data-title="<?php echo lang('heading:personal_update')?>" data-modal-lg="1" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> <?php echo lang('buttons:edit')?></a></td>
                        </tr>
                        <?php endforeach; endif;?>
                    </tbody>
                </table>
            </div>
		</dl>
    </div>
</div>




