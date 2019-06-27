<?php 
namespace app\models;


use yii\base\BaseObject;
use yii\data\ArrayDataProvider;
/**
* 
*/
class Config extends BaseObject
{

	private $_path;

    /**
     * Config constructor.
     * @param array $config
     */
	public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->setPath();
    }

    /**
     * @param string $value
     */
    public function setPath($value = 'params.php'){
        $this->_path = \Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $value;
    }
	public  function getPath(){
	    return $this->_path;
    }

    public function find($key=false){
        $data = require($this->_path);
        if( $key && key_exists($key,$data)){
            $data =  [ $key => $data[$key] ];
        }
        return $data;
    }

    /**
     * @param bool $key
     * @return array|mixed
     */
    public function getData($key=false){
	    $data = $this->find($key);
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            //'key' => ['key','value'],
            'pagination' => [
                'pageSize' => 2,
            ],
            'sort' => [
//                'attributes' => ['id', 'key'],
            ],
        ]);
	    return $provider;
    }

    public function  save(array  $data){
        if(is_array($data) && isset($data['key'])  ){
            $all = $this->find();
            $key =  $data['key'];
            $all[$key] = $data['value'];
            $this->saveFile($all);
            return true;
        }
        return false;
    }

    public function delete($key){
        $all = $this->find();
        unset($all[$key]);
        $this->saveFile($all);
    }

    /**保存到文件
     * @param array $arr
     */
    private  function saveFile(array $arr){
        $str='<?php '.PHP_EOL.' return ' . var_export($arr,true) . ';';
        file_put_contents($this->_path, $str);
    }

}