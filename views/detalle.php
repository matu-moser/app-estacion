<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle estación</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family: Arial;
    background:#eef2f5;
    margin:20px;
}
.container{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.2);
    max-width:900px;
    margin:auto;
}
.datos{
    display:flex;
    justify-content:space-around;
    flex-wrap:wrap;
    gap:10px;
}
.box{
    background:#f7f9fa;
    padding:12px;
    border-radius:8px;
    width:150px;
    text-align:center;
}
.label{
    color:#555;
    font-size:0.8em;
}
canvas{
    margin-top:25px;
}
</style>

</head>
<body>

<div class="container">

    <h2>Detalle de estación</h2>

    <div class="datos">
        <div class="box">
            <div class="label">Temperatura</div>
            <div id="d_temperatura">--</div>
        </div>
        <div class="box">
            <div class="label">Humedad</div>
            <div id="d_humedad">--</div>
        </div>
        <div class="box">
            <div class="label">Viento</div>
            <div id="d_viento">--</div>
        </div>
        <div class="box">
            <div class="label">Presión</div>
            <div id="d_presion">--</div>
        </div>
        <div class="box">
            <div class="label">FWI</div>
            <div id="d_fwi">--</div>
        </div>
    </div>

    <canvas id="grafico"></canvas>
</div>


<script>
document.addEventListener("DOMContentLoaded", async () => {

    const chipid = "<?= $chipid ?>";
    const API = 
`https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=${chipid}&cant=10`;

    let chart;

    async function cargarDatos() {
        try {
            const res = await fetch(API);
            const json = await res.json();

            if (!json || json.length === 0) return;

            json.sort((a,b)=> new Date(a.fecha) - new Date(b.fecha));

            const ultimo = json[json.length - 1];

            document.getElementById("d_temperatura").innerText = 
ultimo.temperatura + " °C";
            document.getElementById("d_humedad").innerText    = 
ultimo.humedad + " %";
            document.getElementById("d_viento").innerText     = 
ultimo.viento + " km/h";
            document.getElementById("d_presion").innerText    = 
ultimo.presion + " hPa";
            document.getElementById("d_fwi").innerText        = 
ultimo.fwi;

            const fechas = json.map(x => x.fecha.substring(11,16));
            const temps  = json.map(x => x.temperatura);
            const hums   = json.map(x => x.humedad);

            if (chart) chart.destroy();

            const ctx = document.getElementById("grafico");

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [
                        {
                            label: 'Temperatura (°C)',
                            data: temps,
                            borderWidth: 2,
                            tension: 0.3
                        },
                        {
                            label: 'Humedad (%)',
                            data: hums,
                            borderWidth: 2,
                            tension: 0.3
                        }
                    ]
                }
            });

        } catch (e) {
            console.log("Error fetch:", e);
        }
    }

    cargarDatos();
    setInterval(cargarDatos, 15000);
});
</script>

</body>
</html>

