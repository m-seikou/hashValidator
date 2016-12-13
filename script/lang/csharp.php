<?php
/**
 * @var resource $fp
 * @var string $nameSpace
 * @var string $class
 * @var mihoshi\hashValidator\hashValidator $validator
 */

if ($nameSpace) {
    fputs($fp, 'namespace ' . $nameSpace . ';' . PHP_EOL);
}
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
            fputs($fp, indent($indent) . 'public ' . $rule['type'] . ' ' . $class . ';' . PHP_EOL);
            return $rule['type'];
        case 'hash':
            fputs($fp, PHP_EOL .
                indent($indent) . '[System.Serializable]' . PHP_EOL .
                indent($indent) . 'public class cls_' . $class . ' {' . PHP_EOL
            );
            foreach ($rule['key'] as $name => $r) {
                export($fp, $r, $name, $indent + 1, true);
            }
            fputs($fp, str_repeat('    ', $indent) . '}' . PHP_EOL);
            if ($addProp) {
                $prefix = ($indent === 0) ? '' : 'cls_';
                fputs($fp, indent($indent) . 'public cls_' . $prefix . $class . ' ' . $class . ';' . PHP_EOL);
            }
            return 'cls_' . $class;
        case 'list':
            $type = export($fp, $rule['rule'], $class, $indent, false);
            fputs($fp, indent($indent) . 'public ' . $type . '[] ' . $class . ';' . PHP_EOL);
            return $type;
        case 'enum':
        case 'func':
            fputs($fp, indent($indent) . 'public ' . $rule['return'] . ' ' . $class . ';' . PHP_EOL);
            return $rule['return'];
        default:
            fputs($fp, indent($indent) . '/*' . PHP_EOL);
            fputs($fp, indent($indent) . ' * Undefined type[' . $rule['type'] . '] ' . $class . PHP_EOL);
            fputs($fp, indent($indent) . ' */' . PHP_EOL);
            return '';
    }
}

export($fp, $validator->dump(), $class, 0, false);
