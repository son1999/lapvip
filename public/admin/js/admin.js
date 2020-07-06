 shop.admin = {
     previewVideo: function(id) {
         var youtubeid = '';
         if (shop.is_link(id)){
             youtubeid = id.match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/embed\/|\/)([^\s&|"]+)/)[1];
         }else{
             youtubeid = id;
         }

         if(youtubeid != null) {
             $('#youtube_id').val(youtubeid);
             return $('#preview_videos').attr('src', 'https://www.youtube.com/embed/' + youtubeid).parent().addClass('d-block');
         }

     },
    showChangePasswordForm: function() {
        var html = shop.join('<form class="form-horizontal" name="changePassword">')
            ('<div class="form-group row">')
                ('<label class="col-md-3 form-control-label" for="current_password">Mật khẩu cũ</label>')
                ('<div class="col-md-9">')
                    ('<input type="password" id="current_password" name="current_password" class="form-control" placeholder="Mật khẩu hiện tại...">')
                    ('<span class="invalid-feedback"></span>')
                ('</div>')
            ('</div>')
            ('<div class="form-group row">')
                ('<label class="col-md-3 form-control-label" for="new_password">Mật khẩu mới</label>')
                ('<div class="col-md-9">')
                    ('<input type="password" id="new_password" name="new_password" class="form-control" placeholder="Mật khẩu mới...">')
                    ('<span class="invalid-feedback"></span>')
                ('</div>')
            ('</div>')
            ('<div class="form-group row">')
                ('<label class="col-md-3 form-control-label" for="re_password">Nhập lại</label>')
                ('<div class="col-md-9">')
                    ('<input type="password" id="re_password" name="re_password" class="form-control" placeholder="Nhập lại mật khẩu mới...">')
                    ('<span class="invalid-feedback"></span>')
                ('</div>')
            ('</div>')
        ('</form>')();

        if($('#changePassword').length <= 0){
            html = shop.join
            ('<div class="modal" id="changePassword">')
                ('<div class="modal-dialog">')
                    ('<div class="modal-content">')
                        ('<div class="modal-header">')
                            ('<h4 class="modal-title">Thay đổi mật khẩu</h4>')
                            ('<button type="button" class="close" data-dismiss="modal">&times;</button>')
                        ('</div>')
                        ('<div class="modal-body">'+html+'</div>')
                        ('<div class="modal-footer">')
                            ('<button type="button" class="btn btn-success" onclick="shop.admin.changePassword()">Cập nhật</button>')
                            ('<button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>')
                        ('</div>')
                    ('</div>')
                ('</div>')
            ('</div>');
            $('body').append(html);
        }
        $('#changePassword').modal();
    },
    changePassword:function(){
        var obj = $('#changePassword'),
            curPass = $('#current_password', obj),
            newPass = $('#new_password', obj),
            rePass = $('#re_password', obj);

        $('.invalid-feedback').html('');
        $('.is-invalid').removeClass('is-invalid');

        //check old pass
        if (shop.is_blank($.trim(curPass.val()))) {
            curPass.addClass('is-invalid');
            $('.invalid-feedback', curPass.parent()).html('Vui lòng nhập mật khẩu cũ');
            return;
        }

        //check new pass
        if (shop.is_blank($.trim(newPass.val()))) {
            newPass.addClass('is-invalid');
            $('.invalid-feedback', newPass.parent()).html('Vui lòng nhập mật khẩu mới');
            return;
        } else if (newPass.val().length < 6) {
            newPass.addClass('is-invalid');
            $('.invalid-feedback', newPass.parent()).html('Mật khẩu mới phải có 6 kí tự trở lên');
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

        shop.ajax_popup('user/change-password', 'post', {
            old: $.trim(curPass.val()),
            new: $.trim(newPass.val())
        }, function(data) {
            $('.invalid-feedback').html('');
            $('.is-invalid').removeClass('is-invalid');
            if (data.error) {
                var getObject;
                switch (data.code) {
                    case 1:
                        getObject = curPass;
                        break;
                    case 2:
                        getObject = newPass;
                        break;
                }
                getObject.addClass('is-invalid');
                $('.invalid-feedback', getObject.parent()).html(data.msg);
            } else {
                $('#changePassword').modal('hide');
                alert(data.msg);
                shop.redirect(data.data['url']);
            }
        });
    },
    actived: function() {
        setTimeout(function () {shop.ajax_popup('user/actived', 'GET')}, 1000);
    },
    activeUser: function(id, status){
        if(confirm('Bạn muốn ' + (status == 0 ? 'bỏ ':'') + 'kích hoạt với người dùng này ?')){
            shop.ajax_popup('user/user-active', 'POST', {id: id, status: status}, function(json){
                if(json.error == 0) {
                    shop.reload();
                }else{
                    alert(json.msg);
                }
            });
        }
    },
    getCat:function(type, def, lang, container){
        shop.ajax_popup('category/fetch-cat-lang', 'POST', {type: type, def: def, lang:lang}, function(json){
            if(json.error == 0) {
                $(container).html(json.data);
            }else{
                alert(json.msg);
            }
        });
    },
    setHot:function(id, show, type){
        shop.ajax_popup(type+'/hot', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
     setVideoTop:function(id, show, type){
        shop.ajax_popup(type+'/is_top', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    copy:function(id, type){
        if(confirm('Bạn muốn copy nội dung?')) {
            shop.ajax_popup(type + '/copy', 'POST', {id: id}, function (json) {
                if (json.error == 0) {
                    shop.reload();
                } else {
                    alert(json.msg);
                }
            });
        }
    },
    updateStatus:function(id, show, type){
        shop.ajax_popup(type+'/status', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    updateHome:function(id, show, type){
        shop.ajax_popup(type+'/showHome', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
     updateFar:function(id, show, type){
        shop.ajax_popup(type+'/updateFar', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },

    updateStatusBanner:function(id, show, type){
        shop.ajax_popup(type+'/status-banner', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    updateHotNews:function(id, show, type){
        shop.ajax_popup(type+'/hotnews', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    updateListHot:function(id, show, type){
        shop.ajax_popup(type+'/listhot', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    updateStatusComment:function(id, show, type){
        shop.ajax_popup(type+'/status_comment', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    updateStatusWarehouse:function(id, show, type){
        shop.ajax_popup(type+'/status_warehouse', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    replyCommentProducts:function(parent_id, data, post_id, type){
        shop.ajax_popup(type+'/reply-cmt', 'POST', {parent_id: parent_id, data: data, post_id: post_id}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    getProvince:function(type){
        shop.ajax_popup(type+'/get-province', 'GET', function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    replyCommentNews:function(id, data,  type_id, type){
        shop.ajax_popup(type+'/reply-cmt-news', 'POST', {parent_id: id, data: data, type_id: type_id}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    editCommentNews:function(id, data, type){
        console.log(data);
        shop.ajax_popup(type+'/edit-cmt-news', 'POST', {id: id, dat: data}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    showquestion:function(id, show, type){
        shop.ajax_popup(type+'/status_question', 'POST', {id: id, show: show ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    answer:function(id, proid, data, type){
        shop.ajax_popup(type+'/answer-question', 'POST', {id: id, proid: proid ,data: data}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    answerQuestion:function(id, data, type){
        shop.ajax_popup(type+'/answer-question-installment', 'POST', {id: id ,data: data}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    setSelling:function(id, set, type){
        shop.ajax_popup(type+'/selling', 'POST', {id: id, set: set ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    setHeightClass:function(id, set, type){
        shop.ajax_popup(type+'/heightClass', 'POST', {id: id, set: set ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    setOutOfStock:function(id, set, type){
        shop.ajax_popup(type+'/out_of_stock', 'POST', {id: id, set: set ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },

    celebrities:function(){
        // var obj = $('#celebrities'),
        var object_id = $('#gallery-object_id_cele').val();
        var name = $('#name').val();
        var fd = new FormData();
        var files = $('#img')[0].files[0];
        fd.append('img',files);
        fd.append('object_id', object_id);
        fd.append('name', name);
        fd.append('token', ENV.token)
        var data = fd;//.serialize()
        console.log();
        $.ajax({
            type: 'POST',
            url: ENV.BASE_URL + "ajax/story_wt/add_celebrities",
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){

            }
        }).done(function(json) {
            if(json.error == 0) {
                $('input[name="name"]').val('');
                $('input[name="img"]').val('');
                alert('Thành công');
                app3.loadCele();
            }else {
                alert(json.msg);
            }
        })
    },
    Createduplicate: function (id, type) {
        if(confirm('Bạn muốn copy nội dung?')) {
            shop.ajax_popup(type + '/duplicate', 'POST', {id: id}, function (json) {
                if (json.error == 0) {
                    shop.redirect(json.data['url']);
                } else {
                    alert(json.msg);
                }
            });
        }
    },
    Processing: function(id,status, type){
        shop.ajax_popup(type+'/updateProcessing', 'POST', {id: id, status: status ? 1 : 0}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    Progress: function(id,status, type){
        shop.ajax_popup(type+'/updateProgress', 'POST', {id: id, status: status ? 2 : 1}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },
    Finish: function(id,status, type){
        shop.ajax_popup(type+'/updateFinish', 'POST', {id: id, status: status ? 3 : 2}, function(json){
            if(json.error == 0) {
                shop.reload();
            }else{
                alert(json.msg);
            }
        });
    },


    system:{
        ckEditor: function (ele,width,height,theme,toolbar, css,id_img_btn) {
            css = css ? css : (ENV.BASE_URL + 'css/style_editor.css?v=1');
            var instance_ck = CKEDITOR.replace(ele ,
                {
                    toolbar : toolbar,
                    width: width,
                    height: height,
                    language : 'vi',
                    contentsCss: css,
                    allowedContent: true
                });
            instance_ck.addCommand("mySimpleCommand", {
                exec: function(edt) {
                    var abc = $('#uploadifive-'+id_img_btn+' input');
                    if(typeof abc != 'undefined') {
                        $(abc[abc.length - 1]).click();
                    }
                }
            });
            instance_ck.ui.addButton('SuperButton', {
                type: 'button',
                label: "Upload ảnh lên chèn vào nội dung",
                command: 'mySimpleCommand',
                // toolbar: 'insert',
                icon: 'plugins/iconfinder_image_272698.png',
            });
        }
    },
    tags:{
        init_more:function (container){
            $(container).tagEditor({
                initialTags: $(container).val().split(','),
                sortable: false,
                forceLowercase: false,
                placeholder: '',
                onChange: function (field, editor, tags) {
                    $(field).val(tags.length ? tags.join(',') : '');
                }

            });
        },
        init: function(type, container, id, suggest, no_load_more){
            if(suggest || no_load_more) {
                $(container).tagEditor({
                    initialTags: $(container).val().split(','),
                    autocomplete: {
                        delay: 0, // show suggestions immediately
                        position: {collision: 'flip'}, // automatic menu position up/down
                        source: suggest ? suggest : []
                    },
                    sortable: false,
                    forceLowercase: false,
                    placeholder: '',
                    onChange: function (field, editor, tags) {
                        $(field).val(tags.length ? tags.join(',') : '');
                    },
                    beforeTagSave: function (field, editor, tags, tag, val) {
                        shop.admin.tags.add(val, type);
                    },
                    beforeTagDelete: function (field, editor, tags, val) {
                        var q = confirm('Xóa tag "' + val + '"?');
                        if (q) {
                            shop.admin.tags.remove(val, type, id);
                        }
                        return q;
                    }
                });
            }else{
                shop.admin.tags.loadSuggest(type, container, id);
            }
        },
        loadSuggest: function(type, container, id){
            shop.ajax_popup('tag/tag-suggest', 'POST', {type: type}, function(json){
                if(json.error == 0) {
                    shop.admin.tags.init(type, container, id, json.data, true);
                }else{
                    alert(json.msg);
                }
            });
        },
        add: function(tag, type){
            shop.ajax_popup('tag/tag-add', 'POST', {tag: tag, type: type}, function(json){
                if(json.error != 0) {
                    alert(json.msg);
                }
            });
        },
        remove: function(tag, type, id){
            shop.ajax_popup('tag/tag-del', 'POST', {tag: tag, type: type, id: id}, function(json){
                if(json.error != 0) {
                    alert(json.msg);
                }
            });
        }
    },
    filters:{
        init_more:function (container){
            $(container).tagEditor({
                initialTags: $(container).val().split(','),
                sortable: false,
                forceLowercase: false,
                placeholder: '',
                onChange: function (field, editor, tags) {
                    $(field).val(tags.length ? tags.join(',') : '');
                }

            });
        },
        init: function(type, container, id, suggest, no_load_more,max_tag){
            if(suggest || no_load_more) {
                $(container).tagEditor({
                    initialTags: $(container).val().split(','),
                    autocomplete: {
                        delay: 0, // show suggestions immediately
                        position: {collision: 'flip'}, // automatic menu position up/down
                        source: suggest ? suggest : []
                    },
                    sortable: false,
                    forceLowercase: false,
                    placeholder: '',
                    maxTags: max_tag ? max_tag : null,
                    // onChange: function (field, editor, tags) {
                    //     $(field).val(tags.length ? tags.join(',') : '');
                    // },
                    beforeTagSave: function (field, editor, tags, tag, val) {
                        shop.admin.filters.add(val, type);
                    },
                    beforeTagDelete: function (field, editor, tags, val) {
                        var q = confirm('Xóa tag "' + val + '"?');
                        if (q) {
                            shop.admin.filters.remove(val, type, id);
                        }
                        return q;
                    }
                });
            }else{
                shop.admin.filters.loadSuggest(type, container, id,max_tag);
            }
        },
        loadSuggest: function(type, container, id,max_tag){
            shop.ajax_popup('filter/filter-suggest', 'POST', {type: type}, function(json){
                if(json.error == 0) {
                    shop.admin.filters.init(type, container, id, json.data, true,max_tag);
                }else{
                    alert(json.msg);
                }
            });
        },
        add: function(filter, type, image){
            shop.ajax_popup('filter/filter-add', 'POST', {filter: filter, type: type}, function(json){
                if(json.error != 0) {
                    alert(json.msg);
                }
            });
        },
        remove: function(filter, type, id){
            shop.ajax_popup('filter/filter-del', 'POST', {filter: filter, type: type, id: id}, function(json){
                if(json.error != 0) {
                    alert(json.msg);
                }
            });
        }
    },
    api:{
        showLog: function(id){
            shop.ajax_popup('api-log', 'POST', {id: id}, function(json){
                if(json.error != 0) {
                    alert(json.msg);
                }else{
                    //update title
                    var data = json.data,
                        html = shop.join
                        ('<div><b>Request URL:</b> '+data.url+'</div>')
                        ('<div class="mt-2"><b>Call time:</b> '+data.created+'</div>')
                        ('<div class="mt-2"><b>Status:</b> '+(data.error ? '<span class="text-danger">Error</span>' : '<span class="text-success">Success</span>')+'</div>')
                        ('<div class="mt-2"><b>Params:</b></div>')
                        ('<div class="mt-2" style="word-wrap: break-word;">'+data.params+'</div>')
                        ('<div class="mt-2"><b>Return:</b></div>')
                        ('<div class="mt-2" style="word-wrap: break-word;">'+(data.error ? data.error : data.return)+'</div>')
                        ();
                    $('#primaryModal .modal-body').html(html);
                    $('#primaryModal').modal();
                }
            });
        }
    }
};
shop.ready.add(function (){
    shop.admin.actived();
    $('.datepicker').datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true
    });
}, true);
