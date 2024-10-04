<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chat with ').$chatWith}}
        </h2>
    </x-slot>

    <div class="py-12 max-h-96">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
        <div class="p-6 text-gray-900 ">
            <div id="chat" class="flex flex-col">
                <div id="chat-window" class="max-h-[700px] overflow-y-auto">
                    <div id="messages">
                        @foreach ($messages as $message)
                            <div
                                data-id="{{ $message->id }}">
                                <strong>{{ $message->user->name }}:</strong> {{ $message->message }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="inputs">
                <x-form-message action="/chat-room-send/{{ $chat_room_id }}" id="messageForm">
                    <x-message-input name="message" id="messageInput" />
                </x-form-message>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#messageForm').on('submit', function(e) {
                        e.preventDefault();

                        let message = $('#messageInput').val().trim();
                        let chatRoomId = "{{ $chat_room_id }}";

                        if (message === "") {
                            return;
                        }

                        $.ajax({
                            url: `/chat-room-send/${chatRoomId}`,
                            method: 'POST',
                            data: {
                                message: message,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $('#messageInput').val('');

                                $('#messages').append(
                                    `<div class="message" data-id="${response.id}"><strong>${response.user.name}:</strong> ${response.message}</div>`
                                );
                            },
                            error: function(xhr) {
                                console.log('Error:', xhr.responseText);
                                alert("An error occurred while sending the message. Please try again.");
                            }
                        });
                    });

                    function fetchMessages() {
                        let chatRoomId = "{{ $chat_room_id }}";

                        $.ajax({
                            url: `/chat-room/${chatRoomId}`,
                            method: 'GET',
                            success: function(data) {
                                $('#messages').html('');

                                data.messages.forEach(function(message) {
                                    $('#messages').append(
                                        `<div class="message" data-id="${message.id}"><strong>${message.user.name}:</strong> ${message.message}</div>`
                                    );
                                });
                            },
                            error: function(xhr) {
                                console.log('Error fetching messages:', xhr.responseText);
                            }
                        });
                    }
                    setInterval(fetchMessages, 1000);
                });
            </script>
        </div>
    </div>
</div>

    </div>


</x-app-layout>
