<button @click="clientChatDropdownOpen = !clientChatDropdownOpen"
    class="p-2 text-gray-600 hover:text-indigo-600 rounded-full hover:bg-gray-100 transition-all duration-200 relative group"
    aria-label="محادثات العملاء">
    <a href="{{ route('chat.index') }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
    </a>


    <span x-show="{{ $totalUnreadConversations ?? 0 }} > 0"
        class="absolute top-0 left-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full transform -translate-x-1/2 -translate-y-1/2 transition-transform duration-200 group-hover:scale-110">
        {{ $totalUnreadConversations ?? 0 }}
    </span>
</button>
