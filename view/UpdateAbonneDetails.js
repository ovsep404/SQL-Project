const modal = document.getElementById("myModal");
const closeModal = document.getElementById("closeModal");

// Fetch data of abonne

const voirFicheLinks = document.querySelectorAll(".voirFicheLink");
voirFicheLinks.forEach((link) => {
    link.addEventListener("click", function () {
        modal.style.display = "block";
        const userId = this.getAttribute("data-user-id");

        fetch(`../requests/AbonneRequest.php?user_id=${userId}`)
            .then((response) => response.json())
            .then((data) => {
                console.log(data);

                // Convertir la chaîne JSON en un tableau JavaScript
                data.livres_empruntes = JSON.parse(data.livres_empruntes);
                data.suggested_books = JSON.parse(data.suggested_books);


                const userDetails = document.getElementById("userDetails");
                userDetails.innerHTML = `
                <div class="containerDetails">
                    <p>ID: ${data.id}</p>
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="${data.prenom}">
                   
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="${data.nom}">
                    
                    <label for="date_naissance">Date de naissance:</label>
                    <input type="date" id="date_naissance" name="date_naissance" value="${data.date_naissance}">
                    
                    <label for="adresse">Adresse:</label>
                    <input type="text" id="adresse" name="adresse" value="${data.adresse}">
                    
                    <label for="code_postal">Code postal:</label>
                    <input type="text" id="code_postal" name="code_postal" value="${data.code_postal}">
                  
                    <label for="ville">Ville:</label>
                    <input type="text" id="ville" name="ville" value="${data.ville}">
                    
                     <label for="date_inscription">Date inscription:</label>
                    <input type="date" id="date_inscription" name="date_inscription" value="${data.date_inscription}">
                    
                    <label for="date_fin_abo">Date fin abonnement:</label>
                    <input type="date" id="date_fin_abo" name="date_fin_abo" value="${data.date_fin_abo}">
                   
                     <button class ="edit-button" id="saveUser" data-user-id="${data.id}">Save</button>
                     </div>
                   <h2>Liste des livres empruntés:</h2>
        <table>
            <div class="borrowed-books">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date d'emprunt</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.livres_empruntes && data.livres_empruntes.length > 0
                    ? data.livres_empruntes.map((livre) => `
                                <tr>
                                    <td>${livre.titre}</td>
                                    <td>${livre.date_emprunt}</td>
                                </tr>
                            `).join('')
                    : '<tr><td colspan="2">Aucun livre emprunté</td></tr>'
                }
                    </tbody>
                </table>
            </div>
            <h2>Liste de suggestion de 5 livres :</h2>
            <div class="suggested-books">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.suggested_books && data.suggested_books.length > 0
                    ? data.suggested_books.map((book) => `
                                <tr>
                                    <td>${book.titre}</td>
                                    <td>${book.categorie}</td>
                                </tr>
                            `).join('')
                    : '<tr><td colspan="2">Aucune suggestion de livre</td></tr>'
                }
                    </tbody>
                </table>
            </div>
                   
                `;
            })
            .catch((error) => {
                console.error("Error fetching user details: " + error);
            });
    });
});
closeModal.addEventListener("click", function () {
    modal.style.display = "none";
    location.reload();
});

window.addEventListener("click", function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

//Update
document.addEventListener("click", function (event) {
    if (event.target && event.target.id === "saveUser") {
        const userId = event.target.getAttribute("data-user-id");
        const prenom = document.getElementById("prenom").value;
        const nom = document.getElementById("nom").value;
        const date_naissance = document.getElementById("date_naissance").value;
        const adresse = document.getElementById("adresse").value;
        const code_postal = document.getElementById("code_postal").value;
        const ville = document.getElementById("ville").value;
        const date_inscription = document.getElementById("date_inscription").value;
        console.log(date_inscription);
        const date_fin_abo = document.getElementById("date_fin_abo").value;

        const updatedUserData = {
            id: userId,
            prenom: prenom,
            nom: nom,
            date_naissance: date_naissance,
            adresse:adresse,
            code_postal:code_postal,
            ville: ville,
            date_inscription:date_inscription,
            date_fin_abo: date_fin_abo
        };
        console.log(updatedUserData)
        fetch(`../requests/UpdateAbonneDetails.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(updatedUserData),
        })
            .then((response) => {
                if (response.status === 200) {
                    return response.json();
                } else {
                    throw new Error("Error updating user: " + response.statusText);
                }
            })
            .then((data) => {

                const successNotification = document.getElementById("successNotification");
                successNotification.style.display = "block";

                setTimeout(function () {
                    successNotification.style.display = "none";
                }, 3000);
            })
            .catch((error) => {
                console.error(error);

                const errorNotification = document.getElementById("errorNotification");
                errorNotification.style.display = "block";

                setTimeout(function () {
                    errorNotification.style.display = "none";
                }, 3000);
            });
    }

});