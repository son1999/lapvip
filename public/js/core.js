/*-----------------------------*/
/*        SHOPPING CORE		   */
/*-----------------------------*/
var shop = {
  _store : {
    ajax: {},
    method: {},
    variable: {},
    cache:{},
    popup:{}
  }
};

/*-----------------------------*/
/*        AJAX - POST - GET	   */
/*-----------------------------*/

//them co che cache, chong duplicate option = {cache: 'cache_key', duplicate: false}
shop.ajax_popup = function(url, method, param, callback, option) {
    var opt = {
            loading: (shop.is_obj(option) && shop.is_func(option.loading)) ? option.loading : shop.show_loading,
            error: (shop.is_obj(option) && shop.is_func(option.error)) ? option.error : shop.hide_loading,
            cache: (shop.is_obj(option) && shop.is_exists(option.cache)) ? option.cache : '',
            duplicate: (shop.is_obj(option) && shop.is_exists(option.duplicate)) ? option.duplicate : false,
            baseUrl: (shop.is_obj(option) && shop.is_exists(option.baseUrl)) ? option.baseUrl : ENV.BASE_URL
        };
    if (!shop.is_exists(url) || (!opt.duplicate && shop.is_exists(shop._store.ajax[url]))) {
        return
    }
    if (shop.is_exists(callback) && shop.is_exists(opt.cache) && (opt.cache != '')) {
        var fromCache = shop.cache.get(opt.cache, false);
        if (fromCache) {
            callback(fromCache);
            return
        }
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': ENV.token
        }
    });
    $.ajax({
        beforeSend: function() {
            opt.loading();
            shop._store.ajax[url] = 1
        },
        url: opt.baseUrl + 'ajax/' + url,
        type: method ? method : 'POST',
        data: param,
        dataType: 'json',
        success: function(xhr) {
            delete shop._store.ajax[url];
            shop.hide_loading();
            if (xhr && shop.is_exists(xhr.intReturn)) {
                switch (xhr.intReturn) {
                    case -1:
                        alert(xhr.msg); // thong bao loi
                        break;
                    case 0:
                        alert(xhr.msg); //canh bao
                        break;
                    case 1:
                        alert(xhr.msg); // thong bao
                        break
                }
            }
            if (shop.is_exists(xhr.script)) {
                eval(xhr.script)
            }
            if (shop.is_exists(callback)) {
                if (shop.is_exists(opt.cache) && (opt.cache != '')) {
                    shop.cache.set(opt.cache, xhr)
                }
                callback(xhr)
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            delete shop._store.ajax[url];
            opt.error();
            if (shop.is_obj(shop.rootPanel) && shop.rootPanel.mode.debug == 1) {
                alert('Status:' + textStatus + '\n' + errorThrown)
            }
        }
    })
};


//loading float container
shop.show_loading = function(txt){txt=shop.is_str(txt)?txt:'Đang tải dữ liệu...';jQuery('.float_loading').remove();jQuery('body').append('<div class="float_loading">'+txt+'</div>');jQuery('.float_loading').fadeTo("fast",0.9);shop.update_position();jQuery(window).scroll(shop.updatePosition)};
shop.update_position = function(){if(shop.is_ie()){jQuery('.mine_float_loading').css('top',document.documentElement['scrollTop'])}};
shop.hide_loading = function(){jQuery('.float_loading').fadeTo("slow",0,function(){jQuery(this).remove()})};

/*-----------------------------*/
/*        CHECKING			   */
/*-----------------------------*/

