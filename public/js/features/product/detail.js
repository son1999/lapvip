const unique = (value, index, self) => {
    return self.indexOf(value) === index
};
var app_prd_detail = new Vue({
    // options content
    el: '#app_prd_detail',
    data: {
        prd_id: prd_id,
        // warehouse: warehouse_ ? JSON.parse(warehouse_) : [],
        // st: JSON.parse(store),
        prices: JSON.parse(prices),
        filters: JSON.parse(filters),
        filter_prices: filter_prices ? JSON.parse(filter_prices) : [],
        filter_cates: JSON.parse(filter_cates),
        percent: JSON.parse(percent),
        // sale: JSON.parse(sale),
        infoPro: JSON.parse(infoPro),
        customer_gp: JSON.parse(customer_gp),
        choosed_item: [],
        filter_ids:[],
        prd_price: 0,
        count_st: 0,
        wh_arr: 0,
        amount: '',
        tit: 'Số Lượng',
        prd_price_strike:0,
        quantity: 1,
        status_text: 'Còn hàng',
        status_storage:1,// 1: còn hàng, 0: Liên hệ
        error_msg:'',
    },
    mounted(){
        // this.loader();
        var vm = this;
        if(Object.keys(this.filter_cates).length <= 0) {
            this.status_text = 'Liên hệ';
            this.status_storage = 0;
        }
        var arr_money = [];
        this.prices.forEach(function(item,key){
            arr_money.push(item.price);
        });


        var min_money = Math.min(...arr_money);
        this.prices.some(function(item){
            if(min_money == item.price) {
                vm.prd_price = item.price;
                vm.prd_price_strike = item.price_strike;
                return true;
            }
        });


    },
    computed: {
        percentPro:function(){
            return 100 - Math.round(this.prd_price/this.prd_price_strike*100);
        }
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
        up_quan: function(e) {
            e.preventDefault();
            this.quantity++;
        },
        down_quan: function(e) {
            e.preventDefault();
            this.quantity = this.quantity > 1 ? this.quantity-1 : 1;
        },
        choose_this: function(e,ele) {
            // alert('fuck');
            e.target.checked = true;
            var vm = this;
            vm.choosed_item[ele.filter_cate_id] = ele.id;
            vm.filter_ids = vm.choosed_item.filter(unique);
            vm.prices.some(function(item){
               if(item.filter_ids == vm.filter_ids.join()){
                   vm.prd_price = item.price;
                   vm.prd_price_strike = item.price_strike;
                   return true;
               }
            });


            vm.filter_prices.some(function (it) {

                if (it.key_price == vm.filter_ids.join()){
                    vm.amount = vm.tit;
                    // vm.wh_arr = vm.warehouse;
                    vm.count_st = it.key_price;
                    return true
                }
            });
        },
        checked_input: function(item){
            console.log(this.filter_ids.indexOf(item.id) > -1);
            // console.log(this.filter_ids);
            // console.log(this.filter_ids.indexOf(item.id));
            return (this.filter_ids.indexOf(item.id) > -1);
        },
        add_to_cart: function(e) {
            e.preventDefault();
            var vm = this;
            vm.error_msg = '';
            if(Object.keys(vm.choosed_item).length < Object.keys(vm.filter_cates).length) {
                vm.error_msg = 'Hãy chắc chắn rằng bạn đã chọn đủ hạng mục sản phẩm';
            }else {
                if(vm.quantity > 0) {
                    var msg_ajax = '';
                    $.ajax({
                        type: 'POST',
                        url: "ajax/cart-add",
                        data: {
                            _token:ENV.token,
                            id:vm.prd_id,
                            filter_key:vm.filter_ids.join(),
                            quan:vm.quantity,
                        },
                        dataType: 'json',
                    }).done(function(json) {
                        if (json.error == 1) {
                            Swal.fire({
                                title: 'Thông báo',
                                text: json.msg,
                                type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                            });
                        } else {
                            $('.counter-cart').text(json.data.number);
                            Swal.fire({
                                title: 'Thông báo',
                                text: 'Thêm vào giỏ hàng thành công',
                                type: 'success',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                            }).then((result) => {
                                shop.redirect(json.data['url']);
                            });
                        }
                    });
                }else{
                    vm.error_msg = 'Vui lòng chọn ít nhất 1 sản phẩm';
                }
            }
            if(vm.error_msg != '') {
                Swal.fire({
                    title: 'Thông báo',
                    text: vm.error_msg,
                    type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                });
            }
        },

            installment: function(e, index){
            e.preventDefault();

            var vm = this;
            vm.error_msg = '';
            if(Object.keys(vm.choosed_item).length < Object.keys(vm.filter_cates).length) {
                vm.error_msg = 'Hãy chắc chắn rằng bạn đã chọn đủ hạng mục sản phẩm';
            }else {
                if(vm.quantity > 0) {
                    var msg_ajax = '';
                    $.ajax({
                        type: 'GET',
                        url: 'ajax/installment',
                        data: {
                            _token:ENV.token,
                            index: index,
                            id:vm.prd_id,
                            filter_key:vm.filter_ids.join(),
                            quan:vm.quantity,
                        },
                        dataType: 'json',
                    }).done(function(json) {
                        if (json.error == 1) {
                            Swal.fire({
                                title: 'Thông báo',
                                text: json.msg,
                                type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                            });
                        } else {
                            shop.redirect(json.data['url']);
                        }
                    });
                }else{
                    vm.error_msg = 'Vui lòng chọn ít nhất 1 sản phẩm';
                }
            }
            if(vm.error_msg != '') {
                Swal.fire({
                    title: 'Thông báo',
                    text: vm.error_msg,
                    type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                });
            }

        },
        formatPrice: function(value) {
            return shop.numberFormat(value);
        },
    }
});
var inFoP = new Vue({
    el: '#info_Product',
    data: {
        infoPro: JSON.parse(infoPro),
    },
});
var inFoPmobile = new Vue({
    el: '#info_Product_mobile',
    data: {
        infoPro: JSON.parse(infoPro),
    },
});

$(document).ready(function(){
    $('.show-form-rate').click(function(){
        $('.form-rate').slideToggle();
    });

});


