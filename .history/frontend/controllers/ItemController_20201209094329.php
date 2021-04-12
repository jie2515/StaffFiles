<?php
namespace frontend\controllers;

use frontend\components\StaffFiles;
use frontend\models\SfUser;
use yii\db\Query;
use yii\helpers\Json;
use yii\web\Controller;
use frontend\models\CategoryTree;
use frontend\models\Category;
use frontend\models\CategoryUser;
use yii\filters\HttpCache;

/**
 * Item controller
 */
class ItemController extends Controller
{

    /**
     * 构造树数据
     * @param $username
     * @return false|string
     * @throws \yii\db\Exception
     */
    public function actionIndex($username)
    {
        // if(StaffFiles::is_member($username)){
        if(empty(StaffFiles::is_member($username))){
                exit;
        }
        $category_tree = CategoryTree::tableName();
        $category = Category::tableName();
        $category_user = CategoryUser::tableName();

        $query = new Query;
        $query  ->  select(["ct.parent", "ct.child AS id", "ct.depth", "c.name AS title", "c.url AS href", "cu.checked"])
            ->  from("$category_tree ct")
            ->  where(["depth"=>1])
            ->  leftJoin("$category c", "c.id = ct.child")
            ->  leftJoin("(select category_id, checked from $category_user where userid='$username') cu", "ct.child = cu.category_id");

        $command = $query->createCommand();
        $data = $command->queryAll();


        $res = StaffFiles::buildTree($data);

        $res2[0] = array(
            'id' => 0,
            'title' => '员工档案',
            'spread' => true,
            'children' => $res
        );
            return json_encode($res2, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 更新CategoryUser表的CheckBox值
     * @param $username
     * @param $category_id
     * @param $checked
     */
    public function actionUpdate($username, $category_id, $checked)
    {
        $data = CategoryUser::find()->where(['userid'=>$username, 'category_id'=>$category_id])->one();
        if($data==null){
            $categoryUser = new CategoryUser();
            $categoryUser -> userid = $username;
            $categoryUser -> category_id = $category_id;
            $checked == "true" ? $categoryUser->checked = "1" : $categoryUser->checked = "0";
            $categoryUser->save();

        }else{
            $checked == "true" ? $data->checked = "1" : $data->checked = "0";
            $data -> save();
        }

    }

    /**
     * 增加category表的name行（树的item项），并更新parent关系
     * @param $category_name
     * @param $parent_id
     * @return int
     */
    public function actionAdd($category_name,$parent_id)
    {
        $category = new Category();
        $category->name = "未命名";
        $category->save();

        //在category_tree新增父节点子节点关系
        $category_tree = new CategoryTree();
        $category_tree -> parent = $parent_id;
        $category_tree -> child = $category->id;
        $category_tree -> depth = 1;
        $category_tree -> save();

        return $category->id;

    }

    /**
     * 编辑树的item名
     * @param $category_name
     * @param $parent_id
     */
    public function actionEdit($category_name,$parent_id)
    {
        $category_name = trim($category_name);

        //修改原有Item名
        $data2 = Category::find()->where(['id'=>$parent_id])->one();

        $data2->name = $category_name;
        $data2 -> save();
    }

    /**删除树的item项
     * @param $category_id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
        public function actionDel($category_id)
    {
        $category = Category::find()->where(['=' , 'id' , $category_id])->one();
        $category -> delete();
    }


    //普通用户不显示CheckBox编辑按钮
    public function actionLevel($name)
    {
        $data = StaffFiles::get_crew($name);
        if($data==Null) return ""; else return ",edit: ['add', 'update', 'del']";
    }


    /**
     * 更新item的URL信息
     * @param $id
     * @param $url
     * @return string
     */
    public function actionUrl($id, $url)
    {
        Category::updateAll(['url'=>$url], ['id'=>$id]);
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>null]);
    }

    public function actionSetrole($role, $name)
    {
	// if(empty(StaffFiles::is_member($name))){
        if(StaffFiles::is_noc_user($name)){
            exit;
        }
        
        StaffFiles::set_role($role,$name);
	    $data =  StaffFiles::get_crews();
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>$data]);
    }

    public function actionDelrole($role, $name)
    {
        StaffFiles::del_role($role,$name);
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>null]);
    }

    public function actionQuerole()
    {
        $data = StaffFiles::que_role();
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>$data]);
    }

    public function actionJoingroup($group, $name)
    {
      //        SfUser::updateAll( ['parent'=>$group],['userid'=>$name]);
        // if(empty(StaffFiles::is_member($name))){
        //if(StaffFiles::is_member($name)){
          //  exit;
        //}
        $data1 = SfUser::find()
            ->where(['userid'=>$name])
            ->one();

        if($data1) {
            $data1->parent = $group;
            $data1->save();
        }else{
            $data2 = new SfUser();
            $data2->userid = $name;
            $data2->parent = $group;
            $data2->save();
            }
            
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>null]);
    }

    public function actionExitgroup($group, $name)
    {
        SfUser::updateAll(['parent'=>new \yii\db\Expression('null')],['userid'=>$name]);
        return Json::encode(['code'=>0, 'message'=>'success', 'data'=>null]);
    }

    public function actionQuegroups()
    {

        $group_data = SfUser::find()
            ->select(["GROUP_CONCAT(userid separator ' , ') as userid",'parent'])
            ->groupBy('parent')
            ->where('parent is not NULL')
//            ->andWhere(['!=','parent','superuser'])
            ->asArray()
            ->all();

        return json_encode($group_data);
    }



}
