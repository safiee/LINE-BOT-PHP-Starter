<?php
$access_token = 'z2TVe07oCkUP2/cDQ8/Y8w+zT6HHfHDF6rRKuewNXRK5qA25Fbkgkl2xQTqSd+tnZ4z1Bacb4YVSi99KuuC5TKbUOJdDNPR2MPWMH+iesB4LI9mcNAsz9HyWcMFWsQYCaIczAdBRSg+W04nC399yOwdB04t89/1O/w1cDnyilFU=';
		$json_string = file_get_contents('php://input');
        $jsonObj = json_decode($json_string); //�Ѻ JSON �� decode �� StdObj
        $to = $jsonObj-&gt;{"result"}[0]-&gt;{"content"}-&gt;{"from"}; //�Ҽ����
        $text = $jsonObj-&gt;{"result"}[0]-&gt;{"content"}-&gt;{"text"}; //�Ң�ͤ����������
        
        $text_ex = explode(':', $text); //��Ң�ͤ������¡ : ���� Array
		if($text_ex[0] == "��ҡ���"){ 
			//��Ң�ͤ������ "��ҡ���" ���ӡ�ô֧�����Ũҡ Wikipedia �Ҩҡ�¡�͹ //https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles=PHP 
			$ch1 = curl_init(); 
			curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch1, CURLOPT_URL,'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]); 
			$result1 = curl_exec($ch1); curl_close($ch1); 
			$obj = json_decode($result1, true); 
			foreach($obj['query']['pages'] as $key => $val){ 
				$result_text = $val['extract']; 
				} 
		
		if(empty($result_text)){//�����辺����Ҩҡ en
			$ch1 = curl_init(); 
			curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false); 
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch1, CURLOPT_URL, 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]); $result1 = curl_exec($ch1); curl_close($ch1); 
			$obj = json_decode($result1, true);
			foreach($obj['query']['pages'] as $key => $val){
				$result_text = $val['extract'] ; 
			} 
		} 

		if(empty($result_text)){ //�Ҩҡ en ��辺��͡��� ��辺������ �ͺ��Ѻ�
			$result_text = '��辺������'; }
			$response_format_text = ['contentType'=>1,"toType"=>1,"text"=>$result_text]
		}else if($text_ex[0] == "�ҡ��"){//��Ҿ��������� �ҡ�� �����仴֧ API �ҡ wunderground �� 
				$ch1 = curl_init(); 
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($ch1, CURLOPT_URL, 'http://api.wunderground.com/api/yourkey/forecast/lang:TH/q/Thailand/'.str_replace(' ', '%20', $text_ex[1]).'.json'); 

				$result1 = curl_exec($ch1); curl_close($ch1);
				$obj = json_decode($result1, true); 
					if(isset($obj['forecast']['txt_forecast']['forecastday'][0]['fcttext_metric'])){ 
						$result_text = $obj['forecast']['txt_forecast']['forecastday'][0]['fcttext_metric']; 
						}else{//�������͡Ѻ�ͺ��Ѻ�����辺������ 
							$result_text = '��辺������'; 
							} 
				
		$response_format_text = ['contentType'=>1,"toType"=>1,"text"=>$result_text]; 
		}
			else if($text == '�͡��'){ //������ ����ͧ������ Bot �ͺ��Ѻ������ʤӹ���� ������� �͡�� ���ͺ��� �����Ѻ�� 
				$response_format_text = ['contentType'=>1,"toType"=>1,"text"=>"�����Ѻ��"]; 
				}else{//�͡�������� ���ʴ� 
					$response_format_text = ['contentType'=>1,"toType"=>1,"text"=>"���ʴ�"]; 
					} 
					
					// toChannel?eventType
					$post_data = ["to"=>[$to],"toChannel"=>"1383378250","eventType"=>"138311608800106203","content"=>$response_format_text]; //�觢������ 
					$ch = curl_init("https://trialbot-api.line.me/v1/events"); 
					curl_setopt($ch, CURLOPT_POST, true); 
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data)); 
					$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				    $result = curl_exec($ch);
					curl_close($ch);
					echo $result . "\r\n";		
?>					
