@props(['error' => null])

<input {{ $attributes->merge([
    'class' => 'rounded border px-3 py-2 w-full
                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                border-gray-300 dark:border-gray-600
                focus:outline-none focus:ring-2 focus:ring-indigo-500 ' .
        ($error ? 'border-red-500 dark:border-red-400' : '')
]) }} />

@if ($error)
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
        class="mt-1 text-sm text-red-600 dark:text-red-400 min-h-[1.25rem]">
        {{ $error }}
    </div>
@endif