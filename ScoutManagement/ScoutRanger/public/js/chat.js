document.addEventListener('DOMContentLoaded', function () {
    const chatWindow = document.getElementById('chat-window');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    
    if (!chatWindow || !chatForm) return;

    const currentUserId = window.currentUserId;
    const fetchUrl = window.chatFetchUrl;
    const sendUrl = window.chatSendUrl;
    const csrfToken = window.csrfToken;

    function fetchMessages() {
        fetch(fetchUrl)
            .then(response => response.json())
            .then(data => {
                chatWindow.innerHTML = '';
                if (data.length === 0) {
                    chatWindow.innerHTML = '<div class="text-center text-muted small py-3">No messages yet. Start the conversation!</div>';
                    return;
                }

                data.forEach(msg => {
                    const isMe = msg.sender_id === currentUserId;
                    const msgBox = document.createElement('div');
                    msgBox.className = `d-flex flex-column mb-2 ${isMe ? 'align-items-end' : 'align-items-start'}`;

                    const senderName = msg.sender ? msg.sender.name : 'Unknown';
                    const senderRole = msg.sender ? msg.sender.role.replace('_', ' ') : '';

                    msgBox.innerHTML = `
                        <div class="small fw-semibold text-secondary" style="font-size: 0.75rem;">
                            ${senderName} (${senderRole})
                        </div>
                        <div class="p-2 px-3 rounded-3 mt-1 ${isMe ? 'bg-olive text-white' : 'bg-light text-dark border'}" style="max-width: 75%;">
                            ${msg.message}
                        </div>
                    `;
                    chatWindow.appendChild(msgBox);
                });

                chatWindow.scrollTop = chatWindow.scrollHeight;
            })
            .catch(err => console.error("Chat Fetch Error:", err));
    }

    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        fetch(sendUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(() => {
            chatInput.value = '';
            fetchMessages();
        })
        .catch(err => console.error("Chat Send Error:", err));
    });

    fetchMessages();
    setInterval(fetchMessages, 3000);
});