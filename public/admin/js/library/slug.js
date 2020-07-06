const app_slug = new Vue({
    el: '#slug-alias',

    data: {
        input:document.getElementById('title').value,
        alias:'',
    },
    beforeMount: function() {
        this.alias = this.slugify(this.input);
    },
    computed: {
        slug: function () {
            return this.slugify(this.input)
        }
    },
    watch:{
        'input':function(){
            this.alias = this.slugify(this.input)
        }
    },
    methods: {
        slugEdit: function () {
            return this.alias = this.slugify(this.input);
        },
        slugify (title) {
            var slug = "";
            // Change to lower case
            var titleLower = title.toLowerCase();
            slug = titleLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, 'e');
            slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, 'a')
                .replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, 'o')
                .replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, 'u')
                .replace(/ị|í|ì|ỉ|ĩ/gi, 'i')
                .replace(/ý|ỵ|ỳ|ỷ|ỹ/gi, 'y')
                .replace(/đ/gi, 'd')
                .replace(/([^0-9a-z-\s])/g, '')
                .replace(/(\s+)/g, '-')
                .replace(/^-+/g, '')
                .replace(/-+$/g, '');
            slug = slug.replace(/\-\-\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-/gi, '-');
            slug = slug.replace(/\-\-/gi, '-');
            slug = '@' + slug + '@';
            slug = slug.replace(/\@\-|\-\@|\@/gi, '').replace(/\s+/g, '-');
            return slug;
        }
    }
});