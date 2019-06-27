<?php

namespace app\models\searchs;

use yii\base\Model;
use yii\base\Theme;
use yii\console\ExitCode;
use yii\data\ActiveDataProvider;
use app\models\Students;
use yii\di\ServiceLocator;

/**
 * StudentsSearch represents the model behind the search form of `app\models\Students`.
 */
class StudentsSearch extends Students
{
	const SCENARIO_SEARCH = 'search';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','sex'], 'integer'],
            [['name', 'hobby'], 'safe'],
//	        [[ 'sex'],'safe','message' => '性别：男\女']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
//        return Model::scenarios();
	   $scenarios = parent::scenarios();
	   $scenarios[self::SCENARIO_SEARCH] = ['name','hobby','sex'];
	   return $scenarios;
    }

    public function validate($attributeNames = 'sex', $clearErrors = true)
    {
	    // 验证性别合法性
	    if ($this->scenario == self::SCENARIO_SEARCH){
//		    $arr = self::getSex();
//		    $arr = array_flip($arr);
//		    if(key_exists($this->sex,$arr)) return null;
		    return  false;
	    }
	    return parent::validate($attributeNames,$clearErrors);
    }

	/**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
    	//$this->scenario = self::SCENARIO_SEARCH;
        $query = Students::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if($this->sex){
        	$arr = self::getSex();
        	$arr = array_flip($arr);
        	if(key_exists($this->sex,$arr))
	        $query->andWhere([
	        	'sex' => $arr[$this->sex],
	        ]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'sex' => $this->sex,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'hobby', $this->hobby]);

        return $dataProvider;
    }
}
