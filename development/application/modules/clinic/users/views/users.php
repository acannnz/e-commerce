<?php if ( !defined( 'BASEPATH' ) ){ exit( 'No direct script access allowed' ); }
?>
<div class="page-subtitle margin-bottom-30">
    <div class="row">
        <div class="col-lg-6"><h3><?php echo lang('system_users') ?></h3></div>
        <div class="col-lg-6">
            <a href="<?php echo base_url( 'users/accounts/create' ) ?>" data-toggle="form-ajax-modal" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> <?php echo lang('new_user')?></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">        	
            <table class="table table-bordered table-sortable">
                <thead>
                    <tr>
                        <th><?php echo lang('full_name')?></th>
                        <th><?php echo lang('username')?> </th>
                        <th><?php echo lang('role')?> </th>
                        <th><?php echo lang('registered_on')?> </th>                    
                        <th class="col-options no-sort"><?php echo lang('options')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($users)) {
                    foreach ($users as $key => $user) { ?>
                    <tr>
                        <td><?php echo $user->fullname ?></td>
                        <td>
                            <a class="pull-left thumb-sm avatar">
                                <?php if(config_item('use_gravatar') == 'TRUE' AND Applib::get_table_field(Applib::$profile_table,array('user_id'=>$user->id),'use_gravatar') == 'Y'){
                                $user_email = Applib::login_info($user->id)->email; ?>
                                <img src="<?php echo $this->applib->get_gravatar($user_email)?>" class="img-circle">
                                <?php }else{ ?>
                                <img src="<?php echo base_url()?>resource/avatar/<?php echo Applib::profile_info($user->id)->avatar?>" class="img-circle">
                                <?php } ?>
                                 <span class="label label-success">
                                <?php echo ucfirst($user->username)?>
                                </span>
                            </a>
                        </td>
                        
                        <td><?php
                                    if ($this->user_profile->role_by_id($user->role_id) == 'admin') {
                                        $span_badge = 'label label-danger';
                                    }elseif ($this->user_profile->role_by_id($user->role_id) == 'staff') {
                                        $span_badge = 'label label-info';
                                    }elseif ($this->user_profile->role_by_id($user->role_id) == 'client') {
                                        $span_badge = 'label label-default';
                                    }else{
                                        $span_badge = '';
                                    }
                            ?><span class="<?php echo $span_badge?>">
                        <?php echo ucfirst($this->user_profile->role_by_id($user->role_id))?></span></td>
                        <td class="hidden-sm"><?php echo strftime(config_item('date_format'), strtotime($user->created));?> </td>
                        
                        <td>
                            <a href="<?php echo base_url()?>users/accounts/auth/<?php echo $user->user_id ?>" class="btn btn-default btn-xs" data-toggle="ajax-modal" title="<?php echo lang('user_edit_login') ?>"><i class="fa fa-lock"></i> <?php echo lang('user_edit_login')?></a>
                            <a href="<?php echo base_url()?>users/accounts/edit/<?php echo $user->user_id ?>" class="btn btn-default btn-xs" data-toggle="ajax-modal" title="<?php echo lang('edit') ?>"><i class="fa fa-edit"></i> </a>
                            <?php if ($user->username != $this->tank_auth->get_username()): ?>
                            <a href="<?php echo base_url()?>users/accounts/delete/<?php echo $user->user_id ?>" class="btn btn-danger btn-xs" data-toggle="ajax-modal" title="<?php echo lang('delete') ?>"><i class="fa fa-trash-o"></i></a>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php } } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

