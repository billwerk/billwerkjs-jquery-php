<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>subscriptionJS Hello World - billwerk</title>
        <script type="text/javascript" src="https://selfservice.sandbox.billwerk.com/subscription.js"></script>  
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript">
            var signupService = new subscriptionJS.Signup();
            var paymentService = new subscriptionJS.Payment({ publicApiKey: "5331a0751d8dd00c4466c9be", providerReturnUrl : "https://your_domain.com/your_finalize_page" },
                function () { console.log("subscriptionJS payment is ready"); },
                function () { alert("initialization failed!"); });

            $(function () {
                $("#form").bind("submit", function (ev) {
                    // enforce HTML5 validation:
                    ev.target.checkValidity();

                    // Create subscriptionJS DTOs from user input
                    var cart = {
                        "planVariantId": "5331a2601d8dd00c4466c9d8", // SamplePlan M
                        "componentSubscriptions": []
                    };

                    var customer = {
                        firstName: $("#firstName").val(),
                        lastName: $("#lastName").val(),
                        email: $("#email").val()
                    };

                    var paymentData = {
                        "bearer": "CreditCard:FakePSP",
                        "cardNumber": $("#cardNumber").val(),
                        "expiryMonth": $("#expMonth").val(),
                        "expiryYear": $("#expYear").val(),
                        "cardHolder": $("#cardHolder").val(),
                        "cvc": $("#cvc").val()
                    };

                    signupService.subscribe(paymentService, cart, customer, paymentData,
                    function (subscribeResult) {
                        console.log(subscribeResult);
                        alert("success! deux stuff now.");
                    },
                    function (errorData) {
                        console.log(errorData);
                        alert("something went wrong :(");
                    });

                    // make sure the form is not submitted!
                    ev.preventDefault();
                    return false;
                });
            });
        </script>
        <style type="text/css">
            body { font-family: Sans-Serif; font-size: 14px; }
            input, select, button { padding: 4px; }
        </style>
    </head>
    <body>
        <h1>Hello subscriptionJS!</h1>
            <form id="form">
                <input type="text" id="firstName" required placeholder="First Name"/><br />
                <input type="text" id="lastName" required placeholder="Last Name"/><br />
                <input type="email" id="email" required placeholder="E-mail"/><br />

                <input type="text" id="cardHolder" required placeholder="Card Holder's Name"/><br />

                <input type="text" id="cardNumber" required placeholder="Card Number" pattern="[0-9]{10,16}" maxlength="16" /><br />

                Expiry
                <select required id="expMonth">
                    <option disabled value="">MM</option>
                    <option value="01">01</option><option value="02">02</option><option value="03">03</option>
                    <option value="04">04</option><option value="05">05</option><option value="06">06</option>
                    <option value="07">07</option><option value="08">08</option><option value="09">09</option>
                    <option value="10">10</option><option value="11">11</option><option value="12">12</option>
                </select>

                /

                <select required id="expYear">
                    <option disabled value="">YY</option>
                    <option value="2014">14</option><option value="2015">15</option><option value="2016">16</option>
                    <option value="2017">17</option><option value="2018">18</option><option value="2019">19</option>
                    <option value="2020">20</option><option value="2021">21</option><option value="2022">22</option>
                </select><br />
                
                <input type="text" id="cvc" required maxlength="3" pattern="[0-9]{3}" placeholder="CVV / CVC"/><br />
                <button id="signup" type="submit">Signup</button>
            </form> 
    </body>
</html>

