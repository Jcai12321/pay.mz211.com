<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:68:"E:\shopweb\pay.mz211.com/application/mobile\view\goods\goodList.html";i:1534411292;s:68:"E:\shopweb\pay.mz211.com/application/mobile\view\common\_header.html";i:1533372786;s:68:"E:\shopweb\pay.mz211.com/application/mobile\view\common\_footer.html";i:1533372820;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/mobile/css/style.css">
    <script type="text/javascript" src="/static/mobile/js/jquery.js"></script>
    <script type="text/javascript" src="/static/mobile/js/common.js"></script>
</head>
<style>
    .nodata{width:100%; padding:20px 0; text-align:center; font-size:16px;}
    .nodata img{width:32px; height:32px; margin:0 auto;}
</style>
<body class="font">
<!-- 头部 -->
<div id="top" class="white pos_rel">
    <h2 class="txt_c f36 white_gray"><?php echo $title; ?></h2>
    <span class="top_back pos_abs f32">
        <a class="go_back white">返回</a>
    </span>
    <a class="top_set pos_abs" href="<?php echo url('mobile/Goods/category'); ?>"></a>
</div>
<!-- 搜索栏 -->
<div id="search_2" class="pos_rel">
    <div class="search ml70">
		<span class="txt">
			<input type="text" value="<?php if(empty($keyword) || (($keyword instanceof \think\Collection || $keyword instanceof \think\Paginator ) && $keyword->isEmpty())): ?>请输入搜索内容<?php else: ?><?php echo $keyword; endif; ?>" id="input_txt" class="f20" onclick="this.value=''">
		</span>
        <input type="hidden" id="is_default" value="<?php echo $is_default; ?>">
        <input type="hidden" id="cat_id" value="<?php echo $cat_id; ?>">
        <input type="hidden" id="keyword" value="<?php echo $keyword; ?>">
        <input type="hidden" id="condition" value="<?php echo $condition; ?>">
        <input type="hidden" id="cur_btn" value="1">
        <input type="hidden" id="sort_type" value="<?php echo $sort_type; ?>">
        <input type="hidden" id="p" value="<?php echo $p; ?>">
        <input type="submit" id="input_btn" value="搜索" class="pos_abs f32" onclick="paramsGoods(this,0)">
    </div>
</div>
<!-- 排序 -->
<ul id="sort_list" class="prod_sort clearfix txt_c mt10 box_shadow_bot2 bgc_white">
    <li data-sort="1" class="cur" onclick="paramsGoods(this,1)"><span>销量&nbsp;<img src="/static/mobile/images/icon_arrow_1.png"></span></li>
    <li data-sort="1" onclick="paramsGoods(this,2)"><span>人气&nbsp;<img src="/static/mobile/images/icon_arrow.png"></span></li>
    <li data-sort="1" onclick="paramsGoods(this,3)"><span>价格&nbsp;<img src="/static/mobile/images/icon_arrow.png"></span></li>
    <li data-sort="1" onclick="paramsGoods(this,4)"><span>时间&nbsp;<img src="/static/mobile/images/icon_arrow.png"></span></li>
</ul>

<!-- 搜索结果 -->
<!--<div id="prod_count" class="mt16 pb10">共找到相关产品<span><?php echo $list_count; ?></span>个</div>-->
<div id="prod_count" class="mt16 pb10"></div>

