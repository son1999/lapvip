var app_prd_filters = new Vue({
    // options content
    el: '#product-admin-filters',
    data: {
        filter_cate_not_price: typeof filter_cate_not_price != 'undefined' && filter_cate_not_price ? filter_cate_not_price : [],
        // filter_cate_not_price: typeof filter_cate_not_price != 'undefined' && filter_cate_not_price ? JSON.parse(filter_cate_not_price) : [],
    },
    mounted(){
        this.loader();
    },
    computed: {

    },
    updated: function(){

    },
    // watch:{
    //     'loading':function(){console.log(1)}
    // },
    methods:{
        check: function(e, data){
            $('#filter_'+data).prop('checked', true);
        },
        loader: function() {
            setTimeout(function() {
                if($('#product-admin-filters .pb_loader').length > 0) {
                    $('#product-admin-filters .pb_loader').removeClass('show');
                }
            }, 700);
        },
        show_loader: function() {
            if($('#product-admin-filters .pb_loader').length > 0) {
                $('#product-admin-filters .pb_loader').addClass('show');
            }
        },
        hide_loader: function() {
            if($('#product-admin-filters .pb_loader').length > 0) {
                $('#product-admin-filters .pb_loader').removeClass('show');
            }
        },
    }
});

var app_fill_collection = new Vue({
    el: '#fill_collection',
    data:{
        collection : typeof collection != 'undefined' && collection ? JSON.parse(collection) : [],
    },
    mounted(){
        this.loader();
    },
    methods:{
        loader: function() {
            setTimeout(function() {
                if($('#fill_collection .pb_loader').length > 0) {
                    $('#fill_collection .pb_loader').removeClass('show');
                }
            }, 700);
        },
        show_loader: function() {
            if($('#fill_collection .pb_loader').length > 0) {
                $('#fill_collection .pb_loader').addClass('show');
            }
        },
        hide_loader: function() {
            if($('#fill_collection .pb_loader').length > 0) {
                $('#fill_collection .pb_loader').removeClass('show');
            }
        },
    }
})