<template>
    <div class="sidebar-left-filter-wrap">
        <div class="title">bộ lọc</div>
        <div id="accordion" role="tablist">
            <div class="card manufacturer">
                <div class="card-header" role="tab" id="headingOne">
                <h5 class="mb-0">
                    <a
                    data-toggle="collapse"
                    href="#collapseOne"
                    aria-expanded="true"
                    aria-controls="collapseOne"
                    >Hãng sản xuất</a>
                </h5>
                </div>

                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                <div class="card-body has-scroll">
                    <div class="box-search">
                    <input class="txtthuonghieu" type="text" placeholder="Tìm thương hiệu">
                    <button><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                <div class="inner">
                    <label>
                    <input type="checkbox" checked="checked" />Tất cả
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Apple ( Macbook)
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Asus
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Dell
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />HP
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Lenovo
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />MSI
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Masstel
                    <span class="checkmark"></span>
                    </label>
                    <label>
                    <input type="checkbox" />Haier
                    <span class="checkmark"></span>
                    </label>
                </div>
                </div>
                </div>
            </div>

            <div class="card price ">
                <div class="card-header" role="tab" id="headingTwo">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseTwo"
                    aria-expanded="false"
                    aria-controls="collapseTwo"
                    >Mức giá</a>
                </h5>
                </div>
                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card cpu">
                <div class="card-header" role="tab" id="headingThree">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseThree"
                    aria-expanded="false"
                    aria-controls="collapseThree"
                    >CPU</a>
                </h5>
                </div>
                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card ram">
                <div class="card-header" role="tab" id="headingFour">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseFour"
                    aria-expanded="false"
                    aria-controls="collapseFour"
                    >RAM</a>
                </h5>
                </div>
                <div id="collapseFour" class="collapse" role="tabpanel" aria-labelledby="headingFour">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card graphic-card">
                <div class="card-header" role="tab" id="headingFive">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseFive"
                    aria-expanded="false"
                    aria-controls="collapseFive"
                    >Loại card đồ họa</a>
                </h5>
                </div>
                <div id="collapseFive" class="collapse" role="tabpanel" aria-labelledby="headingFive">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card promotional-laptop">
                <div class="card-header" role="tab" id="headingSix">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseSix"
                    aria-expanded="false"
                    aria-controls="collapseSix"
                    >Laptop khuyến mại</a>
                </h5>
                </div>
                <div id="collapseSix" class="collapse" role="tabpanel" aria-labelledby="headingSix">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card hard-drive">
                <div class="card-header" role="tab" id="headingSeven">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseSeven"
                    aria-expanded="false"
                    aria-controls="collapseSeven"
                    >Loại ổ cứng</a>
                </h5>
                </div>
                <div id="collapseSeven" class="collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card operating-system">
                <div class="card-header" role="tab" id="headingE  ight">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseEight"
                    aria-expanded="false"
                    aria-controls="collapseEight"
                    >Hệ điều hành</a>
                </h5>
                </div>
                <div id="collapseEight" class="collapse" role="tabpanel" aria-labelledby="headingEight">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card screen">
                <div class="card-header" role="tab" id="headingNine">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseNine"
                    aria-expanded="false"
                    aria-controls="collapseNine"
                    >Màn hình</a>
                </h5>
                </div>
                <div id="collapseNine" class="collapse" role="tabpanel" aria-labelledby="headingNine">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

            <div class="card features">
                <div class="card ">
                <div class="card-header" role="tab" id="headingTen">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseTen"
                    aria-expanded="false"
                    aria-controls="collapseTen"
                    >Tính năng nổi bật</a>
                </h5>
                </div>
                <div id="collapseTen" class="collapse" role="tabpanel" aria-labelledby="headingTen">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

                <div class="card demand">
                <div class="card-header" role="tab" id="headingEleven">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseEleven"
                    aria-expanded="false"
                    aria-controls="collapseEleven"
                    >Nhu cầu sử dụng</a>
                </h5>
                </div>
                <div id="collapseEleven" class="collapse" role="tabpanel" aria-labelledby="headingEleven">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>

                <div class="card special-installment ">
                <div class="card-header" role="tab" id="headingTwelve">
                <h5 class="mb-0">
                    <a
                    class="collapsed"
                    data-toggle="collapse"
                    href="#collapseTwelve"
                    aria-expanded="false"
                    aria-controls="collapseTwelve"
                    >Trả góp ưu đãi</a>
                </h5>
                </div>
                <div id="collapseTwelve" class="collapse" role="tabpanel" aria-labelledby="headingTwelve">
                <div
                    class="card-body"
                >Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.</div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>