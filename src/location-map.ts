let map: google.maps.Map;

function initMap(): void {

    const mapElement = document.getElementById("map") as HTMLElement
    const coordinates = {
        lat: parseFloat(mapElement.dataset.lat),
        lng: parseFloat(mapElement.dataset.long)
    }

    console.log(mapElement.dataset)
    console.log(coordinates);

    map = new google.maps.Map(mapElement, {
        center: coordinates,
        zoom: 15,
    });

    new google.maps.Marker({
        position: coordinates,
        map,
        title: mapElement.dataset.markerTitle
    })
}

export { initMap };