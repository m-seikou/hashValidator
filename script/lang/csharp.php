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

function export($fp, array $rule, string $class, int $indent, bool $addProp)
{
    switch ($rule['type']) {
        case 'string':
        case 'float':
        case 'int':
        case 'bool':
            fputs($fp, str_repeat('    ', $indent) . 'public ' . $rule['type'] . ' ' . $class . ';' . PHP_EOL);
            return $rule['type'];
        case 'hash':
            fputs($fp, str_repeat('    ', $indent) . '[System.Serializable]' . PHP_EOL);
            fputs($fp, str_repeat('    ', $indent) . 'public class cls_' . $class . ' {' . PHP_EOL);
            foreach ($rule['key'] as $name => $r) {
                export($fp, $r, $name, $indent + 1, true);
            }
            fputs($fp, str_repeat('    ', $indent) . '}' . PHP_EOL);
            if ($addProp) {
                fputs($fp, str_repeat('    ', $indent) . 'public cls_' . $class . ' ' . $class . ';' . PHP_EOL);
            }
            return 'cls_' . $class;
            break;
        case 'list':
            $type = export($fp, $rule['rule'], $class, $indent, false);
            fputs($fp, str_repeat('    ', $indent) . 'public ' . $type . '[] ' . $class . ';' . PHP_EOL);
            continue;
        case 'enum':
        case 'func':
            fputs($fp, str_repeat('    ', $indent) . 'public ' . $rule['return'] . ' ' . $class . ';' . PHP_EOL);
            continue;
        default:
            fputs($fp, str_repeat('    ', $indent) . '/*' . PHP_EOL);
            fputs($fp, str_repeat('    ', $indent) . ' * Undefined type[' . $rule['type'] . '] ' . $class . PHP_EOL);
            fputs($fp, str_repeat('    ', $indent) . ' */' . PHP_EOL);
            break;
    }
}

export($fp, $validator->dump(), $class, 0, false);
