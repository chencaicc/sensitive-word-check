# sensitive-word-infalid #

敏感词检测、添加关键词到黑白名单


## 如何安装 ##

见composer的packagist

## 必须使用composer加载 ##

```php
<?php
require "vendor/autoload.php";
use Chencaicc\SensitiveWordCheck\Keyword;

```

### 使用 ###

```php


$whitePath=__DIR__.'/db/white.db';
$blackPath=__DIR__.'/db/black.db';
$obj=new Keyword($blackPath,$whitePath);


// 添加关键词到白名单
$data=[
    '白词0',
    '白词1',
    '白词2',
    '白词3',
    '白词4',
];
$ok = $obj->addWhiteKeyword($data);
var_dump($ok);

// 从白名单中删除关键词
$data=[
    '白词1',
    '白词3',
];
$ok = $obj->deleteWhiteKeyword($data);
var_dump($ok);

// 添加关键词到黑名单
$data=[
    '黑词0',
    '黑词1',
    '黑词2',
    '黑词3',
    '黑词4',
];
$ok = $obj->addBlackKeyword($data);
var_dump($ok);

// 从黑名单中删除关键词
$data=[
    '黑词2',
    '黑词3',
];
$ok = $obj->deleteBlackKeyword($data);
var_dump($ok);

echo '<hr>';

// 检测敏感词-------------start---------------------
$str = '出现黑词0，检测将出现非法！';
$check = $obj->validate($str);
if(!$check)
    echo '出现非法单词===>'.$obj->getIllegalWord().'<br>';
else
    var_dump($check);
// 检测敏感词-------------end---------------------
echo '<hr>';


// 检测关键词在白名单中的示例
$ok=$obj->addWhiteKeyword('黑词0加');
$str = '黑词0加入白名单';
$check = $obj->validate($str);
if(!$check)
{
    echo '非法==>'.$obj->getIllegalWord();
}else{
    echo '合法';
    var_dump($check);
}

```

