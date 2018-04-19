<?php
/**
 * Created by PhpStorm.
 * User: xiazh
 * Date: 2016/6/21
 * Time: 22:48
 */

class Estimate{

    CONST TAOBAO_SHOP = 'https://shopsearch.taobao.com/search?app=shopsearch&q=#NAME#&js=1&initiative_id=staobaoz_20160706&ie=utf8&goodrate=&isb=0&shop_type=&ratesum=';
    CONST TMALL_SHOP_1 = 'https://list.tmall.com/search_product.htm?q=#NAME#&type=p&style=w&spm=a220m.1000858.a2227oh.d100&xl=#NAME#_2&from=.list.pc_2_suggest';
    CONST TMALL_SHOP_2 = 'https://list.tmall.com/search_shopitem.htm?user_id=2349022218&cat=2&spm=a220m.1000858.a2227oh.d100&oq=#NAME#&ds=1&stype=search';
    CONST TMALL_SHOP_3 = 'https://list.tmall.com/search_product.htm?q=#NAME#&type=p&vmarket=&spm=875.7931836%2FB.a2227oh.d100&from=mallfp..pc_1_searchbutton';
    CONST RATE_SHOP = 'https://rate.taobao.com/user-rate-#USERID#.htm?smToken=21091928a9ce4a8aab8b0a862e3e2f29&smSign=KlbZGR0aiFvT%2BCtFd%2BrXEg%3D%3D';

    public $type;
    public $name;
    public $url;
    public $rate_url;

    public function __construct($type,$name){
        $this->name = urlencode($name);
        $this->setUrl($type,$this->name);
    }

    public function add(){

    }

    private function _chinese_parse($name){
        return urlencode(Istring::autoCharset(urldecode($name),'utf8','gbk'));
    }

    private function setUrl($type,$name){
        switch ($type){
            case 1 : $this->url = str_replace('#NAME#', $name , self::TAOBAO_SHOP);break;
            case 2 : $this->url = str_replace('#NAME#', $this->_chinese_parse($name), self::TMALL_SHOP_3);break;
            default:$this->url = str_replace('#NAME#', $this->_chinese_parse($name), self::TMALL_SHOP_3);break;
        }
        $this->type = $type;

    }

    public function getUrl(){
        return $this->url;
    }

    public function parse($content){
        if($this->type == 1){
            preg_match('/encryptedUserId\\\\\":\\\\\"([^\\\\]+)/', $content, $result);
        }elseif($this->type == 2){
            preg_match('/<div\sclass="ks-switchable-content\smps-inner">\s\s\s\s<a\sclass="mpsi-shop"\shref="([^"]+)"/', $content, $result);
            if($result){

            }
        }
        return $result[1];
    }

    public function getRateUrl($encryptedUserId){
        return str_replace('#USERID#',$encryptedUserId , self::RATE_SHOP);
    }

