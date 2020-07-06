<template>
  <header>
    <div class="header-top">
      <div class="container header-top-main">
         <a href="javascript:;" class="nav-mobile d-inline-block d-lg-none js-show-menu-mobile">
            <i class="fa fa-bars" aria-hidden="true"></i>
          </a>
        <a href="/" class="logo">
          <img src="images/logo.png" alt />
        </a>
        <div class="icon_mobile">
          <a href="#" class="icon_mobile-item">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
          </a>
          <a class="mf-hdcall" href="tel:18006601" title="">
            <span>Gọi miễn phí</span>
            <strong>1800 6601</strong>
        </a>
        </div>
        <div class="header-search">
          <form action class="form-search">
            <input type="text" class="form-control" placeholder="Nhập tên điện thoại, máy tính, phụ kiện...cần tìm" />
            <button class="search-btn">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </form>
        </div>
        <div class="header-top-link">
          <a href="#" class="top-link-item hang-my">Hàng Mỹ</a>
          <a href="#" class="top-link-item tin-tuc">Tin tức</a>
          <a href="#" class="top-link-item gio-hang">
            Giỏ hàng
            <span class="counter-cart">0</span>
          </a>
        </div>
      </div>
    </div>
    <div class="menu">
      <nav class="container">
        <!-- <div class="border-bottom d-flex d-lg-none justify-content-between align-items-center">
          <a
            href="javascript:;"
            class="d-inline-block d-lg-none btn-menu-show text-gray px-3 py-3 js-hide-menu"
          >
            <i aria-hidden="true" class="fa fa-chevron-left"></i>
          </a>
          <a href="/" class="logo">
            <img src="images/logo.png" alt />
          </a>
        </div> -->
        <ul class="main-menu">
          <li class="menu-item d-none">
            <a href="#" class="top-link-item hang-my">Hàng Mỹ</a>
            <a href="#" class="top-link-item tin-tuc">Tin tức</a>
          </li>
          <li class="menu-item">
            <a href="#">
              <img src="images/icon_phone.png" alt />
              <span>trang chủ</span>
            </a>
          </li>
          <li class="menu-item item-has-child">
            <a href="javascript:;">
              <img src="images/icon_apple.png" alt />
              <span>apple</span>
            </a>
            <div class="mega-menu-drop apple-drop">
              <div class="mega-menu-main">
                <div class="mega-col mega-col-1">
                  <h6 class="title">các sản phẩm apple</h6>
                  <ul>
                    <li>
                      <a href="#">iPhone</a>
                    </li>
                    <li>
                      <a href="#">iPad</a>
                    </li>
                    <li>
                      <a href="#">Macbook</a>
                    </li>
                    <li>
                      <a href="#">iMac</a>
                    </li>
                    <li>
                      <a href="#">Mac mini</a>
                    </li>
                    <li>
                      <a href="#">Mac Pro</a>
                    </li>
                    <li>
                      <a href="#">Watch</a>
                    </li>
                    <li>
                      <a href="#">Apple TV</a>
                    </li>
                    <li>
                      <a href="#">Phụ kiện</a>
                    </li>
                    <li>
                      <a href="#">Beats</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col mega-col-2">
                  <h6 class="title">Mức giá</h6>
                  <ul>
                    <li>
                      <a href="#">Dưới 5 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 5 - 10 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 10 - 15 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 15-20 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 20-25 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 25- 30 triệu</a>
                    </li>
                    <li>
                      <a href="#">Trên 30 triệu</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col mega-col-3">
                  <h6 class="title">bán chạy nhất</h6>
                  <ul>
                    <li class="menu-product-item">
                      <a href="#" class="p-img">
                        <img src="images/product_menu.png" alt />
                      </a>
                      <div class="p-info">
                        <a href="#" class="p-name">iPhone 7 Plus 32Gb</a>
                        <span class="p-price">13.990.000 đ</span>
                      </div>
                    </li>
                    <li class="menu-product-item">
                      <a href="#" class="p-img">
                        <img src="images/product_menu1.png" alt />
                      </a>
                      <div class="p-info">
                        <a href="#" class="p-name">iPhone 7 Plus 32Gb</a>
                        <span class="p-price">13.990.000 đ</span>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="mega-col col-image">
                  <a href="#" class="banner_mega-menu">
                    <img src="images/banner_mega_menu.png" alt />
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li class="menu-item">
            <a href="#">
              <img src="images/icon_windown.png" alt />
              <span>Microsoft</span>
            </a>
          </li>
          <li class="menu-item item-has-child">
            <a href="javascript:;">
              <img src="images/icon_laptop.png" alt />
              <span>laptop</span>
            </a>
            <div class="mega-menu-drop laptop-drop">
              <div class="mega-menu-main">
                <div class="mega-col mega-col-1">
                  <h6 class="title">các sản phẩm apple</h6>
                  <ul>
                    <li>
                      <a href="#">iPhone</a>
                    </li>
                    <li>
                      <a href="#">iPad</a>
                    </li>
                    <li>
                      <a href="#">Macbook</a>
                    </li>
                    <li>
                      <a href="#">iMac</a>
                    </li>
                    <li>
                      <a href="#">Mac mini</a>
                    </li>
                    <li>
                      <a href="#">Mac Pro</a>
                    </li>
                    <li>
                      <a href="#">Watch</a>
                    </li>
                    <li>
                      <a href="#">Apple TV</a>
                    </li>
                    <li>
                      <a href="#">Phụ kiện</a>
                    </li>
                    <li>
                      <a href="#">Beats</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col mega-col-2">
                  <h6 class="title">Màn hình</h6>
                  <div class="head-mega-list-drop">
                    <div class="item-dropdown">
                        <div class="drop-control">
                             Màn hình theo hãng
                        </div>
                         <ul class="content">
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình SamSung</a></li>
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                        </ul>
                    </div>
                    <div class="item-dropdown">
                        <div class="drop-control">
                             Màn hình theo hãng
                        </div>
                         <ul class="content">
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình SamSung</a></li>
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                        </ul>
                    </div>
                    <div class="item-dropdown">
                        <div class="drop-control">
                             Màn hình theo hãng
                        </div>
                         <ul class="content">
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình SamSung</a></li>
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                        </ul>
                    </div>
                    <div class="item-dropdown">
                        <div class="drop-control">
                             Màn hình theo hãng
                        </div>
                         <ul class="content">
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình SamSung</a></li>
                            <li><a href="#">Màn hình Dell</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                            <li><a href="#">Màn hình Asus</a></li>
                        </ul>
                    </div>
                   </div>
                </div>
                <div class="mega-col mega-col-3">
                  <h6 class="title">mục đích sử dụng</h6>
                  <ul>
                    <li>
                      <a href="#">Sản phẩm 2 trong 1</a>
                    </li>
                    <li>
                      <a href="#">Windows</a>
                    </li>
                    <li>
                      <a href="#">Giá rẻ</a>
                    </li>
                    <li>
                      <a href="#">Hiện đại</a>
                    </li>
                    <li>
                      <a href="#">Gaming - Đồ họa</a>
                    </li>
                    <li>
                      <a href="#">Sinh viên - Văn phòng</a>
                    </li>
                    <li>
                      <a href="#">Mỏng nhẹ</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col col-image">
                  <a href="#" class="banner_mega-menu">
                    <img src="images/banner_mega_menu.png" alt />
                  </a>
                </div>
              </div>
            </div>
          </li>
          <li class="menu-item">
            <a href="#">
              <img src="images/icon_pc.png" alt />
              <span>máy tính pc</span>
            </a>
          </li>
          <li class="menu-item">
            <a href="#">
              <img src="images/icon_laptop1.png" alt />
              <span>kho laptop cũ</span>
            </a>
          </li>
          <li class="menu-item item-has-child">
            <a href="javascript:;">
              <img src="images/icon_headphone.png" alt />
              <span>phụ kiện</span>
            </a>
            <div class="mega-menu-drop apple-drop">
              <div class="mega-menu-main">
                <div class="mega-col mega-col-1">
                  <h6 class="title">các sản phẩm apple</h6>
                  <ul>
                    <li>
                      <a href="#">iPhone</a>
                    </li>
                    <li>
                      <a href="#">iPad</a>
                    </li>
                    <li>
                      <a href="#">Macbook</a>
                    </li>
                    <li>
                      <a href="#">iMac</a>
                    </li>
                    <li>
                      <a href="#">Mac mini</a>
                    </li>
                    <li>
                      <a href="#">Mac Pro</a>
                    </li>
                    <li>
                      <a href="#">Watch</a>
                    </li>
                    <li>
                      <a href="#">Apple TV</a>
                    </li>
                    <li>
                      <a href="#">Phụ kiện</a>
                    </li>
                    <li>
                      <a href="#">Beats</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col mega-col-2">
                  <h6 class="title">Mức giá</h6>
                  <ul>
                    <li>
                      <a href="#">Dưới 5 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 5 - 10 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 10 - 15 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 15-20 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 20-25 triệu</a>
                    </li>
                    <li>
                      <a href="#">Từ 25- 30 triệu</a>
                    </li>
                    <li>
                      <a href="#">Trên 30 triệu</a>
                    </li>
                  </ul>
                </div>
                <div class="mega-col mega-col-3">
                  <h6 class="title">bán chạy nhất</h6>
                  <ul>
                    <li class="menu-product-item">
                      <a href="#" class="p-img">
                        <img src="images/phu-kien.png" alt />
                      </a>
                      <div class="p-info">
                        <a
                          href="#"
                          class="p-name"
                        >Combo Loa Bluetooth Karaoke i.value F12-65N Nhựa đen + Mic không dây</a>
                        <span class="p-price">13.990.000 đ</span>
                      </div>
                    </li>
                    <li class="menu-product-item">
                      <a href="#" class="p-img">
                        <img src="images/phu-kien.png" alt />
                      </a>
                      <div class="p-info">
                        <a
                          href="#"
                          class="p-name"
                        >Combo Loa Bluetooth Karaoke i.value F12-65N Nhựa đen + Mic không dây</a>
                        <span class="p-price">13.990.000 đ</span>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="mega-col col-image"></div>
              </div>
            </div>
          </li>
          <li class="menu-item">
            <a href="#">
              <img src="images/icon_percent.png" alt />
              <span>Thu cũ đổi mới</span>
            </a>
          </li>
          <li class="menu-item mobile-hiden">
            <a href="#">
              <img src="images/icon_pic.png" alt />
              <span>trả góp 0%</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="mobile-menu d-block d-lg-none">
        <div class="head">
             <a href="/" class="logo">
              <img src="images/logo.png" alt />
            </a>
            <div class="close-menu"><i class="fa fa-times"></i></div>
        </div>
       <div class="main-menu">
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_phone.png" alt />
              <div class="wrap">
                <span>trang chủ</span>
              </div>
            </a>
          </div>
          <div class="menu-item item-has-child">
            <div class="wrap-cate-has-child">
              <a href="" class="cate-1">
                <img src="images/icon_apple.png" alt />
                <div class="wrap">
                  <span>apple</span>
                </div>
              </a>
              <i class="fa fa-angle-down"></i>
            </div>
            <div class="mobile-menu-drop" style="display: none">
                <a href="" class="cate-2"><span>iPhone</span></a>
                <a href="" class="cate-2"><span>iPad</span></a>
                <a href="" class="cate-2"><span>Macbook</span></a>
                <a href="" class="cate-2"><span>iMac</span></a>
                <a href="" class="cate-2"><span>Mac mini</span></a>
                <a href="" class="cate-2"><span>Mac Pro</span></a>
                <a href="" class="cate-2"><span>Watch</span></a>
                <a href="" class="cate-2"><span>Apple TV</span></a>
            </div>
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_windown.png" alt />
               <div class="wrap">
                <span>Microsoft</span>                
              </div>
            </a>
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_laptop.png" alt />
              <div class="wrap">
                <span>laptop</span>                                
              </div>
            </a>
  
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_pc.png" alt />
              <div class="wrap">
                <span>máy tính pc</span>                                              
              </div>
            </a>
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_laptop1.png" alt />
              <div class="wrap">
                <span>kho laptop cũ</span>                                                            
              </div>
            </a>
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_headphone.png" alt />
              <div class="wrap">
                <span>kho laptop cũ</span>                                                            
              </div>
            </a>
           
          </div>
          <div class="menu-item">
            <a href="" class="cate-1">
              <img src="images/icon_percent.png" alt />
               <div class="wrap">
                <span>kho laptop cũ</span>                                                            
              </div>
            </a>
          </div>
          <div class="menu-item mobile-hiden">
            <a href="" class="cate-1">
              <img src="images/icon_pic.png" alt />
               <div class="wrap">
                <span>kho laptop cũ</span>                                                            
              </div>
            </a>
          </div>
        </div>
    </div>
  </header>
</template>