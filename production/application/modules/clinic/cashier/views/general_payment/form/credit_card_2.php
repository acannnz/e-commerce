<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="form-group">
    <label class="col-lg-3 control-label">Merchan</label>
    <div class="col-lg-2">
    	<input type="text" id="BankID_2" value="" class="form-control" readonly>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input type="text" id="BankName_2" value="" placeholder="" class="form-control" readonly>
            <span class="input-group-btn">
                <a href="<?php echo @$lookup_merchan_2 ?>" id="merchan_type" data-toggle="lookup-ajax-modal" class="btn btn-success" ><i class="fa fa-search"></i>&nbsp;lookup</a>
                <a href="javascript:;" id="clear_merchan" class="btn btn-danger" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">No Kartu</label>
    <div class="col-lg-9">
        <input type="text" id="NoKartu_2" value="" placeholder="" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Nilai Bayar</label>
    <div class="col-lg-9">
        <input type="text" id="creditCardValue_2" value="" placeholder="" class="form-control text-right">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Add Charge</label>
    <div class="col-lg-9">
        <div class="input-group">
            <input type="number" id="addChargePersen_2" value="" placeholder="" class="form-control text-right">
            <span class="input-group-btn">
                <a href="javascript:;" class="btn btn-default" >%</a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Total</label>
    <div class="col-lg-9">
        <input type="text" id="totalCreditCard_2" value="" placeholder="" class="form-control text-right" readonly>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
        <button type="button" id="btn_merchan_2" class="btn btn-success"><?php echo 'Tambahkan' ?></button>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
var credit_card_actions = {
		init : function (){
			
			credit_card_actions.bind_data();

			$("#creditCardValue_2").on("focus",function(){
					val = mask_number.currency_remove($(this).val());
					if ( val == 0){
						console.log(val);
						$(this).val("");
						return;
					}
					
					val = mask_number.currency_remove($(this).val());
					$(this).val( val );
                });

			$("#creditCardValue_2").on("keyup", function(e){
				credit_card_actions.calculate_credit_card();
			});
				
			$("#creditCardValue_2").on("blur",function(){
				val = mask_number.currency_remove($(this).val());
				if ( val > 0)
				{
					val = mask_number.currency_add($(this).val());
					$(this).val( val );
				} else {
					$(this).val( "0.00" );
				}
				
				credit_card_actions.calculate_credit_card();
			});
					
			$("#addChargePersen_2").on("keyup blur", function(e){
				credit_card_actions.calculate_credit_card();
			});
			
			$('#clear_merchan').on('click', function(){
				$('#BankID_2').val('');
				$('#BankName_2').val('');
			});
			
			$("#btn_merchan_2").on("click",function(){
				
				if($('#BankID_2').val() == '')
				{
					$.alert_error('Anda belum memilih Merchan Bank!');
					return false;
				}
				
				credit_card_actions.store_data();
			})
			
		},
		bind_data : function(){
						
			$("#BankID_2").val( $("#k_BankID_2").val() );
			$("#BankName_2").val( $("#k_BankName_2").val() );
			$("#NoKartu_2").val( $("#k_CardNo_2").val() );
			$("#creditCardValue_2").val( $("#k_Amount_2").val() || 0.00 );
			$("#addChargePersen_2").val( $("#k_Charge_2").val() || 0.00 );
			$("#totalCreditCard_2").val( mask_number.currency_remove($("#k_Amount_2").val()) + (mask_number.currency_remove($("#k_Amount_2").val()) * $("#k_Charge_2").val() / 100));
		},
		store_data: function(){
			var BankID_2 = $("#BankID_2").val();
			var BankName_2 = $("#BankName_2").val();
			var NoKartu_2 = $("#NoKartu_2").val();
			var creditCardValue_2 = $("#creditCardValue_2").val();
			var addChargePersen_2 = $("#addChargePersen_2").val();
			var totalCreditCard_2 = $("#totalCreditCard_2").val();
			
			
			$("#k_BankID_2").val(BankID_2);
			$("#k_BankName_2").val(BankName_2);
			$("#k_CardNo_2").val(NoKartu_2);
			$("#k_Amount_2").val(creditCardValue_2);
			$("#k_Charge_2").val(addChargePersen_2);
			$("#k_Total_2").val(totalCreditCard_2);
			//$("#TaxCC").val(ch);
						
			$("#Kartu_2").val(totalCreditCard_2);
			
			credit_card_actions.calculate_payment();
			
			$("#form-ajax-modal").remove();
			$("body").removeClass("modal-open").removeAttr("style");
		},
		calculate_credit_card : function(){
			var creditCardValue_2 = $( "#creditCardValue_2" );
			var addChargePersen_2 = $( "#addChargePersen_2" );
			var totalCreditCard_2 = $( "#totalCreditCard_2" );
			var totalCreditCard_2_ = 0;
			
			totalCreditCard_2_ = mask_number.currency_remove( creditCardValue_2.val() ) + ( mask_number.currency_remove( creditCardValue_2.val() ) * mask_number.currency_remove(addChargePersen_2.val()) / 100 );
			totalCreditCard_2.val( mask_number.currency_add( totalCreditCard_2_ ) );
						
		},
		calculate_payment : function(){
			var _form = $( "form[name=\"form_general_payment\"]" );
			var Nilai = _form.find( "input[id=\"Nilai\"]" );
			var SubTotal = _form.find( "input[id=\"SubTotal\"]" );
			var TaxCC = _form.find( "input[id=\"TaxCC\"]" );
			var GrandTotal = _form.find( "input[id=\"GrandTotal\"]" );
			var Pembayaran = _form.find( "input[id=\"Pembayaran\"]" );
			var k_Amount_2 = _form.find( "input[id=\"k_Amount_2\"]" );
			var k_Total_2 = _form.find( "input[id=\"k_Total_2\"]" );
			var Tunai = _form.find( "input[id=\"Tunai\"]" );
			var Sisa = _form.find( "input[id=\"Sisa\"]" );
			var JumlahBayar = _form.find( "input[id=\"JumlahBayar\"]" );
			var NilaiKembalian = _form.find( "input[id=\"NilaiKembalian\"]" );
			var Total = 0 , Sisa_ = 0, TaxCC_ = 0;
			
			$(".payment-type").each(function(index, element) {
				element.value = element.value || 0;
				if(element.value == 0 ) return;
				is_credit_card_2 = $(this).hasClass("credit-card-2"); 
				if ( is_credit_card_2 ){
					TaxCC_ = mask_number.currency_remove(k_Total_2.val()) - mask_number.currency_remove(k_Amount_2.val());
					SubTotal_payment = mask_number.currency_remove(k_Amount_2.val());
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
			
			credit_card_actions.init();
			
		});
	})( jQuery );
//]]>
</script>