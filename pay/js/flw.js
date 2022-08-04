document.getElementById("paymentForm").addEventListener("submit", function(e){
	e.preventDefault();
	// Get input values
	let txnRef = document.getElementById('ref').value;
	let amount = document.getElementById('amount').value;
	let plan = document.getElementById('plan').value;
	let businessName = document.getElementById('business_name').value;
	let name = document.getElementById('name').value;
	let email = document.getElementById('email-address').value;
	let phone = document.getElementById('phonenumber').value;

	FlutterwaveCheckout({
		public_key: "FLWPUBK-e535ae456a4097e6d9da992ab9db70b4-X",
		tx_ref: txnRef,
		amount: amount,
		currency: "NGN",
		country: "NG",
		//payment_options: " ",
		redirect_url: // specified redirect URL
				"https://www.yenreach.com/pay.php",
		meta: {
			plan: plan,
			bussiness_name: "92a3-912ba-1192a",
		},
		customer: {
			email: email,
			phone_number: phone,
			name: name,
		},
		callback: function (data) {
			//console.log(data);
		},
		onclose: function () {
			// close modal
		},
		customizations: {
			title: "Yenreach",
			description: "Pay for Subscription",
			logo: "https://www.yenreach.com/assets/img/logo.png",
		},
	});
});
