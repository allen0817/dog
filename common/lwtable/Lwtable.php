<?php

namespace app\common\lwtable;

use app\common\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

class Lwtable extends BaseTable implements LwtableInterface
{
    # 表格的默认配置
    public $lwTableOptions = [
        'tableid' => 'lwtable',
    ];

    # bootstrap-table的默认配置，该配置可以到bootstrap-table官网查看 http://bootstrap-table.wenzhixin.net.cn/zh-cn/documentation/
    public $bootstrapTable = [
        'lwtableid' => 'lwtable',
        'idField' => 'id',
        'uniqueId' => 'id',
        'method' => 'get', // 请求方式
        'url' => '', // 请求数据的url
        'striped' => false, // 表格显示条纹
        'pagination' => true, //启动分页
        'pageSize' => 20, //每页显示的记录数
        'pageNumber' => 1, //当前第几页
        'pageList' => ['All'], //记录数可选列表
        'search' => false, //是否启用查询
        'searchOnEnterKey' => false, //设置为 true时，按回车触发搜索方法，否则自动触发搜索方法。
        'strictSearch' => false, //设置为 true启用全匹配搜索，否则为模糊搜索
        'searchText' => '', //初始化搜索文字。
        'searchTimeOut' => 500,
        'trimOnSearch' => true, // 	设置为 true 将自动去掉搜索字符的前后空格。
        //'customSearch' => '',
//        'resizable' => false, // 开启头部拉伸
//        'liveDrag' => false, // 去看文档 http://www.bacubacu.com/colresizable/#samples
//        'minWidth' => 65, // 最小的宽度
//        'resizeMode' => 'fit', // fit flex overflow
//        'headerOnly' => false, // 去看文档 http://www.bacubacu.com/colresizable/#samples
//        'fixed' => false, // 去看文档 http://www.bacubacu.com/colresizable/#samples
        'resizableColumns' => true, // 使用resizableColumns的插件，不能和resizable同时使用
        'iconsPrefix' => 'glyphicon',
        'icons' => [
            'columns' => 'glyphicon-th',
            'export' => 'glyphicon-export',
            'refresh' => 'glyphicon-refresh',
            'paginationSwitchDown' => 'glyphicon-collapse-down',
            'paginationSwitchUp' => 'glyphicon-collapse-up',
            'toggleOff' => 'glyphicon-list-alt',
            'toggleOn' => 'glyphicon-list-alt',
            'detailOpen' => 'glyphicon-plus',
            'detailClose' => 'glyphicon-minus',
            'fullscreen' => 'glyphicon-fullscreen',
        ],
        'buttonsAlign' => 'right',
        'toolbarAlign' => 'right',
        'showColumns' => true, //显示下拉框勾选要显示的列
        'showRefresh' => true, //显示刷新按钮
        'showFullscreen' => false, // 全屏
        'clickToSelect' => false, // 点击行则自动选择
        'showExport' => false,
        'maintainSelected' => true,
        'maintainServerSelected' => true,
        'exportDataType' => 'basic',
        'exportTypes' => ['excel'],
        'exportOptions' => ['mso' => ['fileFormat' => 'xlshtml']],
        'buttonsClass' => 'primary',
        'undefinedText' => '',
        'sortable' => true,  //是否启用排序
        'sortOrder' => 'desc',  //排序方式
        'silentSort' => false,
        'silent' => true, // 刷新远程服务器数据，可以设置{silent: true}以静默方式刷新数据，并设置{url => newUrl}更改URL。 要提供特定于此请求的查询参数，请设置{query => {foo => 'bar'}}。
        'sidePagination' => 'server', //表示服务端请求 client 客户端
        //设置为undefined可以获取pageNumber，pageSize，searchText，sortName，sortOrder
        //设置为limit可以获取limit, offset, search, sort, order
        'queryParamsType' => 'undefined',
        'queryParams' => '',
        'lwtop' => true, // 是否开启点击分页跳转到头部
        'columns' => [],
    ];

