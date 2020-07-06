if(typeof Vue != 'undefined') {
    var app = new Vue({
        // options content
        el: '#vue-order',
        data: {
            order_details: details,
            item: [],
            code: '',
            total: 0,
            quantity: 0,
            reason: ''
        },
        mounted() {
            
        },
        computed: {
            popupTitle: function () {
                return 'Thông tin đơn hàng #' + this.code;
            }
        },
        updated: function () {
        },
        // watch:{
        //     'loading':function(){console.log(1)}
        // },
        methods: {
            showPopup: function (id, code, total, quan) {
                if(shop.is_exists(this.order_details[id])) {
                    this.item = this.order_details[id];
                    this.code = code;
                    this.total = total;
                    this.quantity = quan;
                }
            },
            priceFormat: function (p) {
                return shop.numberFormat(p) + ' ' + ENV.CURRENCY;
            },
            assignOrder: function (id, is_take,url) {
                let confirm_msg = "Bạn có chắc chắn muốn tiếp nhận đơn hàng này?";
                if (is_take === 0) {
                    confirm_msg = "Bạn có chắc chắn muốn bỏ tiếp nhận đơn hàng này?";
                } else if (is_take === 3) {
                    confirm_msg = "Bạn có chắc chắn muốn tự tiếp nhận đơn hàng này?";
                } else if (is_take === -1) {
                    confirm_msg = "Bạn có chắc chắn muốn hủy đơn hàng này?";
                }
                if (confirm(confirm_msg)) {
                    shop.ajax_popup((url ? url : 'order/assign'), 'POST', {id: id, is_take: is_take}, function (json) {
                        if (json.error == 0) {
                            alert(is_take === 0 ? "Bỏ tiếp nhận thành công!" : "Tiếp nhận thành công!");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            confirmOrderTransport: function (id,url) {
                if (confirm('Bạn muốn xác nhận đơn hàng này Đang được vận chuyển?')) {
                    shop.ajax_popup((url ? url : 'order/confirm_transport'), 'POST', {id: id}, function (json) {
                        if (json.error == 0) {
                            alert("Đơn hàng đã được xác nhận Đang vận chuyển");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            confirmOrderDelivered: function (id,url) {
                if (confirm('Bạn muốn xác nhận đơn hàng này Giao hàng thành công?')) {
                    shop.ajax_popup((url ? url : 'order/confirm_delivered'), 'POST', {id: id}, function (json) {
                        if (json.error == 0) {
                            alert("Đơn hàng đã được xác nhận Giao hàng thành công");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            confirmOrder: function (id,url) {
                if (confirm('Bạn muốn xác nhận đơn hàng này hoàn thành?')) {
                    shop.ajax_popup((url ? url : 'order/confirm'), 'POST', {id: id}, function (json) {
                        if (json.error == 0) {
                            alert("Đơn hàng đã được xác nhận hoàn thành");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            confirmOrderPaid: function (id,url) {
                if (confirm('Bạn muốn xác nhận đơn hàng này đã thanh toán?')) {
                    shop.ajax_popup((url ? url : 'order/confirm_paid'), 'POST', {id: id}, function (json) {
                        if (json.error == 0) {
                            alert("Đơn hàng đã được xác nhận đã thanh toán");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            confirmOrderPendingPaid: function (id,url) {
                if (confirm('Bạn muốn xác nhận đơn hàng này Chờ thanh toán?')) {
                    shop.ajax_popup((url ? url : 'order/confirm_pending_paid'), 'POST', {id: id}, function (json) {
                        if (json.error == 0) {
                            alert("Đơn hàng đã được xác nhận Chờ thanh toán");
                            shop.reload();
                        } else {
                            alert(json.msg);
                        }
                    });
                }
            },
            cancelOrder:function (id,url) {
                shop.ajax_popup((url ? url : 'order/cancel'), 'POST', {id: id}, function (json) {
                    if (json.error == 0) {
                        alert("Hủy đơn hàng thành công");
                        shop.reload();
                    } else {
                        alert(json.msg);
                    }
                });
            }
        }
    });
}

order = {};

order.refreshTotalPrice = function() {
    var total = 0;
    var inputs = $('input.quantity');
    if(inputs.length > 0) {
        inputs.each(function(i, obj) {
            total += $(obj).val()*$(obj).attr('data-price-origin')
        });

        $('#totalCart').html(shop.numberFormat(total)+' đ').attr('data-total',total);
        $('#grandTotal').html(shop.numberFormat(total+parseFloat($('#shippingFee').attr('data-shipping')))+' đ')
    }else {
        $('#totalCart').html(0);
        $('#grandTotal').html(0);
    }
    $('#temp_data_foods').val(getHtml($('#admin_book_cart')));
};

function getHtml(div) {
    div.find("input").each(function () {
        $(this).attr("value", $(this).val());
    });
    return div.html();
}
order.get_district = function(id,callback) {
    shop.ajax_popup('get-list-districts', 'post', {city_id:id}, function(json) {
        if(json.error == 1){
            alert(json.msg);
        }else {
            var i;
            var html = shop.join('<option value="">--Chọn Quận/huyện--</option>')();
            for(i in json.data){
                html += shop.join('<option value="'+json.data[i].id+'">'+json.data[i].title+'</option>')();
            }

            $('#district_id').html(html);
            if (shop.is_exists(callback)) {
                callback();
            }
        }
    });
};
order.get_ward = function(id,callback) {
    shop.ajax_popup('get-list-wards', 'post', {district_id:id}, function(json) {
        if(json.error == 1){
            alert(json.msg);
        }else {
            var i;
            var html = shop.join('<option value="">--Chọn phường--</option>')();
            for(i in json.data){
                html += shop.join('<option value="'+json.data[i].id+'">'+json.data[i].title+'</option>')();
            }

            $('#ward_id').html(html);
            if (shop.is_exists(callback)) {
                callback();
            }
        }
    });
};

order.toJSONString = function ( form ) {
    var obj = {};
    var elements = form.querySelectorAll("input, select, textarea");
    for (var i = 0; i < elements.length; ++i) {
        var element = elements[i];
        var name = element.name;
        var value = element.value;

        if (name) {
            obj[name] = value;
        }
    }

    return obj;
};

order.showModalCancelOrder = function (id) {
    $('#order_id').val(id);
    $('.popup-reason').modal('show');
};

order.showModalRefundOrder = function (id) {
    $('#order_id').val(id);
    $('.popup-refund-reason').modal('show');
};
order.showModalConfirmRefundOrder = function (id) {
    $('#order_id').val(id);
    $('.popup-confirm-refund').modal('show');
};

order.CancelOrder = function ( url ) {
    var id = $('#order_id').val();
    var reason = $('#reason').val();
    shop.ajax_popup((url ? url : 'order/cancel'), 'POST', {id: id,reason: reason}, function (json) {
        if (json.error == 0) {
            alert("Hủy đơn hàng thành công");
            shop.reload();
        } else {
            alert(json.msg);
        }
    });
};
order.RefundOrder = function ( url ) {
    var id = $('#order_id').val();
    var reason = $('#reason_refund').val();
    var refund_fee = $('#refund_fee').val();
    shop.ajax_popup((url ? url : 'order/refund'), 'POST', {id: id,reason: reason,refund_fee:refund_fee}, function (json) {
        if (json.error == 0) {
            alert("Yêu cầu hoàn đơn hàng thành công");
            // shop.reload();
        } else {
            alert(json.msg);
        }
    });
};
order.ConfirmRefundOrder = function ( url ) {
    var id = $('#order_id').val();
    var note = $('#reason_note').val();
    shop.ajax_popup((url ? url : 'order/confirm-refund'), 'POST', {id: id,note: note}, function (json) {
        if (json.error == 0) {
            alert("Xác nhận hoàn đơn hàng thành công");
            shop.reload();
        } else {
            alert(json.msg);
        }
    });
};
order.DoneRefundOrder = function ( id,url ) {
    if (confirm('Bạn muốn hoàn thành hoàn tiền cho đơn hàng? Hãy chắc chắn đã hoàn tiền cho khách hàng.')) {
        shop.ajax_popup((url ? url : 'order/done-refund'), 'POST', {id: id}, function (json) {
            if (json.error == 0) {
                alert("Đơn hàng đã được hoàn tất quá trình hoàn tiền!");
                shop.reload();
            } else {
                alert(json.msg);
            }
        });
    }
};
order.loading = function(id){
    $('#'+id).html('<div class="d-flex align-items-center">\n' +
        '  <strong>Loading...</strong>\n' +
        '  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>\n' +
        '</div>');
}
order.checkOrderWepay = function ( code,url_check ) {
    $('.popup-order-wepay').modal('show');
    order.loading('order_wepay');
    shop.ajax_popup((url_check ? url_check : 'order/wepay-order'), 'POST', {code: code}, function (json) {
        if (json.error == 0) {
            var html = " <table class='table table-striped'>" +
                "                                            <tbody>" +
                "                                            <tr>" +
                "                                                <th scope='row' width='200'>Mã đơn hàng</th>" +
                "                                                <td>"+json.data.shp_order_code+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị đơn hàng</th>" +
                "                                                <td>"+shop.numberFormat(json.data.shp_order_price)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị thanh toán</th>" +
                "                                                <td>"+shop.numberFormat(json.data.shp_payment_total)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Thời gian thanh toán</th>" +
                "                                                <td>"+json.data.shp_payment_time+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Thông tin đơn hàng</th>" +
                "                                                <td>"+json.data.shp_order_info+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Thông tin đơn hàng</th>" +
                "                                                <td>"+(json.data.shp_order_status==1?'Thành công!':'Thất bại!')+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Kết quả giao dịch</th>" +
                "                                                <td>"+json.data.shp_payment_response_description+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Mô tả kết quả giao dịch</th>" +
                "                                                <td>"+json.data.shp_payment_response_message+"</td>" +
                "                                            </tr>" +
                "                                            </tbody>" +
                "                                        </table>";

            $('#order_wepay').html(html);
        } else {
            alert(json.msg);
        }
    });
};
order.checkOrderNganLuon = function ( code,url_check ) {
    $('.popup-order-wepay').modal('show');
    order.loading('order_wepay');
    shop.ajax_popup((url_check ? url_check : 'order/nganluong-order'), 'POST', {code: code}, function (json) {
        if (json.error == 0) {
            var html = " <table class='table table-striped'>" +
                "                                            <tbody>" +
                "                                            <tr>" +
                "                                                <th scope='row' width='200'>Mã đơn hàng</th>" +
                "                                                <td>"+json.data.order_code+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị đơn hàng</th>" +
                "                                                <td>"+shop.numberFormat(json.data.total_amount)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị thanh toán</th>" +
                "                                                <td>"+shop.numberFormat(json.data.total_amount)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Payment Time</th>" +
                "                                                <td>"+json.data.payment_time+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Payment Method</th>" +
                "                                                <td>"+json.data.payment_method+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Thông tin đơn hàng</th>" +
                "                                                <td>"+json.data.order_description+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Transaction_ID</th>" +
                "                                                <td>"+json.data.transaction_id+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Token</th>" +
                "                                                <td>"+json.data.token+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Kết quả giao dịch</th>" +
                "                                                <td>"+(json.data.transaction_status == '00' ? 'Thành công' : 'Thất bại')+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Mô tả kết quả giao dịch</th>" +
                "                                                <td>"+json.data.err_mess+"</td>" +
                "                                            </tr>" +
                "                                            </tbody>" +
                "                                        </table>";

            $('#order_wepay').html(html);
        } else {
            alert(json.msg);
        }
    });
};
order.checkOrderBizPay = function ( code,url_check ) {
    $('.popup-order-wepay').modal('show');
    order.loading('order_wepay');
    shop.ajax_popup((url_check ? url_check : 'order/bizpay-order'), 'POST', {code: code}, function (json) {
        if (json.error == 0) {
            var html = " <table class='table table-striped'>" +
                "                                            <tbody>" +
                "                                            <tr>" +
                "                                                <th scope='row' width='200'>Mã đơn hàng</th>" +
                "                                                <td>"+json.data.order_info.order_id_client+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị đơn hàng</th>" +
                "                                                <td>"+shop.numberFormat(json.data.order_info.total_payment)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Tổng giá trị thanh toán</th>" +
                "                                                <td>"+shop.numberFormat(json.data.order_info.total_payment)+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Payment Time</th>" +
                "                                                <td>"+json.data.payment_time+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Payment Method</th>" +
                "                                                <td>"+json.data.order_info.paygate+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Transaction_ID</th>" +
                "                                                <td>"+json.data.order_info.order_payment_id+"</td>" +
                "                                            </tr>" +
                "                                            <tr>" +
                "                                                <th scope='row'>Kết quả giao dịch</th>" +
                "                                                <td>"+json.data.msg+"</td>" +
                "                                            </tr>" +
                "                                            </tbody>" +
                "                                        </table>";

            $('#order_wepay').html(html);
        } else {
            alert(json.msg);
        }
    });
};