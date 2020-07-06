<div class="inbox" id="inbox">
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
                <a href="javascript:;" v-on:click="sendInfo">Gửi <img data-src="{{asset('html-viettech/images/ic_send.png')}}" class="lazyload" alt=""></a>
            </div>
            <div class="popup_inbox-step3" style="display: none">
                <img src="images/ic_check.png" alt="">
                <h4>Chúc mừng <br> bạn đã gửi thành công !</h4>
            </div>
        </div>
    </div>
</div>
