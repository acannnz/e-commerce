<?php if ( ! defined('BASEPATH')){ exit('No direct script access allowed'); }
?>

<?php /*?><?php if(config_item('enable_languages') == 'TRUE'): ?>
<ul class="dev-lang-navigation">
    <h4><span class="label"><?php echo lang('languages')?></h4>
    <div class="btn-group">
        <button type="button" class="btn btn-icon btn-danger dropdown-toggle" data-toggle="dropdown" title="<?php echo lang('languages')?>">
            <i class="fa fa-globe"></i>
        </button>
        <ul class="dropdown-menu text-left">
            <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
            <li>
                <a href="<?php echo base_url()?>set_language?lang=<?php echo $lang->name?>" title="<?php echo ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?php echo base_url()?>resource/images/flags/<?php echo $lang->icon ?>.gif" alt="<?php echo ucwords(str_replace("_"," ", $lang->name))?>"  /> <?php echo ucwords(str_replace("_"," ", $lang->name))?>
                </a>
            </li>
            <?php endif; endforeach; ?>
        </ul>
    </div>
</ul>
<?php endif ?><?php */?>

<ul class="dev-page-navigation">
    <li class="title"><?php echo lang("nav:users") ?></li>
    <li<?php if(in_array(@$page, array("", "welcome", "dashboard"))){echo " class=\"active\"";} ?>>
        <a href="<?php echo base_url() ?>"><i class="fa fa-desktop"></i><?php echo lang("nav:dashboard") ?></a>
    </li>
    
    <li class="title"><?php echo lang("nav:servings") ?></li>
    <li<?php if(in_array(@$page, array("reservations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'reservations' ) ?>"><i class="fa fa-plus-square"></i> <?php echo lang("nav:reservation") ?></a></li>
    <li<?php if(in_array(@$page, array("reservations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'reservations/calender' ) ?>"><i class="fa fa-square"></i> <?php echo lang("nav:reservation") ?> Calender</a></li>
    <li<?php if(in_array(@$page, array("registrations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations' ) ?>"><i class="fa fa-user-plus"></i> <b><?php echo lang("nav:registration") ?></b></a></li>
	<!-- <li<?php if(in_array(@$page, array("registrations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations/transfer_inpatient' ) ?>"><i class="fa fa-bed"></i> <?php echo lang("nav:transfer_inpatient") ?></a></li> -->
    <li<?php if(in_array(@$page, array("cashier"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/general-payment' ) ?>"><i class="fa fa-money"></i> <?php echo lang("nav:cashier") ?></a></li>
    
    <li class="title"><?php echo lang("nav:transaction") ?></li>
    <?php /*?><li<?php if(in_array(@$page, array("drug_payment"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/drug-payment' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo lang("nav:drug_payment") ?></a></li><?php */?>
    <li<?php if(in_array(@$page, array("outstanding_payment"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/outstanding-payment' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo 'Pembayaran Outstanding' ?></a></li>    
    <li<?php if(in_array(@$page, array("petty_cash"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/petty-cash' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo lang("nav:petty_cash") ?></a></li>
    <li<?php if(in_array(@$page, array("non_invoice_receipt"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/non-invoice-receipt' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo lang("nav:non_invoice_receipt") ?></a></li>
    <li<?php if(in_array(@$page, array("non_invoice_cash_expense"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/non-invoice-cash-expense' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo lang("nav:non_invoice_cash_expense") ?></a></li>
    <li<?php if(in_array(@$page, array("bank_cash_deposit"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/bank-cash-deposit' ) ?>"><i class="fa fa-circle-thin"></i> <?php echo lang("nav:bank_cash_deposit") ?></a></li>    
    
    <li class="title"><?php echo lang("nav:setup") ?></li>
    <li<?php if(in_array(@$page, array("schedules"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'schedules' ) ?>"><i class="fa fa-clock-o"></i><?php echo lang("nav:schedule") ?></a></li>
    <li<?php if(in_array(@$page, array("patients"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'common/patients' ) ?>"><i class="fa fa-users"></i><?php echo lang("nav:patients") ?></a></li>

    <li class="title"><?php echo lang("nav:reports") ?></li>
   
    <li<?php if(in_array(@$page, array("transaction_recap_by_section_doctor"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/reports/daily_cash_report_fo' ) ?>"><i class="fa fa-file-text"></i> <?php echo 'Laporan Kas Harian FO' ?></a></li>
	<li<?php if(in_array(@$page, array("Laporan Rekap Transactiontion"))){echo " class=\"active\"";} ?>>
        <a href="javascript:;"><i class="fa fa-file-text"></i> <?php echo lang("nav:report_transaction_recap") ?></a>
        <ul>       
        <li<?php if(in_array(@$page, array("transaction_recap_by_section_doctor"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/reports/income_recap' ) ?>"><i class="fa fa-circle-thin" aria-hidden="true"></i> <?php echo 'Laporan Rekap Transaksi' ?></a></li>                         
            <li<?php if(in_array(@$page, array("transaction_recap_by_section_doctor"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/general_payments/report/transaction_recap_by_section_doctor' ) ?>"><i class="fa fa-circle-thin" aria-hidden="true"></i> <?php echo 'Laporan Rekap Pendapatan' ?></a></li>
            <li<?php if(in_array(@$page, array("transaction_recap_by_service_group"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'cashier/general_payments/report/transaction_recap_by_service_group' ) ?>" data-toggle="ajax-modal"><i class="fa fa-circle-thin" aria-hidden="true"></i> <?php echo 'Laporan Rekap Pendapatan per Group Jasa' ?></a></li>
        </ul>
    </li>
    <li<?php if(in_array(@$page, array("report_patient_reservations"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'reservations/reports/patient-reservations' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-file-text"></i> <?php echo lang("nav:report_patient_reservations") ?></a></li>
    <li<?php if(in_array(@$page, array("report_polyclinic_registration"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations/reports/polyclinic-registrations' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-file-text"></i> <?php echo lang("nav:report_polyclinic_registrations") ?></a></li>
    <li<?php if(in_array(@$page, array("report_registration_amount_per_patient_type"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations/reports/registration-patient-types' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-file-text"></i> <?php echo lang("nav:report_registration_amount_per_patient_type") ?></a></li>
    <li<?php if(in_array(@$page, array("report_registration_amount_per_patient"))){echo " class=\"active\"";} ?>><a href="<?php echo base_url( 'registrations/reports/registration-patient' ) ?>" data-toggle="form-ajax-modal"><i class="fa fa-file-text"></i> <?php echo 'Registrasi Pasien' ?></a></li>
</ul>


