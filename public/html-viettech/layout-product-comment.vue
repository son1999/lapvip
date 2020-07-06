<template>
  <div id="comment" class="main-wrap comment">
    <div class="title heading">Đánh giá & Nhận xét Acer Nitro AN515-52-51GF</div>
    <div class="box-rating">
      <div class="box-star text-center clearfix">
        <div class="point text-center">3,6/5</div>
        <div class="stars">
          <input
            disabled
            type="hidden"
            class="rating"
            data-filled="star star-filled"
            data-empty="star star-empty"
            data-fractions="2"
            value="5"
          />
        </div>
        <p>103 đánh giá & nhận xét</p>
      </div>
      <div class="box-evaluation text-center">
        <div class="box-evaluation-line">
          <div class="star">5 sao</div>
          <div class="progress-bar-rating">
            <span class="rate-count-show five-star" style="width: 30%;"></span>
          </div>
          <div class="total-rate">40</div>
        </div>

        <div class="box-evaluation-line">
          <div class="star">4 sao</div>
          <div class="progress-bar-rating">
            <span class="rate-count-show four-star" style="width: 55%;"></span>
          </div>
          <div class="total-rate">30</div>
        </div>

        <div class="box-evaluation-line">
          <div class="star">3 sao</div>
          <div class="progress-bar-rating">
            <span class="rate-count-show three-star" style="width: 53%;"></span>
          </div>
          <div class="total-rate">23</div>
        </div>

        <div class="box-evaluation-line">
          <div class="star">2 sao</div>
          <div class="progress-bar-rating">
            <span class="rate-count-show two-star" style="width: 20%;"></span>
          </div>
          <div class="total-rate">34</div>
        </div>

        <div class="box-evaluation-line">
          <div class="star">1 sao</div>
          <div class="progress-bar-rating">
            <span class="rate-count-show one-star" style="width: 12%;"></span>
          </div>
          <div class="total-rate">30</div>
        </div>
      </div>
      <div class="box-send-evaluation text-center">
        <p>Bạn đã dùng sản phẩm này?</p>
        <button class="btn">
          Gửi đánh giá của bạn
          <img src="images/icon-arrow.png" alt />
        </button>
      </div>
    </div>
    <div class="modal-box-comment">
      <div class="modal-box-title">Gửi nhận xét của bạn</div>
      <div class="modal-box-content">
        <p>Bạn chấm sản phẩm này bao nhiêu sao?</p>
        <fieldset class="rating">
          <input type="radio" id="star5" name="rating" value="5" />
          <label class="full" for="star5" title="Awesome - 5 stars"></label>
          <input type="radio" id="star4half" name="rating" value="4 and a half" />
          <label class="half" for="star4half" title="Pretty good - 4.5 stars"></label>
          <input type="radio" id="star4" name="rating" value="4" />
          <label class="full" for="star4" title="Pretty good - 4 stars"></label>
          <input type="radio" id="star3half" name="rating" value="3 and a half" />
          <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
          <input type="radio" id="star3" name="rating" value="3" />
          <label class="full" for="star3" title="Meh - 3 stars"></label>
          <input type="radio" id="star2half" name="rating" value="2 and a half" />
          <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
          <input type="radio" id="star2" name="rating" value="2" />
          <label class="full" for="star2" title="Kinda bad - 2 stars"></label>
          <input type="radio" id="star1half" name="rating" value="1 and a half" />
          <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
          <input type="radio" id="star1" name="rating" value="1" />
          <label class="full" for="star1" title="Sucks big time - 1 star"></label>
          <input type="radio" id="starhalf" name="rating" value="half" />
          <label class="half" for="starhalf" title="Sucks big time - 0.5 stars"></label>
        </fieldset>
        <textarea
          id="txtNoteRating"
          name="txtNoteRating"
          rows="3"
          class
          placeholder="Bạn có khuyên người khác mua sản phẩm này không? Tại sao?"
        ></textarea>
        <div class="actions">
          <div class="action-left">Một đánh giá có ích thường dài từ 100 ký tự trở lên</div>
          <div class="action-right">
            <button type="button" class="btn-cancel" href title>Hủy</button>
            <button type="button" class="btn-send">Gửi</button>
          </div>
        </div>
      </div>
    </div>
    <div class="comment-wrap">
      <div class="comment-wrap-top d-flex justify-content-between">
        <div class="comment-wrap-top-left">
          Khách hàng nhận xét
          <span>(45)</span>
        </div>
        <div class="comment-wrap-top-tab">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#new">Mới nhất</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#useful">Hữu ích nhất</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Tab panes -->
      <div class="tab-content">
        <div id="new" class="tab-pane active">
          <div class="comment-wrap-detail">
            <div class="customer-comment-detail">
              <div class="stars">
                <input
                  disabled
                  type="hidden"
                  class="rating"
                  data-filled="star star-filled"
                  data-empty="star star-empty"
                  data-fractions="2"
                  value="3.5"
                />
              </div>Bởi : Trang
              <img src="images/icon-tick-green.png" alt />
              <span class="text">Đã mua tại LAPVIP Shop</span> vào ngày 1205/2019
              <br />Máy khá ổn mua dùng ngon, sướng, phê càng dùng càng hay
            </div>
          </div>
          <div class="content" id="show-more-product" style="display:none">
            <div class="comment-wrap-detail">
              <div class="customer-comment-detail">
                <div class="stars">
                  <input
                    disabled
                    type="hidden"
                    class="rating"
                    data-filled="star star-filled"
                    data-empty="star star-empty"
                    data-fractions="2"
                    value="3.5"
                  />
                </div>Bởi : Trang
                <img src="images/icon-tick-green.png" alt />
                <span class="text">Đã mua tại LAPVIP Shop</span> vào ngày 1205/2019
                <br />Máy khá ổn mua dùng ngon, sướng, phê càng dùng càng hay
              </div>
            </div>
            <div class="comment-wrap-detail">
              <div class="customer-comment-detail">
                <div class="stars">
                  <input
                    disabled
                    type="hidden"
                    class="rating"
                    data-filled="star star-filled"
                    data-empty="star star-empty"
                    data-fractions="2"
                    value="3.5"
                  />
                </div>Bởi : Trang
                <img src="images/icon-tick-green.png" alt />
                <span class="text">Đã mua tại LAPVIP Shop</span> vào ngày 1205/2019
                <br />Máy khá ổn mua dùng ngon, sướng, phê càng dùng càng hay
              </div>
            </div>
            <div class="comment-wrap-detail">
              <div class="customer-comment-detail">
                <div class="stars">
                  <input
                    disabled
                    type="hidden"
                    class="rating"
                    data-filled="star star-filled"
                    data-empty="star star-empty"
                    data-fractions="2"
                    value="3.5"
                  />
                </div>Bởi : Trang
                <img src="images/icon-tick-green.png" alt />
                <span class="text">Đã mua tại LAPVIP Shop</span> vào ngày 1205/2019
                <br />Máy khá ổn mua dùng ngon, sướng, phê càng dùng càng hay
              </div>
            </div>
          </div>
          <p class="show-more">
            <a href="#show-more-product" class="nav-toggle-comment">
              Hiển thị thêm 41 sản phẩm
              <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
          </p>
        </div>
        <div id="useful" class="tab-pane fade">
          <br />
          <h3>Menu 1</h3>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
          <h3>Menu 1</h3>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
        </div>
      </div>
    </div>
  </div>
</template>