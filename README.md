# sensitive-word-check #

敏感词检测、添加关键词到黑白名单


## 如何安装 ##

见composer的packagist

## 必须使用composer加载 ##

```php
<?php
require "vendor/autoload.php";

```

### 使用 ###

```php


$whiteListPath=__DIR__.'/db/white.db';
$blackListPath=__DIR__.'/db/black.db';

$obj=new \Chencaicc\SensitiveWordCheck\SensitiveWordChecker($blackListPath,$whiteListPath);

// 添加关键词到白名单
$data=[
    '白词0',
    '白词1',
    '白词2',
    '白词3',
    '白词4',
];
try{
    $obj->addWordToWhiteList($data);
    echo '添加白名单成功<br>';
}catch(\Exception $e){
    echo '添加黑名单失败：',$e->getMessage(),'<br/>';
}

// 从白名单中删除关键词
$data=[
    '白词1',
    '白词3',
];

try{
    $obj->deleteWordFromWhiteList($data);
    echo '删除白名单成功<br>';
}catch(\Exception $e){
    echo '删除白名单失败：',$e->getMessage(),'<br/>';
}




// 添加关键词到黑名单
$data=[
    '黑词0',
    '黑词1',
    '黑词2',
    '黑词3',
    '黑词4',
];
try{
    $obj->addWordToBlackList($data);
    echo '添加黑名单成功<br>';
}catch(\Exception $e){
    echo '删除黑名单失败：',$e->getMessage(),'<br/>';
}

// 从黑名单中删除关键词
$data=[
    '黑词2',
    '黑词3',
];

try{
    $obj->deleteWordFromBlackList($data);
    echo '添加黑名单成功<br>';
}catch(\Exception $e){
    echo '删除黑名单失败：',$e->getMessage(),'<br/>';
}



//在黑名单中出现的非法词
$content = '出现黑词0，检测将出现非法！';
$check = $obj->isValid($content);
if(!$check)
    echo '出现非法单词===>'.$obj->getIllegalKeyword().'<br>';
else
    echo '语句中没有非法词';





// 在黑名单中出现的非法词，也出现在白名单中，表示内容合法
$ok=$obj->addWordToWhiteList('黑词0加');
$str = '黑词0加入白名单';
$check = $obj->isValid($str);
if(!$check)
{
    echo '出现非法单词===>'.$obj->getIllegalKeyword().'<br>';
}else{
    echo '语句中没有非法词';
}






```

