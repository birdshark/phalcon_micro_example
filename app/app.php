<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});
$app->post('/tmall',function (){
    $name = $this->request->getPost('name');
    if (!$name) {
        return $this->response->setJsonContent(array('key' => '', 'status' => '2','msg' => 'oh shit, you miss the name'));
    }
    if(!$this->security->checkToken()){
        return $this->response->setJsonContent(array('key' => '', 'status' => '2', 'msg' => 'token miss matched'));
    }
    $type = 2;
    $es = new Estimate($type, $name);

    $key = uniqid();
    $content = HttpCurl::get($es->getUrl());
    // file_put_contents(date('YmdHis').'-start.html',$content);
    $shop = $es->parse($content);
    if ($shop) {
        if ($type == 1) {
            $url = $es->getRateUrl($shop);
            if (preg_match('/isTmall":([^,]+)/', $content, $regs)) {
                if ($regs[1] == 'true') {
                    return $this->response->setJsonContent(array('key' => '', 'status' => '1'));
                }
            }
            if (preg_match('/uid":"\d+","title":"([^"]+)"/', $content, $regs)) {
                $real_name = $regs[1];
                if ($real_name != $name) {
                    return $this->response->setJsonContent(array('key' => '', 'status' => '2'));
                }
            }

            $content = HttpCurl::get($url);
            $data = $es->parse_rate($content, $type);
            if (isset($data['title']) && $data['title']) {
                $data['title'] = $real_name;
            }
        } elseif ($type == 2) {
            $content = HttpCurl::get('https:' . $shop);
            if (preg_match('/dsr-ratelink"\svalue="([^"]+)"/x', $content, $regs)) {
                $result = $regs[1];
                $url = 'https:' . $result;
                $content = HttpCurl::get($url);
                // file_put_contents(date('YmdHis').'-third.html',$content);
            }
            $data = $es->parse_rate($content, $type);

            return $this->response->setJsonContent($data);
        }
    }
});
/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
