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
     * @param $targetRule
     * @param array $fields
     * @param \Closure|null $filter
     * @return array $rules
     */
    public static function deleteRule($rules, $targetRule, $fields = [], \Closure $filter = null)
    {
        $fields = is_array($fields) ? $fields : [$fields];

        foreach($rules as $rk => &$rule) {
            $rule_name = $rule[1];
            if ($targetRule == $rule_name && (null === $filter || true === $filter($rule))) {
                $rule_fields  = !is_array($rule[0]) ? [$rule[0]] : $rule[0];
                if (!empty($fields)) {
                    foreach ($rule_fields as $fk => $field) {
                        if (in_array($field, $fields)) {
                            unset($rule_fields[$fk]);
                        }
                    }
                    $rule[0] = array_values($rule_fields);
                }

                if (empty($fields) || empty($rule_fields)) {
                    unset($rules[$rk]);
                }
            }
        }

        return array_values($rules);
    }
}
