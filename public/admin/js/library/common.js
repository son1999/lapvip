/*by @hoangnm89 */
var arrKeyCode = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13,
    SHIFT: 16,
    CTRL: 17,
    ESCAPE: 27,
    SPACE: 32,
    PAGE_UP: 33,
    PAGE_DOWN: 34,
    END: 35,
    HOME: 36,
    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40,
    DELETE: 46,
    NUMPAD_MULTIPLY: 106,
    NUMPAD_ADD: 107,
    NUMPAD_ENTER: 108,
    NUMPAD_SUBTRACT: 109,
    NUMPAD_DECIMAL: 110,
    NUMPAD_DIVIDE: 111,
    PERIOD: 190,
    COMMA: 188
};
Number.prototype.formatMoney = function (c, d, t) {
    //(123456789.12345).formatMoney(2, ',', '.');
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
var delaySomethings = (function () {
    var timeDelay = 0;
    return function (callback, ms) {
        clearTimeout(timeDelay);
        timeDelay = setTimeout(callback, ms);
    }
})();

jQuery(document).ready(function ($) {

    /* xxxDropdown */

    $.fn.subOverString = function (opt) {
        return this.each(function () {
            var opts = $.extend({
                linkMore: ""
            }, opt);
            var $this = $(this);
            var txtContent = "";
            if ($this.data("text-content") == undefined) {
                txtContent = $.trim($(this).text());
                $this.data("text-content", txtContent);
            } else {
                txtContent = $this.data("text-content");
            }
            var arrTxtContent = txtContent.split(" ");
            var tmpText = "<span style='position: relative'>" + arrTxtContent.join("</span> <span style='position: relative'>") + "</span>";
            $this.css("position", "relative");
            $this.html("<span id='sys_tmp_subOverString'>" + tmpText + "... <a id='sys_linkMore_subOverString' style='position: relative' href='" + opts.linkMore + "'>Xem thêm.</a></span>");
            if ($("#sys_tmp_subOverString").height() > $(this).height()) {
                var sys_linkMore_subOverString = $("#sys_linkMore_subOverString");
                while ((sys_linkMore_subOverString.height() + sys_linkMore_subOverString.position().top) > $this.height()) {
                    sys_linkMore_subOverString.prev().remove();
                    arrTxtContent.pop();
                }
                arrTxtContent = arrTxtContent.join(" ");
                $this.css("position", "").html(arrTxtContent + "... <a href='" + opts.linkMore + "'>Xem thêm</a>");
            } else {
                $this.css("position", "").html(txtContent);
            }
        });
    };
    $.fn.xxxDropdown = function (opt) {
        return this.each(function () {
            var opts = $.extend({
                afterSelect: function afSelect() {
                    console.log("Selected");
                }
            }, opt);
            var selectElem = $(this).children("select").first();
            //Bind event change on select tag
            selectElem.on("change", function () {
                selectElem.parents(".xxxDropdown").find(".show-val").children("span").html(selectElem.children(":selected").html());
                if ($.isFunction(opts.afterSelect)) {
                    selectElem.parents(".xxxDropdown").find(".show-val").children("span").addClass('active');
                    opts.afterSelect.call();
                }
            });
        });
    };


    $(".sys_tabbable").on("click", ".t-lbl", function (e) {
        var getIdx = $(this).index();
        $(this).addClass("active").siblings().removeClass("active").parents(".sys_tabbable").find(".tab-content-item").removeClass("active").eq(getIdx).addClass("active");
        //return false;
    });
});

var ImageStorage = {
    conf: {
        quantity: 100,
        sever: 'https://static11.muachungcdn.com/',
        name_space_seo: 'i:chonmon',
        name_space: 'chonmon'
    },
    buildUrlImageOriginal: function buildUrlImage(name_image, seo) {
        name_image = typeof name_image !== 'undefined' ? name_image : '';
        seo = typeof seo !== 'undefined' ? seo : 0;
        var name_space = '';
        if (seo) {
            name_space = ImageStorage.conf.name_space_seo;
        } else {
            name_space = ImageStorage.conf.name_space;
        }
        var url = '';
        if (name_image != '') {
            url = ImageStorage.conf.sever + 'original/' + name_space + '/' + name_image;
        }
        return url;
    },
    buildUrlImageThumb: function buildUrlImage(name_image, thumb, seo, quality) {
        name_image = typeof name_image !== 'undefined' ? name_image : '';
        thumb = typeof thumb !== 'undefined' ? thumb : '50_50';
        seo = typeof seo !== 'undefined' ? seo : 0;
        quality = typeof quality !== 'undefined' ? quality : 0;
        if (quality == 0) {
            quality = ImageStorage.conf.quantity;
        }
        var name_space = '';
        if (seo) {
            name_space = ImageStorage.conf.name_space_seo;
        } else {
            name_space = ImageStorage.conf.name_space;
        }
        var url = '';
        if (name_image != '') {
            url = ImageStorage.conf.sever + 'thumb,' + quality + '/' + thumb + '/' + name_space + '/' + name_image;
        }
        return url;
    },
    buildUrlImageThumbW: function buildUrlImage(name_image, thumb_w, seo, quality) {
        name_image = typeof name_image !== 'undefined' ? name_image : '';
        thumb_w = typeof thumb_w !== 'undefined' ? thumb_w : 50;
        seo = typeof seo !== 'undefined' ? seo : 0;
        quality = typeof quality !== 'undefined' ? quality : 0;
        if (quality == 0) {
            quality = ImageStorage.conf.quantity;
        }
        var name_space = '';
        if (seo) {
            name_space = ImageStorage.conf.name_space_seo;
        } else {
            name_space = ImageStorage.conf.name_space;
        }
        var url = '';
        if (name_image != '') {
            url = ImageStorage.conf.sever + 'thumb_wl,' + quality + '/' + thumb_w + '/' + name_space + '/' + name_image;
        }
        return url;
    },
    buildUrlImageThumbH: function buildUrlImage(name_image, thumb_h, seo, quality) {
        name_image = typeof name_image !== 'undefined' ? name_image : '';
        thumb_h = typeof thumb_h !== 'undefined' ? thumb_h : 50;
        seo = typeof seo !== 'undefined' ? seo : 0;
        quality = typeof quality !== 'undefined' ? quality : 0;
        if (quality == 0) {
            quality = ImageStorage.conf.quantity;
        }
        var name_space = '';
        if (seo) {
            name_space = ImageStorage.conf.name_space_seo;
        } else {
            name_space = ImageStorage.conf.name_space;
        }
        var url = '';
        if (name_image != '') {
            url = ImageStorage.conf.sever + 'thumb_h,' + quality + '/' + thumb_h + '/' + name_space + '/' + name_image;
        }
        return url;
    },
    buildNameImg:function(img, title)
    {
        img = typeof img !== 'undefined' && img !== null ? img : '';
        title = typeof title !== 'undefined' && title !== null ? title : '';
        title = Common.safeTitle(title);
        if(img != ''){
            img = img.replace('.jpg', '/' + title + '.jpg').replace('.png', '/' + title + '.png').replace('.jpeg', '/' + title + '.jpeg');
        }
        return img;

    }
}

var Common = {
    safeTitle: function (strInput) {
        strInput = typeof strInput !== 'undefined' && strInput !== null ? strInput : '';
        if(strInput != ''){
            strInput = strInput.toLowerCase();
            strInput = strInput.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
            strInput = strInput.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
            strInput = strInput.replace(/ì|í|ị|ỉ|ĩ/g, "i");
            strInput = strInput.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
            strInput = strInput.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
            strInput = strInput.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
            strInput = strInput.replace(/đ/g, "d");
            strInput = strInput.replace(/!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'|\"|\&|\#|\[|\]|~/g, "");
            strInput = strInput.replace(/\_/g, '-');
            strInput = strInput.replace(/\s+/g, '-');
            strInput = strInput.replace(/^\-+|\-+$/g, "");//cắt bỏ ký tự - ở đầu và cuối chuỗi
        }
        return strInput;
    }
}