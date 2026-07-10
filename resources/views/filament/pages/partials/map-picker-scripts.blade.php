<link rel="stylesheet" href="{{ asset('assets/css/vendor/leaflet/leaflet.css') }}" />
<script src="{{ asset('assets/js/vendor/leaflet.js') }}"></script>
<script>
    (function () {
        // Zanjan, Iran — shown until an admin picks a location on the map.
        var DEFAULT_LAT = 36.6736;
        var DEFAULT_LNG = 48.4787;

        function initSettingsMapPicker() {
            var el = document.getElementById('settings-map-picker');
            if (!el || el._leafletInited || typeof L === 'undefined') {
                return;
            }
            el._leafletInited = true;

            var latInput = document.getElementById('site_map_lat_input');
            var lngInput = document.getElementById('site_map_lng_input');

            var lat = parseFloat(latInput.value);
            var lng = parseFloat(lngInput.value);
            if (isNaN(lat)) lat = DEFAULT_LAT;
            if (isNaN(lng)) lng = DEFAULT_LNG;

            var map = L.map(el).setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            function setInputsFrom(latlng) {
                latInput.value = latlng.lat.toFixed(6);
                lngInput.value = latlng.lng.toFixed(6);
                latInput.dispatchEvent(new Event('input', { bubbles: true }));
                lngInput.dispatchEvent(new Event('input', { bubbles: true }));
            }

            map.on('click', function (e) {
                marker.setLatLng(e.latlng);
                setInputsFrom(e.latlng);
            });

            marker.on('dragend', function () {
                setInputsFrom(marker.getLatLng());
            });

            function syncFromInputs() {
                var newLat = parseFloat(latInput.value);
                var newLng = parseFloat(lngInput.value);
                if (isNaN(newLat) || isNaN(newLng)) {
                    return;
                }
                var latlng = { lat: newLat, lng: newLng };
                marker.setLatLng(latlng);
                map.setView(latlng);
            }
            latInput.addEventListener('change', syncFromInputs);
            lngInput.addEventListener('change', syncFromInputs);

            setTimeout(function () { map.invalidateSize(); }, 150);
        }

        document.addEventListener('DOMContentLoaded', initSettingsMapPicker);
        document.addEventListener('livewire:navigated', initSettingsMapPicker);
        if (document.readyState !== 'loading') {
            initSettingsMapPicker();
        }
    })();
</script>
