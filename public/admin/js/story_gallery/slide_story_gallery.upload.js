shop.updateScriptData = function(){
    jQuery('#uploadify_hotel_img_slide').uploadifive('destroy');
    shop.uploadify_hotel_img_slide();
};
var arr_ids = [];
shop.uploadify_hotel_img_slide = function(){
    var oobject_id = jQuery('#gallery-object_id_slide').val();
    var config = {
        'uploadScript' : ENV.BASE_URL+'ajax/story_wt/upload_img_slide',
        'formData' : {
            'object_id': oobject_id,
            'type':'slide_story',
            '_token': ENV.token,
            'lang': jQuery('#filter-lang').val()
        },
        'buttonText' : 'CHỌN ẢNH',
        'fileType'     : 'image/*',
        // 'fileObjName'   : 'img_files',
        'onError': function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        },
        'onUploadComplete' : function(file, data, response) {
            var myObject;
            try {
                myObject = eval('(' + data + ')');
            } catch (e) {
                alert('Lỗi hệ thống upload ' + data);
                return;
            }

            if(oobject_id != '') {
                if (myObject.error == 0) {
                    app.listImages(myObject.data.images, true);
                } else {
                    alert(file.name + "\nError !!! " + myObject.msg);
                }
            }else {
                if(typeof myObject.data.id != 'undefined') {
                    arr_ids.push(myObject.data.id);
                    $('#img_upload_for_add_slide').val(arr_ids.join(','));
                }
                return true;
            }
        }
    };

    if(oobject_id == '') {
        // config.auto = false;
        config.fadeAfterupload = false;
    }

    jQuery('#uploadify_hotel_img_slide').uploadifive(config);
};