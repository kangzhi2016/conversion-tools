<?php

require_once('trans.php');
require_once('workflows.php');

class VarConv{

	function __construct() {
		$this->trans = new trans();
	}

	public function getParas($string){
        if (empty($string)){
            set_result(1, '变量转换小工具', '变量翻译（默认），变量形式转换（驼峰转下划线等）', '', 'icon.png');
        }else{
            $str_arr = explode(' ', $string);
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
					set_result(1, '出错啦', '出错啦', $trans_res['content'], 'icon.png');
				}
				
				$res = $trans_res['res'];
				unset($trans_res['res']);
				$trans_res = array_merge($trans_res, $this->trans->getAllStyle($res));

				$i=1;
				foreach ($trans_res as $key => $value) {
					set_result($i, $value['title'], $value['title'], $value['subtitle'], 'icon.png');
					$i++;
				}
			}elseif ($type == 'c') {
				if (preg_match('/([a-z])([A-Z])/', $para)) {
					$title = $this->trans->toLine($para, '_', false);
					$subtitle = '下划线 => '.$para;
		            set_result(1, $title, $title, $subtitle, 'icon.png');
		            $title = $this->trans->toLine($para, '-', false);
					$subtitle = '中划线 => '.$para;
		            set_result(2, $title, $title, $subtitle, 'icon.png');
		            $title = $this->trans->toLine($para, ' ', false);
					$subtitle = '空格 => '.$para;
		            set_result(3, $title, $title, $subtitle, 'icon.png');
		        }else{
				
			        if (strpos($para, ' ') !== false) {
			            $title = $this->trans->toLine($para, '_', false);
						$subtitle = '下划线 => '.$para;
			            set_result(1, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toLine($para, '-', false);
						$subtitle = '中划线 => '.$para;
			            set_result(2, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            set_result(3, $title, $title, $subtitle, 'icon.png');
			        } 
			        elseif (strpos($para, '_') !== false) {
			            $title = $this->trans->toLine($para, '-', false);
						$subtitle = '中划线 => '.$para;
			            set_result(1, $title, $title, $subtitle, 'icon.png');
			            
			            $title = $this->trans->toLine($para, ' ', false);
						$subtitle = '空格 => '.$para;
			            set_result(2, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            set_result(3, $title, $title, $subtitle, 'icon.png');
			        } 
			        elseif (strpos($para, '-') !== false) {
			            $title = $this->trans->toLine($para, '_', false);
						$subtitle = '下划线 => '.$para;
			            set_result(1, $title, $title, $subtitle, 'icon.png');
			            
			            $title = $this->trans->toLine($para, ' ', false);
						$subtitle = '空格 => '.$para;
			            set_result(2, $title, $title, $subtitle, 'icon.png');

			            $title = $this->trans->toHump($para, false);
						$subtitle = '驼峰 => '.$para;
			            set_result(3, $title, $title, $subtitle, 'icon.png');
			        }else{
			        	set_result(1, 'error', '出错啦', 'error', 'icon.png');
			        }
		        }
			}
		}

		echo_result();
	}


}






