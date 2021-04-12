<?php

namespace frontend\components;

use frontend\models\AuthAssignment;
use frontend\models\SfUser;

class StaffFiles
{

    /**
     * 构造树数据
     * @param $data
     * @param string $parent
     * @return array
     */
   public static function buildTree($data, $parent = '0')
    {
        $arr = [];

        foreach($data as $key => $val ){
            if($val['parent'] == $parent && $val['parent'] !=  $val['id'] ){
                $val['children'] = static::buildTree($data, $val['id']);
                $val['checked'] = $val['checked'] == 1;   //赋值布尔
                $arr[] = $val;
            }
        }
        return $arr;
    }

    /**
     *     *判断是否为NOC用户
    **/
    public static function is_noc_user($username)
    {
         $data = SfUser::findOne(['userid' => $username]);
         if($data)return 0; else return 1;
     }


   /**
     * 判断是否存在该用户
     */
    public static function is_member($username)
    {
        $data = AuthAssignment::findOne(['user_id'=> $username]);
        if(!$data)return 0; else return 1;
    }

    /**
     * 判断是否为管理员用户
     */
    public  static function is_superuser($username)
    {
        $data = AuthAssignment::findOne(['item_name'=>"管理员", 'user_id'=> $username]);
        if(!$data)return 0; else return 1;
    }

    /**
     * 判断是否为组长
     */
    public static function is_leader($username)
    {
        $data = AuthAssignment::findOne(['item_name'=>"组长", 'user_id'=> $username]);
        if(!$data)return 0; else return 1;
    }


    /**
     * 判断是否为普通用户
     */
   public static function is_crew($username)
   {
        $data = AuthAssignment::findOne(['item_name'=>"组员", 'user_id'=> $username]);
        if(!$data)return 0; else return 1;
    }

    /**返回某个组员
     * @return array|\common\models\AuthAssignment[]
     */
    public static function get_crew($name)
    {
        $data = AuthAssignment::find()->where(['item_name'=>'组员', 'user_id'=>$name])->asArray()->all();
        return $data;
    }

    /**
     * 返回组员名单
     */
    public static function get_crews()
    {
        $data = AuthAssignment::find()->where(['item_name'=>'组员'])->asArray()->all();
        return $data;
    }

    public static function get_all_members()
    {
        $data = AuthAssignment::find()->asArray()->all();
        return $data;
    }

    public static function get_leader()
    {
        $data = AuthAssignment::find()->where(['item_name'=>'组长'])->asArray()->all();
        return $data;
    }

    public static function get_group_members($username)
    {
      return SfUser::find()
            ->select('userid as user_id')
            ->from('sf_user')
            ->where(['parent'=>$username])
            ->asArray()
            ->all();
    }

    public static function get_superuser()
    {
        $data = AuthAssignment::find()->where(['item_name'=>'管理员'])->asArray()->all();
        return $data;
    }

    public static function get_noc_all_user()
    {
        $noc_all_user = SfUser::find()
//             ->select(['USER_ID'])
//             ->from('user_group')
// //            ->leftJoin('user_group ug2', "ug1.User_ID = ug2.User_ID and ug2.Group_ID = '51bfafc36c7d093203'")
//             ->where(['Group_ID'=>"51bfafafe2f556080"])
// //            ->andWhere(['is', '`ug2`.`Group_ID`', new \yii\db\Expression('null')])
//             ->orderBy(["Create_Time"  => SORT_DESC,])
//             ->asArray()
//             ->all();
            ->select(['userid'])
            ->from('sf_user')
            // ->orderBy(["Create_Time"  => SORT_DESC,])
            ->asArray()
            ->all();

        return $noc_all_user;
    }


    public static function get_role()
    {
        $data = AuthAssignment::find()->select(['item_name'])->distinct()->asArray()->all();
        return $data;
    }


    public static function set_role($role,$name)
    {
        $data = new AuthAssignment();
        $data->item_name = $role;
        $data->user_id = $name;
        $data->created_at = time();
        $data->save();
    }

    public static function del_role($role,$name)
    {
        AuthAssignment::deleteAll(
            [
                'and',
                'user_id = :user_id',
                'item_name = :item_name',
            ],
            [
                ':user_id' => $name,
                ':item_name' => $role
            ]
        );
    }

    public static function que_role()
    {
        return AuthAssignment::find()
            ->select(["GROUP_CONCAT(user_id separator '  ,  ') as user_id",'item_name'])
            ->groupBy('item_name')
            ->orderBy('item_name desc')
            ->asArray()
            ->all();
    }

}
