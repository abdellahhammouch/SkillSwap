<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Propose Times') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    Propose times for this exchange
                </h3>

                <p class="mt-2 text-sm text-gray-600">
                    You can propose up to 3 times. The helper will choose one to plan the learning session.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('proposed-times.store', $exchangeRequest) }}" class="space-y-6">
                    @csrf

                    @for ($i = 0; $i < 3; $i++)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-base font-semibold text-gray-900 mb-3">
                                Proposed time {{ $i + 1 }} {{ $i === 0 ? '(required)' : '(optional)' }}
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="start_at_{{ $i }}" :value="__('Start at')" />
                                    <x-text-input id="start_at_{{ $i }}" name="proposed_times[{{ $i }}][start_at]" type="datetime-local" class="mt-1 block w-full" :required="$i === 0" />
                                    <x-input-error class="mt-2" :messages="$errors->get('proposed_times.'.$i.'.start_at')" />
                                </div>

                                <div>
                                    <x-input-label for="end_at_{{ $i }}" :value="__('End at')" />
                                    <x-text-input id="end_at_{{ $i }}" name="proposed_times[{{ $i }}][end_at]" type="datetime-local" class="mt-1 block w-full" :required="$i === 0" />
                                    <x-input-error class="mt-2" :messages="$errors->get('proposed_times.'.$i.'.end_at')" />
                                </div>
                            </div>
                        </div>
                    @endfor

                    <div class="flex items-center gap-3">
                        <x-primary-button>{{ __('Save proposed times') }}</x-primary-button>

                        <a href="{{ route('exchange-requests.show', $exchangeRequest) }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
