<template>
    <div class="slider-blog">
        <a href="#" class="b-img">
            <img :src="img_src" alt="">
        </a>
        <div class="b-info">
            <a href="#" class="b-name">{{blog_title}}</a>
            <span class="b-status">Đã ra mắt</span>
            <span class="b-count">1 bài viết</span>
        </div>
    </div>
</template>
<script>
module.exports = {
    props: ['blog_title', 'img_src'],
}
</script>