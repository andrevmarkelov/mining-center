function initMap(addresses) {
    const mainMap = L.map("page-map", {
        dragging: false,
        zoomControl: false,
        scrollWheelZoom: false,
        tap: !L.Browser.mobile,
    });

    // L.tileLayer("//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    //     maxZoom: 4,
    //     attribution:
    //         '&copy; <a href="//www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    // }).addTo(mainMap);

    L.tileLayer("//{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
        maxZoom: 4,
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
    }).addTo(mainMap);

    addresses =
        typeof addresses == "string" ? JSON.parse(addresses) : addresses;

    const markers = [];

    Object.keys(addresses).map(function (key, index) {
        let address = addresses[key],
            marker = new L.marker(L.latLng(address.lat, address.lng), {
                icon: new L.DivIcon({
                    className: "",
                    html: `<div class="b-map-point" onclick="window.location='${address.link}'">${address.count}</div>`,
                }),
            }).addTo(mainMap);

        markers.push(marker);
    });

    let group = new L.featureGroup(markers);

    let paddingMap = [0, 200];

    if (markers.length == 1) paddingMap = [-300, 200];

    if (window.matchMedia("(max-width: 1199px)").matches) {
        paddingMap = [0, 350];
    }

    mainMap.fitBounds(group.getBounds(), {
        paddingTopLeft: paddingMap,
    });
}
