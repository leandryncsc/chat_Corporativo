document.addEventListener("DOMContentLoaded", function () {
  const chatMessages = document.getElementById("chat-messages");
  const mensagemInput = document.getElementById("mensagem");
  const enviarBtn = document.getElementById("enviar-btn");
  const idUsuario = document.getElementById("id_usuario").value;

  // Carrega as mensagens ao abrir a página
  carregarMensagens();

  // Atualiza as mensagens a cada 3 segundos
  setInterval(carregarMensagens, 3000);

  // Permite enviar mensagem pressionando Enter
  mensagemInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      enviarMensagem();
    }
  });

  function carregarMensagens() {
    fetch("carregar_mensagens.php")
      .then((response) => response.json())
      .then((mensagens) => {
        chatMessages.innerHTML = "";
        mensagens.forEach((msg) => {
          const isSent = msg.id_usuario == idUsuario;
          const messageDiv = document.createElement("div");
          messageDiv.className = `message ${
            isSent ? "message-sent" : "message-received"
          }`;

          messageDiv.innerHTML = `
                        <div class="message-content">${msg.conteudo}</div>
                        <div class="message-info">
                            <span>${msg.nome_usuario}</span>
                            <span>${formatarData(msg.data_envio)}</span>
                        </div>
                    `;

          chatMessages.appendChild(messageDiv);
        });

        // Rolagem automática para a última mensagem
        chatMessages.scrollTop = chatMessages.scrollHeight;
      })
      .catch((error) => console.error("Erro ao carregar mensagens:", error));
  }

  function enviarMensagem() {
    const conteudo = mensagemInput.value.trim();
    if (conteudo === "") return;

    const data = {
      id_usuario: idUsuario,
      conteudo: conteudo,
    };

    fetch("enviar_mensagem.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          mensagemInput.value = "";
          carregarMensagens();
        } else {
          console.error("Erro ao enviar mensagem:", data.error);
        }
      })
      .catch((error) => console.error("Erro:", error));
  }

  function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
  }
});
