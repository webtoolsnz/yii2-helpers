<?php

namespace webtoolsnz\helpers\tests;

use PHPUnit\Framework\TestCase;
use webtoolsnz\helpers\ValidationHelper;

class ValidationRuleHelperTest extends TestCase
{
    public function testRemoveFieldFromRequired()
    {
        $rules = [
            [['foo', 'bar'], 'string'],
            [['foo', 'bar', 'baz'], 'required'],
            [['baz'], 'integer'],
        ];

        $withoutBar = ValidationHelper::deleteRule($rules, 'required', ['bar']);

        $this->assertEquals([
            [['foo', 'bar'], 'string'],
            [['foo', 'baz'], 'required'],
            [['baz'], 'integer'],
        ], $withoutBar);
    }

    public function testRemoveAllFieldsFromRule()
    {
        $rules = [
            ['baz', 'required'],
            [['foo', 'bar'], 'some_rule'],
            [['some_date'], 'date'],
        ];

        $result = ValidationHelper::deleteRule($rules, 'some_rule', ['bar', 'foo']);

        $this->assertEquals([
            ['baz', 'required'],
            [['some_date'], 'date'],
        ], $result);
    }

    public function testRemoveFieldUsingFilter()
    {
        $rules = [
            [['foo', 'bar'], 'required'],
            [['client_id', 'slug'], 'unique', 'targetAttribute' => ['client_id', 'slug']],
            [['client_id', 'description'], 'unique', 'targetAttribute' => ['client_id', 'description']],
        ];

        $result = ValidationHelper::deleteRule($rules, 'unique', 'client_id', function ($rule) {
            return $rule['targetAttribute'] === ['client_id', 'slug'];
        });

        $this->assertEquals([
            [['foo', 'bar'], 'required'],
            [['slug'], 'unique', 'targetAttribute' => ['client_id', 'slug']],
            [['client_id', 'description'], 'unique', 'targetAttribute' => ['client_id', 'description']],
        ], $result);
    }

    public function testRemoveAllInstancesOfRule()
    {
        $rules = [
            [['foo', 'bar'], 'required'],
            [['zzz'], 'date'],
            ['abx', 'date', 'foo' => 'bar'],
        ];

        $result = ValidationHelper::deleteRule($rules, 'date');

        $this->assertEquals([[['foo', 'bar'], 'required']], $result);
    }

}