<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>


   <div class="p-6 md:p-10">
                    <h2 class="text-xl font-bold mb-1" style="color: #000066;">Registration Form</h2>
                    <p class="text-gray-400 text-sm mb-8">All fields are required unless stated otherwise.</p>

                    <form>
                        @csrf

                        {{-- ① Member Information --}}
                        <div class="mb-8">
                            <x-event-registration.section-title title="Member Information" />
                            <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">

                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">PSA ID No.</label>
                                    <input
                                        type="number"
                                        name="psa_id"
                                        placeholder="0000"
                                        maxlength="4"
                                        required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">First Name</label>
                                    <input type="text" name="first_name" placeholder="Auto-filled" readonly
                                        class="w-full border border-gray-100 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Last Name</label>
                                    <input type="text" name="last_name" placeholder="Auto-filled" readonly
                                        class="w-full border border-gray-100 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                                </div>

                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Middle Initial</label>
                                    <input type="text" name="middle_initial" placeholder="Auto-filled" readonly
                                        class="w-full border border-gray-100 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-400 cursor-not-allowed">
                                </div>

                            </div>
                        </div>

                        {{-- ② Contact Details --}}
                        <div class="mb-8">
                            <x-event-registration.section-title title="Contact Details" />
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">
                                        PRC Number <span class="text-gray-400">(7 digits)</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="prc_number"
                                        placeholder="1234567"
                                        pattern="\d{7}"
                                        maxlength="7"
                                        inputmode="numeric"
                                        required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Email Address</label>
                                    <input type="email" name="email_address" placeholder="you@example.com" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Contact Number</label>
                                    <input type="number" name="contact_number" placeholder="09XXXXXXXXX" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Hospital / Institution Name</label>
                                    <input type="text" name="hospital_name" placeholder="Name of Hospital" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Hospital Address</label>
                                    <input type="text" name="hospital_address" placeholder="City, Province" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2"
                                        style="--tw-ring-color: #000066;">
                                </div>

                            </div>
                        </div>

                        {{-- Membership & Discount --}}
                        <div class="mb-8">
                            <x-event-registration.section-title title="Membership & Discount" />
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                {{-- Registration Type (read-only, auto-filled) --}}
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-3">Registration Type</label>
                                    <div class="space-y-2">
                                        @foreach ([['RM', 'Regular Member'], ['LM', 'Life Member'], ['TM', 'Trainee Member']] as [$code, $label])
                                            <label class="flex items-center gap-3 px-4 py-3 rounded-lg border border-gray-100 bg-gray-50 cursor-not-allowed">
                                                <input type="radio" name="reg_type_radio" disabled class="accent-blue-800">
                                                <span class="text-sm text-gray-400">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">* Auto-filled based on your PSA ID</p>
                                </div>

                                {{-- Discount Selection --}}
                                <div x-data="{ disc: 'non_disc' }">
                                    <label class="block text-xs font-medium text-gray-500 mb-3">Discount ID</label>
                                    <div class="space-y-2 mb-4">
                                        @foreach ([['senior_disc', 'Senior Citizen'], ['non_disc', 'None']] as [$value, $label])
                                            <label
                                                class="flex items-center gap-3 px-4 py-3 rounded-lg border cursor-pointer transition"
                                                :class="disc === '{{ $value }}' ? 'border-red-200 bg-red-50' : 'border-gray-100 hover:border-gray-300'">
                                                <input
                                                    type="radio"
                                                    name="discount_radio"
                                                    value="{{ $value }}"
                                                    x-model="disc"
                                                    required
                                                    class="accent-red-700">
                                                <span
                                                    class="text-sm"
                                                    :class="disc === '{{ $value }}' ? 'font-semibold text-red-900' : 'text-gray-600'">
                                                    {{ $label }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div x-show="disc === 'senior_disc'" x-transition>
                                        <x-event-registration.image-upload
                                            name="discount_img"
                                            label="Senior Citizen ID"
                                            color="#000066" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- ④ Proof of Payment --}}
                        <div class="mb-10">
                            <x-event-registration.section-title title="Proof of Payment" />
                            <x-event-registration.image-upload
                                name="payment_proof"
                                label="Payment Screenshot"
                                :required="true"
                                color="#ac071a" />
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="px-8 py-3 rounded-xl text-sm font-bold text-white transition hover:opacity-90"
                                style="background-color: #ac071a;">
                                Submit Registration →
                            </button>
                        </div>

                    </form>
                </div>
            </div>