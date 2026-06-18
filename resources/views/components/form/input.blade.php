{{-- passing the name value --}}
@props(['label' => null, 'name' => null, 'hint' => null, 'type' => 'text', 'readonly' => false])
{{-- value name such as first name will be pass if you dont pass something back to default which is type = text --}}
<div>
    @if ($label) {{-- if there is a label = "labelname" if there's not return null, same goes for hint --}}
        <label class="block text-xs font-medium text-gray-500 mb-1.5">
            {{ $label }}
            @if ($hint)  
                <span class="text-gray-400">{{ $hint }}</span>
            @endif
        </label>
    @endif

    
    <input type="{{ $type }}"
        @if ($readonly) readonly @endif {{-- if readonly is true add readonly attribute  else user can type --}}
        {{ $attributes->class([
            'w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#000066]',
            'border-gray-100 bg-gray-50 text-gray-400 cursor-not-allowed' => $readonly,
            'border-gray-200' => ! $readonly,
        ]) }}>

            {{-- error validation --}}
    @if ($name)
        @error($name)
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    @endif
</div>