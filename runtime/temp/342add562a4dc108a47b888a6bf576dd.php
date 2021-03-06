<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"E:\shopweb\pay.mz211.com/application/mobile\view\goods\goodsInfo.html";i:1534391875;s:68:"E:\shopweb\pay.mz211.com/application/mobile\view\common\_header.html";i:1533372786;}*/ ?>
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
<body class="font">
<!-- 头部 -->
<div id="top" class="white pos_rel"><h2 class="txt_c f36 white_gray">商品详情</h2><span class="top_back pos_abs f32"><a class="go_back white">返回</a></span><a class="top_set pos_abs" href="<?php echo url('mobile/Goods/category'); ?>"></a></div>
<!-- 产品展示图 -->
<div><img src="/static/mobile/images/none/pro_default.jpg" alt="产品详情" class="full_img"></div>
<div class="product_detail box_shadow_bot bgc_white">
	<h1><?php echo $goods_info['goods_name']; ?></h1>
	<div class="price">价格<span id="price">￥<?php echo $goods_info['shop_price']; ?></span></div>
	<div style="font-size: 18px;">库存<span id="store_count" style="margin-left: 10px;"><?php echo $goods_info['store_count']; ?></span></div>
	<div style="font-size: 15px;"><span id="selected_msg"></span></div>
</div>
<form id="goods_form" method="get" action="<?php echo url('mobile/Cart/analyse',['from'=>'buy_now']); ?>" >
	<input type="hidden" name="goods_id" value="<?php echo $goods_info['goods_id']; ?>">
	<input type="hidden" name="item_id" value="">
	<input type="hidden" name="goods_num" value="">
	<input type="hidden" name="shop_price" value="">
	<input type="hidden" name="market_price" value="">
	<input type="hidden" name="store_count" value="">
	<input type="hidden" name="default_price" value="<?php echo $goods_info['shop_price']; ?>">
	<input type="hidden" name="default_count" value="<?php echo $goods_info['store_count']; ?>">
</form>
<div id="spec_list" class="box_shadow_bot s_selects mt16 bgc_white">
	<div class="sp_address bor_bot_gray pos_rel pb10"><i>送至：</i><span>北京</span><a href="#" class="iconfont icon_arrow">&#xe621;</a></div>
	<!--<div id="color" class="mt16"><i>颜色：</i><span class="options cur">白色<span class="selected"><img src="/static/mobile/images/icon_select.png"></span></span><span class="options">黑色</span></div>-->
	<!--<div id="size" class="mt16"><i>尺寸：</i><span class="options cur">S<span class="selected"><img src="/static/mobile/images/icon_select.png"></span></span><span class="options">M</span><span class="options">L</span><span class="options">XL</span></div>-->
	<!--<div id="num" class="mt16"><i>数量：</i><span class="btn_num"><a href="javascript:;" id="less" class="less"><img src="/static/mobile/images/btn_less.jpg"></a><span class="num">1</span><a href="javascript:;" id="add" class="add"><img src="/static/mobile/images/btn_add.jpg"></a></span></div>-->
</div>
<div class="box_shadow_bot s_selects mt16 bgc_white"><div class="pos_rel black">图文详情<a href="#" class="iconfont icon_arrow">&#xe621;</a></div></div>
<div class="box_shadow_bot s_selects mt16 bgc_white"><div class="pos_rel black">评价<a href="#" class="iconfont icon_arrow">&#xe621;</a></div></div>

<div class="box_shadow_bot mt16 buyBox pos_rel bgc_white">
	<a href="<?php echo url('mobile/Cart/cart'); ?>" id="goCarts"><img src="/static/mobile/images/img_cart2.png" title="购物车" alt="购物车"></a>
	<div class="buyBtn">
		<a href="#" id="add_cart" class="carts" onclick="return addCart();">加入购物车</a><a href="javascript:void (0);" class="buy">直接购买</a>
	</div>
</div>

