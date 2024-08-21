import Swal from 'sweetalert2'

document.getElementById('unsubscribeBtn').addEventListener('click', function(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        html: `
            <p><strong>Atenção!!!</strong></p>
            <p>Essa ação não poderá ser desfeita!</p>
            <p>Você irá excluir seus resultados e as questões que marcou!</p>
            <p>Você deseja realmente retirar sua inscrição?</p>
            <p>Por favor, digite <strong>RETIRAR</strong> abaixo para confirmar:</p>
            <input type="text" id="confirmationInput" class="swal2-input" placeholder="Digite RETIRAR">
        `,
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, retirar inscrição',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const confirmationInput = Swal.getPopup().querySelector('#confirmationInput').value;
            if (confirmationInput !== 'RETIRAR') {
                Swal.showValidationMessage('Você precisa digitar "RETIRAR" para confirmar.');
                return false;
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('unsubscribeForm').submit();
        }
    });
});
