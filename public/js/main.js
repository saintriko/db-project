ymaps.ready(init);

function init(){

    var longitude = "<?php.echo $place.longitude; ?>";
    var latitude = "<?php.echo $place.latitude; ?>";

    var map = new ymaps.Map("map", {
        center: [latitude, longitude],
        zoom: 17,
        behaviors: ['drag']
    });

    var placemark = new ymaps.Placemark([latitude, longitude], {
        /*hintContent: 'AAAaaa',
        balloonContent: 'AAAaaa'*/
    });

    map.geoObjects.add(placemark);
}