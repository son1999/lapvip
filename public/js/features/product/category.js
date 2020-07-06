const unique = (value, index, self) => {
    return self.indexOf(value) === index
};
var app_prd_category = new Vue({
    // options content
    el: '#app_prd_category',
    data: {
        isCheckAll: false,
        filter:{
            sort_by:JSON.parse(sort_by),
            filter_cate:JSON.parse(filter_cate),
            choosed_filters:JSON.parse(choosed_filters),
            choosed_filters_menu: typeof choosed_filters_menu !== 'undefined' ? JSON.parse(choosed_filters_menu) : '',
            choosed_sort_by:0
        }
    },
    beforeMount(){

    },
    mounted(){
        // this.loader();
        if (sort_by.length > 0){
            $('.cont').removeClass('d-none');
        }
        if(filter_cate.length > 0){
            $('.box-filter').removeClass('d-none');
            $('.filter-cate-mo').removeClass('d-none');
        }
        if(choosed_filters.length > 0){
            $('.box-filter-value').removeClass('d-none');
        }

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
        checkAll: function(e, cate) {
            this.show_loader();
            var id_by_cate = [], fck = [], param_name = [], param_value = [];
            var vm = this;
            $.each(cate.filters, function (key, value) {
                if (value.checked == 1){
                    id_by_cate.push(value.id)
                }
            });
            $.each(id_by_cate, function (key_id, value_id) {
                vm.filter.choosed_filters = vm.filter.choosed_filters.filter(function(el){
                    return el.filter_id != value_id;
                });
            });
            this.filter.choosed_filters.forEach(function(item){
                fck.push(item.filter_id);
            });
            param_name.push('filter_ids');
            param_value.push(fck.join());

            shop.setGetParameter(param_name,param_value);

        },
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
        removeParam: function(key, sourceURL) {
            var rtn = sourceURL.split("?")[0],
                param,
                params_arr = [],
                queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
            if (queryString !== "") {
                params_arr = queryString.split("&");
                for (var i = params_arr.length - 1; i >= 0; i -= 1) {
                    param = params_arr[i].split("=")[0];
                    if (param === key) {
                        params_arr.splice(i, 1);
                    }
                }
                rtn = rtn + "?" + params_arr.join("&");
            }
            return rtn;
        },
        remove_all_filter: function(){
            window.history.pushState('', '', this.removeParam('filter_child', window.location.href));
            window.history.pushState('', '', this.removeParam('child', window.location.href));
            this.show_loader();
            var vm = this;
            vm.filter.arr_choosed_filter = [];
            vm.filter.choosed_filters =[];
            shop.setGetParameter('filter_ids','');
        },
        remove_filter: function(e, item) {
            window.history.pushState('', '', this.removeParam('filter_child', window.location.href));
            window.history.pushState('', '', this.removeParam('child', window.location.href));
            this.show_loader();
            var fck = [], param_name = [], param_value = [];
            this.filter.choosed_filters = this.filter.choosed_filters.filter(function(el){
                return el.filter_id != item.filter_id;
            });

            this.filter.choosed_filters.forEach(function(item){
                fck.push(item.filter_id);
            });
            // if(this.filter.choosed_filters.length == 0){
            //     window.history.pushState('', '', this.removeParam('child', window.location.href));
            // }

            param_name.push('filter_ids');
            param_value.push(fck.join());

            shop.setGetParameter(param_name,param_value);

        },
        remove_filter_checkbox: function(e, item){
            window.history.pushState('', '', this.removeParam('filter_child', window.location.href));
            window.history.pushState('', '', this.removeParam('child', window.location.href));
            this.show_loader();
            var fck = [], param_name = [], param_value = [];
            this.filter.choosed_filters = this.filter.choosed_filters.filter(function(el){
                return el.filter_id != item.id;
            });

            this.filter.choosed_filters.forEach(function(item){
                fck.push(item.filter_id);
            });
            param_name.push('filter_ids');
            param_value.push(fck.join());

            shop.setGetParameter(param_name,param_value);
        },

        choose_fil: function(e){
            window.location.href = e.currentTarget.getAttribute('data-link');
            return;
        },
        choose_filters: function(e,filter,cate) {
            window.history.pushState('', '', this.removeParam('page', window.location.href));
            window.history.pushState('', '', this.removeParam('child', window.location.href));
            var vm = this;
            var param_name = [];
            var param_value = [];
            var fck = [];
            window.history.pushState('', '', vm.removeParam('filter_child', window.location.href));
            this.show_loader();

            if (filter.pid != 0){
                this.filter.choosed_filters = this.filter.choosed_filters.filter(function(el){
                    return el.filter_id != filter.pid;
                });
            }

            if(this.filter.filter_cate.length == this.filter.filter_cate.length){
                this.isCheckAll = true;
            }else{
                this.isCheckAll = false;
            }

            vm.filter.choosed_filters.push({
                cate_title: cate.title,
                cate_id:cate.id,
                filter_title: filter.title,
                filter_id:filter.id
            });

            vm.filter.choosed_filters.forEach(function(item){
                fck.push(item.filter_id);
            });


            param_name.push('filter_ids');
            param_value.push(fck.join());

            shop.setGetParameter(param_name,param_value);

            // console.log(vm.filter.choosed_filters);
        },
        pick_sort_by: function(e,item) {
            e.preventDefault();
            this.filter.choosed_sort_by = item;
            shop.setGetParameter('sort_by',this.filter.choosed_sort_by);
        },
        pick_filters: function(e,item) {
            e.preventDefault();
            this.filter.choosed_filters = item;
            shop.setGetParameter('filter_ids',this.filter.choosed_filters);
        },
        formatPrice: function(value) {
            return shop.numberFormat(value);
        }
    }
});
