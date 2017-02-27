<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `common\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * 在原来的字段上再添加一个字段 author_name，使用 array_merge() 数组函数将它们合并起来
     * @return array
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['author_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'author_id'], 'integer'],
            [['title', 'content', 'tags', 'author_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Post::find();

        // add conditions that should always apply here

        /**
         * 这里可以做为分布的条件
         */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'post.id' => $this->id,
            'post.status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        /**
         * 这是以innerJoinWith 方式进行关联查询
         */
        $query->join('INNER JOIN', 'Adminuser', 'post.author_id = Adminuser.id');
        $query->andFilterWhere(['like', 'Adminuser.nickname', $this->author_name]);

        $dataProvider->sort->attributes['author_name'] = [
            'asc' => ['Adminuser.nickname' => SORT_ASC],
            'desc' => ['Adminuser.nickname' => SORT_DESC],
        ];


        return $dataProvider;
    }
}
