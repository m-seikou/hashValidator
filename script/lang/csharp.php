<?php
/**
 * @var resource $fp
 * @var string $nameSpace
 * @var string $class
 * @var mihoshi\hashValidator\hashValidator $validator
 */

function indent($n)
{
    return str_repeat('    ', $n);
}

function export($fp, array $rule, string $class, int $indent, bool $addProp)
{
    switch ($rule['type']) {
        case 'string':
        case 'float':
        case 'int':
        case 'bool':
            fwrite($fp, indent($indent) . 'public ' . $rule['type'] . ' ' . $class . ';' . PHP_EOL);
            return $rule['type'];
        case 'hash':
            fwrite($fp, PHP_EOL .
                indent($indent) . '[System.Serializable]' . PHP_EOL .
                indent($indent) . 'public class ' . $class . ' {' . PHP_EOL
            );
            foreach ($rule['key'] as $name => $r) {
                export($fp, $r, $name, $indent + 1, true);
            }
            fwrite($fp, str_repeat('    ', $indent) . '}' . PHP_EOL);
            if ($addProp) {
                fwrite($fp, indent($indent) . 'public ' . $class . ' ' . $class . ';' . PHP_EOL);
            }
            return 'cls_' . $class;
        case 'list':
            var_dump($rule);
            if ($rule['rule'] === 'hash') {
                $type = export($fp, $rule['rule'], $class, $indent, false);
            } else {
                $type = $rule['rule'];
            }
            fwrite($fp, indent($indent) . 'public ' . $type . '[] ' . $class . ';' . PHP_EOL);
            return $type;
        case 'enum':
        case 'func':
            fwrite($fp, indent($indent) . 'public ' . $rule['return'] . ' ' . $class . ';' . PHP_EOL);
            return $rule['return'];
        default:
            fwrite($fp, indent($indent) . '/*' . PHP_EOL);
            fwrite($fp, indent($indent) . ' * Undefined type[' . $rule['type'] . '] ' . $class . PHP_EOL);
            fwrite($fp, indent($indent) . ' */' . PHP_EOL);
            return '';
    }
}

fwrite($fp, 'namespace ' . $nameSpace . PHP_EOL . '{' . PHP_EOL);
export($fp, $validator->dump(), $class, 1, false);
fwrite($fp, '}');
