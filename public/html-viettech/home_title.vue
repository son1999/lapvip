<template>
    <div class="home-title-cate">
        <h3 class="name">{{home_title}}</h3>
        <a href="" class="more d-none d-md-inline-block">
            Xem tất cả
        </a>
    </div>
</template>
<script>
module.exports = {
    props: ['home_title'],
}
</script>