网宿科技CDN SDK

## 说明
网宿科技CDN API参考手册（登录客户服务系统后可下载对应文档）：http://www.chinanetcenter.com/

http 请求使用 Guzzle 三方库（v5.3），具体文档可参考：https://github.com/guzzle/guzzle

## 安装
```
composer require eddy/chinanetcdn
```

## 使用方法
```
// 实例化客户端
$client = new eddy\ChinaNetCDNClient('username', 'password');

// 发送请求
// 任务查询
$response = $client->queryReceiver([
    'starttime' => date('Y-m-d H:i:s', strtotime('-6 days')),//开始时间
    'endtime' => date('Y-m-d H:i:s'),//结束时间
    ]);
$response = $client->contReceiver();

// 更新
$response = $client->contReceiver(
    [
        'url' => 'http://www.pc6.com/azyx/142394.html;http://www.pc6.com/azyx/203556.html',//url地址。多个url用分号相隔
        'dir' => 'http://www.pc6.com/azyx/',//目录地址
    ]
);

// http 请求返回内容
echo $response->getBody();
// http 响应状态码
echo $response->getStatusCode();
```