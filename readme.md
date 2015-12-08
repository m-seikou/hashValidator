# hash validator

- 連想配列のバリデーションをやろう。
- おもにAPIのI/Fとか複雑な$_POSTなどの入力チェックにどうぞ

## 使い方

```php:sample.php
include_once './src/hashValidator.php';

$result = (new \mihoshi\hashValidator\hashValidator($def))
        ->check($targetArray);
```

## ルール

### 共通

|key|value|
|:---|:---|
|type|以下のルールを指定するキー|
|comment|コメント|
|optional|hashのキーに対するルールとして設定することができ、trueとした場合キーの未定義を許可する|

### 整数

|key|value|
|:---|:---|
|type|int|
|min|最小値|
|max|最大値|

### 浮動小数

|key|value|
|:---|:---|
|type|float|
|min|最小値|
|max|最大値|

### 文字列

|key|value|
|:---|:---|
|type|string|
|min|最小文字数|
|max|最大文字数|
|preg|正規表現によるパターンマッチ|

### enum

|key|value|
|:---|:---|
|type|enum|
|value|有効な値の配列|

### 任意の関数

|key|value|
|:---|:---|
|type|func|
|function|関数名|
|class|クラス名|
|method|メソッド名|

`function` または`class` `method`の組のどちらかが必須。
指定の関数を呼び出します。(`class` `method`の場合静的methodを呼ぶ)
この関数は、チェック対象の値を受け取り、booleanを返す必要があります。

### リスト

|key|value|
|:---|:---|
|type|list|
|rule|各要素に適用するルール|
|min|最小要素数|
|max|最大要素数|

### 連想配列

|key|value|
|:---|:---|
|type|hash|
|(キー名)|キーに適用するルール|

### 例

ルールだけだとイメージつかないよね

```yml:example.yaml
type:hash
hoge_int:
    type:int
    min:1
    max:9999
hoge_string:
    preg:/\Ahoge/
hoge_list:
    rule:
        type:string
hoge_enum:
    type:enum
    value:
        - a
        - b
        - x
        - z
```
のようなルールがあって、
```php:data.php
$data = array(
    'hoge_int' => 1234,
    'hoge_string' => 'hogehoge',
    'hoge_list' => ['fuga','piyo','honyara'],
    'hoge_enum' => 'b',
);
```
のデータに対してバリデーションを行うには
```php:example.php
include_once './src/hashValidator.php';

$result = (new \mihoshi\hashValidator\hashValidator('example.yaml','hash'))
        ->check($data);
```
とやればよい。
