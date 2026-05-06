// Formulario consts
const name = document.querySelector('input[name="name"]');
const phone = document.getElementById("phone");
const email = document.getElementById("email");
const checkbox = document.querySelector('input[name="politica-de-privacidad"]');
const confirmationBtn = document.getElementById("confirmation");
const text = document.querySelector('textarea[name="comments"]');
const message = document.getElementById("message");
const message2 = document.getElementById("message2");
const message3 = document.getElementById("message3");
    
// Formulario
confirmationBtn.addEventListener("click", function(event) {
    event.preventDefault()
    if (text.value.trim().length > 0 && phone.value.trim().length > 0 && email.value.trim().length > 0 && checkbox.checked) {  
        const originalText = confirmationBtn.innerText;     
        confirmationBtn.innerText = "¡Mensaje enviado!";
        message.classList.remove("hidden");  
        message2.classList.add("hidden2");     
        message3.classList.add("hidden3");     
        //Si la validación es exitosa
        const datos = {
            nombre: name.value,
            telefono: phone.value,
            correo: email.value,
            comentarios: text.value
        };        
        //Enviar datos a PHP (Esto es una API)     
        fetch('/DesarrollodeAplicacionesWebPHP/sitio-web/main/PHP/contacto-formulario.php', {
            method: 'POST',
            body: JSON.stringify(datos), // Convertimos el objeto a texto para enviarlo
            headers: { 'Content-Type': 'application/json' }
        })        
        .then(response => response.text())
        .then(data => console.log("Respuesta del servidor:", data))
        .catch(error => console.error('Error:', error));
     
        setTimeout(() => {            
            message.classList.add("hidden");
            confirmationBtn.innerText = originalText;   
            name.value = "";  
            text.value = "";   
            phone.value = "";  
            email.value = ""; 
            checkbox.checked = false;       
        }, 4000); 
    } else if (text.value.trim().length > 0 && phone.value.trim().length > 0 && email.value.trim().length > 0 && !checkbox.checked) {
        message3.classList.remove("hidden3"); 
        message.classList.add("hidden");
        message2.classList.add("hidden2");

        setTimeout(() => {
            message3.classList.add("hidden3");
        }, 3000);

    } else {
        message2.classList.remove("hidden2");
        message.classList.add("hidden");
        message3.classList.add("hidden3");         
                
        setTimeout(() => {        
        message2.classList.add("hidden2");
    }, 3000);
    };     
});