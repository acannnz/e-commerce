<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="form-group">
    <label class="col-lg-3 control-label">Dokter</label>
    <div class="col-lg-9">
        <div class="input-group">
			<input type="hidden" id="LDokterBonID" value="" placeholder="" class="form-control" readonly>
            <input type="text" id="LDokterBonName" value="" placeholder="" class="form-control" readonly>
            <span class="input-group-btn">
                <a href="<?php echo @$lookup_customer ?>" id="merchan_type" data-toggle="lookup-ajax-modal" class="btn btn-success" ><i class="fa fa-search"></i></a>
                <a href="javascript:;" id="clear_supplier" class="btn btn-danger" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Total</label>
    <div class="col-lg-9">
        <input type="text" id="LNilaiPembayaranBonPegawai" value="" placeholder="" class="form-control text-right mask-number">
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
        <button type="button" id="save_btn_bon" class="btn btn-success"><?php echo 'Tambahkan' ?></button>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
var credit_bon_actions = {
		init : function (){
			
			credit_bon_actions.bind_data();

			$("#creditCardValue").on("keyup blur", function(e){					
				credit_bon_actions.calculate_credit_card();
			});
			
			$('#clear_supplier').on('click', function(){
				$('#DokterBonID').val('');
				$('#DokterBonName').val('');
			});
			
			$("#save_btn_bon").on("click",function(){
				if($('#LDokterBonID').val() == '')
				{
					$.alert_error('Anda belum memilih Dokter/Karyawan Bon!');
					return false;
				}
				
				credit_bon_actions.store_data();
			})
			
		},
		bind_data : function(){						
			$("#LDokterBonID").val( $("#DokterBonID").val() );
			$("#LDokterBonName").val( $("#DokterBonName").val() );
			$("#LNilaiPembayaranBonPegawai").val( mask_number.currency_remove($("#NilaiPembayaranBonPegawai").val()) );
		},
		store_data: function(){			
			$("#DokterBonID").val($("#LDokterBonID").val());
			$("#DokterBonName").val($("#LDokterBonName").val());			
			$("#NilaiPembayaranBonPegawai").val(mask_number.currency_remove($("#LNilaiPembayaranBonPegawai").val()));
				
			credit_bon_actions.calculate_payment();
			
			$("#form-ajax-modal").remove();
			$("body").removeClass("modal-open").removeAttr("style");
		},
		calculate_payment : function(){
			var _form = $( "form[name=\"form_general_payment\"]" );
			var Nilai = _form.find( "input[id=\"Nilai\"]" );
			var SubTotal = _form.find( "input[id=\"SubTotal\"]" );
			var TaxCC = _form.find( "input[id=\"TaxCC\"]" );
			var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
			var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
			var k_Amount = _form.find( "input[id=\"k_Amount\"]" );
			var k_Total = _form.find( "input[id=\"k_Total\"]" );
			var Tunai = _form.find( "input[id=\"Tunai\"]" );
			var Sisa = _form.find( "input[id=\"Sisa\"]" );
			var JumlahBayar = _form.find( "input[id=\"JumlahBayar\"]" );
			var NilaiKembalian = _form.find( "input[id=\"NilaiKembalian\"]" );
			var Total = 0 , Sisa_ = 0, TaxCC_ = 0;
			
			$(".payment-type").each(function(index, element) {
				element.value = element.value || 0;
				if(element.value == 0 ) return;
				is_credit_card = $(this).hasClass("credit-card"); 
				if ( is_credit_card ){
					TaxCC_ = mask_number.currency_remove(k_Total.val()) - mask_number.currency_remove(k_Amount.val());
					SubTotal_payment = mask_number.currency_remove(k_Amount.val());
				} else {
					SubTotal_payment = mask_number.currency_remove(element.value);
				}
				
				console.log("SubTotal_payment : ", SubTotal_payment);
				Total = Total + SubTotal_payment;				
			});
			console.log("Total : ", Total);
			
			var SubTotal_ = mask_number.currency_remove(SubTotal.val());
			var GrandTotal_ = SubTotal_ + TaxCC_;
			TaxCC.val( mask_number.currency_add(TaxCC_));	
			GrandTotal.val( mask_number.currency_add(GrandTotal_));	
			
			var JumlahBayar_ = mask_number.currency_remove(JumlahBayar.val()) || 0;;
			var Pembayaran_ = Total + JumlahBayar_ + TaxCC_;
			Pembayaran_ = Pembayaran_ > GrandTotal_ ? GrandTotal_ : Pembayaran_;
			Pembayaran.val(mask_number.currency_add( Pembayaran_ ));
			
			var Sisa_ = GrandTotal_ - Pembayaran_;
			Sisa.val( mask_number.currency_add(Sisa_));
			
			NilaiKembalian.val(0.00);
			var NilaiKembalian_ = JumlahBayar_ - (GrandTotal_ - TaxCC_ - Total) || 0;
			if( JumlahBayar_ > 0 && NilaiKembalian_ > 0 && JumlahBayar_ > NilaiKembalian_){
				NilaiKembalian.val(mask_number.currency_add(NilaiKembalian_));
			}
			
			NilaiKembalian_  = mask_number.currency_remove(NilaiKembalian.val());
			Tunai.val(mask_number.currency_add(JumlahBayar_ - NilaiKembalian_));
			
		},		
	};
	
	$( document ).ready(function(e) {						
			
			credit_bon_actions.init();
			//mask_number.init();
		});
	})( jQuery );
//]]>
</script>