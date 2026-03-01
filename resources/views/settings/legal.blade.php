<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Legal Content Management
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl">

                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <button type="button" class="tab-link active-tab" data-tab="terms">
                            Terms & Conditions
                        </button>
                        <button type="button" class="tab-link" data-tab="privacy">
                            Privacy Policy
                        </button>
                        <button type="button" class="tab-link" data-tab="cookie">
                            Cookie Policy
                        </button>
                        <button type="button" class="tab-link" data-tab="imprint">
                            Imprint
                        </button>
                        <button type="button" class="tab-link" data-tab="agb">
                            AGB
                        </button>
                    </nav>
                </div>

                <div class="p-6">

                    {{-- TERMS --}}
                    <div class="tab-pane" id="terms">
                        <form method="POST" action="{{ route('legal.update') }}" onsubmit="syncQuill('terms')">
                            @csrf
                            <input type="hidden" name="key" value="terms">
                            <input type="hidden" name="value" id="terms_input">

                            <div id="terms_editor" class="quill-editor">
                                {!! old('value', $terms->value ?? '') !!}
                            </div>

                            <div class="mt-6 text-right">
                                <x-primary-button>Save Terms</x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- PRIVACY --}}
                    <div class="tab-pane hidden" id="privacy">
                        <form method="POST" action="{{ route('legal.update') }}" onsubmit="syncQuill('privacy')">
                            @csrf
                            <input type="hidden" name="key" value="privacy_policy">
                            <input type="hidden" name="value" id="privacy_input">

                            <div id="privacy_editor" class="quill-editor">
                                {!! old('value', $privacy->value ?? '') !!}
                            </div>

                            <div class="mt-6 text-right">
                                <x-primary-button>Save Privacy Policy</x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- Cookine Policy --}}
                    <div class="tab-pane hidden" id="cookie">
                        <form method="POST" action="{{ route('legal.update') }}" onsubmit="syncQuill('cookie')">
                            @csrf
                            <input type="hidden" name="key" value="cookie_policy">
                            <input type="hidden" name="value" id="cookie_input">

                            <div id="cookie_editor" class="quill-editor">
                                {!! old('value', $cookie->value ?? '') !!}
                            </div>

                            <div class="mt-6 text-right">
                                <x-primary-button>Save Cookie Policy</x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- Imprint Policy --}}
                    <div class="tab-pane hidden" id="imprint">
                        <form method="POST" action="{{ route('legal.update') }}" onsubmit="syncQuill('imprint')">
                            @csrf
                            <input type="hidden" name="key" value="imprint">
                            <input type="hidden" name="value" id="imprint_input">

                            <div id="imprint_editor" class="quill-editor">
                                {!! old('value', $imprint->value ?? '') !!}
                            </div>

                            <div class="mt-6 text-right">
                                <x-primary-button>Save Imprint</x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- AGB --}}
                    <div class="tab-pane hidden" id="agb">
                        <form method="POST" action="{{ route('legal.update') }}" onsubmit="syncQuill('agb')">
                            @csrf
                            <input type="hidden" name="key" value="agb">
                            <input type="hidden" name="value" id="agb_input">

                            <div id="agb_editor" class="quill-editor">
                                {!! old('value', $agb->value ?? '') !!}
                            </div>

                            <div class="mt-6 text-right">
                                <x-primary-button>Save AGB</x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <!-- Quill CSS -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <style>
            .quill-editor {
                height: 400px;
                background: white;
            }
            .tab-link {
                padding: 1rem 0;
                border-bottom: 2px solid transparent;
                color: #6B7280;
            }
            .active-tab {
                border-color: #3B82F6;
                color: #111827;
                font-weight: 600;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Quill JS -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

        <script>
            // Initialize editors
            const editors = {
                terms: new Quill('#terms_editor', { theme: 'snow' }),
                privacy: new Quill('#privacy_editor', { theme: 'snow' }),
                cookie: new Quill('#cookie_editor', { theme: 'snow' }),
                imprint: new Quill('#imprint_editor', { theme: 'snow' }),
                agb: new Quill('#agb_editor', { theme: 'snow' })
            };

            // Sync HTML to hidden input before submit
            function syncQuill(type) {
                document.getElementById(type + '_input').value =
                    editors[type].root.innerHTML;
            }

            // Tab Switching
            const tabs = document.querySelectorAll('.tab-link');
            const panes = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active-tab'));
                    panes.forEach(p => p.classList.add('hidden'));

                    tab.classList.add('active-tab');
                    document.getElementById(tab.dataset.tab).classList.remove('hidden');
                });
            });
        </script>
    @endpush

</x-app-layout>
