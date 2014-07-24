<div class="row">
    <?php echo $MS->messages('/'.$CUSTOMER->username.'/settings'); ?>
    <div class="col-md-4 col-sm-12 col-xs-12">          
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
                                    data-key="<?php echo STRIPE_TEST_KEY; ?>"
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
                                    data-key="<?php echo STRIPE_TEST_KEY; ?>"
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
                                    data-key="<?php echo STRIPE_TEST_KEY; ?>"
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
                                    data-key="<?php echo STRIPE_TEST_KEY; ?>"
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
                                    data-key="<?php echo STRIPE_TEST_KEY; ?>"
                                    data-amount="50000"
                                    data-name="Beast Franchise Funds"
                                    data-description="($500.00)"
                                    data-label="Add $500"
                                    data-image="/img/Claw_Marks.png">
                                </script>
                            </form>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-4 col-sm-12 col-xs-12">
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
    //$form.get(0).submit();
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
});
</script>