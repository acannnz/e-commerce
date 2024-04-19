<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="page-subtitle text-primary">
            <h2><i class="fa fa-table"></i> <?php echo lang("reports:latest_registrations_subtitle") ?></h2>
            <div class="pull-right">
                <div class="btn-group">
                    <?php if($items_count): ?>
                    <a href="<?php echo base_url("registrations") ?>" class="btn btn-xs btn-clean btn-primary"><i class="fa fa-eye"></i> <?php echo lang("buttons:view_all") ?></a>
                	<?php endif ?>
                </div>
            </div>
        </div>
        <?php if($items_count): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-report">
                <tbody>
                    <tr>
                        <th width="15%"><?php echo lang("reports:registration_number_label") ?></th>
                        <th><?php echo lang("reports:name_label") ?></th>
                        <th><?php echo lang("reports:mrn_label") ?></th>
                        <th width="20%"><?php echo lang("reports:address_label") ?></th>
                        <th><?php echo lang("reports:status_label") ?></th>
                        <th><?php echo lang("reports:date_label") ?></th>
                    </tr>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><span class="label label-primary"><?php echo @$item->registration_number ?></span></td>
                        <td><i class="fa fa-user-md text-primary"></i> <strong><?php echo @$item->personal_name ?></strong></td>
                        <td><span class="text-danger"><?php echo @$item->mr_number ?></span></td>
                        <?php /*?><td><?php echo @format_address(@$item->personal_address, @$item->area_name, @$item->district_name, @$item->county_name) ?></td><?php */?>
                        <td><?php echo (@$item->area_name ? @$item->area_name : "-") ?></td>
                        <td class="text-center">
                        	<?php if(3 == @$item->state): ?><span class="text-success text-upper uppercase"><?php echo lang("reports:completed") ?></span>
                        	<?php elseif(2 == @$item->state): ?><span class="text-warning text-upper uppercase"><?php echo lang("reports:incomplete") ?></span>
                            <?php else: ?><span class="text-danger text-upper uppercase"><?php echo lang("reports:registered") ?></span>
							<?php endif ?>
                        </td>
                        <td><i class="fa fa-clock-o"></i> <?php echo @strftime( lang("global:format_datetime"), @$item->created_at ) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-info alert-dismissible" role="alert">
            <strong><?php echo lang("global:alert_info") ?></strong> <?php echo lang("reports:empty_registration") ?>
        </div>
        <?php endif ?>
    </div>
</div>