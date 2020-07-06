shop.updateScriptData = function(){
    jQuery('#uploadify').uploadifive('destroy');
    shop.multiupload(); 
};
shop.multiupload = function(){
    jQuery('#uploadify').uploadifive({
        'uploadScript' : ENV.BASE_URL+'ajax/gallery/upload',
        'formData' : {
            'cat_id': jQuery('#gallery-category').val(),
            'lang': jQuery('#filter-lang').val()
        },
        'buttonText' : 'CHỌN ẢNH',
        'fileType'     : 'image/*',
        'onError': function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        },
        'onUploadComplete' : function(file, data, response) {
            var myObject;
            try {
              myObject = eval('(' + data + ')');
            } catch (e) {
              alert('Lỗi hệ thống upload '+ data);
              return;
            }
            if (myObject.error == 0) {
                app.listImages(myObject.data.images, true, myObject.data.category);
            } else {
                alert(file.name+"\nError !!! "+myObject.msg);
            }
        }
    });
};