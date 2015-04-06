<div class='col-md-12 col-sm-12 col-xs-12'>
    <div class="row">
        <?php echo $MS->messages('/'.$CUSTOMER->username.'/settings'); ?>
        
        <?php if($CUSTOMER->funds < 1000): ?>
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>You will be able to Withdraw Funds at $10.00</strong>
        </div>
        <?php endif; ?>
        
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">          
            <div class='box'>
                <div class='box-header purple-background'>
                  <div class='title'>
                      ADD FUNDS
                  </div>
                </div>
                
                <div class='box-content'>
                    <div class="row">
                        <div class="col-md-3">
                            <form action="/<?php echo $CUSTOMER->username; ?>/addFunds" method="POST">
                                <input type="hidden" name="amount" value="2000" />
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?php echo STRIPE_LIVE_KEY; ?>"
                                    data-amount="2000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($20.00)"
                                    data-label="Add $20"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="/<?php echo $CUSTOMER->username; ?>/addFunds" method="POST">
                                <input type="hidden" name="amount" value="5000" />
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?php echo STRIPE_LIVE_KEY; ?>"
                                    data-amount="5000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($50.00)"
                                    data-label="Add $50"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="/<?php echo $CUSTOMER->username; ?>/addFunds" method="POST">
                                <input type="hidden" name="amount" value="10000" />
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?php echo STRIPE_LIVE_KEY; ?>"
                                    data-amount="10000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($100.00)"
                                    data-label="Add $100"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                        <div class="col-md-3">
                            <form action="/<?php echo $CUSTOMER->username; ?>/addFunds" method="POST">
                                <input type="hidden" name="amount" value="25000" />
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?php echo STRIPE_LIVE_KEY; ?>"
                                    data-amount="25000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($250.00)"
                                    data-label="Add $250"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <div class="row">
                        <div class="col-md-3">
                            <form action="/<?php echo $CUSTOMER->username; ?>/addFunds" method="POST">
                                <input type="hidden" name="amount" value="50000" />
                                <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="<?php echo STRIPE_LIVE_KEY; ?>"
                                    data-amount="50000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($500.00)"
                                    data-label="Add $500"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class='box'>
                <div class='box-header purple-background'>
                  <div class='title'>
                      CURRENT BALANCE
                  </div>
                </div>
                
                <div class='box-content'>
                    <h3>Current Balance: <span class="label label-success"><?php echo '$' . number_format(bcdiv($CUSTOMER->funds, 100), 2); ?></span></h3>
                </div>
            </div>
        </div>
        
        <?php if($CUSTOMER->funds >= 1000000000000): ?>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <div class='box'>
                <div class='box-header red-background'>
                  <div class='title'>
                      WITHDRAW FUNDS
                  </div>
                </div>
                
                <div class='box-content'>
                    <form action="/<?php echo $CUSTOMER->username; ?>/withdrawFunds" metod="post" role="form" id="withdraw-form">
                        <div class="form-group">
                            <label for="routing">Routing Number</label>
                            <input type="text" class="form-control" id="routing" placeholder="Routing Number">
                            <span id="routing_message"></span>
                        </div>
                        <div class="form-group">
                            <label for="account">Account Number</label>
                            <input type="text" class="form-control" id="account" placeholder="Account Number">
                            <span id="account_message"></span>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="amount" id="amount">
                                <?php for($i = 10; $i <= 500; $i++): ?>
                                    <?php if($i % 10 == 0 && $i <= ($CUSTOMER->funds / 100)): ?>
                                        <option value="<?php echo $i * 100; ?>">$<?php echo $i; ?></option>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <input type="hidden" id="country" name="country" value="US" />
                        <button type="submit" id="withdraw" class="btn btn-danger">Withdraw</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">          
            <div class='box'>
                <div class='box-header purple-background'>
                  <div class='title'>
                      My Info
                  </div>
                </div>
                
                <div class='box-content'>
                    <form role="form" action="/<?php echo $CUSTOMER->username; ?>/updateInfo" method="post">
                        <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $CUSTOMER->ID; ?>" />
                        <div class="form-group">
                            <label for="customer[email]">Email</label>
                            <input type="text" class="form-control" id="customer[email]" name="customer[email]" placeholder="Email Address" value="<?php echo $CUSTOMER->email; ?>">
                        </div>
                        <div class="form-group">
                            <label for="customer[username]">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $CUSTOMER->username; ?>">
                        </div>
                        <button class="btn btn-success pull-right">Update</button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
