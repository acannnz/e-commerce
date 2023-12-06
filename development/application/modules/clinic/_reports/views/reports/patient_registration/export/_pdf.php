<html>
<head>
    <meta charset="utf-8"/>
    <base href="<?php echo site_url() ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo @$file_name ?></title>
    <style>
        body{
            font-family: "arial-ce"!important;
            color: #000;
            font-size: 12px;
        }
        .table_header th {
            border: 1px solid black;
            padding: 5px;
        }
        .table_header td {
            border: 1px solid black;
            padding: 5px;
        }

        .table_header {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <div class="row" style="margin:0 !important;">
    	<div class="col-lg-12" style="margin:0 !important;">
        	<div class="row" style="border-bottom:1px solid #000; margin:0 !important;">
                <div class="col-lg-12">
                	<p>
                        <span style="font-size:20px"><strong><?php echo $this->config->item( "company_name" ) ?></strong></span><br>
                        <?php echo sprintf("%s, %s, %s %s", $this->config->item( "company_address" ), $this->config->item( "company_city" ), $this->config->item( "company_country" ), ($this->config->item( "company_zip_code" ) ? " (".$this->config->item( "company_zip_code" ).")" : "")) ?><br>
                        Telp  <?php echo ($this->config->item( "company_phone" ) ? $this->config->item( "company_phone" ) : "n/a") ?>
                    </p>
                </div>
            </div>
            <div class="row" style="margin:0; padding-top:10px !important;">
                <p align="center">
                    <span style="font-size:14px"><strong><?php echo sprintf("%s %s", 'Laporan Registrasi Pasien', @$section->SectionName ); ?></strong></span> <br> 
                    <?php echo 'Periode'; ?> <?php echo $post_data->date_start ?>  <?php echo 's/d'; ?> <?php echo $post_data->date_end ?>
                </p>
            </div>
            
			<?php $total = 0; if(!empty($collection)) : foreach ($collection as $key => $value) :   ?>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <strong><?php echo sprintf("%s %s", '', $key );?></strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table_header" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center"><?php echo 'No'; ?></th>
                                        <th><?php echo 'Tgl Reg'; ?></th>
                                        <th><?php echo 'No Registrasi'; ?></th>
                                        <th><?php echo 'NRM'; ?></th>
                                        <th><?php echo 'Nama Pasien'; ?></th>
                                        <th><?php echo 'Jenis Kelamin'; ?></th>
                                        <th><?php echo 'Tgl Lahir'; ?></th>
                                        <th><?php echo 'Jenis Kerjasama'; ?></th>
                                        <th><?php echo 'Section'; ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php $sub_count = 0; $no = 1; if(!empty($value)) : foreach ($value as $row): ?>
                                    <?php $sub_count += count($row->TglReg) ?>
                                    <tr>
                                        <td align="center" width="6px"><?php echo $no++; ?></td>
                                        <td><?php echo @$row->TglReg ?></td>
                                        <td><?php echo @$row->NoReg ?></td>
                                        <td><?php echo @$row->NRM ?></td>
                                        <td><?php echo @$row->NamaPasien ?></td>
                                        <td><?php echo @$row->JenisKelamin ?></td>
                                        <td><?php echo date('d/m/Y', strtotime(@$row->TglLahir)) ?></td>
                                        <td><?php echo @$row->JenisKerjasama ?></td>
                                        <td><?php echo @$row->SectionName ?></td>
                                       
                                    </tr>
                                    <?php endforeach; ?>
                                    <!-- <tr>
                                        <td colspan="9"><?php echo sprintf("%s %s",'Jumlah Pasien '. @$key , $sub_count ) ?></td>
                                    </tr> -->
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php $total += $sub_count; ?>
			<?php endforeach; ?>
            	<br>
                <div class="row">
                	<div class="col-md-12">
                    	<table width="100%" cellpadding="0">
                        	<tr>
                            	<td align="left"><strong><?php echo 'Total Pasien ' ?> <?php echo $total ?></strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

			<?php else: ?>
	            <h1 class="text-center"><?php echo 'Data tidak tersedia!'; ?></h1>
            <?php endif;?>
        </div>
    </div>
</body>
</html>
