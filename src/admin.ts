import './css/admin.css';

const dcr_checkbox = document.getElementById('wp-locator-oauth-use-dcr') as HTMLInputElement;
const client_id_input = document.getElementById('wp-locator-oauth-client-id') as HTMLInputElement;
const client_secret_input = document.getElementById('wp-locator-oauth-client-secret') as HTMLInputElement;

dcr_checkbox.addEventListener('change', function(event) {

    if (this.checked){
        console.log('this is not checked');
        client_id_input.disabled = true;
        client_secret_input.disabled = true;
        return;
    }

    client_id_input.disabled = false;
    client_secret_input.disabled = false;

})