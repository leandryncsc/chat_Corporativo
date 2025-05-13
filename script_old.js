function enviarMensagem() {
    const id_usuario = document.getElementById('id_usuario').value;
    const mensagem = document.getElementById('mensagem').value;

    fetch('send_message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_usuario=${id_usuario}&mensagem=${mensagem}`
    }).then(() => {
        document.getElementById('mensagem').value = '';
        carregarMensagens();
    });
}

function carregarMensagens() {
    fetch('get_messages.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na rede: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const chat = document.getElementById('chat');
            chat.innerHTML = '';
            data.forEach(msg => {
                chat.innerHTML += `<p><strong>${msg.usuario}:</strong> ${msg.mensagem} <em>${msg.data_hora}</em></p>`;
            });
        })
        .catch(error => {
            console.error('Erro ao carregar mensagens:', error);
        });
}

setInterval(carregarMensagens, 1000);
