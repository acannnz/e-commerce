<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="table-responsive">
    <table id="table-staff" class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo lang('permissions:full_name')?></th>
                <th><?php echo lang('permissions:username')?> </th>
                <th><?php echo lang('permissions:role')?> </th>
                <th class="hidden-sm"><?php echo lang('permissions:registered_on')?> </th>    
                <th class="col-options no-sort"><?php echo lang('permissions:options')?></th>
            </tr> 
        </thead> 
        <tbody>
        <?php if( isset($users) && !empty($users) ): ?>
        <?php foreach( $users as $key => $user ): ?>
            <tr>
                <td><?php echo $user->fullname ?></td>
                <td><?php echo $user->username ?></td>
                <td><span class="label label-primary"><?php echo ucfirst($this->user_profile->role_by_id($user->role_id))?></span></td>
                <td class="hidden-sm"><?php echo strftime(config_item('date_format'), strtotime($user->created));?></td>
                <td>
                    <a href="<?php echo base_url( "settings/permissions/permission/{$user->user_id}" ) ?>" class="btn btn-default btn-sm" title="<?php echo lang('button:edit_permissions')?>">
                        <i class="fa fa-edit"></i> <?php echo lang('button:edit_permissions')?> 
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>


