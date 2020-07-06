<div class="product-filter">
    <div class="box-filter d-none d-lg-flex flex-wrap">
        <div class="dropdown box-filter-item" v-for="cate in filter.filter_cate">
            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @{{ cate.title }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownSize">
                <a class="dropdown-item" href="#" v-for="filter in cate.filters" @click="choose_filters($event,filter,cate)">@{{ filter.title }}</a>
            </div>
        </div>

        <div class="dropdown box-filter-item ml-auto">
            <button class="btn dropdown-toggle" type="button" id="dropdownColor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Lọc sản phẩm
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownColor">
                <a class="dropdown-item" href="#" v-for="(item, index) in filter.sort_by" @click="pick_sort_by($event,index)">@{{ item }}</a>
            </div>
        </div>
    </div>

    <div class="box-filter box-filter-mobile d-block d-lg-none">
        <div class="d-flex justify-content-between">
            <a href="javascript:;" class="show-filter-mobile">Bộ lọc</a>

            <div class="dropdown box-filter-item ml-auto">
                <button class="btn dropdown-toggle flex-row-reverse" type="button" id="dropdownColor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sắp xếp theo
                    <i class="fa fa-exchange" aria-hidden="true"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownColor">
                    <a class="dropdown-item" href="#" v-for="(item, index) in filter.sort_by" @click="pick_sort_by($event,index)">@{{ item }}</a>
                </div>
            </div>
        </div>
        <div class="cates-filter">
            <div class="title d-lg-none">
                <span>Bộ lọc</span>
                <span class="js-sidebar-close text-right">✕</span>
            </div>
            <ul class="list-unstyled grade-1">
                <li v-for="cate in filter.filter_cate">
                    <a v-bind:href="'#wm-filter_' + cate.id" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle text-uppercase grade-1">
                        @{{ cate.title }}
                    </a>
                    <ul class="collapse list-unstyled filter-size" v-bind:id="'wm-filter_' + cate.id">
                        <li class="size-item" v-for="filter in cate.filters">
                            <input type="radio" v-bind:name="'select-filter_'+cate.id">
                            <span>@{{ filter.title }}</span>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="bottom-filter">
                <span>102 sản phẩm</span>
                <a href="javascript:;" class="apply-filter">Áp dụng</a>
            </div>
        </div>
    </div>

    <div class="ouput-filter d-flex">
        <div class="list-filter">
            <div class="ouput-filter-item" v-for="choosed_filter in filter.choosed_filters">
                <span class="filter-lable">@{{ choosed_filter.cate_title }}: <i>@{{ choosed_filter.filter_title }}</i></span>
                <span class="filter-item-close" @click="remove_filter($event,choosed_filter)"></span>
            </div>
        </div>
        <a href="javascript:;" v-if="filter.choosed_filters.length > 0" class="close-all-filter" @click="remove_all_filter()"></a>
    </div>
</div>