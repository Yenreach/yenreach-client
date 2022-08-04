<?php
	if(isset($_GET['status']))
	{
		//* check payment status
		if($_GET['status'] == 'cancelled')
		{
			// echo 'YOu cancel the payment';
			header('Location: /optin.php');
		}
		elseif($_GET['status'] == 'successful')
		{
			$txid = $_GET['transaction_id'];

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$txid}/verify",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json",
					"Authorization: Bearer FLWSECK-d0c7583b3b82a8380ac81946df7a3980-X"  // Your secret key
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);

			$res = json_decode($response);
//			echo '<pre>';
//			print_r($res);
//			echo '</pre>';
//			exit();
			if($res->status)
			{
				$amountPaid = $res->data->charged_amount;
				$amountToPay = $res->data->meta->price;
				if($amountPaid >= $amountToPay)
				{
					echo 'Payment successful <br>';
					echo 'You can give item to user and redirect here';

					//* Continue to give item to the user
				}
				else
				{
					echo 'Fraud transaction detected';
				}
			}
			else
			{
				echo 'Can not process payment';
			}
		}
	}
?>
