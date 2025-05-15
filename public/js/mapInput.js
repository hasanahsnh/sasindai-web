let inputMap, inputMarker, editMap, editMarker;

async function getApiKey() {
    const response = await fetch('/api-key');
    const data = await response.json();
    return data.api_key;
}

function initMap(key) {
    console.log("initMap called");
    inputInitMap();
    editInitMap(key);
}

function inputInitMap() {
    const defaultLocation = { lat: -3.32849900, lng: 114.58920300 };

    inputMap = new google.maps.Map(document.getElementById('map'), {
        center: defaultLocation,
        zoom: 13
    });

    inputMarker = new google.maps.Marker({
        position: defaultLocation,
        map: inputMap,
        draggable: true
    });

    google.maps.event.addListener(inputMarker, 'dragend', function () {
        const position = inputMarker.getPosition();
        updateCoordinates(position.lat(), position.lng());
    });

    const input = document.getElementById('pac-input');
    const resultsContainer = document.getElementById('autocomplete-results');

    input.addEventListener('input', async () => {
        const query = input.value.trim();
        if (query.length < 3) {
            resultsContainer.style.display = 'none';
            return;
        }
        
        const apiKey = await getApiKey();
        const response = await fetch(`https://maps.gomaps.pro/maps/api/geocode/json?address=${query}&key=${apiKey}`);
        const data = await response.json();

        if (data.status === 'OK') {
            const results = data.results;
            resultsContainer.innerHTML = '';
            results.forEach((result) => {
                const li = document.createElement('li');
                li.textContent = result.formatted_address;
                li.style.padding = '10px';
                li.style.cursor = 'pointer';

                li.addEventListener('click', () => {
                    input.value = result.formatted_address;
                    resultsContainer.style.display = 'none';

                    const location = result.geometry.location;
                    inputMap.setCenter(location);
                    inputMap.setZoom(17);

                    inputMarker.setPosition(location);
                    updateCoordinates(location.lat, location.lng);
                });

                resultsContainer.appendChild(li);
            });
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.style.display = 'none';
        }
    });
}

function editInitMap(key) {
    const mapDiv = document.getElementById(`edit-map-${key}`);
    if (!mapDiv) {
        console.error("Map div not found for key: " + key);
        return; // Jika tidak ada, tidak lanjutkan
    }
    
    const defaultLocation = { lat: -3.32849900, lng: 114.58920300 };

    editMap = new google.maps.Map(mapDiv, {
        center: defaultLocation,
        zoom: 13
    });

    editMarker = new google.maps.Marker({
        position: defaultLocation,
        map: editMap,
        draggable: true
    });

    google.maps.event.addListener(editMarker, 'dragend', function () {
        const position = editMarker.getPosition();
        console.log("Marker dragged to:", position.lat(), position.lng());
        editUpdateCoordinates(key, position.lat(), position.lng());
    });

    const input = document.getElementById(`edit-pac-input-${key}`);
    const resultsContainer = document.getElementById(`edit-autocomplete-results-${key}`);

    input.addEventListener('input', async () => {
        const query = input.value.trim();
        if (query.length < 3) {
            resultsContainer.style.display = 'none';
            return;
        }

        const apiKey = await getApiKey();
        const response = await fetch(`https://maps.gomaps.pro/maps/api/geocode/json?address=${query}&key=${apiKey}`);
        const data = await response.json();

        if (data.status === 'OK') {
            const results = data.results;
            resultsContainer.innerHTML = '';
            results.forEach((result) => {
                const li = document.createElement('li');
                li.textContent = result.formatted_address;
                li.style.padding = '10px';
                li.style.cursor = 'pointer';

                li.addEventListener('click', () => {
                    input.value = result.formatted_address;
                    resultsContainer.style.display = 'none';

                    const location = result.geometry.location;
                    editMap.setCenter(location);
                    editMap.setZoom(17);

                    editMarker.setPosition(location);
                    const sanitizedKey = key.toString().replace('.', '-');
                    console.log(`Updating coordinates for key: ${sanitizedKey} Lat: ${location.lat} Lng: ${location.lng}`);
                    editUpdateCoordinates(location.lat, location.lng, sanitizedKey);
                });

                resultsContainer.appendChild(li);
            });
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.style.display = 'none';
        }
    });
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;
}

function editUpdateCoordinates(lat, lng, key) {
    const sanitizedKey = key.toString().replace('.', '-');
    console.log(`Looking for latitude input with ID: edit-latitude-${sanitizedKey}`);
    console.log(`Looking for longitude input with ID: edit-longitude-${sanitizedKey}`);

    const latitudeInput = document.getElementById(`edit-latitude-${sanitizedKey}`);
    const longitudeInput = document.getElementById(`edit-longitude-${sanitizedKey}`);

    if (latitudeInput && longitudeInput) {
        latitudeInput.value = lat;
        longitudeInput.value = lng;
    } else {
        console.error(`Latitude or Longitude input elements not found for key: ${sanitizedKey}`);
    }
}
