<?php
namespace app\common\lwtable;

use Yii;
use yii\base\InvalidConfigException;
use yii\i18n\Formatter;

class BaseTable
{
    public $dataColumnClass;

    public $columns;

    public $initColumns;

    public $emptyCell;

    protected $dataProvider;

    public $formatter;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
    }

    /**
     * 初始化列
     */
    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }

        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ?: Column::className(),
                    'table' => $this,
                ], $column));
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * 设置列
     * @param array $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
        $this->initColumns = $columns;
        $this->initColumns();
        return $this;
    }

    public function setProvider($provider)
    {
        $this->dataProvider = $provider;
        return $this;
    }

    public function renderColumns()
    {
        if (is_array($this->dataProvider)) {
            $models = array_values($this->dataProvider);
            $keys   = array_keys($this->dataProvider);
        } else {
            $models = array_values($this->dataProvider->getModels());
            $keys   = $this->dataProvider->getKeys();
        }
        $rows   = [];

        foreach ($models as $index => $model) {
            $key    = $keys[$index];
            $rows[] = $this->renderTableRow($model, $key, $index);
        }

        return $rows;
    }

    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column Column */
        foreach ($this->columns as $column) {
//            if (empty($column->field)) {
//                continue;
//            }
            $cells[$column->field] = $column->renderDataCell($model, $key, $index);

            /* 特殊列 */
            if ($column->enableTitle) {
                $field = $column->field;
                // todo 目前先兼容ArrayDataProvider 后期再优化
                if (is_array($model)) {
                    $cells['_' . $column->field . '_title'] = $model[$field];
                } else {
                    $cells['_' . $column->field . '_title'] = $model->$field;
                }
            }

            /* 如果设置类名 */
            if ($column->setClass) {
                $cells['_' . $column->field . '_class'] = $column->setClass;
            }

            /* 是否换行 */
            if ($column->wrap) {
                if ($column->setClass) {
                    $cells['_' . $column->field . '_class'] = $column->setClass . ' lw_table_td_wrap';
                } else {
                    $cells['_' . $column->field . '_class'] = 'lw_table_td_wrap';
                }
            }
        }
        return $cells;
    }

    /**
     * 通过字符串，格式:"attribute:format:label" 创建一个DataColumn对象.
     * @param string $text
     * @return DataColumn
     * @throws InvalidConfigException
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        //print_r($matches);die;

        return Yii::createObject([
            'class'  => $this->dataColumnClass ?: Column::className(),
            'table'  => $this,
            'field'  => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'title'  => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * 未配置columns时，尝试猜测列
     */
    protected function guessColumns()
    {
        $models = $this->dataProvider->getModels();
        $model  = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                if ($value === null || is_scalar($value) || is_callable([$value, '__toString'])) {
                    $this->columns[] = (string) $name;
                }
            }
        }
    }
}