function stripeResponseHandler(status, response) {
  var $form = $('#payment-form');

  if (response.error) {
    // Show the errors on the form
    $form.find('.payment-errors').text(response.error.message).removeClass('hide');
    $form.find('button').prop('disabled', false);
  } else {
    // response contains id and card, which contains additional card details
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
    // and submit
    $form.get(0).submit();
  }
}

function stripeResponseHandlerWithdraw(status, response) {
  var $form = $('#withdraw-form');

  if (response.error) {
    // Show the errors on the form
    $form.find('.withdraw-errors').text(response.error.message).removeClass('hide');
    $form.find('button').prop('disabled', false);
  } else {
    // response contains id and card, which contains additional card details
    var token = response.id;
    // Insert the token into the form so it gets submitted to the server
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
    // and submit
    $form.get(0).submit();
  }
}

Stripe.setPublishableKey('pk_test_3WzVRDUbE8hAL8Wazx5rL1J0');


$(function($) {
    $('#payment-form').submit(function(event) {
    var $form = $(this);
    
    Stripe.card.createToken({
      name: $('input[data-stripe=name]').val(),
      number: $('input[data-stripe=number]').val(),
      cvc: $('input[data-stripe=cvc]').val(),
      exp_month: $('input[data-stripe=exp-month]').val(),
      exp_year: $('input[data-stripe=exp-year]').val()
    }, stripeResponseHandler);
    
    // Disable the submit button to prevent repeated clicks
    $form.find('button').prop('disabled', true);
    
    Stripe.card.createToken($form, stripeResponseHandler);
    
    // Prevent the form from submitting with the default action
    return false;
    });
    
    $('#withdraw-form').submit(function(event) {
        var $form = $(this);
        $("#withdraw").addClass('btn-disabled').html('<h4 class="text-danger"><i class="fa fa-refresh fa-spin"></i> Processing...</h4>');
        
        Stripe.bankAccount.createToken({
          country: 'US',
          routingNumber: $('#routing').val(),
          accountNumber: $('#account').val()
        }, stripeResponseHandlerWithdraw);
        
        Stripe.bankAccount.createToken($form, stripeResponseHandlerWithdraw);
        
        //$($form).submit();
       
       return false; 
    });
    
    $("#routing").keyup(function() {
      
        $("#routing_message").html('<h4 class="text-primary"><i class="fa fa-refresh fa-spin"></i> Processing...</h4>');
      
        var routing = $(this).val();
        var valid = Stripe.bankAccount.validateRoutingNumber(routing, 'US');
        if(valid == true) {
          $.get('/<?php echo $CUSTOMER->username; ?>/checkRouting/?rt='+routing, function(data) {
              console.log(data);
              if(data != "") {
                  $("#routing_message").html('<h5 class="text-success"><i class="glyphicon glyphicon-ok"></i> '+data+'</h5>');
              } else {
                  $("#routing_message").html('<h5 class="text-danger"><i class="glyphicon glyphicon-remove"></i> Not a valid US routing number</h5>');
              }
          });
        } else {
          $("#routing_message").html('<h5 class="text-danger"><i class="glyphicon glyphicon-remove"></i> Not a valid US routing number</h5>');
        }
    });
  
    $("#account").keyup(function() {
      
        $("#account_message").html('<h4 class="text-primary"><i class="fa fa-refresh fa-spin"></i> Processing...</h4>');
      
        var account = $(this).val();
        var valid = Stripe.bankAccount.validateAccountNumber(account, 'US');

        if(valid == true) {
            $("#account_message").html('<h5 class="text-success"><i class="glyphicon glyphicon-ok"></i></h5>');
        } else {
            $("#account_message").html('<h5 class="text-danger"><i class="glyphicon glyphicon-remove"></i> Not a valid US account number</h5>');
        }
      
    });
});
</script>