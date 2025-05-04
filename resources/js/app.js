// Import des dépendances principales
require('./bootstrap');

// Configuration de Leaflet (carte)
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

// Configuration de Flatpickr (datepicker)
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

// Correction des icônes Leaflet (problème courant avec Webpack)
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    iconUrl: require('leaflet/dist/images/marker-icon.png'),
    shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

// Initialisation de la carte (accessible globalement)
window.initMap = function(elementId, lat, lng, zoom = 13) {
    const map = L.map(elementId).setView([lat || 46.2276, lng || 2.2137], zoom);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    return map;
};

// Initialisation des datepickers Flatpickr au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des champs de date
    flatpickr('[data-date-input]', {
        dateFormat: 'd/m/Y',
        allowInput: true,
        locale: 'fr', // Configuration française
        disableMobile: true // Meilleure expérience sur desktop
    });

    // Vous pouvez ajouter d'autres initialisations ici...
});