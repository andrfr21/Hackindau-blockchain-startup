// Récupérer le bouton mode sombre
const modeSombreBtn = document.getElementById('mode-sombre');

// Vérifier si le mode sombre est activé dans le localStorage
const isDarkMode = localStorage.getItem('darkMode');

if (isDarkMode === 'enabled') {
    document.body.classList.add('dark-mode');
}

// Ajouter un événement de clic au bouton
modeSombreBtn.addEventListener('click', function () {
    document.body.classList.toggle('dark-mode');
    
    // Sauvegarder la préférence dans le localStorage
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
    } else {
        localStorage.setItem('darkMode', 'disabled');
    }
});
