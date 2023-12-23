<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class="form-group">
    <label class="col-lg-3 control-label">Merchan</label>
    <div class="col-lg-2">
    	<input type="text" id="BankID" value="" class="form-control" readonly>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input type="text" id="BankName" value="" placeholder="" class="form-control" readonly>
            <span class="input-group-btn">
                <a href="<?php echo @$lookup_merchan ?>" id="merchan_type" data-toggle="lookup-ajax-modal" class="btn btn-success" ><i class="fa fa-search"></i>&nbsp;lookup</a>
                <a href="javascript:;" id="clear_merchan" class="btn btn-danger" ><i class="fa fa-times"></i></a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">No Kartu</label>
    <div class="col-lg-9">
        <input type="text" id="NoKartu" value="" placeholder="" class="form-control">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Nilai Bayar</label>
    <div class="col-lg-9">
        <input type="text" id="creditCardValue" value="" placeholder="" class="form-control text-right">
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Add Charge</label>
    <div class="col-lg-9">
        <div class="input-group">
            <input type="number" id="addChargePersen" value="" placeholder="" class="form-control text-right">
            <span class="input-group-btn">
                <a href="javascript:;" class="btn btn-default" >%</a>
            </span>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-3 control-label">Total</label>
    <div class="col-lg-9">
        <input type="text" id="totalCreditCard" value="" placeholder="" class="form-control text-right" readonly>
    </div>
</div>
<div class="form-group">
    <div class="col-lg-12 text-right">
        <button type="button" id="btn_merchan" class="btn btn-success"><?php echo 'Tambahkan' ?></button>
    </div>
</div>

<script type="text/javascript">
//<![CDATA[
(function( $ ){
var credit_card_actions = {
		init : function (){
			
			credit_card_actions.bind_data();

			$("#creditCardValue").on("focus",function(){
					val = mask_number.currency_remove($(this).val());
					if ( val == 0){
						console.log(val);
						$(this).val("");
						return;
					}
					
					val = mask_number.currency_remove($(this).val());
					$(this).val( val );
                });

			$("#creditCardValue").on("keyup", function(e){
				credit_card_actions.calculate_credit_card();
			});
				
			$("#creditCardValue").on("blur",function(){
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
					
			$("#addChargePersen").on("keyup blur", function(e){
				credit_card_actions.calculate_credit_card();
			});
			
			$('#clear_merchan').on('click', function(){
				$('#BankID').val('');
				$('#BankName').val('');
			});
			
			$("#btn_merchan").on("click",function(){
				
				if($('#BankID').val() == '')
				{
					$.alert_error('Anda belum memilih Merchan Bank!');
					return false;
				}
				
				credit_card_actions.store_data();
			})
			
		},
		bind_data : function(){
						
			$("#BankID").val( $("#k_BankID").val() );
			$("#BankName").val( $("#k_BankName").val() );
			$("#NoKartu").val( $("#k_CardNo").val() );
			$("#creditCardValue").val( $("#k_Amount").val() || 0.00 );
			$("#addChargePersen").val( $("#k_Charge").val() || 0.00 );
			$("#totalCreditCard").val( mask_number.currency_remove($("#k_Amount").val()) + (mask_number.currency_remove($("#k_Amount").val()) * $("#k_Charge").val() / 100));
		},
		store_data: function(){
			var BankID = $("#BankID").val();
			var BankName = $("#BankName").val();
			var NoKartu = $("#NoKartu").val();
			var creditCardValue = $("#creditCardValue").val();
			var addChargePersen = $("#addChargePersen").val();
			var totalCreditCard = $("#totalCreditCard").val();
			
			
			$("#k_BankID").val(BankID);
			$("#k_BankName").val(BankName);
			$("#k_CardNo").val(NoKartu);
			$("#k_Amount").val(creditCardValue);
			$("#k_Charge").val(addChargePersen);
			$("#k_Total").val(totalCreditCard);
			//$("#TaxCC").val(ch);
						
			$("#Kartu").val(totalCreditCard);
			
			credit_card_actions.calculate_payment();
			
			$("#form-ajax-modal").remove();
			$("body").removeClass("modal-open").removeAttr("style");
		},
		calculate_credit_card : function(){
			var creditCardValue = $( "#creditCardValue" );
			var addChargePersen = $( "#addChargePersen" );
			var totalCreditCard = $( "#totalCreditCard" );
			var totalCreditCard_ = 0;
			
			totalCreditCard_ = mask_number.currency_remove( creditCardValue.val() ) + ( mask_number.currency_remove( creditCardValue.val() ) * mask_number.currency_remove(addChargePersen.val()) / 100 );
			totalCreditCard.val( mask_number.currency_add( totalCreditCard_ ) );
						
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
			
			credit_card_actions.init();
			
		});
	})( jQuery );
//]]>
</script>