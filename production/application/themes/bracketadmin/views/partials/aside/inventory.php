<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!isset($active_menu)){ $active_menu = ''; }
?>
        		<div class="logopanel text-center">
					<h1><span><?php echo config_item('company_name') ?> </span></h1>
				</div>
				
				<div class="leftpanelinner">
					<div class="visible-xs ">   
						<div class="media userlogged">
							<img src="{{ base_theme }}/bracketadmin/images/no-user-avatar.jpg" alt="" class="media-object">
							<div class="media-body">
								<h4><?php echo $this->session->userdata('username')?></h4>
								<?php /*?><span>"Life is so..."</span><?php */?>
							</div>
						</div>
				
						<h5 class="sidebartitle actitle">Account</h5>
						<ul class="nav nav-pills nav-stacked nav-bracket mb30">
							<?php /*?><li><a href="javascript:;" data-action-url="<?php echo site_url("system/users/edit") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:my_profile'); ?>"><i class="glyphicon glyphicon-user"></i> <?php echo lang('nav:my_profile'); ?></a></li>
							<li><a href="<?php echo site_url("help/ticket") ?>"><i class="glyphicon glyphicon-question-sign"></i> <?php echo lang('nav:help'); ?></a></li><?php */?>
							<li><a href="javascript:;" data-action-url="<?php echo site_url("auth/logout") ?>" data-act="ajax-modal" data-title="<?php echo lang('nav:logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> <?php echo lang('nav:logout'); ?></a></li>
						</ul>
					</div>


                    <h5 class="sidebartitle"><?php echo lang('nav:heading_panel'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li><a href="<?php echo site_url(''); ?>"><i class="fa fa-dashboard"></i> <span><?php echo 'Dashboard' ?></span></a></li>
                    </ul>
                    
                    <h5 class="sidebartitle"><?php echo lang('nav:heading_inventory'); ?></h5>
                    <ul class="nav nav-pills nav-stacked nav-bracket">
                    	<li class="nav-parent">
                        	<a href=""><i class="fa fa-tasks" aria-hidden="true"></i> <span><?php echo lang('nav:transactions'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('inventory/transactions/purchase_request'); ?>"><i class="fa fa-circle-o" ></i> Permintaan Pembelian</a></li>
								<li><a href="<?php echo site_url('inventory/transactions/goods_receipt'); ?>"><i class="fa fa-circle-o"></i> Penerimaan Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/transactions/gift_receipt'); ?>"><i class="fa fa-circle-o"></i> Penerimaan Barang Bonus</a></li>
								<li><a href="<?php echo site_url('inventory/transactions/goods_receipt/detail'); ?>"><i class="fa fa-circle-o"></i> Detail Penerimaan Barang</a></li>
                                <?php /*?><li><a href="<?php echo site_url('inventory/transactions/return_stock'); ?>"><i class="fa fa-circle-o"></i> Penerimaan Fee</a></li><?php */?>
                                <?php /*?><li><a href="javascript:;"><i class="fa fa-circle-o"></i> Retur Penerimaan</a></li><?php */?>                                
                                <?php /*?><li><a href="<?php echo site_url('inventory/transactions/return_stock'); ?>"><i class="fa fa-circle-o"></i> Retur Stok</a></li><?php */?>                             
                                <li><a href="<?php echo site_url('inventory/transactions/amprahan'); ?>"><i class="fa fa-circle-o"></i> Amprahan</a></li>
                                <li><a href="<?php echo site_url('inventory/transactions/mutations'); ?>"><i class="fa fa-circle-o"></i> Mutasi Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/transactions/mutation_returns'); ?>"><i class="fa fa-circle-o"></i> Retur Mutasi</a></li>   
                                <?php /*?><li><a href="javascript:;"><i class="fa fa-circle-o"></i> Pembelian Obat Keluar</a></li><?php */?>
                                <li><a href="<?php echo site_url('inventory/transactions/stock_opname'); ?>"><i class="fa fa-circle-o"></i> Stock Opname</a></li>
                            </ul>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-database" aria-hidden="true"></i> <span><?php echo lang('nav:references'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('inventory/references/item'); ?>"><i class="fa fa-circle-o"></i> Master Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_grading_group'); ?>"><i class="fa fa-circle-o"></i> Grup Grading</a></li>
                                
                                <li><a href="<?php echo site_url('inventory/references/item_category'); ?>"><i class="fa fa-circle-o"></i> Kategori Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_class'); ?>"><i class="fa fa-circle-o"></i> Kelas Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_group'); ?>"><i class="fa fa-circle-o"></i> Golongan Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_typegroup'); ?>"><i class="fa fa-circle-o"></i> Jenis Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_unit'); ?>"><i class="fa fa-circle-o"></i> Satuan Barang</a></li>
                                
                                <li><a href="<?php echo site_url('inventory/references/item_supplier'); ?>"><i class="fa fa-circle-o"></i> Supplier Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/item_location'); ?>"><i class="fa fa-circle-o"></i> Stok Barang Lokasi</a></li>
                            	
								<li><a href="<?php echo site_url('inventory/references/item_grading'); ?>"><i class="fa fa-circle-o"></i> Grading Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/warehouse'); ?>"><i class="fa fa-circle-o"></i> Gudang</a></li>
                                <li><a href="<?php echo site_url('inventory/references/section'); ?>"><i class="fa fa-circle-o"></i> Section</a></li>
                                
                            </ul>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-cogs" aria-hidden="true"></i> <span><?php echo lang('nav:behaviors'); ?></span></a>
                            <ul class="children">
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/stock_out'); ?>"><i class="fa fa-circle-o"></i> Daftar Stok Habis</a></li><?php */?>
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/stock_minimum'); ?>"><i class="fa fa-circle-o"></i> Daftar Stok Minimum</a></li><?php */?>
                                
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/price_history'); ?>"><i class="fa fa-circle-o"></i> Daftar Perubahan Harga</a></li><?php */?>
                                
                                <li><a href="<?php echo site_url('inventory/behaviors/posting'); ?>"><i class="fa fa-circle-o"></i> Posting Keuangan</a></li>
                                <li><a href="<?php echo site_url('inventory/behaviors/posting/cancel'); ?>"><i class="fa fa-circle-o"></i> Batalkan Posting Keuangan</a></li>
                                
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/post_mutation/posting'); ?>"><i class="fa fa-circle-o"></i> Posting Mutasi</a></li><?php */?>
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/post_mutation/canceling'); ?>"><i class="fa fa-circle-o"></i> Batalkan Posting Mutasi</a></li><?php */?>
                                
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/close_book/closing'); ?>"><i class="fa fa-circle-o"></i> Tutup Buku</a></li><?php */?>
                                <?php /*?><li><a href="<?php echo site_url('inventory/behaviors/close_book/canceling'); ?>"><i class="fa fa-circle-o"></i> Batalkan Tutup Buku</a></li><?php */?>
                            </ul>
                        </li>
                        <li class="nav-parent">
                        	<a href=""><i class="fa fa-pencil" aria-hidden="true"></i> <span><?php echo lang('nav:preferences'); ?></span></a>
                            <ul class="children">
                                <?php /*?><li><a href="<?php echo site_url('inventory/preferences/inventory_config'); ?>"><i class="fa fa-circle-o"></i> Setup Awal Barang</a></li><?php */?>
                                <li><a href="<?php echo site_url('inventory/preferences/system_config'); ?>"><i class="fa fa-circle-o"></i> Setup Awal Sistem</a></li>
                            </ul>
                        </li>
						<li class="nav-parent">
							<a href=""><i class="fa fa-clipboard" aria-hidden="true"></i> <span><?php echo lang('nav:reports'); ?></span></a>
                            <ul class="children">
                                <li><a href="<?php echo site_url('inventory/reports/inventory_value'); ?>"><i class="fa fa-circle-o"></i> Nilai Persediaan</a></li>
                                <li><a href="<?php echo site_url('inventory/reports/purchase_receipt/dialog'); ?>"><i class="fa fa-circle-o"></i> Penerimaan Pembelian</a></li>
                                <li><a href="<?php echo site_url('inventory/reports/moving_stock/slow'); ?>"><i class="fa fa-circle-o"></i> Slow Moving Stok</a></li>
                                <li><a href="<?php echo site_url('inventory/reports/moving_stock/death'); ?>"><i class="fa fa-circle-o"></i> Death Moving Stok</a></li>
                                <!-- <li><a href="<?php echo site_url('inventory/reports/mutation_stocks/dialog'); ?>"><i class="fa fa-circle-o"></i> Mutasi Barang</a></li>
                                <li><a href="<?php echo site_url('inventory/reports/mutation_returns/dialog'); ?>"><i class="fa fa-circle-o"></i> Retur Mutasi Barang</a></li> -->
                            </ul>
                        </li>
                        
                        <?php /*?><li><a href="<?php echo site_url('inventory/help'); ?>"><i class="fa fa-life-ring"></i> <span><?php echo lang('nav:help'); ?></span></a></li><?php */?>
                    </ul>
                </div>