<div class="nodata"></div>
<script>
    function setValue(name,value) {
        $('#'+name).val(value);
        return value;
    }

    function getValue(name) {
        return $('#'+name).val();
    }

    //key,value都为字符串
    function sessionSet(key,value) {
        return sessionStorage.setItem(key,value);
    }

    function sessionGet(key) {
        return sessionStorage.getItem(key);
    }

    function sessionRemove(key) {
        return sessionStorage.removeItem(key);
    }

    function sessionClear() {
        return sessionStorage.clear();
    }

    //全局数据
    var params = {};
    params.cat_id = '';
    params.keyword = '';
    params.condition = ''; //条件列
    params.sort_type = '';	//1从高到底,0从底到高
    params.p = ''; //页数
    cur_btn = 1;  //当前排序按钮
    ajaxUrl = "<?php echo url('mobile/Goods/ajaxGoodList','',''); ?>";

    $(document).ready(function () {
        sessionString = sessionGet("sessionString");
        scrollTop = sessionGet("scrollTop");
        enterPage = sessionGet("enterPage");    //判断是否有值，说明是从详情页返回

        console.log(sessionString);
        console.log(scrollTop);
        console.log(enterPage);

        if (sessionString != null && scrollTop != null && enterPage != null) {
            //已有数据
            sessionRemove("enterPage"); //清空进入详情页标记

            sessionJson = JSON.parse(sessionString);
            goodsList = sessionJson.goodsList;
            params = sessionJson.params;

            //搜索条件还原
            setValue('condition',params.condition);
            setValue('is_default',params.is_default);
            setValue('cat_id',params.cat_id);
            setValue('sort_type',params.sort_type);
            setValue('p',params.p);

            //跳转到历史滚动位置
            $('#prod_count').after(goodsList);
            $("#prod_count").animate({ scrollTop:scrollTop}, 0);
        } else {
            //清空所有访问
            sessionRemove("sessionString");
            sessionRemove("scrollTop");
            sessionRemove("enterPage");

            params.cat_id = getValue('cat_id');
            params.keyword = getValue('keyword');
            params.condition = getValue('keyword');
            params.sort_type = getValue('sort_type');
            params.p = getValue('p');
            params.cur_btn = getValue('cur_btn');
            sessionJson = {goodsList:"",params:params};
            //发送请求
            $.ajax({
                type: "POST",
                url: ajaxUrl,
                data: params,
                dataType: "json",
                beforeSend: function () {
                    $(".nodata").html('<img src="/static/mobile/images/loading.gif"/>');
                },
                success: function (data) {
                    $('.prod_lists').remove();
                    if (data.length > 0) {
                        var html = '';
                        $.each(data, function (key, val) {
                            html += '<div class="prod_lists"><a href="/goods/info/'+val.goods_id+'"><div class="pro_img"><img src="/static/mobile/images/none/pro_default.jpg" alt="产品图" title="产品图" class="full_img"></div></a><div class="pro_info"><h3><a href="/goods/info/'+val.goods_id+'">' + val.goods_name + '</a></h3><h4><a href="/goods/info/'+val.goods_id+'">' + val.goods_remark + '</a></h4><div><span class="price">￥' + val.shop_price + '</span><span class="num_comm">已卖:' + val.sales_sum + '件</span></div></div></div>';
                        });
                        //同级后追加
                        sessionJson.goodsList += html;
                        sessionSet("sessionString",JSON.stringify(sessionJson));
                        $('#prod_count').after(html);
                    } else {
                        //没有数据
                        $(".nodata").html("已全部加载");
                    }
                }
            });
        }
    });

    function paramsGoods(obj, condition) {
//        cur_type = getValue('cur_type');
        params.condition = setValue('condition',condition);
        params.is_default = setValue('is_default',0);
        params.cat_id = getValue('cat_id');
        if(cur_btn == condition) {
            params.sort_type = setValue('sort_type',1-getValue('sort_type'));
        } else {
            cur_btn = condition;
            setValue('cur_btn',condition);
            params.sort_type = getValue('sort_type');
        }
        params.p = setValue('p',1);
        if(getValue('input_txt') == '请输入搜索内容'){
            params.keyword = setValue('keyword','');
        } else {
            params.keyword = setValue('keyword',getValue('input_txt'));
        }
        //修改显示
        if(condition == 0) {
            params.sort_type = setValue('sort_type',1);
            $("#sort_list").children().find("img").attr('src', '<?php echo $SITE_URL; ?>static/mobile/images/icon_arrow.png');
            $("#sort_list").children().first().find("img").attr('src', '<?php echo $SITE_URL; ?>static/mobile/images/icon_arrow_'+params.sort_type+'.png');
        } else {
            $(obj).children().find("img").attr('src', '<?php echo $SITE_URL; ?>static/mobile/images/icon_arrow_'+params.sort_type+'.png');
            $(obj).siblings().children().find("img").attr('src', '<?php echo $SITE_URL; ?>static/mobile/images/icon_arrow.png');
        }
        //发送请求
        $.ajax({
            type: "POST",
            url: "<?php echo url('mobile/Goods/ajaxGoodList'); ?>",
            data: params,
            dataType: "json",
            success: function(data){
                $('.prod_lists').remove();
                if(data.length > 0){
//                $("#prod_count span").html(data.length);
                    var html = '';
                    $.each(data, function(key, val){
                        html += '<div class="prod_lists"><a href="/goods/info/'+val.goods_id+'"><div class="pro_img"><img src="/static/mobile/images/none/pro_default.jpg" alt="产品图" title="产品图" class="full_img"></div></a><div class="pro_info"><h3><a href="/goods/info/'+val.goods_id+'">' + val.goods_name + '</a></h3><h4><a href="/goods/info/'+val.goods_id+'">' + val.goods_remark + '</a></h4><div><span class="price">￥' + val.shop_price + '</span><span class="num_comm">已卖:' + val.sales_sum + '件</span></div></div></div>';
                    });
                    //同级后追加
                    $('#prod_count').after(html);
                } else {
                    $(".nodata").html("已全部加载");
                }

            }
        });
    }

    //绑定点击进入详情页的标记设置,滚动到的页数
    $("body").on("click",".prod_lists",function () {
        sessionSet("scrollTop",scrollTop);
        sessionSet('enterPage',getValue('p'));
    });

</script>
<script type="text/javascript" src="/static/mobile/js/autoload.js"></script>
<div class="m100"></div>
<ul id="bot_nav" class="clearfix">
    <li class="cur"><a href="<?php echo url('mobile/Index/index'); ?>"><img src="/static/mobile/images/img_shopping.png" alt="首页"><!-- 购物 --></a></li>
    <li><a href="<?php echo url('mobile/Goods/goodList'); ?>"><img src="/static/mobile/images/img_search.png" alt="搜索"><!-- 搜索 --></a></li>
    <li><a href="<?php echo url('mobile/User/userCenter'); ?>"><img src="/static/mobile/images/img_person.png" alt="个人中心"><!-- 个人中心 --></a></li>
    <li><a href="<?php echo url('mobile/Cart/cart'); ?>"><img src="/static/mobile/images/img_cart.png" alt="购物车"><!-- 购物车 --></a></li>
    <li><a href="<?php echo url('mobile/SalesService/service'); ?>"><img src="/static/mobile/images/img_selled.png" alt="售后"><!-- 售后 --></a></li>
</ul>
<script>
    //返回
    $(".go_back").live('click',function () {
        window.history.go(-1);
    });

    //刷新
    function refresh() {
        location.replace(location.href);
    }
</script>
</body>
</html>