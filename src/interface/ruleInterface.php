<?php

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/rule/ruleException.php';

interface ruleInterface
{

    public function __construct($rule);

    /**
     * @param $value
     * @return mixed �o���f�[�V�������������l
     * @throws ruleException �o���f�[�V������ʉ߂ł��Ȃ��ꍇ�ɔ��������O
     */
    public function check($value);

    /**
     * hash �̕K�{�ݒ���Q�Ƃ��邽�߂̃C���^�[�t�F�[�X
     * @return bool
     */
    public function isOptional();

    public function dump();
}