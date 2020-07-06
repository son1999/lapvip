var app_cart = new Vue({
    // options content
    el: '#app_cart',
    data: {
        cart_items:[],
        total_items:0,
        total_cart:0,
        shipping_fee:0,
        grand_total:0,
        provisional: 0,
        coupons_code: coupons,
        dccoupon:0,
        // active: true,

    },
    mounted(){
        this.load();
        // this.getPay();
        // this.loader();

    },

    computed: {
    },
    updated: function(){

    },
    // watch:{
    //     'loading':function(){console.log(1)}
    // },
    methods:{

        getPay: function(e, value) {
            document.getElementById('pay_method').value= value;
            $('.pay-method .tab-control a').removeClass('active');
            $(e.target).addClass('active');
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
        returnPros: function(filter_id) {
            return list_filter[filter_id];
        },
        check_out_info: function(e) {
            window.location.href = e.target.getAttribute('data-link');
        },
        load:function() {
            $.ajax({
                type: 'POST',
                url: ENV.BASE_URL+"ajax/cart-load",
                data: {_token:ENV.token, coupons_code: this.coupons_code},
                dataType: 'json',
            }).done(function(json) {
                if (json.error == 1) {
                    Swal.fire({
                        title: 'Thông báo',
                        text: json.msg,
                        type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                    });
                } else {
                    app_cart.cart_items = json.data.details;
                    app_cart.updatePriceToShow(json.data);

                }
            })
        },
        updatePriceToShow: function(data){
            // alert(typeof data.gr_total == "undefined")

            this.dccoupon = data.dccoupon;
            this.total_cart = data.total;
            this.shipping_fee = data.shipping_fee;
            this.grand_total = typeof data.gr_total == "undefined" ? (parseFloat(data.total) ) : (parseFloat(data.gr_total) );
            this.provisional = parseFloat(data.total);
            this.total_items = data.number;
            // alert(data.number);
            $('.counter-cart').html(data.number);
            if(data.pass_min_order == 1) {

            }else {

            }
        },

        change_input: function(index,item,e) {
            this.update(index, item.filter_key, parseInt(e.target.value), item.quan, item);
        },
        update:function(index,filter_key, quan,old_quan, item, opt){
            if(quan > 0) {
                $.ajax({
                    type: 'POST',
                    url: ENV.BASE_URL+"ajax/cart-update",
                    data: {
                        _token:ENV.token,
                        index:index,
                        id:item.id,
                        filter_key:filter_key,
                        quan:quan,
                        opt:opt
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
                        item.quan = quan;
                        app_cart.cart_items = json.data.details;
                        app_cart.updatePriceToShow(json.data);
                    }
                });
            }else{
                item.quan = old_quan;
                Swal.fire({
                    title: 'Thông báo',
                    text: 'Số lượng sản phẩm không hợp lệ',
                    type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                });
            }
        },
        remove: function(e,index,item){
            e.preventDefault();
            Swal.fire({
                title: 'Thông báo',
                text:'Bạn muốn loại sản phẩm này ra khỏi giỏ hàng?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#41bb29',
                cancelButtonColor: '#f36f21',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Không',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: ENV.BASE_URL+"ajax/cart-remove",
                        data: {
                            _token:ENV.token,
                            index:index,
                            id:item.id,
                            filter_key:item.filter_key,
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
                            app_cart.cart_items = json.data.details;
                            app_cart.updatePriceToShow(json.data);
                        }
                    });
                }
            });
        },
        formatPrice: function(value) {
            let val = (value/1).toFixed(0).replace('.', ',');
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        },

        change_province: function(event) {
            var province_id = event.target.value;
            if(province_id){
                $.ajax({
                    type:"GET",
                    url:ENV.BASE_URL+"getDistrict?pro_id="+province_id,
                    dataType: 'json',
                    success:function(response){
                        var len  = 0;
                        $("#districts").empty();
                        if (response['data'] != null){
                            len = response['data'].length;
                        }
                        if(len > 0){
                            $("#districts").append('<option selected disabled>Quận/Huyện</option>');
                            for (var i =0 ; i < len; i++){
                                var name = response['data'][i].Name_VI;
                                var id = response['data'][i].id;
                                $("#districts").append('<option  value="'+id+'">'+name+'</option>');
                            }
                        }else{
                            $("#districts").empty();
                        }
                    }
                });
            }else{
                $("#districts").empty();
            }
        }
    }
});