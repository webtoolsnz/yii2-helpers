<?php
namespace webtoolsnz\helpers;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class DataProviderConverter
 * @package webtoolsnz\helpers
 */
class DataProviderConverter
{

    /**
     * Convert a DataProvider instance and given columns into an array,
     * ideal for a CSV export, can reuse GridView columns array
     * @param ActiveDataProvider $dataProvider
     * @param array $columns
     * @return array
     */
    public static function convertProviderToArray(ActiveDataProvider $dataProvider, $columns)
    {
        $header = [];
        $data = [];
        foreach($dataProvider->getModels() as $model) {
            /* @var ActiveRecord $model */
            $row = [];
            foreach($columns as $column) {
                if (is_string($column)) {
                    // its an attribute
                    if ($header !== false) {
                        $header[] = $model->getAttributeLabel($column);
                    }
                    $row[] = ArrayHelper::getValue($model, $column);
                } else if(is_array($column)) {
                    if ($header !== false) {
                        $header[] = isset($column['label']) ? $column['label'] : $model->getAttributeLabel($column['attribute']);
                    }
                    $row[] = isset($column['value'])
                        ? (
                        is_callable($column['value'])
                            ? call_user_func($column['value'], $model)
                            : ArrayHelper::getValue($model, $column['value'])
                        )
                        : ArrayHelper::getValue($model, $column['attribute']);
                }
            }

            if (is_array($header)) {
                $data[] = $header;
                $header = false;
            }
            $data[] = $row;
        }
        return $data;
    }
}
