const unique = (value, index, self) => {
    return self.indexOf(value) === index
};
var app_prd_category = new Vue({
    // options content
    el: '#app_prd_category_menu',
    data: {
        filter:{
            // sort_by:JSON.parse(sort_by),
            filter_cate:JSON.parse(filter_cate),
            // choosed_filters:JSON.parse(choosed_filters),
            // choosed_sort_by:0
        }
    },

    mounted(){
        // this.loader();
        $('.js-sidebar-close').click(function(){
            $('.cates-sidebar').removeClass('active');
            $('.cates-filter').removeClass('active');
        });
        $('.mobile-show-cat').click(function(){
            $('.cates-sidebar').addClass('active');
        });

        $('.show-filter-mobile').click(function(){
            $('.cates-filter').addClass('active');
        });
    },
    computed: {
    },
    updated: function(){

    },
    // watch:{
    //     'loading':function(){console.log(1)}
    // },
    methods:{
        loader: function() {
            setTimeout(function() {
                if($('#pb_loader').length > 0) {
                    $('#pb_loader').removeClass('show');
                }
            }, 700);
        },
        show_loader: function() {
            if($('#pb_loader').length > 0) {
                $('#pb_loader').addClass('show');
            }
        },
        hide_loader: function() {
            if($('#pb_loader').length > 0) {
                $('#pb_loader').removeClass('show');
            }
        },
        // remove_all_filter: function(){
        //     this.show_loader();
        //     var vm = this;
        //     vm.filter.arr_choosed_filter = [];
        //     vm.filter.choosed_filters =[];
        //     shop.setGetParameter('filter_ids','');
        // },
        // remove_filter: function(e, item) {
        //     this.show_loader();
        //     var fck = [], param_name = [], param_value = [];
        //     this.filter.choosed_filters = this.filter.choosed_filters.filter(function(el){
        //         return el.filter_id != item.filter_id;
        //     });
        //
        //     this.filter.choosed_filters.forEach(function(item){
        //         fck.push(item.filter_id);
        //     });
        //
        //     param_name.push('filter_ids');
        //     param_value.push(fck.join());
        //
        //     shop.setGetParameter(param_name,param_value);
        //
        // },
        // choose_fil: function(e){
        //     window.location.href = e.currentTarget.getAttribute('data-link');
        //     return;
        // },
        // choose_filters: function(e,filter,cate) {
        //     var vm = this;
        //     var param_name = [];
        //     var param_value = [];
        //     var fck = [];
        //
        //     this.show_loader();
        //
        //     vm.filter.choosed_filters.push({
        //         cate_title: cate.title,
        //         cate_id:cate.id,
        //         filter_title: filter.title,
        //         filter_id:filter.id
        //     });
        //
        //     vm.filter.choosed_filters.forEach(function(item){
        //         fck.push(item.filter_id);
        //     });
        //
        //     param_name.push('filter_ids');
        //     param_value.push(fck.join());
        //
        //     shop.setGetParameter(param_name,param_value);
        //
        //     // console.log(vm.filter.choosed_filters);
        // },
        // pick_sort_by: function(e,item) {
        //     e.preventDefault();
        //     this.filter.choosed_sort_by = item;
        //
        //     shop.setGetParameter('sort_by',this.filter.choosed_sort_by);
        // },
        // formatPrice: function(value) {
        //     return shop.numberFormat(value);
        // }
    }
});
