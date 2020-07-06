var app3 = new Vue({
    // options content
    el: '#celebrities',
    data: loadCelebrities,
    mounted(){
        this.loadCele();
    },

    updated: function(){
        $(".gallery ul").sortable().bind('sortupdate', function(e, item) {
            app3.updatePosition(item);
        });
    },
    // watch:{
    //     'loading':function(){console.log(1)}
    // },
    methods:{
        showListCe: function(){
            return this.images && this.images.length > 0;
        },
        updateCover:function(){
            if(this.showListCe()){
                for (let i in this.images){
                    if(this.images[i].is_cover == 1){
                        this.cover = this.images[i].image_sm;
                        return;
                    }
                }
            }
            this.cover = '';
        },


        filter:function(){
            this.images = [];
            if(this.celebrities && this.celebrities.length > 0) {
                this.images = this.celebrities;
                this.updateCover();
            }
        },
        listImages: function(images, cover, cat, cur_cat_id){
            this.celebrities = images;
            this.filter();
        },
        loadCele:function(e){
            let data = {object_id: this.object_id,type:(typeof this.type != 'undefined' ? this.type : '')};
            shop.ajax_popup('story_wt/loadCelebrities', 'POST', data,
                function (json) {
                    app3.loading = false;
                    if (json.error == 0) {
                        app3.listImages(json.data.images, true);
                    }else{
                        alert(json.msg);
                    }
                },
                {
                    error: function () {
                        app3.loading = false;
                    }
                }
            );
        },
        imageDel:function(item)     {
            if(confirm('Bạn muốn xóa ảnh?')) {
                this.loading = true;
                shop.ajax_popup('story_wt/remove_celebrities', 'POST', {id: item.id, object_id:this.object_id,type:this.type},
                    function (json) {
                        app3.loading = false;
                        let msg = json.msg;
                        if (json.error == 0) {
                            app3.listImages(json.data.images, true, json.data.category);
                            msg = 'Đã xóa ảnh thành công';
                        }
                        alert(msg);
                    },
                    {
                        error: function () {
                            app3.loading = false;
                        }
                    }
                );
            }
        },
    }
});