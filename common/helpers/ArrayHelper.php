<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\common\helpers;

/**
 * ArrayHelper provides additional array functionality that you can use in your
 * application.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * ------------------------------------------
     * 把返回的数据集转换成Tree
     * @param array $list 要转换的数据集
     * @param string $pk 主键
     * @param string $pid parent标记字段
     * @param string $child
     * @param int $root
     * @return array
     * ------------------------------------------
     */
    public static function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * ---------------------------------------------------
     * 将list_to_tree的树还原成列表
     * @param  array $tree 原来的树
     * @param  string $child 孩子节点的键
     * @param  string $order 排序显示的键，一般是主键 升序排列
     * @param  array $list 过渡用的中间数组，
     * @return array        返回排过序的列表数组
     * ---------------------------------------------------
     */
    public static function tree_to_list($tree, $child = '_child', $order = 'id', &$list = [])
    {
        if (is_array($tree)) {
            $refer = [];
            foreach ($tree as $key => $value) {
                $reffer = $value;
                if (isset($reffer[$child])) {
                    unset($reffer[$child]);
                    static::tree_to_list($value[$child], $child, $order, $list);
                }
                $list[] = $reffer;
            }
            $list = static::list_sort_by($list, $order, $sortby = 'asc');
        }
        return $list;
    }

    /**
     * --------------------------------------------------
     * 对查询结果集进行排序
     * @access public
     * @param array $list 查询结果
     * @param string $field 排序的字段名
     * @param string $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
     * @return array|boolean
     * --------------------------------------------------
     */
    public static function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data) {
                $refer[$i] = &$data[$field];
            }

            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }

            return $resultSet;
        }
        return false;
    }

    /**
     * ---------------------------------------
     * 递归方式将tree结构转化为 表单中select可使用的格式
     * @param  array $tree 树型结构的数组
     * @param  string $title 将格式化的字段
     * @param  int $level 当前循环的层次,从0开始
     * @return array
     * ---------------------------------------
     */
    public static function format_tree($tree, $title = 'title', $level = 0)
    {
        static $list;
        /* 按层级格式的字符串 */
        $tmp_str = str_repeat("　　", $level) . "└";
        $level == 0 && $tmp_str = '';

        foreach ($tree as $key => $value) {
            $value[$title] = $tmp_str . $value[$title];
            $arr = $value;
            if (isset($arr['_child'])) {
                unset($arr['_child']);
            }

            $list[] = $arr;
            if (array_key_exists('_child', $value)) {
                static::format_tree($value['_child'], $title, $level + 1);
            }
        }
        return $list;
    }

    /**
     * ---------------------------------------
     * 获取dropDownList的data数据，主要是二级栏目及以上数据，一级栏目可以用ArrayHelper::map()生成
     * 示例：ArrayHelper::listDataLevel(\backend\models\Menu::find()->asArray()->all(), 'id', 'title', 'id', 'pid')
     * @param $list array 由findAll或->all()生成的数据
     * @param $key string dropDownList的data数据的key
     * @param $value string dropDownList的data数据的value
     * @param string $pk 主键字段名
     * @param string $pid 父id字段名
     * @param int $root 根ID
     * @return array
     * ---------------------------------------
     */
    public static function listDataLevel($list, $key, $value, $pk = 'id', $pid = 'pid', $root = 0)
    {
        if (!is_array($list)) {
            return [];
        }
        $_tmp = $list;
        /* 判断$list是否由findAll生成的数据 */
        if (array_shift($_tmp) instanceof \yii\base\Model) {
            $list = array_map(function ($record) {
                return $record->attributes;
            }, $list);
        }
        unset($_tmp);
        $tree = static::list_to_tree($list, $pk, $pid, '_child', $root);
        return static::map(static::format_tree($tree, $value), $key, $value);
    }

    /**
     * ---------------------------------------
     * 生成jQuery tree所需的数据
     * @param $list array 由self::list_to_tree生成的数据
     * @return array
     * ---------------------------------------
     */
    public static function jstree($list)
    {
        $node = [];
        if ($list) {
            foreach ($list as $value) {
                $_tmp = [];
                $_tmp['id'] = $value['id'];
                $_tmp['text'] = $value['title'];
                if (isset($value['_child'])) {
                    $_tmp['icon'] = 'fa fa-folder icon-state-warning';
                    $_tmp['state']['opened'] = true;
                    $_tmp['children'] = self::jstree($value['_child']);
                } else {
                    $_tmp['icon'] = 'fa fa-file icon-state-warning';
                }
                $node[] = $_tmp;
            }
        }
        return $node;
    }

    /**
     * 数组的key和value互换
     * @param $array
     * @return array
     */
    public static function exchange($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[$value] = $key;
        }
        return $result;
    }

    /**
     * 返回数组第一个元素
     * @param  array $arr
     * @return arrray | null
     */
    public static function first($arr)
    {
        if (!is_array($arr)) {
            return null;
        }

        foreach ($arr as $k => $v) {
            return $v;
        }

        return null;
    }

    /**
     * 返回数组最后一个元素
     * @param  array $arr
     * @return arrray | null
     */

    public static function last($arr)
    {
        if (!is_array($arr)) {
            return null;
        }

        return end($arr);
    }

    /**
     * 排除指定key
     */
    public static function except($array, array $keys)
    {

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * 仅返回期望key
     */
    public static function expect($array, array $keys)
    {
        $new = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $new[$key] = $array[$key];
            }
        }

        return $new;
    }

    /**
     * 将unix时间戳数组转为可读时间数组
     */
    public static function readableTime($arr, $format, $reverse = false)
    {
        $r = [];
        /**
         * 智能转换
         */
        if ('auto' == $format) {
            //@todo
        } else {
            $r = array_map(function ($t) use ($format) {
                return date($format, $t);
            }, $arr);
        }

        return $reverse ? array_reverse($r) : $r;
    }

    public static function flatModelErrors($errors, $separator = PHP_EOL)
    {
        $newArr = [];
        /**
         * 将二维数组并为一维
         * @var [type]
         */
        foreach ($errors as $k => $v) {
            foreach ($v as $msg) {
                $newArr[] = $msg;
            }
        }

        return implode($separator, $newArr);
    }

    /**
     * 将数组转换成字符串
     * @param string|array $arr
     * @param bool $haskey
     * @return string
     */
    public static function arrayToString($arr, $haskey = true)
    {
        if (is_array($arr)) {
            $string = '';
            foreach ($arr as $key => $data) {
                if ($haskey) {
                    $string .= $key . '：' . self::arrayToString($data, $haskey);
                } else {
                    $string .= self::arrayToString($data, $haskey);
                }

            }
            return $string;
        }
        return $arr;
    }

    /**
     * Sort array by multiple fields.
     *
     * @static
     *
     * @param array $array array to sort passed by reference
     * @param array $fields fields to sort, can be either string with field name or array with 'field' and 'order' keys
     */
    public static function sort(array &$array, array $fields)
    {
        foreach ($fields as $fid => $field) {
            if (!is_array($field)) {
                $fields[$fid] = ['field' => $field, 'order' => SORT_ASC];
            }
        }

        @uasort($array, function ($a, $b) use ($fields) {
            foreach ($fields as $field) {
                // if field is not set or is null, treat it as smallest string
                // strnatcasecmp() has unexpected behaviour with null values
                if (!isset($a[$field['field']]) && !isset($b[$field['field']])) {
                    $cmp = 0;
                } elseif (!isset($a[$field['field']])) {
                    $cmp = -1;
                } elseif (!isset($b[$field['field']])) {
                    $cmp = 1;
                } else {
                    $cmp = strnatcasecmp($a[$field['field']], $b[$field['field']]);
                }

                if ($cmp != 0) {
                    return $cmp * ($field['order'] == SORT_ASC ? 1 : -1);
                }
            }
            return 0;
        });
    }

    /**
     * PHP stdClass Object转array
     * @param $array
     * @return array
     */
    public static function object_array($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::object_array($value);
            }
        }
        return $array;
    }

    /**
     * [
     *      [
     *          'name' => 'ip',
     *          'value' => '192.168.1.2'
     *      ],
     *      ````````````
     * ]
     *
     * 转换成：
     * [
     *      'ip' => '192.168.1.2'
     * ]
     * @param $arr array 将form表单格式化成数组
     * @return array
     */
    public static function serializeArray($arr)
    {
        $data = [];
        foreach ($arr as $item) {
            $data[$item['name']] = $item['value'];
        }
        return $data;
    }

    /**
     * 获取字符在某个字符出现的所有位置
     * @param $str
     * @param $char
     * @return array
     */
    public static function getCharpos($str, $char)
    {
        $j = 0;
        $arr = array();
        $count = substr_count($str, $char);
        for ($i = 0; $i < $count; $i++) {
            $j = strpos($str, $char, $j);
            $arr[] = $j;
            $j = $j + 1;
        }
        return $arr;
    }

    /*
     * 查询出数组中指定值的数据
     * @params array 数组，（一维或者二维）
     * @params string 键值，需要模糊查询的键值
     * @params string $keyword 需要查询的条件值
     * @params bool $like 是否开始模糊查询
     * @return array
     * */
    public static function selectArray($arr, $field = '', $keyword, $like = true)
    {
        $data = [];
        $is = false; // 判断是一维还是二维， 默认一维数组
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $is = true;
            }
            break;
        }
        if (!$is) { // 一维
            foreach ($arr as $k => $v) {
                if (empty($field)) {
                    if ($like && (strstr($v, $keyword) !== false)) { // 模糊查询
                        array_push($data, $keyword);
                    }
                    if (!$like && ($v == $keyword)) { // 非模糊查询
                        array_push($data, $keyword);
                    }
                } else {
                    if ($like && (strstr($arr[$field], $keyword) !== false)) { // 模糊查询
                        array_push($data, $keyword);
                    }
                    if (!$like && ($arr[$field] == $keyword)) { // 非模糊查询
                        array_push($data, $keyword);
                    }
                }
            }
        }

        if ($is) { // 二维
            foreach ($arr as $k => $v) {
                if (empty($field)) {
                    foreach ($v as $k1 => $v1) {
                        if ($like && (strstr($v1, $keyword) !== false)) { // 模糊查询
                            $data[] = $v;
                        }
                        if (!$like && ($v1 == $keyword)) { // 非模糊查询
                            $data[] = $v;
                        }
                    }
                } else {
                    if ($like && (strstr($v[$field], $keyword) !== false)) { // 模糊查询
                        $data[] = $v;
                    }
                    if (!$like && ($v[$field] == $keyword)) { // 非模糊查询
                        $data[] = $v;
                    }
                }

            }
        }

        return $data;
    }
}
