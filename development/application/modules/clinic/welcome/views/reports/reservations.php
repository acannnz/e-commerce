<?php if ( ! defined('BASEPATH') ){ exit('No direct script access allowed'); }
?>
<div class="row">
    <div class="col-md-12">
        <div class="page-subtitle text-success">
            <h2><i class="fa fa-table"></i> <?php echo lang("reports:latest_reservations_subtitle") ?></h2>
            <div class="pull-right">
                <div class="btn-group">
                	<?php if($items_count): ?>
                    <a href="<?php echo base_url("reservations") ?>" class="btn btn-xs btn-clean btn-success"><i class="fa fa-eye"></i> <?php echo lang("buttons:view_all") ?></a>
                	<?php endif ?>
                </div>
            </div>
        </div>
        <?php if($items_count): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-report">
                <tbody>
                    <tr>
                        <th width="15%"><?php echo lang("reports:reservation_number_label") ?></th>
                        <th><?php echo lang("reports:name_label") ?></th>
                        <th width="20%"><?php echo lang("reports:address_label") ?></th>
                        <th><?php echo lang("reports:for_date_label") ?></th>
                        <th><?php echo lang("reports:date_label") ?></th>
                    </tr>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td><span class="label label-success"><?php echo @$item->reservation_number ?></span></td>
                        <td><i class="fa fa-user-md text-success"></i> <strong><?php echo @$item->personal_name ?></strong></td>
                        <?php /*?><td><?php echo @format_address(@$item->personal_address, @$item->area_name, @$item->district_name, @$item->county_name) ?></td><?php */?>
                        <td><?php echo (@$item->area_name ? @$item->area_name : "-") ?></td>
                        <td><?php echo ((@$item->schedule_date) ? @strftime( lang("global:format_date"), @strtotime(@$item->schedule_date) ) : 'n/a') ?></td>
                        <td><i class="fa fa-clock-o"></i> <?php echo @strftime( lang("global:format_datetime"), @$item->created_at ) ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong><?php echo lang("global:alert_info") ?></strong> <?php echo lang("reports:empty_reservation") ?>
        </div>
        <?php endif ?>
    </div>
</div>

