shop.login = function () {
    var email = $('#pop-email-login'),
        password = $('#pop-pw-login');
    if(shop.is_email(email.val())){
        if(password.val() != ''){
            shop.ajax_popup('login', 'post', {
                email: $.trim(email.val()),
                password: $.trim(password.val())
            }, function(json) {
                if(json.error == 1){
                    alert(shop.authMsg(json.code));
                }else {
                    shop.reload();
                    // shop.popup.hide('loginModal', function () {
                    //     shop.redirect(data.data['url']);
                    // });
                }
            });
        }else{
            alert('Vui lòng nhập mật khẩu');
            password.focus();
        }
    }else {
        alert('Email không chính xác');
        phone.focus();
    }
};

shop.register = function () {
    var name = $('#pop_name'),
        email = $('#pop_email'),
        password = $('#pop-pw'),
        password_confirm = $('#pop-pw-rp'),
        provinces = $('#provinces'),
        districts = $('#districts'),
        icheck = $('#icheck');
    if(shop.is_email(email.val())){
        if(password.val() != ''){
            if(password.val().length >= 6) {
                if (password.val() == password_confirm.val()) {
                    shop.ajax_popup('register', 'post', {
                        name: $.trim(name.val()),
                        email: $.trim(email.val()),
                        password: $.trim(password.val()),
                        password_confirmation: $.trim(password_confirm.val()),
                        provinces : $.trim(provinces.val()),
                        districts : $.trim(districts.val()),
                        icheck : $.trim(icheck.val())
                    }, function (json) {
                        if (json.error == 1) {
                            var msg = shop.authMsg(json.code);
                            if(msg == '') {
                                for(var i in json.code){
                                    msg += json.code[i] + '\n';
                                }
                            }
                            alert(msg);
                        } else {
                            shop.redirect(json.data['url']);
                        }
                    });
                } else {
                    alert('Xác thực mật khẩu không khớp');
                    password_confirm.focus();
                }
            }else{
                alert('Mật khẩu phải có tối thiểu 6 kí tự');
                password.focus();
            }
        }else{
            alert('Vui lòng nhập mật khẩu');
            password.focus();
        }
    }else {
        alert('Email không hợp lệ');
        phone.focus();
    }
};

shop.changeCustomerPassword = function(){
    var curPass = $('#current_password'),
         newPass = $('#new_password'),
         rePass = $('#re_password');
 
     $('.invalid-feedback').html('');
     $('.is-invalid').removeClass('is-invalid');
 
     //check old pass

     if (shop.is_blank($.trim(curPass.val()))) {
         // nếu chưa nhâp thì vào đây
         curPass.addClass('is-invalid');
         $('.invalid-feedback', curPass.parent()).html('Vui lòng nhập mật khẩu cũ');
         return;
     }
 
     //check new pass
     if (shop.is_blank($.trim(newPass.val()))) {
         newPass.addClass('is-invalid');
         $('.invalid-feedback', newPass.parent()).html('Vui lòng nhập mật khẩu mới');
         return;
     } else if (newPass.val().length < 8) {
         newPass.addClass('is-invalid');
         $('.invalid-feedback', newPass.parent()).html('Mật khẩu mới phải có 8 kí tự trở lên');
         return;
     } else if (newPass.val() == curPass.val()) {
         newPass.addClass('is-invalid');
         $('.invalid-feedback', newPass.parent()).html('Mật khẩu mới phải khác mật khẩu cũ');
         return;
     }
 
     //check retype pass
     if (shop.is_blank($.trim(rePass.val()))) {
         rePass.addClass('is-invalid');
         $('.invalid-feedback', rePass.parent()).html('Vui lòng nhập lại mật khẩu mới');
         return;
     } else if (newPass.val() != rePass.val()) {
         rePass.addClass('is-invalid');
         $('.invalid-feedback', rePass.parent()).html('Nhập lại mật khẩu mới không khớp');
         return;
     }
 
     shop.ajax_popup('change-password', 'post', {
         oldPassword: $.trim(curPass.val()),
         newPassword: $.trim(newPass.val()),
     }, function(json) {
         $('.invalid-feedback').html('');
         if (json.error == 1) {
             var msg = shop.authMsg(json.code);
             if(msg == '') {
                 for(var i in json.code){
                     msg += json.code[i] + '\n';
                 }
             }
             alert(msg);
         } else {
             Swal.fire({
                 type: 'success',
                 title: 'Thành Công',
                 text: 'Bạn sẽ được Logout về Trang Chủ!',
                 inputAttributes: {
                     autocapitalize: 'off'
                 },
                 confirmButtonText: 'ok',
                 showLoaderOnConfirm: true,
                 preConfirm: (login) => {
                     shop.redirect(json.data['url']);
                 },
             });
         }
     });
 }
 
