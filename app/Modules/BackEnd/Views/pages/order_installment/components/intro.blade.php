<script type="text/javascript">
    function introBooking() {
        intro.setOptions({
            steps: [
                {
                    element: '#booking_search_collapse',
                    intro: 'Tìm kiếm đơn hàng',
                    position: 'left'
                },
                {
                    element: '#booking_add_order',
                    intro: "Thêm đơn đặt món cho khách hàng",
                    position: 'bottom'
                },
                {
                    element: '#booking_add_table',
                    intro: "Thêm đơn đặt bàn cho khách hàng",
                    position: 'bottom'
                },
                {
                    element: '#tab_order_news',
                    intro: "Danh sách các đơn mới cập nhật",
                    position: 'bottom'
                },
                {
                    element: '#tab_order_received',
                    intro: "Danh sách các đơn đã được tiếp nhận",
                    position: 'bottom'
                },
                {
                    element: '#tab_order_shipping',
                    intro: "Danh sách các đơn đã được xác nhận từ CSKH và đang chuyển cho bộ phận ship",
                    position: 'bottom'
                },
                {
                    element: '#tab_order_paid',
                    intro: "Danh sách các đơn đã thanh toán",
                    position: 'bottom'
                },
                {
                    element: '#tab_order_completed',
                    intro: "Danh sách các đơn đã hoàn thành",
                    position: 'bottom'
                }
            ]
        });
        intro.start();
        $.cookie("show_booking_index", false);
    }

</script>