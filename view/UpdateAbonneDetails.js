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
                const userDetails = document.getElementById("userDetails");
                userDetails.innerHTML = `

                <p>ID: ${data.id}</p>
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" value="${data.prenom}">
                <br>
                <label for="nom">Nom:</label>
                <input type="text" id="nom" value="${data.nom}">
                <br>
                <label for="ville">Ville:</label>
                <input type="text" id="ville" value="${data.ville}">
                <br>
                <label for="date_naissance">Date de naissance:</label>
                <input type="text" id="date_naissance" value="${data.date_naissance}">
                <br>
                <label for="date_fin_abo">Date fin abonnement:</label>
                <input type="text" id="date_fin_abo" value="${data.date_fin_abo}">
                <br>
                <button id="saveUser" data-user-id="${data.id}">Save</button>
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
        const ville = document.getElementById("ville").value;
        const date_naissance = document.getElementById("date_naissance").value;
        const date_fin_abo = document.getElementById("date_fin_abo").value;

        const updatedUserData = {
            id: userId,
            prenom: prenom,
            nom: nom,
            ville: ville,
            date_naissance: date_naissance,
            date_fin_abo: date_fin_abo,
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