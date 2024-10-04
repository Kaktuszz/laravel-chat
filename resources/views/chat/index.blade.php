<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="/chats">
                        @csrf

                        <input class='m-5' type="text" name="name"
                            placeholder="Create a new chat with">
                        <input type="submit" value="Create" />
                        @if ($errors->any())
                            <div class="error-messages">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </form>
                    <div>
                        Your Chats
                        <ul>
                            @foreach ($chats as $chat)
                                <li><a href="chat-room/{{ $chat->id }}">
                                        <strong>{{ $chat->participant_1 === auth()->id() ? $chat->participantTwo->name : $chat->participantOne->name }}</strong>
                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
