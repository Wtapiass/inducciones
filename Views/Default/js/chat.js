
$(document).ready(function() {

    const form = document.querySelector(".typing-area"),
    incoming_id = form.querySelector(".incoming_id").value,
    outgoing_id = form.querySelector(".outgoing_id").value,
    inputField = form.querySelector(".input-field"),
    sendBtn = form.querySelector("button"),
    chatBox = document.querySelector(".chat-box");
    const startButton = $('#start-record');
    const stopButton = $('#stop-record');
    const sendButton = $('#send-audio');
    const audioPlayback = $('#audio-playback');
    var selectedFiles = [];
    let mediaRecorder;
    let audioChunks = [];
    let audioBlob;
    let lastMessageId = 0;

    //llamada
    let localStream;
    let remoteStream;
    let peerConnection;
    const callBtn = document.getElementById('callBtn');
    const endCallBtn = document.getElementById('endCallBtn');
    const localAudio = document.getElementById('localAudio');
    const remoteAudio = document.getElementById('remoteAudio');
    const servers = {
        iceServers: [
            {
                urls: "stun:stun.l.google.com:19302"
            }
        ]
    };
    //---------------

    document.addEventListener("DOMContentLoaded", function() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/get-chat.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = xhr.response;
                    document.querySelector("#chatBox").innerHTML = data;
                    scrollToBottom();
                }
            }
        };
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("incoming_id=" + incoming_id);
    });


    

     // Inicializar la grabación de audio
     startButton.on('click', function() {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();

                mediaRecorder.ondataavailable = event => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    audioChunks = [];
                    const audioURL = window.URL.createObjectURL(audioBlob);
                    audioPlayback.attr('src', audioURL);
                    audioPlayback.show();
                };

                startButton.prop('disabled', true);
                stopButton.prop('disabled', false);
            })
            .catch(err => console.error('Error al acceder al micrófono:', err));
    });

    //- Detener la grabación
    stopButton.on('click', function() {
        mediaRecorder.stop();
        startButton.prop('disabled', false);
        stopButton.prop('disabled', true);
        sendButton.prop('disabled', false);
        sendBtn.classList.add("active");
    });


    form.onsubmit = (e)=>{
        e.preventDefault();
    }

    inputField.focus();
    inputField.onkeyup = ()=>{
        if(inputField.value != ""){
            sendBtn.classList.add("active");
        }else{
            sendBtn.classList.remove("active");
        }
    }

    inputField.onchange = ()=>{
        if(inputField.value != ""){
            sendBtn.classList.add("active");
        }else{
            sendBtn.classList.remove("active");
        }
    }

    sendBtn.onclick = ()=>{
        //console.log(selectedFiles);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/insert-chat.php", true);
        xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                inputField.value = "";
                
                $('#adjunto').val('');
                $('.remove-file').parent().remove();
                selectedFiles = [];
                scrollToBottom();
            }
        }
        }
        let formData = new FormData(form);
        for (let i = 0; i < selectedFiles.length; i++) {
            formData.append('files[]', selectedFiles[i]);
        }
        if (typeof audioBlob !== 'undefined') {
            formData.append('audio', audioBlob, 'audio.wav');
        }
        xhr.send(formData);
    }


    chatBox.onmouseenter = ()=>{
        chatBox.classList.add("active");
    }

    chatBox.onmouseleave = ()=>{
        chatBox.classList.remove("active");
    }

    setInterval(() =>{
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/get-chat.php", true);
        xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
               //chatBox.innerHTML = data;
               //let chatBox = document.querySelector("#chatBox"); // Asegúrate de que el elemento chatBox tenga el ID correcto
                let currentContent = chatBox.innerHTML;
                if (data.trim() !== "") {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(data, 'text/html');
                    let newMessages = doc.querySelectorAll('.chat');

                    newMessages.forEach(message => {
                        let msgId = message.getAttribute('data-msg-id');
                        if (document.querySelector(`.chat[data-msg-id="${msgId}"]`) === null) {
                            chatBox.appendChild(message);
                        }
                    });

                    // Scroll to bottom if not active
                    if (!chatBox.classList.contains("active")) {
                        scrollToBottom();
                    }
                }
            }
        }
        }
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send("incoming_id="+incoming_id);
    }, 500);

    function scrollToBottom(){
        chatBox.scrollTop = chatBox.scrollHeight;
    }


   
    $("#adjunto").on("change",(e)=>{
        var newElement = '';
        var files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            selectedFiles.push(files[i]);
            newElement = $('<div class="element" >'+ files[i].name + '<i class="fa fa-trash remove-file" data-filename="' + files[i].name + '" aria-hidden="true" title="Eliminar"></i>');
            $('#contentAdjunto').append(newElement);
        }
        sendBtn.classList.add("active");
        $(document).on('click', '.remove-file', function() { 
            $(this).parent().remove();
            var filename = $(this).data('filename');
            selectedFiles = selectedFiles.filter(file => file.name !== filename);
            console.log(selectedFiles);
            $(this).parent().remove();
            if($("#message").val() == ''){
                sendBtn.classList.remove("active");
            }
        });
    });

   

    //-------------- llamda

    // Obtener acceso al micrófono
    navigator.mediaDevices.getUserMedia({ audio: true })
    .then(stream => {
        localStream = stream;
        localAudio.srcObject = stream;
    })
    .catch(error => {
        console.error('Error accessing media devices.', error);
    });

// Iniciar llamada
    callBtn.onclick = () => {
    peerConnection = new RTCPeerConnection(servers);
    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    peerConnection.ontrack = event => {
        remoteStream = event.streams[0];
        remoteAudio.srcObject = remoteStream;
    };

    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            sendSignal(event.candidate);
        }
    };

    peerConnection.createOffer()
        .then(offer => {
            return peerConnection.setLocalDescription(offer);
        })
        .then(() => {
            sendSignal(peerConnection.localDescription);
        });

    callBtn.style.display = 'none';
    endCallBtn.style.display = 'inline';
    };

    // Terminar llamada
    endCallBtn.onclick = () => {
    peerConnection.close();
    peerConnection = null;
    endCallBtn.style.display = 'none';
    callBtn.style.display = 'inline';
    };

    // Función para enviar la señal al servidor
    function sendSignal(data) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "https://intranet.hogarymoda.com.co/Library/handle-signal.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify({
            outgoing_id: outgoing_id,
            incoming_id: incoming_id,
            signal: data
        }));
    }

    // Función para manejar la señal recibida del servidor
    function handleSignal(signal) {
    if (signal.type === 'offer') {
        peerConnection.setRemoteDescription(new RTCSessionDescription(signal))
            .then(() => {
                return peerConnection.createAnswer();
            })
            .then(answer => {
                return peerConnection.setLocalDescription(answer);
            })
            .then(() => {
                sendSignal(peerConnection.localDescription);
            });
    } else if (signal.type === 'answer') {
        peerConnection.setRemoteDescription(new RTCSessionDescription(signal));
    } else if (signal.candidate) {
        peerConnection.addIceCandidate(new RTCIceCandidate(signal));
    }
    }

    // Función para recibir señales del servidor (a implementar)
    function listenForSignals() {
    // Aquí puedes usar WebSockets o consultas periódicas para escuchar señales del servidor
    }

    // Iniciar la escucha de señales
    listenForSignals();

   

});
  