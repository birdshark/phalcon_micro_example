<?php
/**
 * HttpCurl Curl模拟Http工具类
 *
 *
 * @author      gaoming13 <gaoming13@yeah.net>
 * @link        https://github.com/gaoming13/HttpCurl
 * @link        http://me.diary8.com/
 */
class HttpCurl {
    /**
     * 模拟GET请求
     *
     * @param string $url
     * @param string $data_type
     *
     * @return mixed
     *
     * Examples:
     * ```
     * HttpCurl::get('http://api.example.com/?a=123&b=456', 'json');
     * ```
     */
    static public function get($url, $data_type='text') {
        $cl = curl_init();
        if(stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($cl, CURLOPT_HEADER, 1);
        curl_setopt($cl, CURLOPT_URL, $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($cl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36');
        curl_setopt($cl, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($cl, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($cl, CURLOPT_TIMEOUT, 20);
        curl_setopt($cl, CURLOPT_BINARYTRANSFER,1);
		if(strpos($url,'taobao') === false){
			$cookie = '_med=dw:1920&dh:1080&pw:1920&ph:1080&ist:0; x=__ll%3D-1%26_ato%3D0; OZ_1U_2061=vid=v8230f7b44a589.0&ctime=1478692949&ltime=1478692838; l=At3d7j7wt0AG1L9aZapRvWUCbbPQZRFv; otherx=e%3D1%26p%3D*%26s%3D0%26c%3D0%26f%3D0%26g%3D0%26t%3D0; cq=ccp%3D0; isg=AtPTBu1ajmXcJEyuMh-Q62kCYlf3jRDi8SgauoXwJvIpBPOmDVj3mjFUSkKR; cna=lgd8DzUalGMCAdNnxL62xwCe; _tb_token_=c6758e9738e37; ck1=; uc1=cookie14=UoTcDNzR4R71iw%3D%3D&lng=zh_CN&cookie16=VFC%2FuZ9az08KUQ56dCrZDlbNdA%3D%3D&existShop=false&cookie21=U%2BGCWk%2F7p4sj&tag=8&cookie15=W5iHLLyFOGW7aA%3D%3D&pas=0; uc3=sg2=Bvck8cabPlRYhfinFFcK8GGBppFCexvgILgZH9GsVQw%3D&nk2=G4mgOXuHkrDhS2jj&id2=VASp3M%2FvfeNK&vt3=F8dBzWYaFd4SpGLskG8%3D&lg2=WqG3DMC9VAQiUQ%3D%3D; lgc=xiazhichao75; tracknick=xiazhichao75; cookie2=12ecfef229dd1304f9ee565d9a5925b6; cookie1=B0auxdkjQWshj%2Bh9Odvu0mOIZe3Tu9vkITmlciUWRh8%3D; unb=753888380; t=bc92d28f6690c3c6b21ed523ba8ae6b5; skt=12687ba9dbcb68a4; _nk_=xiazhichao75; _l_g_=Ug%3D%3D; cookie17=VASp3M%2FvfeNK; hng=CN%7Czh-cn%7CCNY; uss=UoSOflwES873z8plZ5DRreUOJhVojWCjWS2gI%2B8W5%2FsSb85H6OXz65r3; login=true';
		}else{
            $cookie = 'miid=9190215243064562819; hng=CN%7Czh-cn%7CCNY; l=AqioBFnNQTZoT8rFeDF8SnCp-Jy6UQzb; thw=cn; v=0; _tb_token_=36e5ea0f0e66d; swfstore=307725; x=e%3D1%26p%3D*%26s%3D0%26c%3D0%26f%3D0%26g%3D0%26t%3D0%26__ll%3D-1%26_ato%3D0; whl=-1%260%260%261503905583384; cq=ccp%3D1; cna=lgd8DzUalGMCAdNnxL62xwCe; uc3=sg2=Bvck8cabPlRYhfinFFcK8GGBppFCexvgILgZH9GsVQw%3D&nk2=G4mgOXuHkrDhS2jj&id2=VASp3M%2FvfeNK&vt3=F8dBzWYcZlhbr4AOQss%3D&lg2=V32FPkk%2Fw0dUvg%3D%3D; existShop=MTUwMzkwNTgzNA%3D%3D; uss=WqGmBsJo%2FVLTICkWCVZbC%2Feqsfmo7BBOXt2OWVeUukazS%2FfZGE%2BoFFom; lgc=xiazhichao75; tracknick=xiazhichao75; cookie2=36179ca17c6384858774bf3c8cf76ade; sg=50d; mt=np=&ci=-1_0; cookie1=B0auxdkjQWshj%2Bh9Odvu0mOIZe3Tu9vkITmlciUWRh8%3D; unb=753888380; skt=be5d7b58ea09dddd; t=bc92d28f6690c3c6b21ed523ba8ae6b5; _cc_=UIHiLt3xSw%3D%3D; tg=0; _l_g_=Ug%3D%3D; _nk_=xiazhichao75; cookie17=VASp3M%2FvfeNK; uc1=cookie14=UoTcDNNg05q9Kw%3D%3D&lng=zh_CN&cookie16=UtASsssmPlP%2Ff1IHDsDaPRu%2BPw%3D%3D&existShop=false&cookie21=UIHiLt3xThN%2B&tag=8&cookie15=URm48syIIVrSKA%3D%3D&pas=0; isg=AtXVAN5OILcLnQrogBWOtXto5NGF1_5Mg7Lc_Fd6kcybrvSgHiKZtONsDITj';
            curl_setopt($cl, CURLOPT_REFERER, 'https://sec.taobao.com/query.htm');
		}
        curl_setopt($cl, CURLOPT_COOKIE, $cookie);
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        $headerSize = curl_getinfo($cl, CURLINFO_HEADER_SIZE);
// 根据头大小去获取头信息内容
        $header = substr($content, 0, $headerSize);
        curl_close($cl);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($data_type == 'json') {
                $content = json_decode($content);
            }
            if(strpos($header, 'gzip') === false){
                return $content;
            }else{
                $content = substr($content, $headerSize);
                return gzdecode($content);
            }

        } else {
            return FALSE;
        }
    }
    /**
     * 模拟POST请求
     *
     * @param string $url
     * @param array $fields
     * @param string $data_type
     *
     * @return mixed
     *
     * Examples:
     * ```
     * HttpCurl::post('http://api.example.com/?a=123', array('abc'=>'123', 'efg'=>'567'), 'json');
     * HttpCurl::post('http://api.example.com/', '这是post原始内容', 'json');
     * 文件post上传
     * HttpCurl::post('http://api.example.com/', array('abc'=>'123', 'file1'=>'@/data/1.jpg'), 'json');
     * ```
     */
    static public function post($url, $fields, $data_type='text') {
        $cl = curl_init();
        if(stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($cl, CURLOPT_URL, $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($cl, CURLOPT_POST, true);
        curl_setopt($cl, CURLOPT_POSTFIELDS, $fields);
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        echo ($content);
        curl_close($cl);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($data_type == 'json') {
                $content = json_decode($content);
            }
            return $content;
        } else {
            return FALSE;
        }
    }
}