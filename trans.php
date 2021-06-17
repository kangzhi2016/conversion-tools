<?php
class trans
{
    const FIXEDKEY = [
        [
            'keyfrom' => 'CoderVar',
            'key' => '802458398'
        ],
        [
            'keyfrom' => 'whatMean',
            'key' => '1933652137'
        ],
        [
            'keyfrom' => 'chinacache',
            'key' => '1247577973'
        ],
        [
            'keyfrom' => 'huipblog',
            'key' => '439918742'
        ],
        [
            'keyfrom' => 'chinacache',
            'key' => '1247577973'
        ],
        [
            'keyfrom' => 'fanyi-node',
            'key' => '593554388'
        ],
        [
            'keyfrom' => 'wbinglee',
            'key' => '1127870837'
        ],
        [
            'keyfrom' => 'forum3',
            'key' => '1268771022'
        ],
        [
            'keyfrom' => 'node-translator',
            'key' => '2058911035'
        ],
        [
            'keyfrom' => 'kaiyao-robot',
            'key' => '2016811247'
        ],
        [
            'keyfrom' => 'stone2083',
            'key' => '1576383390'
        ],
        [
            'keyfrom' => 'myWebsite',
            'key' => '423366321'
        ],
        [
            'keyfrom' => 'leecade',
            'key' => '54015339'
        ],
        [
            'keyfrom' => 'github-wdict',
            'key' => '619541059'
        ],
        [
            'keyfrom' => 'lanyuejin',
            'key' => '2033774719'
        ],
    ];


//    function __construct(argument)
//    {
//        # code...
//    }

    function getTrans($input)
    {
        $res = '';

        $fixed_key = self::FIXEDKEY;
        foreach ($fixed_key as $key) {
            $paras = [
                'keyfrom' => $key['keyfrom'],
                'key' => $key['key'],
                'type' => 'data',
                'doctype' => 'json',
                'version' => '1.1',
                'q' => $input,
            ];
            $res = $this->curl_get('http://fanyi.youdao.com/openapi.do', $paras);

            if ($res) break;
        }

        $res_arr = json_decode($res, true);
        if ($res_arr['errorCode']) {
            return [
                'res'=>'error',
                'content'=>json_encode($res_arr)
            ];
        }

        $res_data = [];
        $res_data['res'] = $res_arr['translation'][0];

        $stand_trans = [
            'title' => $this->toLine($res_arr['translation'][0], '_'),
            'subtitle' => '推荐翻译 => ' . $res_arr['query'] .':'. $res_arr['translation'][0]
        ];
        $res_data[] = $stand_trans;

        if (isset($res_arr['web'])) {
            foreach ($res_arr['web'] as $web) {
                $web_trans = [
                    'title' => $this->toLine($web['value'][0], '_'),
                    'subtitle' => '网络翻译 => ' . $web['key'] .':'. $web['value'][0]
                ];
                $res_data[] = $web_trans;
            }
        }

        return $res_data;
    }

    function getAllStyle($str)
    {
        $res_data = [];

        //驼峰
        $hump = [
            'title' => $this->toHump($str),
            'subtitle' => '驼峰 => ' . $str
        ];
        $res_data[] = $hump;
        //_
        $web_trans = [
            'title' => $this->toLine($str, '_'),
            'subtitle' => '下划线 => ' . $str
        ];
        $res_data[] = $web_trans;
        //-
        $web_trans = [
            'title' => $this->toLine($str, '-'),
            'subtitle' => '中划线 => ' . $str
        ];
        $res_data[] = $web_trans;

        return $res_data;
    }


    function getFilter()
    {
        return ['to', 'and', 'or', 'the', 'a', 'at', 'of'];
    }

    function toHump($str, $filter = true)
    {
        $symbol = '';
        if (strpos($str, ' ') !== false) {
            $symbol = ' ';
        } elseif (strpos($str, '_') !== false) {
            $symbol = '_';
        } elseif (strpos($str, '-') !== false) {
            $symbol = '-';
        }

        if ($symbol == '') return $str;

        $str_arr = explode($symbol, $str);
        $i = 0;
        $res_str = '';
        foreach ($str_arr as $str_val) {
            if ($str_val == ' ' || ($filter && in_array(strtolower($str_val), $this->getFilter()))) {
                continue;
            }

            $res_str .= $i ? ucfirst($str_val) : $str_val;
            $i++;
        }

        return $res_str;
    }

    function toLine($str, $sep = '', $filter = true)
    {
        $symbol = '';
        if (strpos($str, ' ') !== false) {
            $symbol = ' ';
        } elseif (strpos($str, '_') !== false) {
            $symbol = '_';
        } elseif (strpos($str, '-') !== false) {
            $symbol = '-';
        } elseif (preg_match('/([a-z])([A-Z])/', $str)) {
            $symbol = 'hump';
        }

        if ($symbol == '') return $str;

        if ($symbol == 'hump') {
            return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $sep . "$2", $str));
        } else {
            $str_arr = explode($symbol, $str);
            foreach ($str_arr as $str_key => $str_val) {
                if ($str_val == ' ' || ($filter && in_array(strtolower($str_val), $this->getFilter()))) {
                    unset($str_arr[$str_key]);
                }
            }
            return implode($sep, $str_arr);
        }
    }


    function curl_get($url, $paras = null, $config = null)
    {
        if (is_array($paras) && 0 < count($paras)) {
            $url = $url . (strrpos($url, '?') > 0 ? '&' : '?') . http_build_query($paras);
        }
        //         echo $url.'<br>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (isset($config) && isset($config['readTimeout'])) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $config['readTimeout']);
        }
        if (isset($config) && isset($config['connectTimeout'])) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $config['connectTimeout']);
        }
        //https 请求
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            //             echo $httpStatusCode;
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }


}


