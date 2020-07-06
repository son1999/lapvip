shop.priceFormat = function (number) {
    return shop.numberFormat(number)+' đ';
};

shop.dateConvert = function(date){
    date = date.split('/');
    return new Date(date[2]+'-'+date[1]+'-'+date[0]);
};

shop.ready.add(function (){

});