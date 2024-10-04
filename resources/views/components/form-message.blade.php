<form {{ $attributes->merge(['method' => 'post']) }}>
    @csrf

    {{ $slot }} 
    <input type="submit" value="Send" class="chat-button" />
</form>
