<?php

require_once('trans.php');
require_once('workflows.php');

class VarConv{

	function __construct() {
		date_default_timezone_set('PRC');
		$this->trans = new trans();
		$this->workflows = new Workflows();
	}

	public function getParas($string){
		$str_arr = explode(' ', $string);

		if(count($str_arr)){
			if (empty($str_arr[1])) {
				$type = 'g';
				$para = $str_arr[0];
			}else{
				$type = $str_arr[0]=='c'?'c':'g';
				$para = $str_arr[1];
			}

			if ($type == 'g') {
				$trans_res = $this->trans->getTrans($para);
				if ($trans_res['res'] == 'error') {
					$this->workflows->result(1, '出错啦', '出错啦', $trans_res['content'], 'icon.png'); 
					echo $this->workflows->toxml();
					return;
				}
				
				$res = $trans_res['res'];
				unset($trans_res['res']);
				$trans_res = array_merge($trans_res, $this->trans->getAllStyle($res));

				$i=1;
				foreach ($trans_res as $key => $value) {
					$this->workflows->result($i, $value['title'], $value['title'], $value['subtitle'], 'icon.png'); 
					$i++;
				}
			}elseif ($type == 'c') {
				if (preg_match('/([a-z])([A-Z])/', $para)) {
					$title = $this->trans->toLine($para, '_', false);
					$subtitle = '下划线 => '.$para;
		            $this->workflows->result(1, $title, $title, $subtitle, 'icon.png'); 
		            $title = $this->trans->toLine($para, '-', false);
					$subtitle = '中划线 => '.$para;
		            $this->workflows->result(2, $title, $title, $subtitle, 'icon.png'); 
		            $title = $this->trans->toLine($para, ' ', false);
					$subtitle = '空格 => '.$para;
		            $this->workflows->result(3, $title, $title, $subtitle, 'icon.png'); 
		        }else{
				
			        if (strpos($para, ' ') !== false) {
			            $title = $this->trans->toLine($para, '_', false);
						$subtitle = '下划线 => '.$para;
			            $this->workflows->result(1, $title, $title, $subtitle, 'icon.png'); 

			            $title = $this->trans->toLine($para, '-', false);
						$subtitle = '中划线 => '.$para;
			            $this->workflows->result(2, $title, $title, $subtitle, 'icon.png'); 

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            $this->workflows->result(3, $title, $title, $subtitle, 'icon.png'); 
			        } 
			        elseif (strpos($para, '_') !== false) {
			            $title = $this->trans->toLine($para, '-', false);
						$subtitle = '中划线 => '.$para;
			            $this->workflows->result(1, $title, $title, $subtitle, 'icon.png'); 
			            
			            $title = $this->trans->toLine($para, ' ', false);
						$subtitle = '空格 => '.$para;
			            $this->workflows->result(2, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            $this->workflows->result(3, $title, $title, $subtitle, 'icon.png');
			        } 
			        elseif (strpos($para, '-') !== false) {
			            $title = $this->trans->toLine($para, '_', false);
						$subtitle = '下划线 => '.$para;
			            $this->workflows->result(1, $title, $title, $subtitle, 'icon.png'); 
			            
			            $title = $this->trans->toLine($para, ' ', false);
						$subtitle = '空格 => '.$para;
			            $this->workflows->result(2, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            $this->workflows->result(3, $title, $title, $subtitle, 'icon.png');
			        }else{
			        	$this->workflows->result(1, 'error', '出错啦', 'error', 'icon.png'); 
			        }
		        }
			}
		}

		echo $this->workflows->toxml();
	}


}






