<?php

/* @var $this yii\web\View */
/* @var $levelOne frontend\models\categoryTree */
/* @var $category frontend\models\category */
/* @var $categoryUser frontend\models\categoryUser */
use frontend\components\StaffFiles;

$this->title = 'Staff Files';

$this->registerJsFile('@web/layui/layui.js', ['position' => $this::POS_HEAD]);

isset($_SESSION['username']) ? $username = $_SESSION['username'] : $username = null;

?>
<div class="panel-group" id="accordion">
    <?php
        if(StaffFiles::is_superuser($username)):
            $noc_all_user = StaffFiles::get_noc_all_user();           //$db_all_user 存放数据库所有NOC成员名单
            $crew = StaffFiles::get_crews();                        //$crew 存放所有属于组员角色的名单
            $leader = StaffFiles::get_leader();                    //$leader 存放组长名单
            $role = StaffFiles::get_role();                        //$role 存放角色权限
        ?>

    <!-- 角色分配-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion"
                   href="#collapseFive">
                    角色分配
                </a>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse out">
            <div class="panel-body">
                <label>角色: <input id="role"  list="role_list" name="role" class='form-control' " autocomplete="on"  style="width:100%" /></label>
                <datalist id="role_list">
                    <option selected="selected"> </option>
                    <?php
                    foreach ($role as $key => $val)

                    {
                        echo '<option value="'.$val['item_name'].'">';
                    }
                    ?>
                </datalist>
                <label>候选成员 : <input id="noc_all_user_1" list="noc_all_user_list" name="name" class='form-control'" autocomplete="off"  style="width:100%" /></label>
                <datalist id="noc_all_user_list">
                    <option selected="selected"> </option>
                    <?php
                    foreach ($noc_all_user as $key => $val)

                    {
                        echo '<option value="'.$val['userid'].'">';
                    }
                    ?>
                </datalist>
                <label><input type="button" class="form-control" value="提交" onclick="set_role()" /></label>
                <label><input type="button" class="form-control" value="删除" onclick="del_role()" /></label>
                <label><input type="button" class="form-control" value="查询" onclick="que_role()" /></label>
                <div id="table2"></div>
            </div>
        </div>
    </div>

    <!-- 成员分组-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion"
                   href="#collapseOne">
                    成员分组
                </a>
            </h4>
        </div>
    <div id="collapseOne" class="panel-collapse collapse out">
        <div class="panel-body">
            <label>组长:
                <input id="group"  list="leader_user_list" name="group" class='form-control' " autocomplete="on"  style="width:100%" />
            </label>
            <datalist id="leader_user_list">
                <option selected="selected"> </option>
                <?php
                foreach ($leader as $key => $val)
                {
                    echo '<option value="'.$val['user_id'].'">';
                }
                ?>
            </datalist>
        <label>
            候选组员 :
            <input id="all_user" list="crew_list" name="user" class='form-control'" autocomplete="off"  style="width:100%" />
        </label>
        <datalist id="crew_list">
            <option selected="selected"> </option>
            <?php
            foreach ($crew as $key => $val)
            {
                echo '<option value="'.$val['user_id'].'">';
            }
            ?>
        </datalist>
            <label><input type="button" class="form-control" value="提交" onclick="join_group()" /></label>
            <label><input type="button" class="form-control" value="删除" onclick="exit_group()" /></label>
            <label><input type="button" class="form-control" value="查询" onclick="que_group()" /></label>
            <div id="table">            </div>
        </div>
     </div>
    </div>

        <?php
            else:
                //组长
                 $crew = StaffFiles::get_group_members($username);
            endif;
        ?>

    <!--组员详情-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion"
                   href="#collapseTree">
                    组员详情
                </a>
            </h4>
        </div>

<div id="collapseTree" class="panel-collapse collapse out">
    <div class="panel-body">
        <label><input id="user" list="crew_list" name="user" class='form-control'  autocomplete="off"  style="width:100%;"  /></label>
        <datalist id="crew_list">
            <option selected="selected"> </option>
            <?php
            foreach ($crew as $key => $val)
            {
                echo '<option value="'.$val['user_id'].'">';
            }
            ?>
        </datalist>
        <label><input type="button" class="form-control" value="确定" onclick="get_user()" /></label>

        <div id="tree" class="demo-tree demo-tree-box" style="width:100%; height:100%;"></div>
    </div>
        </div>

    </div>
