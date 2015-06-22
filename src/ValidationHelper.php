<?php
namespace webtoolsnz\helpers;

/**
 * Class ValidationHelper
 * @package webtoolsnz\helpers
 */
class ValidationHelper
{
    /**
     * Deletes either an entire rule, or just some fields from a specified rule.
     *
     * @example Helpers::deleteRule($rules, 'required'); // Deletes all required rules.
     * @example Helpers::deleteRule($rules, 'required', array('foo', 'bar')); // 'foo' and 'bar' are removed from the required rule.
     *
     * @param Array $rules - Rules to modify
     * @param String $target_rule - The rule to target
     * @param (optional) Mixed $fields - fields to remove from target rule.
     *
     * @return array $rules
     */
    public static function deleteRule($rules, $target_rule, $fields = array())
    {
        $fields = is_array($fields) ? $fields : array($fields);
        foreach($rules as $rk => &$rule) {
            $rule_name = $rule[1];
            if ($target_rule == $rule_name) {
                $rule_fields  = !is_array($rule[0]) ? [$rule[0]] : $rule[0];
                if (!empty($fields)) {
                    foreach($rule_fields as $fk => $field) {
                        if (in_array($field, $fields)) {
                            unset($rule_fields[$fk]);
                        }
                    }
                    $rule[0] = $rule_fields;
                } else {
                    unset($rules[$rk]);
                }
            }
        }

        return $rules;
    }
}