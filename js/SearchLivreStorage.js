document.addEventListener('DOMContentLoaded', function () {
    // Get the input elements
    let searchTitle = document.getElementById('searchTitle');
    let searchAuteur = document.getElementById('auteur');
    let searchEditeur = document.getElementById('editeur');
    let searchDisponible = document.getElementById('disponible');
    let clearStorageButton = document.getElementById('clearStorage');

    // Store the input values in the local storage when they change
    [searchTitle, searchAuteur, searchEditeur].forEach(input => {
        input.addEventListener('change', function () {
            localStorage.setItem(input.id, this.value);
        });

        // Retrieve the stored values from the local storage and set them as the default values
        let storedValue = localStorage.getItem(input.id);
        if (storedValue !== null) {
            input.value = storedValue;
        }
    });

    // Save the change of selection in the local storage
    searchDisponible.addEventListener('change', function () {
        localStorage.setItem('searchDisponible', this.value);
    });

    // Retrieve the value from the local storage and set it as the default value
    let storedDisponible = localStorage.getItem('searchDisponible');
    if (storedDisponible !== null) {
        searchDisponible.value = storedDisponible;
    }

    // Clear the local storage and input fields when the clear button is clicked
    if (clearStorageButton) {
        clearStorageButton.addEventListener('click', function () {
            localStorage.clear();
            [searchTitle, searchAuteur, searchEditeur, searchDisponible].forEach(input => {
                input.value = '';
            });
        });
    }
});