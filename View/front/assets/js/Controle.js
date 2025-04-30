function validateForm()
{
    console.log("validateForm called !");
    var Destination = document.getElementById('destination').value;
    var Restaurant = document.getElementById('restaurant').value;
    var Hotel = document.getElementById('hotel').value;


    var destinationError = document.getElementById('destinationError');
    var restaurantError = document.getElementById('restaurantError');
    var hotelError = document.getElementById('hotelError');


    var isValid = true ;

    if(Destination.trim() === "")
        {
            destinationError.innerHTML= "Destination can not be empty";
            isValid = false;
        } else if (Destination.length < 5)
        {
            destinationError.innerHTML= "Destination must be at least 5 characters";
            isValid = false;
        } else 
        {
            destinationError.innerHTML = "";
        }

    if(Restaurant.trim() === "")
        {
            restaurantError.innerHTML= "Restaurant can not be empty";
            isValid = false;
        } else if(!/[A-Z]/.test(Restaurant))
        {
            restaurantError.innerHTML= "Restaurant must contain at least one uppercase letter";
            isValid = false;
        } else if (Restaurant.length < 5)
        {
            restaurantError.innerHTML= "Restaurant must be at least 5 characters";
            isValid = false;
        }  
        else 
        {
            restaurantError.innerHTML = "";
        }

    if(Hotel.trim() === "")
        {
            hotelError.innerHTML= "Hotel can not be empty";
            isValid = false;
        } else if(!/[A-Z]/.test(Hotel))
        {
            hotelError.innerHTML= "Hotel must contain at least one uppercase letter";
            isValid = false;
        } else if (Hotel.length < 5)
        {
            hotelError.innerHTML= "Hotel must be at least 5 characters";
            isValid = false;
        } else 
        {
            hotelError.innerHTML = "";
        }

    return isValid;
}

function validateReservationForm() {
    console.log("validateReservationForm called!");
    var dateDepart = document.getElementById('dateDepart').value;
    var dateRetour = document.getElementById('dateRetour').value;
    var nbPersonne = document.getElementById('nbPersonne').value;
    var commentaire = document.getElementById('commentaire').value;

    var dateDepartError = document.getElementById('dateDepartError');
    var dateRetourError = document.getElementById('dateRetourError');
    var nbPersonneError = document.getElementById('nbPersonneError');
    var commentaireError = document.getElementById('commentaireError');

    var isValid = true;

    // Validate date format and required fields
    if(dateDepart.trim() === "") {
        dateDepartError.innerHTML = "La date de départ est obligatoire";
        isValid = false;
    } else {
        dateDepartError.innerHTML = "";
    }

    if(dateRetour.trim() === "") {
        dateRetourError.innerHTML = "La date de retour est obligatoire";
        isValid = false;
    } else {
        dateRetourError.innerHTML = "";
    }

    // Validate that departure date is before return date
    if(dateDepart && dateRetour) {
        var departDate = new Date(dateDepart);
        var retourDate = new Date(dateRetour);
        
        if(departDate >= retourDate) {
            dateRetourError.innerHTML = "La date de retour doit être après la date de départ";
            isValid = false;
        }
    }

    // Validate number of people
    if(nbPersonne.trim() === "") {
        nbPersonneError.innerHTML = "Le nombre de personnes est obligatoire";
        isValid = false;
    } else if(parseInt(nbPersonne) < 1) {
        nbPersonneError.innerHTML = "Le nombre de personnes doit être au moins 1";
        isValid = false;
    } else {
        nbPersonneError.innerHTML = "";
    }

    // Validate comment (optional field, just checking length if provided)
    if(commentaire.trim() !== "" && commentaire.length < 5) {
        commentaireError.innerHTML = "Le commentaire doit comporter au moins 5 caractères";
        isValid = false;
    } else {
        commentaireError.innerHTML = "";
    }

    return isValid;
}

// Function to prepare reservation form with bonplan data
function prepareReservation(bonplanId, destination) {
    document.getElementById('selectedBonPlanId').value = bonplanId;
    document.getElementById('destination').value = destination;
    
    // Set default dates
    var today = new Date();
    var tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    var nextWeek = new Date(today);
    nextWeek.setDate(nextWeek.getDate() + 7);
    
    // Format dates for the date inputs (YYYY-MM-DD)
    document.getElementById('dateDepart').value = formatDate(tomorrow);
    document.getElementById('dateRetour').value = formatDate(nextWeek);
}

// Helper function to format dates to YYYY-MM-DD
function formatDate(date) {
    var year = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
}