shop.is_search = function(value) {return (value.match(/[^!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g))};

shop.is_arr = function(arr) { return (arr != null && arr.constructor == Array) };

shop.is_str = function(str) { return (str && (/string/).test(typeof str)) };

shop.is_func = function(func) { return (func != null && func.constructor == Function) };

shop.is_num = function(num) { var num = Number(num); return (num != null && !isNaN(num)) };

shop.is_int = function (x) {var y=parseInt(x);if (isNaN(y)) return false;return x==y && x.toString()==y.toString();}

shop.is_obj = function(obj) { return (obj != null && obj instanceof Object) };

shop.is_ele = function(ele) { return (ele && ele.tagName && ele.nodeType == 1) };

shop.is_exists = function(obj) { return (obj != null && obj != undefined && obj != "undefined") };

shop.is_json = function(){};

shop.is_blank = function(str) { return (shop.util_trim(str) == "") };

shop.is_phone = function(num) {return (/^(01([0-9]{2})|09[0-9]|08[0-9]|07[0-9]|03[0-9])(\d{7})$/i).test(num)};

shop.is_email = function(str) {return (/^[a-z-_0-9\.]+@[a-z-_=>0-9\.]+\.[a-z]{2,3}$/i).test(shop.util_trim(str))};

shop.is_username = function(value){ return (value.match(/^[0-9]/) == null) && (value.search(/^[0-9_a-zA-Z]*$/) > -1); }

shop.is_link = function(str){ return (/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/).test(shop.util_trim(str)) };

shop.is_image = function(imagePath){
  var fileType = imagePath.substring(imagePath.lastIndexOf("."),imagePath.length).toLowerCase();
  return (fileType == ".gif") || (fileType == ".jpg") || (fileType == ".png") || (fileType == ".jpeg");
};

shop.is_ff  = function(){ return (/Firefox/).test(navigator.userAgent) };

shop.is_ie  = function() { return (/MSIE/).test(navigator.userAgent) };

shop.is_ie6 = function() { return (/MSIE 6/).test(navigator.userAgent) };

shop.is_ie7 = function() { return (/MSIE 7/).test(navigator.userAgent) };

shop.is_ie8 = function() { return (/MSIE 8/).test(navigator.userAgent) };

shop.is_chrome = function(){ return (/Chrome/).test(navigator.userAgent) };

shop.is_opera = function() { return (/Opera/).test(navigator.userAgent) };

shop.is_safari = function(){ return (/Safari/).test(navigator.userAgent) };

/*-----------------------------*/
/*        CACHE & DATA  	   */
/*-----------------------------*/
shop.cache={get:function(key,def){if(shop.is_exists(shop._store.cache[key])){return shop._store.cache[key]}return shop.is_exists(def)?def:''},set:function(key,val){shop._store.cache[key]=val},del:function(key){shop._store.cache[key]=null;delete shop._store.cache[key]}};

/*-----------------------------*/
/*        WORKING COOKIE	   */
/*-----------------------------*/

shop.cookie = {
  mode:0, //0: default, 1: no COOKIE_ID
  set:function(name,value,expires,path,domain,secure){expires instanceof Date?expires=expires.toGMTString():typeof(expires)=='number'&&(expires=(new Date(+(new Date)+expires*1e3)).toGMTString());if(shop.cookie.mode){var r=[name+"="+escape(value)],s,i}else{var r=[COOKIE_ID+'_'+name+"="+escape(value)],s,i}if(domain==undefined&&DOMAIN_COOKIE_REG_VALUE>0){domain=DOMAIN_COOKIE_STRING}if(path==undefined){path='/'}for(i in s={expires:expires,path:path,domain:domain}){s[i]&&r.push(i+"="+s[i])}return secure&&r.push("secure"),document.cookie=r.join(";"),true},
  get:function(a){if(document.cookie.length>0){if(shop.cookie.mode==0){a=COOKIE_ID+'_'+a}c_start=document.cookie.indexOf(a+"=");if(c_start!=-1){c_start=c_start+a.length+1;c_end=document.cookie.indexOf(";",c_start);if(c_end==-1)c_end=document.cookie.length;return unescape(document.cookie.substring(c_start,c_end))}}return""}
};

/*-----------------------------*/
/*        TOOLS				   */
/*-----------------------------*/

/* function core connect */
String.prototype.E = function() {return shop.get_ele(this)}; // var obj = ('ads_zone2').E()

// join string to make theme
shop.join = function(b){var c=[b];return function extend(a){if(a!=null&&'string'==typeof a){c.push(a);return extend}return c.join('')}};

//auto increment
shop.nextNumber = (function(){var i=0;return function(){return++i}}());

shop.util_trim = function(str) {return (/string/).test(typeof str) ? str.replace(/^\s+|\s+$/g, "") : ""};

shop.util_random = function(a, b) { return Math.floor(Math.random() * (b - a + 1)) + a };

shop.get_ele = function(id) { return document.getElementById(id) };

shop.get_uuid = function() { return (new Date().getTime() + Math.random().toString().substring(2))};

shop.get_top_page = function(){if(shop.is_exists(window.pageYOffset)){return window.pageYOffset}if(shop.is_exists(document.compatMode)&&document.compatMode!='BackCompat'){return document.documentElement.scrollTop}if(shop.is_exists(document.body)){scrollPos=document.body.scrollTop}return 0};

//get all value of form
shop.get_form = function(a){var b=shop.get_ele(a);if(!shop.is_ele(b))return'';var c=[],inputs=b.getElementsByTagName("input");for(var i=0;i<inputs.length;i++){var d=inputs[i];if(d.type!='button'){c.push(d.name+"="+encodeURIComponent(d.value))}}var e=b.getElementsByTagName("select");for(var i=0;i<e.length;i++){var d=e[i],key=d.name,value=d.options[d.selectedIndex].value;c.push(key+"="+encodeURIComponent(value))}var f=b.getElementsByTagName("textarea");for(var i=0;i<f.length;i++){var d=f[i];c.push(d.name+"="+encodeURIComponent(d.value))}return c.join("&")};

/*-----------------------------*/
/*        EXTRA FUNCTIONS	   */
/*-----------------------------*/

//redirect to url
shop.redirect=function(url,is_new){if(url!=''){if(is_new){window.open(url)}else{window.location=url}}};

//reload page
shop.reload = function(){window.location.reload()};

//auto scroll to #point
shop.auto_scroll = function(anchor){var target=jQuery(anchor);target=target.length&&target||jQuery('[name='+anchor.slice(1)+']');if(target.length){var targetOffset=target.offset().top;jQuery('html,body').animate({scrollTop:targetOffset},1000);return false}return true};

//input number only
shop.numberOnly = function(myfield, e) {
    var key, keychar;
    if (window.event) {
        key = window.event.keyCode
    } else if (e) {
        key = e.which
    } else {
        return true
    }
    keychar = String.fromCharCode(key);
    if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
        return true
    } else if ((".0123456789").indexOf(keychar) > -1) {
        return true
    }
    return false
};

