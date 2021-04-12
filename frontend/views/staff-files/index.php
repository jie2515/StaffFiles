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

<div id="tree" class="demo-tree demo-tree-box" style="width:100%; height:100%;"></div>

<br><br><br>


<div style="color:blue; font-size:10px;">
<i>
<p>每项后面的四个按钮使用说明：</p>
<p>第一个按钮（+）用于增加子项目；</p>
<p>第二个按钮（🖊）用于修改项目名称；</p>
<p style="color:red;">第三个按钮（🗑️）用于删除项目包括子项目，请谨慎使用；</p>
<p>第四个按钮（🖊）用于填写网址，网址格式需要以http://或https://开头，网址填写完毕刷新网页后会消失</p>
</i>
</div>

<script>
    layui.use(['tree', 'util'], function(){
        var tree = layui.tree
            ,layer = layui.layer
            ,util = layui.util;

        var request = new XMLHttpRequest();
        request.open('GET', "../item/index?username=<?=$username ?>", true);
        request.onload = function() {
            // Begin accessing JSON data here
            var data = JSON.parse(this.response);
            if (request.status >= 200 && request.status < 400) {
                tree.render({
                    elem: '#tree'
                    ,data: data
                    ,isJump: true  //link 为参数匹配
                    ,onlyIconControl: true


                    <?php
                    $data = !(StaffFiles::is_superuser($username) || StaffFiles::is_leader($username));
                    if($data){
                        echo ",showCheckbox: true";   // 组员的显示
                    }else{
                        echo ",edit: ['add', 'update', 'del','url']";    //组长以及管理员的显示
                    }
                    ?>


                    ,operate: function(obj) {
                        var type = obj.type; //得到操作类型：add、edit、del
                        var data = obj.data; //得到当前节点的数据
                        var elem = obj.elem; //得到当前节点元素
                        var id = data.id; //得到节点索引

                        //Ajax 操作
                        if(type === 'add'){ //增加节点
                            var category_name = elem.find('.layui-tree-txt').html();
                            var new_id;
                            category_name = category_name.replace(/\+/g, "%2B");
                            category_name = category_name.replace(/\&/g, "%26");

                            $.ajax({
                                type: "get",
                                url: "../item/add",
                                data: "category_name=" + category_name + "&parent_id="+ id,
                                dataType: 'text',
                                async: false,
                                beforeSend:function(){
                                },
                                error:function(data){
                                    alert("Error status:" + data.status);
                                },
                                success:function(data){
                                    var json = eval('(' + data + ')');
                                    new_id = json;
                                    return false;
                                }
                            });

                            return new_id;   //返回 key 值


                        } else if(type === 'update'){ //修改节点
                            var parent_id = id;

                            var request = new XMLHttpRequest();
                            var category_name = elem.find('.layui-tree-txt').html();

                            category_name = category_name.replace(/\+/g, "%2B");
                            category_name = category_name.replace(/\&/g, "%26");

                            request.open('GET', "../item/edit?category_name=" + category_name + "&parent_id="+ parent_id, true);
                            request.send();


                        } else if(type === 'del'){ //删除节点
                            var request = new XMLHttpRequest();
                            request.open('GET', "../item/del?category_id=" + id, true);
                            request.send();



                        }else if(type === 'url'){ //修改节点

                            var url = elem.find('.layui-tree-txt2').html();
                            var url_id = "url_" + id;
                            $.ajax({
                                type: "get",
                                url: "../item/url",
                                data: "id="+ id + "&url=" + url,
                                dataType: 'text',
                                async: false,
                                beforeSend:function(){
                                },
                                error:function(data){
                                    alert("Error status:" + data.status);
                                },
                                success:function(data){
                                    $("#" + url_id).attr("href",url);
                                }
                            });

                        }
                    }
                });
            } else {
                console.log('error')
            }
        };
        request.send();
    });


    function check(element) {
        element.checked = !element.checked;  //当为true时变为false，当为false时变为true

        <?php if(!$data)echo 'return';   //不执行以下JavaScript脚本，退出，组员不能更新CheckBox值 ?>

        var category_name = element.name;
        var category_array = category_name.split("_");
        var category_id = category_array[1];

        var request = new XMLHttpRequest();
        request.open('GET', "../item/update?username=<?=$username ?>&category_id="+ category_id + "&checked=" + element.checked, true);
        request.send();
    }

</script>