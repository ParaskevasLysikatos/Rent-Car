function loadPlaces(element) {
    const $this = $(element);
    const station_id = $this.val();
    const station_dom_id = $this.attr('id');
    var places = $('.places[data-station="'+station_dom_id+'"]');
    var placeVal = places.val();
    let placesHtml = '';
    $.get(findPlaces + '?station_id='+station_id, function (data) {
        if (data.length != 0) {
            for (const place of data) {
                const selected = '';
                if (place.place_id == placeVal) {
                    selected = "selected";
                }
                placesHtml += '<option value="'+place.place_id+'" '+selected+'>'+place.title+'</option>';
            }
            for (const place of places) {
                place.innerHTML = placesHtml;
            }
        } else {
            if (places.length != 0) {
                places[0].innerHTML = '<option disabled>-</option>';
            }
        }
    });
}
$(document).ready(function () {
    const stations = $('select.station');
    for (const station of stations) {
        loadPlaces(station);
    }
    $('select.station').change(function () {
        loadPlaces(this);
    });
});
