var app_prd_prices = new Vue({
    // options content
    el: '#product-admin-prices',
    data: {
        filter_cate_price: filter_cate_price ? JSON.parse(filter_cate_price) : [],
        filter_prices: filter_prices ? JSON.parse(filter_prices) : [],
        // mount: mount ? JSON.parse(mount) : [],
        warehouse: warehouse_ ? JSON.parse(warehouse_) : [],
    },
    beforeMount() {
        // console.log(this.temp_warehouse);
        var temp_warehouse = [];
        for (var i = 0; i < this.filter_prices.length; i++) {
            temp_warehouse = JSON.parse(JSON.stringify(this.warehouse));
            for (var k = 0; k < this.filter_prices[i].storage.length;k++) {
                for (var j = 0; j < temp_warehouse.length; j++) {
                    if(this.filter_prices[i].storage[k].warehouse_id == this.warehouse[j].id) {
                        temp_warehouse[j].amount = this.filter_prices[i].storage[k].amount;
                        break;
                    }
                }
            }
            this.filter_prices[i].warehouse = temp_warehouse;
        }

        // for (var i = 0; i < this.filter_prices.length; i++) {
        //     for (var k = 0; k < this.filter_prices[i].storage.length;k++) {
        //         for (var j = 0; j < temp_warehouse.length; j++) {
        //             if(this.filter_prices[i].storage[k].warehouse_id == temp_warehouse[j].id) {
        //                 temp_warehouse[j].amount = this.filter_prices[i].storage[k].amount;
        //                 break;
        //             }
        //             // else {
        //             //     temp_warehouse[j].amount = 0;
        //             // }
        //         }
        //     }
        //     this.filter_prices[i].warehouse = temp_warehouse;
        // }

        // console.log(this.filter_prices);
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
    filters: {

    },
    methods:{
        loader: function() {
            setTimeout(function() {
                if($('#product-admin-prices .pb_loader').length > 0) {
                    $('#product-admin-prices .pb_loader').removeClass('show');
                }
            }, 700);
        },
        show_loader: function() {
            if($('#product-admin-prices .pb_loader').length > 0) {
                $('#product-admin-prices .pb_loader').addClass('show');
            }
        },
        hide_loader: function() {
            if($('#product-admin-prices .pb_loader').length > 0) {
                $('#product-admin-prices .pb_loader').removeClass('show');
            }
        },
        filter_click:function(e) {
            e.preventDefault();
            var obj = [];
            var key_price = '';
            var base_price = $('#base_price').val();
            var base_priceStrike = $('#base_priceStrike').val();
            this.filter_cate_price.forEach(function(item,key){
                if(item.checked > 0) {
                    item.filters.forEach(function (o,k) {
                        if(o.id == item.checked) {
                            key_price += key_price == '' ? o.id : ',' + o.id;
                            return obj.push({id:o.id,title:o.title});
                        }
                    });
                }
            });

            if(obj.length > 0) {
                // console.log(storage);
                // console.log(app_prd_prices.filter_prices);
                // console.log(app_prd_prices.mount);
                var temp = {
                    key_price: key_price,
                    obj: obj,
                    base_price: base_price,
                    base_priceStrike: base_priceStrike,
                    warehouse: this.warehouse
                };
                var existed = 0;
                if(app_prd_prices.filter_prices.length > 0) {
                    for (var i = 0; i < app_prd_prices.filter_prices.length; i++) {
                        if (app_prd_prices.filter_prices[i].key_price == temp.key_price) {
                            existed = 1;
                            break;
                        }
                    }
                    if(existed == 0) {
                        app_prd_prices.filter_prices.push(temp);
                    }
                }else {
                    app_prd_prices.filter_prices.push(temp);
                }
            }
        },
        remove_price: function(e, ele){
            e.preventDefault();
            for (var i =0; i < app_prd_prices.filter_prices.length; i++) {
                if (app_prd_prices.filter_prices[i].key_price == ele.key_price) {
                    app_prd_prices.filter_prices.splice(i, 1);
                    break;
                }
            }
        }
    }
});

var app_collection = new Vue({

})