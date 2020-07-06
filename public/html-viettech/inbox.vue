<template>
    <div class="inbox">
        <a href="javascript:;" v-on:click="show_inbox" class="btn-show-popup">Gửi tin nhắn</a>
        <div class="popup_inbox" style="display: none" v-on:click="close_inbox">
            <div class="popup-main">
                <div class="popup_inbox-step1">
                    <h5>Gửi câu hỏi</h5>
                    <p>Vui lòng nhập câu hỏi bên dưới</p>
                    <textarea name="" id="messeng" cols="30" rows="6" placeholder="Vui lòng nhập câu hỏi của bạn"></textarea>
                    <a href="javascript:;" v-on:click="sendMes">Tiếp theo <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                </div>
                <div class="popup_inbox-step2" style="display: none">
                    <h5>Nhập Email</h5>
                    <p>Vui lòng nhập email và tên bên dưới</p>
                    <input type="text" id="mes_mail" placeholder="Nhập email hoặc sđt của bạn">
                    <input type="text" id="mes_name" placeholder="Nhập tên của bạn">
                    <a href="javascript:;" v-on:click="sendInfo">Gửi <img src="images/ic_send.png" alt=""></a>
                </div>
                <div class="popup_inbox-step3" style="display: none">
                    <img src="images/ic_check.png" alt="">
                    <h4>Chúc mừng <br> bạn đã gửi thành công !</h4>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                messeng: '',
                email: '',
                name: '',
            }
        },
        methods: {
            sendMes: function(even){
                messeng = $('#messeng').val();
                if(messeng.trim() === ''){
                    alert('Vui lòng nhập câu hỏi');
                }else{
                    $('.popup_inbox-step1').hide();
                    $('.popup_inbox-step2').show();
                }
            },
            sendInfo: function(even){
                let err = '';
                email = $('#mes_mail').val();
                name = $('#mes_name').val();
                if(email === ''){
                    err += 'Vui lòng nhập email hoạc số điện thoại \n';
                }
                if(name === ''){
                    err += 'Vui lòng nhập tên';
                }
                if(err != ''){
                    alert(err);
                }else{
                    $('.popup_inbox-step2').hide();
                    $('.popup_inbox-step3').show();
                    alert(messeng + '\n'+ email + '\n' + name);
                }
            },
            show_inbox: function(even){
                $('.popup_inbox').show();
            },
            close_inbox: function(){
                $(document).mouseup(function(e) {
                    var container = $(".popup-main");
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        $('.popup_inbox').hide();
                    }
                });
            }
        }
    }
</script>