shop.authMsg = function ($code) {
    switch ($code){
        case 'LOGIN_FAIL': return 'Sai tên đăng nhập hoặc mật khẩu';
        case 'BANNED': return 'Tài khoản đã bị vô hiệu, không thể đăng nhập';
        case 'NOT_ACTIVE': return 'Tài khoản chưa được kích hoạt';
        case 'NOT_EXISTED': return 'Email không hợp lệ';
        case 'LOGINED': return 'Đã đăng nhập thành công trước đó';
        case 'EXISTED': return 'Email không hợp lệ';
    }
    return '';
};
shop.getDataByID = function(id){
    shop.ajax_popup('getDataP', 'post', {id:id}, function(json) {
        if(json.error == 1){
            $.alertable.alert(json.msg);
        }else {
            $('#product_name_sub').html(json.data.product.title_sub)
            if (json.data.product.out_of_stock == 0){
                $('#price').html('<span class="price-discount text-danger">'+shop.numberFormat(json.data.product.price)+' đ </span>');
                $('#priceStrike').html('<span class="price-old">'+shop.numberFormat(json.data.product.priceStrike)+' đ </span>');
            }else{
                $('#out_of_stock').html('<span class="price-discount text-danger"> Liên hệ </span>');
            }
            if(json.data.prd_have_sale){
                var data_prd = JSON.parse(json.data.prd_have_sale);
                var data_prd_count = 0;
                var html_prd = '';
                var html_prd_mobile = '';
                var html_prd_detail = '';
                $.each(data_prd, function (key, value) {
                    data_prd_count += value.props.length;
                    if (data_prd_count < 6){
                        $.each(value.props, function (key_props, value_props) {
                            html_prd += '<div class="w-100 item-desc">' +
                                '           <b>'+value_props.title+' : </b>' +
                                '           <span>'+value_props.value+'</span>' +
                                '       </div>'
                            html_prd_mobile += '<div class="w-100">' +
                                '           <b>'+value_props.title+' : </b>' +
                                '           <span>'+value_props.value+'</span>' +
                                '       </div>'
                        })
                    }
                    var html_de = '';
                    $.each(value.props, function (key_props, value_props) {
                        html_de += '<li>' +
                            '          <label data-id="49">'+value_props.title+':</label>' +
                            '          <span><a href="javascript:;">'+value_props.value+'</a></span>' +
                            '      </li>'
                    })
                    html_prd_detail += ' <ul class="fs-dttsktul list-unstyled" style="max-width : 100%;" >' +
                        '                    <li class="modal-specifications-title">'+value.title+'</li>' +
                        ''+html_de+''+
                        '               </ul>'
                })
                $('#specifications_top').html(html_prd);
                $('#specifications_mobile').html(html_prd_mobile);
                $('#specifications').html(html_prd_mobile);
                $('#specifications_detail').html(html_prd_detail);
                console.log(data_prd)
            }


            console.log(json.data)
        }
    });
}

