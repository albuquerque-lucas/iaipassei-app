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
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, retirar inscrição',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('unsubscribeForm').submit();
        }
    });
});
