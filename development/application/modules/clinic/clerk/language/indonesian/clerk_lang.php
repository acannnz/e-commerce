<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$lang['heading:clerk']= "Clerk";
$lang['heading:clerk_list']= "Daftar Clerk";
$lang['heading:clerk_create']= "Buat Clerk Baru";
$lang['heading:clerk_update']= "Perbarui Clerk";
$lang['heading:clerk_view']= "Lihat Clerk";

// labels
$lang['label:clerk_start'] = 'Clerk Start';
$lang['label:clerk_end'] = 'Clerk End';
$lang['label:code'] = 'Kode';
$lang['label:name'] = 'Nama';
$lang['label:date'] = 'Tanggal';
$lang['label:section'] = 'Section';
$lang['label:payment_type'] = 'Jenis Pembayaran';
$lang['label:qty_sales'] = 'Jumlah Penjualan';
$lang['label:amount_discount'] = 'Jumlah Diskon';
$lang['label:amount_total'] = 'Jumlah Total';
$lang['label:amount_system'] = 'Jumlah Sistem';
$lang['label:amount_clerk'] = 'Jumlah Clerk';
$lang['label:amount_diff'] = 'Jumlah Selisih';
$lang['label:total'] = 'Total';
$lang['label:enter_password'] = 'Masukan Password Anda disini...';
$lang['label:click_to_transaction'] = 'Klik disini untuk memulai transaksi.';

// message
$lang['message:clerk_start'] = 'Anda harus memulai Clerk sebelum melakukan transaksi.<br/> Setelah proses Clerk Start dimulai, maka Anda dapat melakukan transaksi.';
$lang['message:clerk_end'] = 'Clerk juga merupakan proses penyetoran hasil transaksi akhir. Disini Anda dapat melakukan pencocokan jumlah total transaksi fisik dengan jumlah total transaksi sistem.<br/> Setelah Anda melakukan proses Clerk End, maka Anda tidak dapat melakukan transaksi lainnya.';
$lang['message:wrong_password'] = 'Password Anda Salah... Proses tidak dapat dilanjutkan.';
$lang['message:done_clerk_start'] = 'Proses Clerk Start sudah dilakukan';
$lang['message:allowed_transaction'] = 'Anda bisa melakukan transaksi.';
$lang['message:done_clerk_end'] = 'Proses Clerk End sudah dilakukan';