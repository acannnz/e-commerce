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
        .col-kelompok-umur{
            width: 4.9%;
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
                    <span style="font-size:14px"><strong><?php echo sprintf("%s %s", @$report_title, ucwords(strtolower(@$section->SectionName ))); ?></strong></span> <br> 
                    <?php echo 'Periode'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo 's/d'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?>
                </p>
            </div>
            
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table_header" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center" rowspan="2" width="3.5%"><?php echo 'No'; ?></th>
                                        <th rowspan="2" width="5.2%"><?php echo 'Kode ICD'; ?></th>
                                        <th rowspan="2" width="18%"><?php echo 'Nama ICD'; ?></th>
                                        <th colspan="12"><?php echo 'Umur'; ?></th>
                                        <th rowspan="2"><?php echo 'Laki-Laki'; ?></th>
                                        <th rowspan="2"><?php echo 'Perempuan'; ?></th>
                                        <th rowspan="2"><?php echo 'Jumlah'; ?></th>
                                    </tr> 
                                    <tr>
                                        <th class="col-kelompok-umur"><?php echo '0 - 7'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '8 - 28'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '< 1'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '1 - 4'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '5 - 9'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '10 - 14'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '15 - 19'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '20 - 44'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '45 - 54'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '55 - 59'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '60 - 69'; ?></th>
                                        <th class="col-kelompok-umur"><?php echo '70'; ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php $no = 1; if(!empty($collection)) : foreach ($collection as $row): ?>
                                    <tr>
                                        <td align="center" width="6px"><?php echo $no++; ?></td>
                                        <td><?php echo @$row->KodeICD ?></td>
                                        <td><?php echo @$row->Descriptions ?></td>
                                        <td align="center"><?php echo @$row->K_1 ?></td>
                                        <td align="center"><?php echo @$row->K_2 ?></td>
                                        <td align="center"><?php echo @$row->K_3 ?></td>
                                        <td align="center"><?php echo @$row->K_4 ?></td>
                                        <td align="center"><?php echo @$row->K_5 ?></td>
                                        <td align="center"><?php echo @$row->K_6 ?></td>
                                        <td align="center"><?php echo @$row->K_7 ?></td>
                                        <td align="center"><?php echo @$row->K_8 ?></td>
                                        <td align="center"><?php echo @$row->K_9 ?></td>
                                        <td align="center"><?php echo @$row->K_10 ?></td>
                                        <td align="center"><?php echo @$row->K_11 ?></td>
                                        <td align="center"><?php echo @$row->K_12 ?></td>
                                        <td align="center"><?php echo @$row->M ?></td>
                                        <td align="center"><?php echo @$row->F ?></td>
                                        <td align="center"><?php echo (@$row->M + @$row->F) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4"><?php echo 'Tidak terdapat data!' ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</body>
</html>
