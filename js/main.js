ymaps.ready(init);

function init(){
    var map = new ymaps.Map("map", {
        center: [56.630674200455, 47.892130372023],
        zoom: 17,
        behaviors: ['drag']
    });

    var placemark = new ymaps.Placemark([56.630674200462, 47.892130372023], {
        hintContent: 'AAAaaa',
        balloonContent: 'AAAaaa'
    });

    map.geoObjects.add(placemark);
}