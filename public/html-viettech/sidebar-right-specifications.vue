<template>
    <div class="sidebar-right specifications">
        <div class="title">Thông số kỹ thuật</div>
        <div class="specifications-desc">
            <div class="desc-detail">
                <span>CPR :</span>
                <span>Intel, Core i3</span>
            </div>
            <div class="desc-detail">
                <span>RAM :</span>
                <span>4GB, DDR4</span>
            </div>
            <div class="desc-detail">
                <span>Ổ cứng :</span>
                <span>HĐ 5400rpm, HDD: 1TB SATA3, hỗ trợ khe cắm SSD M2, SATA 3</span>
            </div>
            <div class="desc-detail">
                <span>Màn hình :</span>
                <span>15.6 inchs, 1920x1080px</span>
            </div>
            <div class="desc-detail">
                <span>Card màn hình :</span>
                <span>Intel@, HD graphics</span>
            </div>
            <div class="desc-detail">
                <span>Cổng kết nối :</span>
                <span>LAN : Không, Wifi : 802.11 b/g/n</span>
            </div>
            <div class="desc-detail">
                <span>Hệ điều hành</span>
                <span>Windows 10 Home</span>
            </div>
            <div class="desc-detail">
                <span>Trọng lượng :</span>
                <span>1.68 Kg</span>
            </div>
            <div class="desc-detail">
                <span>Kích thước :</span>
                <span>365x256x21.9 mm</span>
            </div>
            <div class="view-detail">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalSpecifications">
                    Xem cấu hình chi tiết
                    <img src="images/icon-arrow-blue.png" alt />
                </button>
            </div>
        </div>
        <div class="modal fade" id="modalSpecifications">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Thông số kỹ thuật chi tiết</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <ul class="fs-dttsktul list-unstyled" style="max-width : 100%;">

                            <li class="modal-specifications-title">Bộ xử lý</li>
                            <li>
                                <label data-id="49">Hãng CPU :</label>
                                <span><a target="_blank" href="" title="Intel">Intel</a></span>
                            </li>
                            <li>
                                <label data-id="50">Công nghệ CPU :</label>
                                <span><a target="_blank" href="" title="Core i9">Core i9</a></span>
                            </li>
                            <li>
                                <label data-id="51">Loại CPU :</label>
                                <span>8 - core</span>
                            </li>
                            <li>
                                <label data-id="52">Tốc độ CPU :</label>
                                <span>2.3GHz</span>
                            </li>
                            <li>
                                <label data-id="53">Bộ nhớ đệm :</label>
                                <span>16 MB L3 Cache</span>
                            </li>
                            <li>
                                <label data-id="517">Tốc độ tối đa :</label>
                                <span>4.8GHz</span>
                            </li>
                            <li class="modal-specifications-title">Bo mạch</li>
                            <li>
                                <label data-id="58">Chipset :</label>
                                <span>8 Lõi</span>
                            </li>
                            <li>
                                <label data-id="59">Tốc độ Bus :</label>
                                <span>2400 MHz</span>
                            </li>
                            <li>
                                <label data-id="60">Hỗ trợ RAM tối đa :</label>
                                <span>32 GB</span>
                            </li>
                            <li class="modal-specifications-title">RAM</li>
                            <li>
                                <label data-id="61">Dung lượng RAM :</label>
                                <span>16 GB</span>
                            </li>
                            <li>
                                <label data-id="62">Loại RAM :</label>
                                <span><a target="_blank" href="" title="DDR4">DDR4</a></span>
                            </li>
                            <li>
                                <label data-id="63">Tốc độ BUS RAM :</label>
                                <span>2400 MHz</span>
                            </li>
                            <li>
                                <label data-id="1258">Số lượng khe RAM :</label>
                                <span>2</span>
                            </li>
                            <li class="modal-specifications-title">Đĩa cứng</li>
                            <li>
                                <label data-id="64">Loại ổ đĩa :</label>
                                <span><a target="_blank" href="" title="SSD">SSD</a></span>
                            </li>
                            <li>
                                <label data-id="65">Dung lượng ổ đĩa :</label>
                                <span>512 GB</span>
                            </li>
                            <li>
                                <label data-id="1259">Khe cắm ổ SSD :</label>
                                <span>Không</span>
                            </li>
                            <li class="modal-specifications-title">Đồ họa</li>
                            <li>
                                <label data-id="73">Chipset đồ họa :</label>
                                <span>Radeon Pro 560X</span>
                            </li>
                            <li>
                                <label data-id="74">Bộ nhớ đồ họa :</label>
                                <span>4 GB</span>
                            </li>
                            <li>
                                <label data-id="75">Kiểu thiết kế đồ họa :</label>
                                <span>Card rời và tích hợp</span>
                            </li>
                            <li class="modal-specifications-title">Màn hình</li>
                            <li>
                                <label data-id="773">Kích thước màn hình :</label>

                            </li>
                            <li>
                                <label data-id="774">Độ phân giải (W x H) :</label>
                                <span>2880 x 1800 Pixels</span>
                            </li>
                            <li>
                                <label data-id="775">Công nghệ màn hình :</label>
                                <span><a target="_blank" href="" title="Retina">Retina</a></span>
                            </li>
                            <li>
                                <label data-id="776">Cảm ứng :</label>
                                <span>Không</span>
                            </li>
                            <li class="modal-specifications-title">Âm thanh</li>
                            <li>
                                <label data-id="76">Kênh âm thanh :</label>
                                <span>5.0</span>
                            </li>
                            <li>
                                <label data-id="77">Thông tin thêm :</label>
                                <span><a target="_blank" href="" title="Không">Không</a></span>
                            </li>
                            <li class="modal-specifications-title">Đĩa quang</li>
                            <li>
                                <label data-id="78">Có sẵn đĩa quang :</label>
                                <span><a target="_blank" href="" title="Không">Không</a></span>
                            </li>
                            <li class="modal-specifications-title">Tính năng mở rộng &amp; cổng giao tiếp</li>
                            <li>
                                <label data-id="82">Cổng giao tiếp :</label>
                                <span><a target="_blank" href="" title="4 cổng USB-C tích hợp Thunderbolt 3 và 1 cổng tai nghe 3.5">4 cổng USB-C tích hợp Thunderbolt 3 và 1 cổng tai nghe 3.5</a></span>
                            </li>
                            <li>
                                <label data-id="1335">Tính năng mở rộng :</label>
                                <span>Không</span>
                            </li>
                            <li class="modal-specifications-title">Giao tiếp mạng</li>
                            <li>
                                <label data-id="779">LAN :</label>
                                <span>Không</span>
                            </li>
                            <li>
                                <label data-id="780">Chuẩn Wi-Fi :</label>
                                <span><a target="_blank" href="" title="802.11 a/b/g/n/ac">802.11 a/b/g/n/ac</a></span>
                            </li>
                            <li>
                                <label data-id="1346">Kết nối không dây khác :</label>
                                <span><a target="_blank" href="" title="Bluetooth 5.0">Bluetooth 5.0</a></span>
                            </li>
                            <li class="modal-specifications-title">Card Reader</li>
                            <li>
                                <label data-id="1336">Đọc thẻ nhớ :</label>
                                <span>Không</span>
                            </li>
                            <li>
                                <label data-id="1337">Khe đọc thẻ nhớ :</label>
                                <span>Không</span>
                            </li>
                            <li class="modal-specifications-title">Webcam</li>
                            <li>
                                <label data-id="1338">Độ phân giải :</label>
                                <span>720p FaceTime HD camera</span>
                            </li>
                            <li>
                                <label data-id="1339">Thông tin thêm :</label>
                                <span><a target="_blank" href="" title="HD Webcam">HD Webcam</a></span>
                            </li>
                            <li class="modal-specifications-title">Hệ điều hành, phầm mềm có sẵn</li>
                            <li>
                                <label data-id="96">Hệ điều hành :</label>
                                <span><a target="_blank" href="" title="Mac OS">Mac OS</a></span>
                            </li>
                            <li>
                                <label data-id="1340">Phần mềm có sẵn :</label>
                                <span>Không</span>
                            </li>
                            <li class="modal-specifications-title">PIN/Battery</li>
                            <li>
                                <label data-id="88">Loại pin :</label>
                                <span>83.6 W h Li-Poly</span>
                            </li>
                            <li>
                                <label data-id="1260">Kiểu pin :</label>
                                <span><a target="_blank" href="" title="Lithium-Polymer, Liền">Lithium-Polymer, Liền</a></span>
                            </li>
                            <li class="modal-specifications-title">Thông tin khác</li>
                            <li>
                                <label data-id="1263">Cảm biến vân tay :</label>
                                <span>Có</span>
                            </li>
                            <li>
                                <label data-id="1264">Đèn bàn phím :</label>
                                <span>Có</span>
                            </li>
                            <li>
                                <label data-id="1265">Bàn phím số :</label>
                                <span>Không</span>
                            </li>
                            <li>
                                <label data-id="1266">Phụ kiện kèm theo :</label>
                                <span>Củ sạc, dây sạc</span>
                            </li>
                            <li class="modal-specifications-title">Kích thước &amp; trọng lượng</li>
                            <li>
                                <label data-id="1341">Kích Thước :</label>
                                <span>Cao 1.55 cm - Dài 34.93 cm - Rộng 24.07 cm</span>
                            </li>
                            <li>
                                <label data-id="93">Trọng lượng :</label>
                                <span>1.83 kg</span>
                            </li>
                            <li>
                                <label data-id="1261">Chất liệu :</label>
                                <span>Nhôm nguyên khối</span>
                            </li>
                            <li class="modal-specifications-title">Bảo hành</li>
                            <li>
                                <label data-id="94">Thời gian bảo hành :</label>
                                <span>12 Tháng</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
</template>