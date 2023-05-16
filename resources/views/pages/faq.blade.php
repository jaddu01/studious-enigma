@extends('layouts.app')
@section('content')
    <section class="topnave-bar">
    <div class="container">
    <ul>
    <li><a href="{{url('/')}}">Home</a> </li>
    <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
     <li><a href="{{url('/profile')}}">Profile</a></li>   
      <li> <i class="fa fa-angle-right" aria-hidden="true"></i> </li>
     <li><a href="{{url('/faq')}}">FAQ</a></li> 
    </ul>
    </div>  
</section>
<section class="product-listing-body">
    <div class="container">
        <div class="row">
         <div class="col-sm-12 col-md-12">
         <div class="wshlst_rt_mn clearfix new-address-form">
            <h3>FAQ's</h3>   
           <div class="faq_accordian_pg">
            <div class="panel-group" id="accordion">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title " data-toggle="collapse" data-parent="#accordion" href="#collapse1">What is Darbaar Mart?</h4>
                </div>
                <div id="collapse1" class="panel-collapse collapse in">
                  <div class="panel-body">
                    <p>Darbaar Mart is most convenient hyper local delivery company which enables you to order grocery, bakery, fruits & vegetables and other products that you need every day, directly via your mobile application (android/ iOS) or website. We deliver in select localities across the Beawar city we are present in Rajasthan. You can edit your location settings to check if we deliver in your area.  </p>
            
                  </div>
                </div>
              </div>
        
        <h3 class="main_hd">Download the app</h3>
        <h4 class="sub_hd">Sign Up and Login</h4>
        
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse2">How do I create/sign up an account on Darbaar Mart App/Website?</h4>
                </div>
                <div id="collapse2" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You can create your account on Darbaar Mart by entering and verifying your mobile number. Click on "Create New Account" after that and fill up the form to create your Darbaar Mart account.</p>
          
            
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse3">How do I login on Darbaar Mart? Can I use my email, Google/Facebook ID to login to the same?</h4>
                </div>
                <div id="collapse3" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Yes, you can login directly with your email, Google/ Facebook Login IDs. Once you have entered the details, you will be logged in to your account. Next time onwards, you can use your mobile number and OTP for easy login.</p>
                  </div>
                </div>
              </div>
            
               <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4">Can I still login with password on Darbaar Mart?</h4>
                </div>
                <div id="collapse4" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You can login with mobile number and password. This works if your mobile number is verified on your account. To login with mobile and password, enter your mobile number and "Continue". On the OTP page, you will find the option to login with password. However, it is highly recommended that you login with OTP for convenience and security.</p>
 
            
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse5">Do I need to provide email to create an account on Darbaar Mart?</h4>
                </div>
                <div id="collapse5" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Providing an email is not mandatory to create an account on Darbaar Mart. However, we still recommend adding your email to your account as it will allow you to easily reset your password if you need to.</p>
                  </div>
                </div>
              </div>
        
        
         <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse6">What happens if my mobile number is given to someone else by the telecom operator? Is my Darbaar Mart account still safe?</h4>
                </div>
                <div id="collapse6" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Yes, your Darbaar Mart account is safe under following circumstances:</p>
            
            <ul class="faq_sub_content">
              <li>You delete your account with Darbaar Mart before you close or deactivate your SIM card.</li>
              <li>Your online payment details/purchase history are safe with you; and your number or SIM can not carry the same to the new owner of the SIM.In any case, new user of the sim getting registered with the same number on Darbaar Mart, does not affect your account.</li>
            </ul>
           
            
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse7">Does Darbaar read my SMSes?</h4>
                </div>
                <div id="collapse7" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Darbaar Mart can only read OTP SMSes that are sent by Darbaar Mart. This is in accordance with guidelines provided by Google and Apple in line with user privacy policies.</p>
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Order</h4>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse8">Can I schedule an order to my convenience?</h4>
                </div>
                <div id="collapse8" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Sure. At the checkout page, you can select a delivery slot of your choice.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse9">What is the minimum order value?</h4>
                </div>
                <div id="collapse9" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>The minimum order value is INR 500. However, each store has a minimum order value to qualify for free delivery as benchmark it should be INR 800. In case you do not reach the limit, a delivery charge will be levied against that order.</p>
                  </div>
                </div>
              </div>
        
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse10">Do you charge any amount or taxes over and above the rates shown?</h4>
                </div>
                <div id="collapse10" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>No, we do not charge anything over and above the rates shown. However, we do have a delivery fee in case the order value does not reach INR 500 for free delivery.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse11">Can I track the status of my order?</h4>
                </div>
                <div id="collapse11" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Yes, you can track the status of your order under the My Orders section.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse12">How can I make changes to my order before and after confirmation?</h4>
                </div>
                <div id="collapse12" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You can edit your products in the cart before checkout. If you’ve already placed your order, you can cancel and reorder with the required list from the app and this will be soon released on web as well.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse13">How can I be sure the fruits and vegetables I order are of good quality?</h4>
                </div>
                <div id="collapse13" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Our fruits and vegetables vendors have a quality check process in place to ensure quality of the items delivered, is up to the mark. Do let us know at the time of delivery if you’re not happy with the quality of the product received. </p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse14">How will I know if any item in my order is unavailable?</h4>
                </div>
                <div id="collapse14" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You will receive an SMS notification informing you about the unavailable items in this situation. Refund (if any) will also be initiated within working 48 hours. </p>
            <p>*Order value is calculated after applying discounts/GST or any other applicable charges.</p>
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Delivery</h4>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse15">What is Darbaar Mart Delivery Policy?</h4>
                </div>
                <div id="collapse15" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>We strive to deliver products purchased from Darbaar Mart in excellent condition and in the fastest time possible. If this is your first order with Darbaar Mart, delivery will be completely FREE. Also, for all the subsequent Orders of Rs. 500/- or more, we will deliver the order to your doorstep free of cost. If the order is cancelled, lost or un-delivered to your preferred location, we will refund the complete order amount if paid online.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse16">Do you charge for delivery?</h4>
                </div>
                <div id="collapse16" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Every store has its own delivery charges, which are waived off if you order above a specified minimum amount from the store. The minimum charges and the delivery charges are mentioned on the app and web at the checkout page.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse17">What are your delivery times?</h4>
                </div>
                <div id="collapse17" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>In some locations, our deliveries begin from 6 AM and the last delivery is completed by 8 PM.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse18">What is Darbaar Mart Fair Usage Policy?</h4>
                </div>
                <div id="collapse18" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>We always strive hard to provide the best experience to our customers. However, we have noticed that few accounts abuse our liberal returns policy. These accounts typically return most of the items bought or choose to not accept our shipments. Hence, our regular customers are deprived of the opportunity to buy these items. To protect the rights of our customers, we reserve the right to disable cash on delivery option for accounts which have high percentage of returns and delivery not accepted by value of orders placed.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse19">How are orders placed on Darbaar Mart delivered to me?</h4>
                </div>
                <div id="collapse19" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>All orders placed on Darbaar Mart are dispatched through our own delivery service</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse20">Does Darbaar Mart deliver products outside Beawar?</h4>
                </div>
                <div id="collapse20" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Yes. At this point, Darbaar Mart delivers products within Indiabut the minimum charges and the delivery charges are mentioned on the app and web at the checkout page for outside of Beawar.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse21">How can I get my order delivered faster?</h4>
                </div>
                <div id="collapse21" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Yes, you can get your order delivered faster through opt a membership plan of Darbaar Mart. And 50% to 60% Membership amount will be reverted in member wallet, that wallet amount you will be used for purchases of goods.</p>
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Payments</h4>
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse22">How can I pay for my order at Darbaar Mart?</h4>
                </div>
                <div id="collapse22" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>We support the following payment options at Darbaar Mart:</p>
            <p>Cash on Delivery (available in selected pin codes)</p>
            <p>Credit Card</p>
            <p>Debit Card</p>
            <p>Net banking</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse23">How does the COD (Cash on Delivery) payment option work?</h4>
                </div>
                <div id="collapse23" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Darbaar’s Cash on Delivery option allows you to pay order value at the time of delivery for all orders between INR 500 and INR 49,999. In event of COD order CANCELLATION, your order will stand cancelled and you will have to place a new order using Pre-payment options, from thereon.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse24">Why can't I see the COD option on my payment page?</h4>
                </div>
                <div id="collapse24" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>If you do not see a COD option on your payment page, this may be due to one of the following reasons:</p>
            
            <ul class="faq_sub_content">
              <li>Your order value may be less than INR500/- or over INR 49,999/-.</li>
              <li>You may have placed another order using the COD option which is a pending delivery.</li>
              <li>If the amount of this order when added to your current order exceeds Rs. 49,999, then the COD option will be temporarilydisabled.</li>
            </ul>
            
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse25">GST, what is GST?</h4>
                </div>
                <div id="collapse25" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>GST is a single tax on the supply of goods and services that is levied on every value addition (through production and services) and is added to a product's sale price. GST has to be borne/paid by the ultimate consumer of the product or service. If your order is fulfilled on or after July 1st 2017, GST will be applicable on your orders. GST subsumes all other taxes like Excise duty, VAT, Entry tax etc.</p>
            
          <p>If I return/cancel the purchased product, will the GST/VAT amount charged be refunded?
