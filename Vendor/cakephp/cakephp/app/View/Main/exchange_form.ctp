<?php

echo $this->Form->create("exchangeMoney", array("url" => "exchange"));
echo $this->Form->input("amountToBuy", array("type" => "number", "placeholder" => "Max: "));
echo $this->Form->input('currencyToExchange', array('options' => $currencies, 'selected' => 'usd'));
echo $this->Form->input('currencyToBuy', array('options' => $currencies, 'selected' => 'usd'));

echo $this->Form->end("submit", array("class" => "submitBtn"));

?>

<script>

    window.addEventListener("DOMContentLoaded", function () {
        var currencies = ['usd', 'eur', 'chf', 'pln', 'gbp', 'jpy', 'cad', 'rub', 'cny', 'try', 'nok', 'huf'];
        var rate;
        var value;

        var submitBtn = document.querySelector("div.submit input");
        var amountInput = document.querySelector("#exchangeMoneyAmountToBuy");
        var exchangeSelect = document.querySelector("#exchangeMoneyCurrencyToExchange");
        var buySelect = document.querySelector("#exchangeMoneyCurrencyToBuy");
        var req = new XMLHttpRequest();

        amountInput.value = '';

        checkInput(amountInput);
        exchange();

        exchangeSelect.addEventListener("change", function () {exchange();});
        buySelect.addEventListener("change", function () {exchange();});
        amountInput.addEventListener("keyup", function () {checkInput(amountInput);});
        amountInput.addEventListener("change", function () {checkInput(amountInput);});
        document.querySelector("form").addEventListener("submit", function(e) {
            e.preventDefault();
            var rate = exchange();
            var req = new XMLHttpRequest();
            req.open('GET', 'http://localhost/wallency/Vendor/cakephp/cakephp/exchange?currencyToExchange='+exchangeSelect.value+'&exchangeAmout='+(Math.floor((amountInput.value)/rate * 100) / 100)+'&currencyToBuy='+buySelect.value+'&buyAmount='+amountInput.value, false);
            req.send(null);
            if (req.status == 200) {
                window.location = 'http://localhost/wallency/Vendor/cakephp/cakephp/wallet?currencyToExchange='+exchangeSelect.value+'&exchangeAmout='+(Math.floor((amountInput.value)/rate * 100) / 100)+'&currencyToBuy='+buySelect.value+'&buyAmount='+amountInput.value+'&showModal=true';
            }    
            
        });

        function exchange () {
            chosenCurrency = exchangeSelect.options[exchangeSelect.selectedIndex].value;
            req.open('GET', 'https://api.ratesapi.io/api/latest?base='+chosenCurrency.toUpperCase(), false);
            req.send(null);
            if (req.status == 200) {
                rate = JSON.parse(req.responseText).rates[buySelect.options[buySelect.selectedIndex].value.toUpperCase()];
            }
            req.open('GET', 'http://localhost/wallency/Vendor/cakephp/cakephp/get-wallet', false);
            req.send();
            if (req.status == 200) {
                value = JSON.parse(req.responseText).Wallet[chosenCurrency];
            }

            amountInput.setAttribute("placeholder", "Max: "+Math.floor(parseFloat(value) * rate * 1) / 1)
            amountInput.setAttribute("max", +Math.floor(parseFloat(value) * rate * 1) / 1)

            return rate;
        }

        function checkInput (input) {
            if(input.value <= 0 || input.value.trim() == '' || input.value > Math.floor(parseFloat(value) * rate * 1) / 1) {
                submitBtn.setAttribute('disabled', true);
            } else {
                submitBtn.removeAttribute("disabled");
            }
        }
    });

    

</script>