    # 自定义按钮配置
    public $toolbar = [
        [
            'data-href' => '',
            'icon' => 'glyphicon-fullscreen',
            'class' => 'btn-primary',
            'text' => '全屏',
            'data-lw-action' => 'btn_fullscreen',
        ],
    ];

    # 操作列的按钮默认配置
    public $buttons = [
        'isopend' => false, // 默认关闭
        'idfield' => 'id', // 必须要的字段
        'template' => [
            'update' => [
                'key' => 'update', // 键
                'href' => 'update', // 跳转的链接
                'isfield' => 'id', // 字段是否存在值，如果存在则显示，不存在则不显示图标
                'type' => '1', // 1为跳转类型，2为触发事件类型
            ],
            'delete' => [
                'key' => 'delete', // 键
                'href' => 'delete', // 跳转的链接
                'isfield' => 'id', // 字段是否存在值，如果存在则显示，不存在则不显示图标
                'type' => '2', // 1为跳转类型，2为触发事件类型
            ],
        ],
        'icons' => [ // 对应每个模版的的图标
            'update' => 'glyphicon-edit',
            'delete' => 'glyphicon-trash',
        ],
        'titles' => [ // 对应每个图标的title提示
            'update' => '修改',
            'delete' => '删除',
        ],
    ];

    # 默认启选择框
    public $isShowCheckBox = true;

    # 默认不格式化选择框
    public $formatCheckBox = [false, 'formatter_check'];

    # 默认开启自定义工具
    public $isShowToolbar = true;

    /**
     * see http://bootstrap-table.wenzhixin.net.cn/zh-cn/documentation/#%E5%88%97%E5%8F%82%E6%95%B0
     */
    protected $columnsFilters = [
        'radio', 'checkbox', 'field', 'title', 'titleTooltip', 'class', 'rowspan', 'colspan',
        'align', 'halign', 'falign', 'valign', 'visible', 'width', 'formatter', 'sortable', 'escape',
        'editable', 'switchable', 'supportExport', 'order',
    ];

    protected $show = [
        'lwTableOptions',
    ];

    protected $fileds;

    /**
     * 避免使用 =,+,-,@4种字符 否则导出会存在问题
     * see https://www.owasp.org/index.php/CSV_Injection
     * @var string
     */
    public $emptyCellValue = '';

    public function __construct()
    {
        parent::__construct();
    }

