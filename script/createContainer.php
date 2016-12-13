<?php
/**
 * hashValidatorをwebAPIのインターフェースに使用すると、リクエスト/レスポンスのコンテナを用意する必要が出てくるので、このスクリプトで
 * コンテナを作れるようにする。とりあえずC#用のスクリプトを作成した。
 *
 * トップレベルがhashになってないと動かない気がする
 *
 */
if (!isset($argc)) {
    exit (0);
}

include_once dirname(__DIR__) . '/src/hashValidator.php';
try {
    $opt = (new mihoshi\hashValidator\hashValidator([
        'type' => 'hash',
        'key'  => [
            't'         => [
                'type'    => 'enum',
                'value'   => ['yaml', 'json'],
                'default' => 'yaml',
            ],
            'i'         => [
                'type' => 'string',
            ],
            'l'         => [
                'type'    => 'enum',
                'value'   => ['csharp'],
                'default' => 'csharp',
            ],
            'o'         => [
                'type' => 'string',
            ],
            'namespace' => [
                'type'    => 'string',
                'default' => '',
            ],
            'class'     => [
                'type'    => 'string',
                'default' => '',
            ],
        ],
    ]))->check(getopt('t:i:l:o:', ['namespace::', 'class::']));

} catch (mihoshi\hashValidator\invalidDataException $e) {
    echo <<< USAGE
usage
$ php {$argv[0]} [option [Option]...]

option
    -t input file type [yaml|json] default yaml
    -i input file path *required*
    -l output language [csharp] default csharp
    -o output file path *required*
    --namespace output file add `namespace ..`
    --class output top level class name (for hash rule)
USAGE;
    exit(1);
}

$type = $opt['t'];
$inputFile = $opt['i'];
$lang = $opt['l'];
$output = $opt['o'];
$nameSpace = $opt['namespace'];
$class = $opt['class'];

$validator = new mihoshi\hashValidator\hashValidator($inputFile, $type);

$fp = fopen($output, 'w');

include __DIR__ . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $lang . '.php';

fclose($fp);
