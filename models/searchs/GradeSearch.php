<?php

namespace app\models\searchs;

use app\models\Students;
use app\models\Subject;
use app\models\Year;
use http\QueryString;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Grade;
use yii\data\Sort;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * GradeSearch represents the model behind the search form of `app\models\Grade`.
 */
class GradeSearch extends Grade
{
	public $year;
	public $student;
	public $china;
	public $math;
	public $english;
	public $history;
	public $total;
	public $avg;
//	public

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'score', 'student_id', 'subject_id', 'year_id'], 'integer'],
	        [['year','student','china','math','english','history','total','avg'],'string'],
	        [['year','student','china','math','english','history','total','avg'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
//        $query = Grade::find();
//        $query->alias('g');
//	    $query->joinWith([
//          'subject' => function (\yii\db\ActiveQuery $query) {
//	            $query->select('name');
//	    }])->all();

	    $query = new Query();
	    $query->from([
			'g' => Grade::tableName()
	    ]);
        $query->leftJoin(['stu'=>Students::tableName()],'stu.id=g.student_id');
        $query->leftJoin(['sub' => Subject::tableName()],'sub.id=g.subject_id');
	    $query->leftJoin(['y'=>Year::tableName()],'y.id=g.year_id');
	    $query->select([
	    	'g.*',
		    'stu.name as student',
		    'y.des as year',
		    'sum( case when sub.name="语文" then g.score end ) as china',
		    'sum( case when sub.name="数学" then g.score end ) as math',
		    'sum( case when sub.name="英语" then g.score end ) as english',
		    'sum( case when sub.name="历史" then g.score end ) as history',
		    'sum(g.score) as total',
		    'format(avg(g.score),2) as avg '
	    ]);
	    $query->groupBy(['stu.name','y.id']);


//	    pre($query->createCommand()->getRawSql());die;
        // add conditions that should always apply here
//		pre($params);die;

	    $this->load($params);
//        if($this->student){  // 这种也可以
//        	$query->andWhere(['like','stu.name',$this->student]);
//        }
	    $query->andFilterWhere([  // 这个函数就是调用andWhere的
	    	'REGEXP','stu.name',$this->student
	    ]);
	    $query->andFilterWhere([
		    'REGEXP','y.des',$this->year
	    ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'score' => $this->score,
            'student_id' => $this->student_id,
            'subject_id' => $this->subject_id,
            'year_id' => $this->year_id,
        ]);


	    $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
//		    	'pageSize' => 4
		    ],
		    'sort' =>[
			    'attributes' => ['china', 'math', 'english', 'history', 'total','avg','year',
					'student' =>[
						'asc' => ['student' => SORT_ASC],
						'desc' => ['student' => SORT_DESC],
						'default' => SORT_DESC,
						'label' => 'Name',
					],
			],
			'defaultOrder' => [ 'total' => SORT_DESC ]
		    ],
	    ]);

//	    $dataProvider->setSort([
//	    	'attributes' => ['china','math','total'],
//		    'defaultOrder' => ['total'=>SORT_DESC],
//	    ]);

//        $dataProvider->setModels($query->all());
	    if (!$this->validate()) {
		    // uncomment the following line if you do not want to return any records when validation fails
		    // $query->where('0=1');
		    return $dataProvider;
	    }

        return $dataProvider;
    }
}