shop.get_district = function(id,callback) {
    shop.ajax_popup('list-districts', 'post', {province_id:id}, function(json) {
        if(json.error == 1){
            $.alertable.alert(json.msg);
        }else {
            var i;
            var html = shop.join('<option value="">--'+ 'Chọn Quận/Huyện' +'--</option>')();
            for(i in json.data){
                html += shop.join('<option value="'+json.data[i].id+'">'+json.data[i].Name_VI+'</option>')();
            }

            $('#selectDistrict').html(html);
            if (shop.is_exists(callback)) {
                callback();
            }
        }
    });
};

shop.get_ward = function(id,callback) {
    shop.ajax_popup('list-ward', 'post', {district_id:id}, function(json) {
        if(json.error == 1){
            $.alertable.alert(json.msg);
        }else {
            var i;
            var html = shop.join('<option value="">--'+ 'Chọn Xã/Phường' +'--</option>')();
            for(i in json.data){
                html += shop.join('<option value="'+json.data[i].id+'">'+json.data[i].Name_VI+'</option>')();
            }

            $('#selectWard').html(html);
            if (shop.is_exists(callback)) {
                callback();
            }
        }
    });
};

shop.search = function () {
    var sh = $('#search');
    if (shop.is_search(sh.val())){
        if(sh.val() != ''){
            shop.ajax_popup('searchForm', 'post', {
                search: $.trim(sh.val()),
            }, function(json) {
                if (json.error == 1) {
                    var msg = shop.authMsg(json.code);
                    if(msg == '') {
                        for(var i in json.code){
                            msg += json.code[i] + '\n';
                        }
                    }
                    alert(msg);
                } else {
                    shop.redirect(json.data['url']);
                }
            });
        }else{
            alert('Bạn không nhập gì vào thì mình tìm thế lìn nào được! bạn óc chó vkl');
            sh.focus();
        }
    }else {
        alert('Chuỗi bạn nhập chứa ký tự đặc biệt');
        sh.focus();
    }
};

shop.commentproduct = function(){
    var name = $('#name'),
        type_id = $('#type_id'),
        rate = $('#rating'),
        content = $('#txtNoteRating');
    if(rate.val() != '') {
        if (content.val() != '') {
            if (content.val().length >= 0) {
                shop.ajax_popup('comment', 'post', {
                    name: $.trim(name.val()),
                    rate: $.trim(rate.val()),
                    type_id: $.trim(type_id.val()),
                    content: $.trim(content.val()),
                }, function (json) {
                    if (json.error == 1) {
                        var msg = shop.authMsg(json.code);
                        if (msg == '') {
                            for (var i in json.code) {
                                msg += json.code[i] + '\n';
                            }
                        }
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: msg,
                        });
                    }else {
                        Swal.fire({
                            type: 'success',
                            title: 'Success',
                            text: 'LAPVIP xin được cám ơn những đánh giá vào nhận xét của bạn'
                        });
                        name.val('');
                        rate.val('');
                        content.val('');
                    }
                });
            }else{
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Nội dụng đánh giá quá ngắn',
                });
                content.focus();
            }
        }else{
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Bạn không được để trống phần nội dung',
            });
            content.focus();
        }
    }else{
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'Bạn chưa đánh giá cho sản phẩm này',
        });
        rate.focus();
    }

};
// {{route('save.saveInfo', ['alias' => str_slug($data->title), '_token' => request()->_token, 'id' => request()->id, 'filter_key' => request()->filter_key, 'quan'=>request()->quan])}}

shop.getURL = function getQueryVariable(variable)
{
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if(pair[0] == variable){return pair[1];}
    }
    return(false);
};

shop.addCart = function (id) {
    shop.ajax_popup('cart-add', 'post', {_token:ENV.token, id:id, quan: 1}, function(json) {
        if(json.error == 1){
            Swal.fire({
                title: 'Thông báo',
                text: json.msg,
                type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
            });
        }else {
            $('.counter-cart').html(json.data.number);
            Swal.fire({
                title: 'Thông báo',
                text: 'Thêm vào giỏ hàng thành công',
                type: 'success',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
            }).then((result) => {
                shop.redirect(json.data['url']);
            });
        }
    });
}



