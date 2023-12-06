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
    <li class="title"><?= 'Setup' ?></li>
    <!-- <li><a href="<?= base_url('medical_record/patient_registration') ?>"><i class="fa fa-plus-square"></i> <?= 'Group ICD' ?></a></li> -->
    <li><a href="<?= base_url('medical_record/icd') ?>"><i class="fa fa-plus-square"></i> <?= 'ICD' ?></a></li>

    <li class="title"><?= lang("nav:reports") ?></li>
    <li><a href="<?= base_url('medical_record/patient_registration') ?>"><i class="fa fa-file"></i> <?= 'Register Pasien' ?></a></li>
    <li><a href="<?= base_url('medical_record/most_diseases') ?>"><i class="fa fa-file"></i> <?= '10 Besar Penyakit' ?></a></li>
    <li><a href="<?= base_url('medical_record/reports/patient') ?>"><i class="fa fa-file"></i> <?= 'Pasien' ?></a></li>
    <li><a href="<?= base_url('medical_record/reports/average_age_patient') ?>"><i class="fa fa-file"></i> <?= 'Usia Rata-Rata Pasien' ?></a></li>
    <li><a href="<?= base_url('medical_record/most_diseases_index') ?>"><i class="fa fa-file"></i> <?= 'Index Penyakit Rawat Jalan' ?></a></li>
</ul>