<script type="text/javascript">
    var spec_info = <?php echo $spec_info; ?>;//规格价格信息
    var spec_list = <?php echo $spec_list; ?>;//规格详细

	//每种规则的信息
    var spec_id_list = [];

	var cur_info = {};
	cur_info.goods_id = '';
    cur_info.goods_num = '';
    cur_info.item_id = '';
	cur_info.spec_id_list = {};

    //返回
    $(".go_back").live('click',function () {
        window.history.go(-1);
    });

    function initSpecList() {
		if(spec_list){
            $.each(spec_list,function (spec_name,data) {
                spec_div = '<div class="mt16"><i>'+spec_name+'：</i>';
                $.each(data,function (kk,vv) {
                    //初始化spec_id_list
                    if(spec_id_list[vv.spec_id] == undefined){
                        spec_id_list[vv.spec_id] = [];
                    }
                    spec_id_list[vv.spec_id].push(vv.id);

                    //初始化cur_info.spec_id_list
                    if(cur_info.spec_id_list[vv.spec_id] == undefined){
                        cur_info.spec_id_list[vv.spec_id] = '';
                    }
					spec_div += '<span class="options spec_id_'+vv.spec_id+'" data-spec_id="'+vv.spec_id+'" data-spec_item_id="'+vv.id+'" ">'+vv.item+'</span>'; //onclick="change_spec_item(this);
                });
                spec_div += '</div>';
                $("#spec_list").append(spec_div);
            });
        }
        var num_div = '<div id="num" class="mt16"><i>数量：</i><span class="btn_num"><a href="javascript:void(0);" id="less" class="less"><img src="/static/mobile/images/btn_less.jpg"></a><span class="num">1</span><a href="javascript:void(0);" id="add" class="add"><img src="/static/mobile/images/btn_add.jpg"></a></span></div>';
        $("#spec_list").append(num_div);
    }

    //选择商品属性
    function fnGetSelected(){
        $('.options').each(function() {
            var $this=$(this);
            $this.click(function(){
                if(!!$(this).hasClass("cur")){
                    $(this).removeClass('cur');
                    $(this).find('.selected').remove();
                }else{
                    $(this).siblings(".options").removeClass('cur');
                    $(this).siblings(".options").find('.selected').remove();
                    $(this).addClass("cur");
                    $(this).append('<span class="selected"><img src="/static/mobile/images/icon_select.png"></span>');
                }
                //执行完样式赋值，再执行自定义函数
                change_spec_item($this);
            });
        });
    }


    //点击切换规格
    function change_spec_item(ths){
//		$this = $(ths);
		$this = ths;
        spec_id = $this.attr('data-spec_id');
        spec_item_id = $this.attr('data-spec_item_id');
        //修改值
		if(!!$this.hasClass("cur")){
		    //点击后有选中样式
            cur_info.spec_id_list[spec_id] = spec_item_id;
		} else {
            cur_info.spec_id_list[spec_id] = '';
		}
        checkChangeStoreCount();
    }

    function sortNumber(a,b) {
        return a - b
    }

	function checkChangeStoreCount() {
        var all_spec_selected = true;
        var keys_arr = [];
        $.each(cur_info.spec_id_list,function (key,val) {
            if(val.trim() == ""){
                all_spec_selected = false;
                return false;
            }
            keys_arr.push(val);
        });
        //清空数据
        $("input[name='item_id']").val("");
        cur_info.item_id = '';
        $("#selected_msg").text('');
//        $(".num").text(1);	//异常

        //考虑没有规格的商品
        price = $("#price");
        store_count = $("#store_count");
        if(!all_spec_selected ){
            store_count.text($("input[name='default_count']").val());
            price.text('￥'+$("input[name='default_price']").val());
		} else if(all_spec_selected) {
            //查找当前所选规格对应的库存
            keys_arr = keys_arr.sort(sortNumber);
            keys = keys_arr.join('_');
            $.each(spec_info,function (key,info) {
                if(keys == info.key){
                    store_count.text(info.store_count);
                    price.text('￥'+info.price);
                    cur_info.goods_id = info.goods_id;
                    cur_info.item_id = info.item_id;
                    cur_info.goods_num = $(".num").text();
                    $("#selected_msg").text('已选 ： '+info.key_name);
                    //直接购买赋值
                    $("input[name='item_id']").val(cur_info.item_id);
                    $("input[name='goods_num']").val(cur_info.goods_num);
                }
            });
		}
    }

    //添加购物车
    function addCart(){
        var is_data_ok = true;
        $.each(cur_info.spec_id_list,function (key,val) {
            if(val.trim() == ""){
                alert("请选择商品属性");
                is_data_ok = false;
                return false;
            }
        });
        cur_info.goods_id = $("input[name='goods_id']").val();
        cur_info.item_id = $("input[name='item_id']").val();
        cur_info.goods_num = $(".num").text();
        if(!is_data_ok) return false;
        $.ajax({
            type: "POST",
            url: "<?php echo url('mobile/Cart/ajaxAdd'); ?>",
            data: cur_info,
            dataType: "json",
            success: function(json){
                if(json.code == 0){
                    alert("已添加到购物车");
                } else if (json.code == 9999){
                    location.href = json.data.url;
                } else if(json.code > 0) {
                    alert("添加失败:"+json.msg);
                } else {
                    alert("操作失败，请稍后再试！");
				}
            }
        });
	}

    $(".buy").live('click',function () {
        goods_id = $("input[name='goods_id']").val();
        item_id = $("input[name='item_id']").val();
        goods_num = $(".num").text();
        $("input[name='goods_num']").val(goods_num);
        is_empty = JSON.stringify(cur_info.spec_id_list) == "{}";
        if((parseInt(goods_id) && parseInt(goods_num) && (is_empty || !is_empty && parseInt(item_id)))){
            $("#goods_form").submit();
//            $(this).preventDefault();
		} else {
            alert('请选择属性');
            return false;
		}
    });

    function init() {
		//初始化商品规格
        initSpecList();
    }

    $(function(){
        init();
        fnCountNum();
        fnGetSelected();
    });


	//监听返回按钮
    window.addEventListener('popstate', function (e) {
         alert('我监听到了浏览器的返回按钮事件啦'); // 根据自己的需求实现自己的功能
        //window.location.href = 'home.html';
        // history.go(0);
    }, false);

</script>
</body>
</html>
