const forms_ajax = document.querySelectorAll(".FormAjax");

function send_form_ajax(e) {
        e.preventDefault();

        let send = confirm("Quieres enviar el formulario?");

        if(send == true) {
                let data = new FormData(this);
                let method = this.getAttribute("method");
                let action = this.getAttribute("action");

                let headers = new Headers();

                let config = {
                        method: method, 
                        headers: headers, 
                        mode: 'cors', 
                        cache: 'no-cache', 
                        body: data
                }

                fetch(action, config)
                        .then(response => response.text())
                        .then(response => {
                                let container = document.querySelector(".form-rest");
                                container.innerHTML = response;
                        })
        }
}

forms_ajax.forEach(forms => {
        forms.addEventListener("submit", send_form_ajax);
});