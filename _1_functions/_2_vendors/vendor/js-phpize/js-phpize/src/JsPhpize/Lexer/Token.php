<?php

namespace JsPhpize\Lexer;

class Token
{
    /**
     * @var array
     */
    protected $data;

    public function __construct($type, array $data)
    {
        $this->data = array_merge(array(
            'type' => $type,
        ), $data);
    }

    public function is($value)
    {
        return in_array($value, array($this->type, $this->value));
    }

    public function isIn($values)
    {
        $values = is_array($values) ? $values : func_get_args();

        return in_array($this->type, $values) || in_array($this->value, $values);
    }

    public function isValue()
    {
        return in_array($this->type, array('variable', 'constant', 'string', 'number'));
    }

    protected function isComparison()
    {
        return in_array($this->type, array('===', '!==', '>=', '<=', '<>', '!=', '==', '>', '<'));
    }

    protected function isLogical()
    {
        return in_array($this->type, array('&&', '||', '!'));
    }

    protected function isBinary()
    {
        return in_array($this->type, array('&', '|', '^', '~', '>>', '<<', '>>>'));
    }

    protected function isArithmetic()
    {
        return in_array($this->type, array('+', '-', '/', '*', '%', '**', '--', '++'));
    }

    protected function isVarOperator()
    {
        return in_array($this->type, array('delete', 'void', 'typeof'));
    }

    public function isAssignation()
    {
        return substr($this->type, -1) === '=' && !$this->isComparison();
    }

    public function isOperator()
    {
        return $this->isAssignation() || $this->isComparison() || $this->isArithmetic() || $this->isBinary() || $this->isLogical() || $this->isVarOperator();
    }

    public function isNeutral()
    {
        return $this->isIn('comment', 'newline');
    }

    public function expectNoLeftMember()
    {
        return in_array($this->type, array('!', '~')) || $this->isVarOperator();
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
