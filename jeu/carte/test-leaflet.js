var points = data; // data loaded from data.js
var leafletMap = L.map('map').setView([50.00, 14.44], 9);
L.tileLayer("http://{s}.sm.mapstack.stamen.com/(toner-lite,$fff[difference],$fff[@23],$fff[hsl-saturation@20])/{z}/{x}/{y}.png")
    .addTo(leafletMap);

L.canvasOverlay()
    .drawing(drawingOnCanvas)
    .addTo(leafletMap);

function drawingOnCanvas(canvasOverlay, params) {
    var ctx = params.canvas.getContext('2d');
    ctx.clearRect(0, 0, params.canvas.width, params.canvas.height);
    ctx.fillStyle = "rgba(255,116,0, 0.2)";
    for (var i = 0; i < data.length; i++) {
        var d = data[i];
        if (params.bounds.contains([d[0], d[1]])) {
            dot = canvasOverlay._map.latLngToContainerPoint([d[0], d[1]]);
            ctx.beginPath();
            ctx.arc(dot.x, dot.y, 3, 0, Math.PI * 2);
            ctx.fill();
            ctx.closePath();
        }
    }
};

