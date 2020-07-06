<template>
  <div id="faq" class="main-wrap faq">
    <div class="title heading">Hỏi Đáp về Acer Nitro AN515-52-51GF</div>
    <div class="form-group">
      <textarea
        placeholder="Viết bình luận của bạn( Vui lòng gõ tiếng Việt có dấu) "
        class="form-control"
        rows="5"
        id="comment"
        style="overflow:auto"
      ></textarea>
    </div>
    <div class="send text-right">
      <button>Gửi câu hỏi</button>
    </div>
    <div class="for-user">
      <div class="user">
        <img src="images/icon-person.png" alt />
        Phạm Đức phước
        <span>9 giờ trước</span>
      </div>
      <div
        class="user-comment"
      >Lorem ipsum dolor, sit amet consectetur adipisicing elit. Mollitia est, doloribus laboriosam blanditiis provident pariatur tempore nisi neque. Eveniet eaque rerum ex aliquam omnis ad dolorum maiores sit magni numquam!</div>
      <div class="reply">
        <a href="javscript:;">Trả lời</a>
      </div>
    </div>
    <div class="for-admin">
      <div class="name">
        <div class="avatar">
          <img src="images/icon-person.png" alt />
        </div>
        <div class="ad-fullname">
          Nguyễn Hông Quân
          <span>QTV</span>
          <small>23 giờ trc</small>
        </div>
      </div>
      <div
        class="admin-comment"
      >Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam necessitatibus quibusdam perspiciatis corrupti in doloremque libero, temporibus ad maiores explicabo facilis porro magni asperiores dicta placeat incidunt. Cumque, veritatis voluptatem!</div>
    </div>
    <div class="box-reply">
      <textarea
        rows="3"
        class="box-ad-comment"
        placeholder="Viết bình luận của bạn (Vui lòng gõ tiếng Việt có dấu)"
      ></textarea>
      <div class="fs-cmbtn-send text-right">
        <button class="btn_comment_send_sub" type="button">Gửi câu hỏi</button>
      </div>
    </div>
  </div>
</template>