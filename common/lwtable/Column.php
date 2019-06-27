<?php
namespace app\common\lwtable;


use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class Column extends BaseObject
{
    public $table;

    public $field;

    public $format = 'text';

    /**
     * 列标题
     * @var string
     */
    public $title;

    public $value;

    /**
     *  水平对齐方式 
     *  支持 'left', 'right', 'center'
     * @var string
     */
    public $align;

    /**
     * 垂直对齐方式
     * 支持 'top', 'middle', 'bottom'
     * @var string
     */
    public $valign;

    /**
     * 表头的水平对齐方式
     * 支持 'left', 'right', 'center'  
     * @var string
     */
    public $halign;

    /**
     * 是否显示该列
     * @var boolean
     */
    public $visible = true;

    public $formatter;

    /**
     * 列宽，若未定义，则根据单元格内容自动调整。
     * 在响应式表格的情况下，该宽度可能由于实际可用宽度小于给定宽度，而被忽略。
     *  支持百分比或px
     * @var string
     */
    public $width;

    public $titleTooltip;

    public $sortable; // true为可以排序，false为不能

    /**
     * 是否显示title(html属性)
     * @var boolean
     */
    public $enableTitle = false;

    public $escape = false; // html转义字符处理

    public $setClass; // td的类名

    public $wrap = false; // 是否换行,默认不换行

    public $editable; // 行内编辑

    public $switchable = true; // 显示列中是否显示，默认显示

    public $supportExport = 'default'; // 该列是否支持导出，默认不处理

    public $order = 'desc'; // 默认列的排序

    public function renderDataCell($model, $key, $index)
    {
        return $this->renderDataCellContent($model, $key, $index);
    }

    /**
     * 计算单元格的内容
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $cellValue = $this->getDataCellValue($model, $key, $index);
        if (null === $cellValue || '' === $cellValue) {
            return $this->table->emptyCellValue;
        }
        return $this->table->formatter->format($cellValue, $this->format);
    }

    /**
     * Returns the data cell value.
     * @param mixed $model the data model
     * @param mixed $key the key associated with the data model
     * @param int $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the data cell value
     */
    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            } else {
                return call_user_func($this->value, $model, $key, $index, $this);
            }
        } elseif ($this->field !== null) {
            return ArrayHelper::getValue($model, $this->field);
        }

        return null;
    }

    /**
     * 兼容grid部分写法
     */
    protected function compatible($name, $value)
    {
        if ('attribute' == $name) {
            $this->field = $value;
            return true;
        } else if ('label' == $name) {
            $this->title = $value;
            return true;
        }

        return false;
    }

    public function __set($name, $value)
    {
        if ($this->compatible($name, $value)) {
            return;
        }

        parent::__set($name, $value);
    }
}
