document.addEventListener('DOMContentLoaded', function () {
    // Get the input elements
    let searchNom = document.getElementById('searchNom');
    let searchPrenom = document.getElementById('searchPrenom');
    let searchVille = document.getElementById('searchVille');
    let searchFilter = document.getElementById('abonneOUexpire');
    let clearStorageButton = document.getElementById('clearStorage');

    // Store the input values in the local storage when they change
    [searchNom, searchPrenom, searchVille, searchFilter].forEach(input => {
        input.addEventListener('change', function () {
            localStorage.setItem(input.id, this.value);
        });

        // Retrieve the stored values from the local storage and set them as the default values
        let storedValue = localStorage.getItem(input.id);
        if (storedValue !== null) {
            input.value = storedValue;
        }
    });

    // Clear the local storage and input fields when the clear button is clicked
    if (clearStorageButton) {
        clearStorageButton.addEventListener('click', function () {
            localStorage.clear();
            [searchNom, searchPrenom, searchVille, searchFilter].forEach(input => {
                input.value = '';
            });
        });
    }
});