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
                    <span style="font-size:14px"><strong><?php echo sprintf("%s %s", 'Laporan 10 Besar Penyakit', @$section->SectionName ); ?></strong></span> <br> 
                    <?php echo 'Periode'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_start)) ?>  <?php echo 's/d'; ?> <?php echo date('d-m-Y', strtotime($post_data->date_end)) ?>
                </p>
            </div>
            
                <div class="row">
                    <div class="col-sm-12">
                        <div class="">
                            <table class="table_header" width="100%">
                                <thead>
                                    <tr>
                                        <th align="center"><?php echo 'No'; ?></th>
                                        <th><?php echo 'Kode ICD'; ?></th>
                                        <th><?php echo 'Diagnosa'; ?></th>
                                        <th><?php echo 'Jumlah'; ?></th>
                                    </tr> 
                                </thead>
                                <tbody>	
                                    <?php $no = 1; if(!empty($collection)) : foreach ($collection as $row): ?>
                                    <tr>
                                        <td align="center" width="6px"><?php echo $no++; ?></td>
                                        <td><?php echo @$row->KodeICD ?></td>
                                        <td><?php echo @$row->Descriptions ?></td>
                                        <td align="center"><?php echo @$row->Jumlah ?></td>
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