Yes,if you return the product, the applicable GST/VAT amount will also be refunded into the source account selected at the time of return initiation. 
</p>
            
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Coupons and “My Cashback”</h4>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse26">How do I apply a coupon on my order?</h4>
                </div>
                <div id="collapse26" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You can apply a coupon on cart page before order placement. The complete list of your unused and valid coupons will be available under “My Coupons” tab of App/Darbaar Mart-Website.</p>
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Cancellation Policy</h4>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse27">What is Darbaar Mart Cancellation Policy?</h4>
                </div>
                <div id="collapse27" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>You can now cancel an order when it is in packed/shipped status, as long as the cancel option is available on App/Website/Darbaar Mart-site. This includes items purchased on sale also. Any amount paid will be credited into the same payment mode using which the payment was made.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse28">How do I cancel my Order?</h4>
                </div>
                <div id="collapse28" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Tap on “My Orders” section under the main menu of your App/Website Darbaar Mart-site and then select the item or order you want to cancel.</p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse29">I just cancelled my order. When will I receive my refund?</h4>
                </div>
                <div id="collapse29" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>If you had selected Cash on Delivery, there is no amount to be refunded because you haven't paid for your order. For payments made via Credit Card, Debit Card, Net Banking, or Wallet you will receive refund into the source account within 7-10 working days from the time of order cancellation. If payment was made by redeeming (…Payment Gateway Name.) wallet balance then, then refund will be instant post order cancellation, which can be later transferred into your bank account, by contacting (…Payment Gateway Name.) customer support team.</p>
                  </div>
                </div>
              </div>
        
        <h4 class="sub_hd">Returns & Exchange</h4>
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse30">What is Darbaar’s Return and Exchange Policy? How does it work?</h4>
                </div>
                <div id="collapse30" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>Darbaar's returns and exchange policy gives you an option to return or exchange items purchased on Darbaar Mart for any reason within the specified return/exchange period. We only ask that you don't use the product and preserve its original condition, tags, and packaging. </p>
                  </div>
                </div>
              </div>
        
        <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse31">Why has my return request been declined?</h4>
                </div>
                <div id="collapse31" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>This may have happened, if the item you returned is used, damaged or original tags are missing. In the event that the return request is declined, the user shall not be eligible for a refund, and Darbaar Mart assumes no liability in this regard. For more details, please call our customer care.</p>
                  </div>
                </div>
              </div>
        
         
            </div>
          </div>
        </div>
    </div>
     
  </div>
    </div>  
</section>
    <!-- /page content -->
@endsection