</div>

<script>
    /**
     * 封装所有需要二次确认的网络请求
     */
    function confirm_operate(url, data, fun=null) {
        layui.use(['layer'], function() {
            var layer = layui.layer;
            layer.confirm('确认提交?', {icon: 3, title: '提示'}, function (index) {
                $.ajax({
                    type: "get",
                    url: url,
                    data: data,
                    dataType: 'json',
                    async: false,
                    error: function (res) {
                        alert("请检查是否填写正确");
                    },
                    success: function (res) { // response request
                        if (res.code == 0) {
                            layer.open({title: '执行结果', content: '提交成功'});
                        } else {
                            console.log(res);
                            // alert("请检查是否填写正确");
                        }
                        if(fun!=null){fun(res)}
                    }
                });
                layer.close(index);
            });
        });
    }


    function set_role(){
        var name = $('#noc_all_user_1').val();
        var role = $('#role').val();

        var fun = function (res) {
            var html = '<option selected="selected"> </option>';
            for(key in res['data']) {
                var val = res['data'][key]['user_id'];
               html += '<option value="' + val + '"></option>';
            }
            document.getElementById('crew_list').innerHTML = html;
        };

        confirm_operate("../item/setrole", {'name': name, 'role':role }, fun );
    }

    function del_role(){
        var name = $('#noc_all_user_1').val();
        var role = $('#role').val();
        confirm_operate("../item/delrole", { 'name': name, 'role':role });

    }

    function que_role(){
        $.ajax({
            type: "get",
            url: "../item/querole",
            dataType: 'json',
            success:function(res){
                var html = ' <table class="table" style="width: 30%" ><tr><th>角色</th><th>成员</th></tr>';

                for(var key in res['data']){
                    html += '<tr><td>' + res['data'][key]['item_name'] + '</td><td>' + res['data'][key]['user_id'] +  '</td></tr>';
                }
                html += "</table>";
                document.getElementById("table2").innerHTML = html;
            }
        });
    }

    function join_group() {
        var group = $('#group').val();
        var name = $('#all_user').val();
        confirm_operate("../item/joingroup", { 'name': name, 'group':  group });
    }

    function exit_group() {
        var group = $('#group').val();
        var name = $('#all_user').val();
        confirm_operate("../item/exitgroup", { 'name': name, 'group':  group });
    }

    function que_group() {
        $.ajax({
            type: "get",
            url: "../item/quegroups",
            dataType: 'json',
            success:function(res){
                var html = ' <table class="table" style="width: 30%" ><tr><th>组长</th><th>组员</th></tr>';

                for(var key in res){
                    html += '<tr><td>' + res[key]['parent'] + '</td><td>' + res[key]['userid'] +  '</td></tr>';
                }

                html += "</table>";
                document.getElementById("table").innerHTML = html;
            }
        });
    }

    function get_user(){
        var username = $('#user').val();
        layui.use(['tree', 'util'], function(){
            var tree = layui.tree
                ,layer = layui.layer
                ,util = layui.util;

            $.ajax({
                type: "get",
                url: "../item/index",
                data: {username: username},
                // async: false,
                dataType: 'json',
                error:function(data){
                    alert("请检查是否填写正确");
                },
                success:function(data){
                    tree.render({
                        elem: '#tree'
                        ,data: data
                        ,showCheckbox: true
                        ,isJump: true  //link 为参数匹配
                        ,onlyIconControl: true

                    });
                }
            });
        });
    }

    /**
     * 在数据库记录checkbox值
     * @param element
     */
    function check(element) {
        element.checked = !element.checked;

        var username = $("#user").val();

        var category_name = element.name;
        var category_array = category_name.split("_");
        var category_id = category_array[1];

        var request = new XMLHttpRequest();
        request.open('GET', "../item/update?username=" + username + "&category_id="+ category_id + "&checked=" + element.checked, true);
        request.send();
    }

</script>