//go top button
shop.goTopStart = function(){jQuery('body').append('<a href="javascript:void(0)" onclick="jQuery(\'html,body\').animate({scrollTop: 0},1000);" class="go_top" style="display:none"></a>');jQuery(window).scroll(function(){var a=0;if(document.documentElement&&document.documentElement.clientHeight){a=document.documentElement.scrollTop}else if(document.body){a=document.body.scrollTop}if(a>0){if(shop.is_ie6()||shop.is_ie7()){a=a+jQuery(window).height()-30;jQuery('.go_top').css('top',a)}jQuery('.go_top').show()}else{jQuery('.go_top').hide()}})};

shop.enter = function(id,cb){if(cb){if(!shop.is_exists(shop._store.variable['key_listener'])){shop._store.variable['key_listener']=0}jQuery(id).keydown(function(event){if(event.keyCode==13){shop._store.variable['key_listener']=setTimeout(cb,10)}else{clearTimeout(shop._store.variable['key_listener'])}})}};

shop.numberFormat = function(number,decimals,dec_point,thousands_sep){var n=number,prec=decimals;n=!isFinite(+n)?0:+n;prec=!isFinite(+prec)?0:Math.abs(prec);var sep=(typeof thousands_sep=="undefined")?'.':thousands_sep;var dec=(typeof dec_point=="undefined")?',':dec_point;var s=(prec>0)?n.toFixed(prec):Math.round(n).toFixed(prec);var abs=Math.abs(n).toFixed(prec);var _,i;if(abs>=1000){_=abs.split(/\D/);i=_[0].length%3||3;_[0]=s.slice(0,i+(n<0))+_[0].slice(i).replace(/(\d{3})/g,sep+'$1');s=_.join(dec)}else{s=s.replace(',',dec)}return s};

