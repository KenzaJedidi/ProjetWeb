function validateForm()
{
    console.log("validateForm called !");
    var Message = document.getElementById('Message').value;


    var messageError = document.getElementById('messageError');


    var isValid = true ;

    if(Message.trim() === "")
        {
            messageError.innerHTML= "Message can not be empty";
            isValid = false;
        } else if (Message.length < 20)
        {
            messageError.innerHTML= "Message must be at least 20 characters";
            isValid = false;
        } else 
        {
            messageError.innerHTML = "";
        }

    return isValid;
}