    # 设置表格的配置文件
    public function setLwTableOptions($data)
    {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                $this->lwTableOptions[$k] = $v;
                // 这里做下兼容，如果设置了表格id，默认绑定到bootstrap表格配置中
                if ($k == 'tableid') {
                    $this->bootstrapTable['lwtableid'] = $v;
                }
            }
        }
        return $this;
    }

    /*
     * # bootstrap-table配置
     * @params array $data bootstrap-table的配置
     * */
    public function setBootstrapTable($data)
    {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                if ($k == 'idField') {
                    $this->bootstrapTable['uniqueId'] = $v;
                }
                $this->bootstrapTable[$k] = $v;
            }
        }
        return $this;
    }

    /*
     * # 自定义按钮设置
     * @params array $data 自定义按钮配置
     * */
    public function setToolbar($data)
    {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                if (!array_key_exists('visible', $v) || false !== $v['visible']) {
                    $this->toolbar[] = $v;
                }

            }
        }
        return $this;
    }

    /*
     * # 操作列的按钮配置
     * @params array $data 操作按钮配置
     * */
    public function setButtons($data)
    {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $k => $v) {
                $this->buttons[$k] = $v;
            }
        }
        return $this;
    }

    /*
     * # 设置是否开启选择框
     * */
    public function setIsShowCheckBox($bool)
    {
        $this->isShowCheckBox = $bool;
        return $this;
    }

    /*
     * # 设置是否开启选择框
     * */
    public function setFormatCheckBox($bool, $name = 'formatter_check')
    {
        $this->formatCheckBox[0] = $bool;
        $this->formatCheckBox[1] = $name;
        return $this;
    }

    /*
     * # 设置是否开启自定义工具栏
     * */
    public function setIsShowToolbar($bool)
    {
        $this->isShowToolbar = $bool;
        return $this;
    }

    public function setFieldsOptions($attrs, $option, $value)
    {
        if (!empty($attrs)) {
            $fields = $this->getFields();

            foreach ($fields as &$field) {
                if (in_array($field['field'], $attrs)) {
                    $field[$option] = $value;
                }
            }
        }
        $this->setFields($fields);
        return $this;
    }

    /**
     * 获取bootstrapTable的columns配置
     * @return Array
     */
    public function getFields()
    {
        if (null !== $this->fields) {
            return $this->fields;
        }

        $fields = [];

        if ($this->isShowCheckBox) {
            if ($this->formatCheckBox[0]) {
                $fields[] = [
                    'field' => 'checkStatus', 'align' => 'center', 'valign' => 'middle', 'checkbox' => true, 'formatter' => $this->formatCheckBox[1]
                ];
            } else {
                $fields[] = [
                    'field' => 'checkStatus', 'align' => 'center', 'valign' => 'middle', 'checkbox' => true
                ];
            }
        }

        foreach ($this->columns as $column) {
            $buf = ArrayHelper::expect(get_object_vars($column), $this->columnsFilters);
            $buf = array_filter($buf, function ($v) {
                return null !== $v;
            });
            $fields[] = $buf;
        }

        return $this->fields = array_filter($fields);
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    # 获取表格的配置数据
    public function render()
    {

        $this->bootstrapTable['columns'] = $this->getFields();
        $this->toolbar = $this->isShowToolbar ? $this->toolbar : [];
        $arr = [
            'toolbar' => $this->toolbar,
            'buttons' => $this->buttons,
            'bootstrapTable' => $this->bootstrapTable,
            'emptyCellValue' => $this->emptyCellValue,
        ];
        $this->setlwTableOptions($arr);

        return ArrayHelper::filter(get_object_vars($this), $this->show);
    }

    /*
     * # 为了兼容数据，对getModel的数据进行处理，并且能对column里面的字段值进行处理
     * @params object $data
     * @params array $column
     * @return array
     * */
    public function getModels()
    {
        if (null === $this->columns) {
            throw new InvalidConfigException('未设置columns参数');
        }

        return $this->renderColumns();
    }

    /*
     * # 获取原始的models的所有键值
     *
     * */
    public function getModelsColumns()
    {
        $all_columns = [];
        if (!empty($this->dataProvider->getModels())) {
            foreach ($this->dataProvider->getModels() as $k => $v) {
                $all_columns = array_keys($v);
                if (!empty($all_columns)) {
                    break;
                }
            }
        }
        $tmp_columns = [];
        if (!empty($this->initColumns)) {
            foreach ($this->initColumns as $k => $v) {
                array_push($tmp_columns, $v['field']);
            }
        }

        if (!empty($all_columns)) {
            foreach ($all_columns as $k => $v) {
                if (in_array($v, $tmp_columns)) {
                    unset($all_columns[$k]);
                }
            }
        }

        if (!empty($all_columns)) {
            foreach ($all_columns as $k => $v) {
                $arr = [
                    'field'   => $v,
                    'title'   => $v,
                    'valign'  => 'middle',
                    'align'   => 'center',
                    'visible' => false,
                ];
                array_push($this->initColumns, $arr);
            }
        }
        $this->setColumns($this->initColumns);
        return $this;
    }

    /*
     * # 获取对象的公有属性值
     * @params object $instance
     * @return array
     * */
    protected function get_object_vars($instance)
    {
        $varArray = get_object_vars($instance);
        return array_keys($varArray);
    }

    public function __get($name)
    {
        if (isset($this->name)) {
            return $this->name;
        }
    }
}
