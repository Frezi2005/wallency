<?php

    echo "<div class='transferForm col-8k-3 col-4k-3 col-wqhd-3 col-fhd-3 col-hd-3 col-480p-6 col-360p-8 col-sd-9'>";
    echo "<div class='overlay'></div>";
    echo "<h2>".__('transfer')."</h2>";
    echo $this->Form->create("transferMoney", array("url" => "transfer"));
    echo "<div class='col'>";
    echo $this->Form->input("amountToSend", array("type" => "number", "placeholder" => __('max_transfer_amount'), 'div' => false));
    echo "<span class='focus-border'></span></div>";
    echo "<div class='col'>";
    echo $this->Form->input("recipientLogin", array("type" => "text", "placeholder" => __('recipient_login'), 'div' => false));
    echo "<span class='focus-border'></span></div>";
    echo $this->Form->input('currencyToSend', array('options' => $currencies, 'selected' => 'usd'));
    echo $this->Form->end(__('send'), array("class" => "submitBtn"));
    echo "</div>";

    echo $this->Html->css('transferForm');
    echo $this->Html->css('form');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');


?>

<script>

    var select = document.querySelector('select#transferMoneyCurrencyToSend');
    var amountInput = document.querySelector('#transferMoneyAmountToSend');
    var currency = 'usd';
    var response;

    amountInput.value = '';

    if(amountInput.value < 0 || amountInput.value == '') {
        document.querySelector('div.submit input').setAttribute('disabled', true);
    }

    currency = select.options[select.selectedIndex].value;
    var req = new XMLHttpRequest();
    req.open('GET', 'http://localhost/wallency/Vendor/cakephp/cakephp/check-money?currency='+currency, false);
    req.send(null);
    if (req.status == 200) {
        response = JSON.parse(req.responseText).Wallet;
        amountInput.setAttribute('max', response[currency]);
        amountInput.setAttribute('placeholder', "<?php echo __('max_transfer_amount');?>"+response[currency]);
    }     

    amountInput.addEventListener('keyup', function () {
        if(amountInput.value <= 0 || amountInput.value == '' || amountInput.value > parseInt(response[currency])) {
            console.log('disabled');
            document.querySelector('div.submit input').setAttribute('disabled', true);
        } else {
            console.log('not disabled');
            document.querySelector('div.submit input').removeAttribute('disabled');
        }
    });

    select.addEventListener('change', function () {
        currency = select.options[select.selectedIndex].value;
        var req = new XMLHttpRequest();
        req.open('GET', 'http://localhost/wallency/Vendor/cakephp/cakephp/check-money?currency='+currency, false);
        req.send(null);
        if (req.status == 200) {
            response = JSON.parse(req.responseText).Wallet;
            amountInput.setAttribute('max', response[currency]);
            amountInput.setAttribute('placeholder', "<?php echo __('max_transfer_amount');?>"+response[currency]);
        }     
    });

</script>