    public function parse_rate($content,$type)
    {
        $data = array();
        if($type == 1){
            //店铺名称
            if (preg_match('%<div class="title">([^<]+)<span%', $content, $regs)) {
                $title = $regs[1];
                $data['title'] = trim($title);
            }

            if (preg_match('%<div class="title">([^<]+)<span%', $content, $regs)) {
                $title = $regs[1];
                $data['title'] = trim($title);
            }

            //主营
            if (preg_match(Istring::autoCharset('/主营行业：([^\d]+)(\d+)/','utf8','gbk'), $content, $regs)) {
                $business = $regs[1];
                $business_number = $regs[2];
                $data['business'] = trim($business);
                $data['business_number'] = $business_number;
            }
            if (preg_match(Istring::autoCharset('/主营占比：(\d{1,3}.\d{1,2}%)/','utf8','gbk'), $content, $regs)) {
                $major_percent = $regs[1];
                $data['major_percent'] = $major_percent;
            }
            if (preg_match(Istring::autoCharset('/主营占比：(\d{1,3}.\d{1,2}%)/','utf8','gbk'), $content, $regs)) {
                $major_percent = $regs[1];
                $data['major_percent'] = $major_percent;
            }
            preg_match_all(Istring::autoCharset('%<td>总数</td>\s+<td\sclass="rateok">[^\>]+>?(\d+)(</a>)?\s+</td>\s+<td class="ratenormal">[^\>]+>?(\d+)(</a>)?\s+</td>\s+<td class="ratebad">[^\>]+>?(\d+)(</a>)?\s+</td>%','utf8','gbk'), $content, $total_set, PREG_PATTERN_ORDER);
            if($total_set){
                $latest_half_year = 0;
                $before_half_year = 0;
                for ($i = 1 ; $i < count($total_set); $i+=2){
                    $latest_half_year += $total_set[$i][2];
                    $before_half_year += $total_set[$i][3];
                }
                $data['total'] = $latest_half_year + $before_half_year;
                $data['latest_half_year'] = $latest_half_year ;
                $data['before_half_year'] = $before_half_year;
            }
            if (preg_match('/api: "([^"]+)"/', $content, $regs)) {

                $extra_info_url = $regs[1];
                $data['extra_info_url'] = $extra_info_url;
                $extra_info_content = HttpCurl::get('http:'.$extra_info_url);
//                file_put_contents(time().'.extra_1.html', $extra_info_content);
                if (preg_match('/id-time\\\\">([^-]+)/', $extra_info_content,$regs)) {
                    $data['date'] = $regs[1].'年';
                }else{
                    if (preg_match('/<a href=\\\\\"([^\\\\]+)\\\\" target=\\\\\"_blank\\\\" class=\\\\\"company-info-entry/', $extra_info_content,$regs)) {
                        $company_url = $regs[1];
                        $company_url_content = HttpCurl::get('https:'.$company_url.'?spm=a1z0b.7.0.0.7s75qQ');
//                        file_put_contents(time().'.company.html', $company_url_content);
                        if (preg_match(Istring::autoCharset('/创店时间：([^-]+)/', 'utf8','gbk'), $company_url_content, $regs)) {
                            $data['date'] = $regs[1].'年';
                        }
                    }
                }
            }
        }
        if($type == 2) {
            //卖家信息-年限
            if (preg_match('%<span\sclass="tm-shop-age-content"[^>]+>(.*)</span>%x', $content, $result)) {
                $age = $result[1];
                $data['age'] = $age?Istring::autoCharset($age):"";
            } else {
                $data['age'] = "";
            }
            //卖家信息-主营
            if (preg_match(Istring::autoCharset('%当前主营：<a[^>]+>\s(.*)</a>%x','utf8','gbk'), $content, $result)) {
                $domain = $result[1];
                $data['domain'] = $domain?Istring::autoCharset($domain):"";
            } else {
                $data['domain'] = "";
            }

            //卖家信息-所在地区
            if (preg_match(Istring::autoCharset('/所在地区：([^<]+)/x','utf8','gbk'), $content, $result)) {
                $location = $result[1];
                $data['location'] = $location?Istring::autoCharset($location):"";
            } else {
                $data['location'] = "";
            }

            //店铺服务
            if (preg_match_all('%<a\s.*title="([^"]+)"[^>]+><span\sclass="pro\d"></span></a>%x', $content, $result)) {
                $services = $result[1];
                foreach($services as $service){
                    $data['services'][] = $service?Istring::autoCharset($service):"";
                }
            } else {
                $services = [];
            }

            //店铺半年内动态评分
            if (preg_match_all(Istring::autoCharset('%<em\stitle="[^"]+"\s+class="h">([^<]+)</em>分\s+共<span>(\d+)</span>人%x','utf8','gbk'), $content, $scores)) {
                $score = $scores[1];
            } else {
                $scores = [];
            }
            //百分比-店铺半年内动态评分
            if (preg_match_all('%<div\sclass="count\scount(\d)">\s+<span\sclass="small-star-no\d"></span>\s+<span\s[^>]+></span>\s+<em\sclass="h">([^<]+)</em>+\s+</div>%x', $content, $percents)) {
                $percent = $percents[2];
            } else {
                $percents = [];
            }
            $scores_detail = array();
            foreach($score as $key => $sco){
                $start = $key*5;   
                $temp = array();
                $temp['score'] = $sco;
                $temp['actor'] = $scores[2][$key];
                for($i = 0 ; $i < 5; $i ++){
                    $detail['score'] = $percents[1][$i+$start];
                    $detail['percent'] = $percents[2][$i+$start];
                    $temp['detail'][$i] = $detail;
                }
                $scores_detail[] = $temp;
            }
            $data['scores_detail'] = $scores_detail;
        }
        return $data;
    }
}