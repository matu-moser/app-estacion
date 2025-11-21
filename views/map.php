<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mapa de Clientes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        #map { width: 100%; height: 100%; }
        .volver-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: #0275d8;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="index.php?page=administrator" class="volver-btn">Volver</a>

<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-34.6037, -58.3816], 5); // Coordenadas centradas en Argentina

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const clientes = <?php echo json_encode($clientes); ?>;

    clientes.forEach(cliente => {
        const lat = parseFloat(cliente.latitud);
        const lng = parseFloat(cliente.longitud);
        const accesos = cliente.accesos;

        if (!isNaN(lat) && !isNaN(lng)) {
            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`<b>IP:</b> ${cliente.ip}<br><b>Accesos:</b> ${accesos}`);
        }
    });
</script>

</body>
</html>
