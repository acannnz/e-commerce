<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<?php if (config_item('enable_languages') == 'TRUE') : ?>
    <ul class="dev-lang-navigation">
        <h4><span class="label"><?= lang('languages') ?></h4>
        <div class="btn-group">
            <button type="button" class="btn btn-icon btn-danger dropdown-toggle" data-toggle="dropdown" title="<?= lang('languages') ?>">
                <i class="fa fa-globe"></i>
            </button>
            <ul class="dropdown-menu text-left">
                <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
                        <li>
                            <a href="<?= base_url() ?>set_language?lang=<?= $lang->name ?>" title="<?= ucwords(str_replace("_", " ", $lang->name)) ?>">
                                <img src="<?= base_url() ?>resource/images/flags/<?= $lang->icon ?>.gif" alt="<?= ucwords(str_replace("_", " ", $lang->name)) ?>" /> <?= ucwords(str_replace("_", " ", $lang->name)) ?>
                            </a>
                        </li>
                <?php endif;
                endforeach; ?>
            </ul>
        </div>
    </ul>
<?php endif ?>

<ul class="dev-page-navigation">
    <li class="title"><?= lang("nav") ?></li>
    <li<?php if (in_array(@$page, array("statistics"))) {
            echo " class=\"active\"";
        } ?>>
        <a href="<?= base_url('reports/statistics') ?>"><i class="fa fa-line-chart"></i><?= 'Statistik' ?></a>
        </li>

        <li class="title"><?= lang("nav:reports") ?></li>
        <li><a href="<?= base_url('reports/daily_cash_report_fo') ?>"><i class="fa fa-file"></i> <?= 'Laporan Kas Harian FO' ?></a></li>
		<li><a href="<?= base_url('reports/drug_price') ?>"><i class="fa fa-file"></i> <?= 'Laporan Harga Obat Keseluruhan' ?></a></li>
        <li><a href="<?= base_url('reports/recap_transactions') ?>"><i class="fa fa-file"></i> <?= 'Laporan Rekap Transaksi Obat' ?></a></li>
        <li><a href="<?= base_url('reports/used_drugs') ?>"><i class="fa fa-file"></i> <?= 'Laporan Penggunaan Obat' ?></a></li>
        <li><a href="<?= base_url('reports/recap_stocks') ?>"><i class="fa fa-file"></i> <?= 'Laporan Rekap Stok Obat' ?></a></li>
        <li><a href="<?= base_url('reports/medical_records') ?>"><i class="fa fa-file"></i> <?= 'Laporan Rekam Medis Pasien' ?></a></li>
        <li><a href="<?= base_url('reports/drug_history') ?>"><i class="fa fa-file"></i> <?= 'Laporan Rekam Medis Obat Pasien' ?></a></li>
        <li><a href="<?= base_url('reports/group_by_icd') ?>"><i class="fa fa-file"></i> <?= 'Laporan Diagnosa Pasien' ?></a></li>
        <li><a href="<?= base_url('reports/patient_registration') ?>"><i class="fa fa-file"></i> <?= 'Laporan Registrasi Pasien' ?></a></li>
        <li><a href="<?= base_url('reports/most_diseases') ?>"><i class="fa fa-file"></i> <?= '10 Besar Penyakit' ?></a></li>
        <li><a href="<?= base_url('reports/patient') ?>"><i class="fa fa-file"></i> <?= 'Pasien' ?></a></li>
        <li><a href="<?= base_url('reports/average_age_patient') ?>"><i class="fa fa-file"></i> <?= 'Usia Rata-Rata Pasien' ?></a></li>
        
        <li class="title"><?= 'Surat Keterangan' ?></li>
        <li><a href="<?= base_url('reports/patient_certificate') ?>"><i class="fa fa-file"></i> <?= 'Surat Keterangan Pasien' ?></a></li>
</ul>