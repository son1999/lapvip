shop.multiupload = function(container, size){
    var data = {
        'size': size,
        'action': 'insert'
    };
    $('#uploadintro').uploadifive({
        'uploadScript' : ENV.BASE_URL+'ajax/file/upload-file',
        'formData' : data,
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
                alert('Lỗi hệ thống upload \n'+ data);
                return;
            }
            if (myObject.error == 0) {
                var imgSrc = myObject.data.image,
                    name = myObject.data.name,
                    text = '<p style="text-align: center;"><img src="'+imgSrc+'" alt="'+name+'" /></p>';
                CKEDITOR.instances[container].insertHtml(text);
            } else {
                alert('Uploading: ' + file.name + '\nError !!! ' + myObject.msg);
            }
        }
    });
};

shop.multiupload2 = function(container, size){
    var data = {
        'size': size,
        'action': 'insert'
    };
    $('#uploadstore').uploadifive({
        'uploadScript' : ENV.BASE_URL+'ajax/file/upload-file',
        'formData' : data,
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
                alert('Lỗi hệ thống upload \n'+ data);
                return;
            }
            if (myObject.error == 0) {
                var imgSrc = myObject.data.image,
                    name = myObject.data.name,
                    text = '<p style="text-align: center;"><img src="'+imgSrc+'" alt="'+name+'" /></p>';
                CKEDITOR.instances[container].insertHtml(text);
            } else {
                alert('Uploading: ' + file.name + '\nError !!! ' + myObject.msg);
            }
        }
    });
};

shop.multiupload_ele = function(container, size, ele){
    var data = {
        'size': size,
        'action': 'insert'
    };
    $(ele).uploadifive({
        'uploadScript' : ENV.BASE_URL+'ajax/file/upload-file',
        'formData' : data,
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
                alert('Lỗi hệ thống upload \n'+ data);
                return;
            }
            if (myObject.error == 0) {
                var imgSrc = myObject.data.image,
                    name = myObject.data.name,
                    text = '<p style="text-align: center;"><img src="'+imgSrc+'" alt="'+name+'" /></p>';
                CKEDITOR.instances[container].insertHtml(text);
            } else {
                alert('Uploading: ' + file.name + '\nError !!! ' + myObject.msg);
            }
        }
    });
};