shop.selectAllText = function(o){o.focus();o.select()};

//for debug
shop.debug_contain = 'main.main .container-fluid';
shop.debug=function(a){var mid=jQuery(shop.debug_contain);if(mid.length){mid.append(prettyPrint(a))}else{jQuery('body').append(prettyPrint(a))}};

//auto run
shop.isAdminUrl = function () { return ENV.isAdminUrl == '1' };
shop.ready={func:{'web':[],'admin':[]},add:function(cb,admin){if(admin){shop.ready.func.admin[shop.ready.func.admin.length]=cb}else{shop.ready.func.web[shop.ready.func.web.length]=cb}},run:function(){if(shop.isAdminUrl()){if(shop.ready.func.admin.length>0){for(var i in shop.ready.func.admin){shop.ready.func.admin[i]()}}}else{if(shop.ready.func.web.length>0){for(var i in shop.ready.func.web){shop.ready.func.web[i]()}}}}};

shop.updateParameter = function(url, param, paramVal)
{
    var TheAnchor = null;
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";

    if (additionalURL)
    {
        var tmpAnchor = additionalURL.split("#");
        var TheParams = tmpAnchor[0];
        TheAnchor = tmpAnchor[1];
        if(TheAnchor)
            additionalURL = TheParams;

        tempArray = additionalURL.split("&");

        for (var i=0; i<tempArray.length; i++)
        {
            if(tempArray[i].split('=')[0] != param)
            {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    else
    {
        var tmpAnchor = baseURL.split("#");
        var TheParams = tmpAnchor[0];
        TheAnchor  = tmpAnchor[1];

        if(TheParams)
            baseURL = TheParams;
    }

    if(TheAnchor)
        paramVal += "#" + TheAnchor;

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
};

shop.setGetParameter = function(paramName, paramValue){
    var url = window.location.href;
    var hash = location.hash;
    url = url.replace(hash, '');
    if(Array.isArray(paramName) && Array.isArray(paramValue)) {
        paramName.forEach(function(item,index) {
            if (url.indexOf(item + "=") >= 0)
            {
                var prefix = url.substring(0, url.indexOf(item));
                var suffix = url.substring(url.indexOf(item));
                suffix = suffix.substring(suffix.indexOf("=") + 1);
                suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
                url = prefix + item + "=" + paramValue[index] + suffix;
            }
            else
            {
                if (url.indexOf("?") < 0)
                    url += "?" + item + "=" + paramValue[index];
                else
                    url += "&" + item + "=" + paramValue[index];
            }
        });
        window.location.href = url + hash;
        return;
    }

    if (url.indexOf(paramName + "=") >= 0)
    {
        var prefix = url.substring(0, url.indexOf(paramName));
        var suffix = url.substring(url.indexOf(paramName));
        suffix = suffix.substring(suffix.indexOf("=") + 1);
        suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
        url = prefix + paramName + "=" + paramValue + suffix;
    }
    else
    {
        if (url.indexOf("?") < 0)
            url += "?" + paramName + "=" + paramValue;
        else
            url += "&" + paramName + "=" + paramValue;
    }
    window.location.href = url + hash;
};