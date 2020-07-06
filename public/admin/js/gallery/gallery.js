var app = new Vue({
    // options content
    el: '#gallery-vue',
    data: searchData,
    mounted(){
        this.updateCurrentCat(true);
        this.loadImages();
        shop.multiupload();
	},
    computed: {
    	catTitle: function () {
            return this.curCat ? this.curCat.title : '';
        },
        catPopupTitle: function () {
            return this.catMode == 0 ? 'Thêm danh mục mới' : 'Sửa danh mục';
        }
	},
    updated: function(){
        $(".gallery ul").sortable().bind('sortupdate', function(e, item) {
            app.updatePosition(item);
        });
    },
    // watch:{
    //     'loading':function(){console.log(1)}
    // },
    methods:{
        showList: function(){
            return this.images && this.images.length > 0;
        },
        updateCover:function(){
            if(this.showList()){
                for (let i in this.images){
                    if(this.images[i].is_cover == 1){
                        this.cover = this.images[i].image_sm;
                        return;
                    }
                }
            }
			this.cover = '';
        },
        updateCurrentCat: function(no_cover){
            this.curCat = this.getCurCat();

            //update cover
            if(no_cover){}else {
                this.updateCover();
            }
        },
        notDefault: function () {
        	if(this.curCat){}else{
                this.updateCurrentCat();
			}
            return this.curCat.id != 1;
        },
        updatePosition:function(item){
            var curItem = $(".gallery ul li:nth-child("+(item.now+1)+")"), nextCurItem, type = 'left';
            if(item.last < item.now){
                type = 'right';
                nextCurItem = $(".gallery ul li:nth-child("+(item.now)+")");
            }else{
                nextCurItem = $(".gallery ul li:nth-child("+(item.now+2)+")");
            }
            shop.ajax_popup('gallery/change-pos','POST',{id: curItem.data("id"), next: nextCurItem.data("id"), type: type},
                function(j){
                    if(j.error > 0) {
                        alert("Không thay đổi được vị trí");
                    }
                }
            );
        },
        changeWindow: function () {
            var url = document.URL,
                cat_id = jQuery('#gallery-category').val(),
                lang = jQuery('#filter-lang').val();
            if(url.indexOf('cat=') > -1){
                url = url.replace(/cat=[0-9]+/gi, "cat="+cat_id);
            }else{
                url += '?cat='+cat_id;
            }
            if(url.indexOf('lang=') > -1){
                url = url.replace(/lang=[a-z]+/i, "lang="+lang);
            }else{
                url += '&lang='+lang;
            }
            window.history.pushState({state:cat_id}, document.title, url);

            shop.updateScriptData();
        },
        getCurCat:function(){
            for(let i in this.category){
                if(this.category[i].id == this.selected){
                    return this.category[i];
                }
            }
            return false;
        },
        catChange: function(e) {
    		this.curCat = this.getCurCat();
    		this.loadImages();
            this.changeWindow();
    		//shop.updateScriptData();
        },
        catAdd: function(e){
    		this.catMode = 0;
            this.catForm.title = '';
            this.catForm.des = '';
        },
        catEdit: function(e){
            this.catMode = 1;
            this.catForm.title = this.curCat.title;
            this.catForm.des = this.curCat.description;
		},
        listCategory: function(cat, cur_id){
			this.category = cat;
			if(cur_id){
				this.selected = cur_id;
			}
            this.updateCurrentCat();
		},
        catSubmit: function(){
            let data = {
                id: 0,
                title: $('#upload-form-title-cat').val(),
                des: $('#upload-form-des-cat').val()
            }, action = 'add';
            if(this.catMode != 0){
                data.id = this.curCat.id;
                action = 'edit';
            }
            this.loading = true;
            shop.ajax_popup('gallery/cat-'+action, 'POST', data,
                function(json) {
                    app.loading = false;
                    let msg = json.msg;
                    if(json.error == 0){
                        if(data.id > 0){
                            msg = 'Cập nhật danh mục thành công'
                        }else{
                            msg = 'Đã thêm danh mục thành công'
                        }
                        app.listCategory(json.data);
                    }
                    $('.category-form').modal('hide');
                    alert(msg);
                },
                {
                    error: function() {
                        app.loading = false;
                    }
                }
            );
        },
        catUpdate: function(e){
            let data = {id: this.curCat.id};
            this.loading = true;
            shop.ajax_popup('gallery/cat-refresh', 'POST', data,
                function (json) {
                    let msg = json.msg;
                    app.loading = false;
                    if (json.error == 0) {
                        app.listCategory(json.data);
                        msg = 'Đã cập nhật số lượng ảnh của danh mục thành công';
                    }
                    alert(msg);
                },
                {
                    error: function () {
                        app.loading = false;
                    }
                }
            );
		},
        catRemove: function(e){
        	if(confirm('Danh mục '+this.curCat.title+' sẽ bị xóa.\nBạn có muốn tiếp tục?')) {
                let data = {id: this.curCat.id};
                this.loading = true;
                shop.ajax_popup('gallery/cat-remove', 'POST', data,
                    function (json) {
                		let msg = json.msg;
                        app.loading = false;
                        if (json.error == 0) {
                            app.listImages(json.data.images, true, json.data.category, 1);
                            this.changeWindow();
                            msg = 'Xóa danh mục thành công';
                        }
                        alert(msg);
                    },
                    {
                        error: function () {
                            app.loading = false;
                        }
                    }
                );
            }
        },
        filter:function(){
            this.images = [];
            if(this.storage && this.storage.length > 0) {
                //filter lang
                this.langDef = $('#filter-lang').val();
                this.images = this.storage.filter(function(item) {
                    return item.lang == app.langDef;
                });
                this.updateCover();
            }
            this.changeWindow();
            //shop.updateScriptData();
        },
		listImages: function(images, cover, cat, cur_cat_id){
            this.storage = images;
            this.filter();
            if(cat){
                this.listCategory(cat, cur_cat_id);
            }else if(cover) {
                this.updateCover();
            }
		},
        loadImages:function(e){
            let data = {id: this.curCat.id};
            this.loading = true;
            shop.ajax_popup('gallery/load', 'POST', data,
                function (json) {
                    app.loading = false;
                    if (json.error == 0) {
                    	app.listImages(json.data.images, true);
                    }else{
                    	alert(json.msg);
					}
                },
                {
                    error: function () {
                        app.loading = false;
                    }
                }
            );
		},
        setCover:function(item){
        	if(confirm('Bạn có muốn đổi ảnh đại diện?')) {
                let data = {cat_id: this.curCat.id, id: item.id};
                this.loading = true;
                shop.ajax_popup('gallery/cover', 'POST', data,
                    function (json) {
                        app.loading = false;
                        if (json.error == 0) {
                            app.listImages(json.data, true);
                        } else {
                            alert(json.msg);
                        }
                    },
                    {
                        error: function () {
                            app.loading = false;
                        }
                    }
                );
            }
		},
        dateFormat:function(timestamp){
            if(timestamp > 0) {
                let date = new Date(timestamp * 1000), d = date.getDay(), m = date.getMonth()+1;
                return (d<=9?'0'+d:d)+'/'+(m<=9?'0'+m:m)+'/'+date.getFullYear();
            }
            return '';
        },
        size:function(curImage){
            let html = '', one = 1024, size = 0;
            if(curImage){
                size = parseInt(curImage.size);
                if(size < one){
                    html += shop.numberFormat(curImage.size) + ' Bytes';
                }else{
                    size = size / 1024;
                    if(size < one){
                        html += shop.numberFormat(curImage.size) + ' KB';
                    }else{
                        html = shop.numberFormat(curImage.size / one) + ' MB';
                    }
                }
            }
            return html;
        },
        htmlCode: function(curImage){
            let html = '';
            if(curImage){
                html = '<a href="'+curImage.image+'" target="_blank" title="'+curImage.title+'"><img src="'+curImage.image+'" alt="'+curImage.img+'" /></a>';
            }
            return html;
        },
        imageEdit: function(item){
            this.curImage = item;
            this.viewMore = false;
            $('#new_image').val('');
        },
        imageDel:function(item){
        	if(confirm('Bạn muốn xóa ảnh?')) {
                this.loading = true;
                shop.ajax_popup('gallery/remove', 'POST', {id: item.id, cat_id: item.cat_id},
                    function (json) {
                        app.loading = false;
                        let msg = json.msg;
                        if (json.error == 0) {
                            app.listImages(json.data.images, true, json.data.category);
                            msg = 'Đã xóa ảnh thành công';
                        }
                        alert(msg);
                    },
                    {
                        error: function () {
                            app.loading = false;
                        }
                    }
                );
            }
		},
        imageSubmit:function(){
            let data = {
                id: this.curImage.id,
                title: $('#image-title').val(),
                sort: $('#image-sort').val(),
				cat_id: $('#image-cat').val(),
                lang: $('#image-lang').val()
            };
            this.loading = true;
            jQuery('#upload-image-form').ajaxSubmit({
                data: data,
                dataType: 'json',
                success: function(json){
                    app.loading = false;
                    let msg = json.msg;
                    if(json.error == 0){
                        app.listImages(json.data.images, true, json.data.category);
                        msg = 'Cập nhật ảnh thành công';
                        $('.image-form').modal('hide');
                    }
                    alert(msg);
                },
                error: function() {
                    app.loading = false;
                }
            });
		}
    }
});