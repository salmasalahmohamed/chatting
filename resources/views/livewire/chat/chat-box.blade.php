<div






 >

    <div class="border-b flex flex-col overflow-y-scroll grow h-full">


        {{-- header --}}
        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b " >

            <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">

                <a class="shrink-0 lg:hidden" href="#">


                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>


                </a>


                {{-- avatar --}}


                <div class="shrink-0">

                    <x-avatar class="h-9 w-9 lg:w-11 lg:h-11" />
                </div>


                <h6 class="font-bold truncate"> {{$selectedConversation->receiver()->name}} </h6>


            </div>


        </header>

        <main
       class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto">
            @if($loadMessages)
                @foreach($loadMessages as $loadMessage)
                    @php
                        $isSender = $loadMessage->sender_id === auth()->id();

                    @endphp

                    <div
                        @class([
                            'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative',
                            'ml-auto flex-row-reverse' => $isSender,
                            'mr-auto' => !$isSender,
                        ])
                    >

                        <div @class(['shrink-0', 'hidden' => $isSender])>
                            <x-avatar />
                        </div>

                        <div @class([
                    'flex flex-wrap text-[15px] p-2.5 flex-col text-black shadow-md',
                    'rounded-xl max-w-full',

                    'bg-[#f6f6f8] rounded-bl-none border border-gray-200/40' => !$isSender,

                    'bg-blue-500/80 text-white rounded-br-none' => $isSender,
                ])>

                            <p class="whitespace-pre-wrap text-sm md:text-base tracking-wide lg:tracking-normal break-words">
                                {{ $loadMessage->body }}
                            </p>

                            <div @class([
                        'flex mt-1 items-center gap-1.5',
                        'ml-auto',
                    ])>

                                <p @class([
                            'text-[10px] whitespace-nowrap',
                            'text-gray-500' => !$isSender,
                            'text-blue-100' => $isSender,
                        ])>
                                   {{$loadMessage->created_at->format('g:i a')}}
                                </p>

                                @if ($isSender)
                                    <div class="shrink-0">
                                        @if($loadMessage->isRead())
                                            {{-- Double Ticks (Read) --}}
                                            <span class="text-white drop-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2-all w-3 h-3" viewBox="0 0 16 16">
                                            <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z"/>
                                            <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z"/>
                                        </svg>
                                    </span>
                                        @else
                                            {{-- Single Tick (Sent) --}}
                                            <span class="text-blue-200 drop-shadow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 w-3 h-3" viewBox="0 0 16 16">
                                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                    </span>
                                        @endif
                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>
                @endforeach
            @endif
        </main>        <footer>
            <div class=" p-2 border-t">

                <form wire:submit="save"
                    method="POST" autocapitalize="off">
                    @csrf

                    <input type="hidden" autocomplete="false" style="display:none">

                    <div class="grid grid-cols-12">
                        <input
                            wire:model="body"
                            type="text"
                            autocomplete="off"
                            autofocus
                            placeholder="write your message here"
                            maxlength="1700"
                            class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg  focus:outline-none"
                        >

                        <button  class="col-span-2" type='submit'>Send</button>
                    </div>
                </form>
                    </div>
        </footer>




    </div>

</div>


