<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Member;

new class extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $filterType = '';
    public string $filterReg  = '';

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingFilterReg(): void  { $this->resetPage(); }

    #[Computed]
    public function stats(): array
    {
        $total      = Member::count();
        $registered = Member::whereHas('registration')->count();
        return [
            'total'        => $total,
            'registered'   => $registered,
            'unregistered' => $total - $registered,
        ];
    }

    #[Computed]
    public function members()
    {
        return Member::query()
            ->leftJoin('registrations', 'members.member_id_no', '=', 'registrations.psa_id')
            ->select([
                'members.member_id_no',
                'members.psa_chapter_code',
                'members.psa_mem_type',
                'members.mem_last_name',
                'members.mem_first_name',
                'members.mem_middle_name',
                'members.mem_email_address',
                'registrations.id         as reg_id',
                'registrations.status     as reg_status',
                'registrations.created_at as registered_at',
            ])
            ->when($this->search, function ($q) {
                $s = '%' . trim($this->search) . '%';
                $q->where(function ($q2) use ($s) {
                    $q2->where('members.mem_last_name',   'like', $s)
                       ->orWhere('members.mem_first_name', 'like', $s)
                       ->orWhere('members.member_id_no',   'like', $s);
                });
            })
            ->when($this->filterType, fn($q) => $q->where('members.psa_mem_type', $this->filterType))
            ->when($this->filterReg === 'registered',   fn($q) => $q->whereNotNull('registrations.id'))
            ->when($this->filterReg === 'unregistered', fn($q) => $q->whereNull('registrations.id'))
            ->orderBy('members.mem_last_name')
            ->paginate(10);
    }
};
?>

<div>

    {{--  Stats Bar  --}}
    @php
        $statTotal        = $this->stats['total'];
        $statRegistered   = $this->stats['registered'];
        $statUnregistered = $this->stats['unregistered'];
        $membersList      = $this->members;
    @endphp
<x-event-registration.event-registration-layout>
    <div class="grid grid-cols-3 gap-4 mb-6 mt-10">

        <div class="rounded-2xl px-5 py-4 flex items-center gap-4 bg-[#e8e8f7]">
            <span class="text-3xl font-black text-[#000066]">{{ $statTotal }}</span>
            <span class="text-xs font-semibold leading-tight text-[#000066]">Total Members</span>
        </div>

        <div class="rounded-2xl px-5 py-4 flex items-center gap-4 bg-green-50">
            <span class="text-3xl font-black text-green-800">{{ $statRegistered }}</span>
            <span class="text-xs font-semibold leading-tight text-green-800">Registered</span>
        </div>

        <div class="rounded-2xl px-5 py-4 flex items-center gap-4 bg-orange-50">
            <span class="text-3xl font-black text-orange-900">{{ $statUnregistered }}</span>
            <span class="text-xs font-semibold leading-tight text-orange-900">Not Yet Registered</span>
        </div>

    </div>

    {{-- ── Filters ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="h-1 bg-gradient-to-r from-[#000066] to-[#0000aa]"></div>
        <div class="p-5 flex flex-col sm:flex-row gap-3">

            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or PSA ID…"
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#000066]">
            </div>

            <select wire:model.live="filterType"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#000066] text-gray-600">
                <option value="">All Types</option>
                <option value="RM">Regular Member</option>
                <option value="LM">Life Member</option>
                <option value="TM">Trainee Member</option>
            </select>

            <select wire:model.live="filterReg"
                class="text-sm border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#000066] text-gray-600">
                <option value="">All Members</option>
                <option value="registered">Registered</option>
                <option value="unregistered">Not Yet Registered</option>
            </select>

        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white bg-[#000066]">
                        <th class="px-5 py-3.5">PSA ID</th>
                        <th class="px-5 py-3.5">Name</th>
                        <th class="px-5 py-3.5">Chapter</th>
                        <th class="px-5 py-3.5">Type</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Registered On</th>
                        <th class="px-5 py-3.5">Reg. Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">

                    @forelse ($membersList as $member)
                        @php $isReg = !is_null($member->reg_id); @endphp

                        <tr class="hover:bg-gray-50 transition {{ $loop->even ? 'bg-gray-50/40' : '' }}">

                            {{-- PSA ID --}}
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs font-bold px-2 py-1 rounded-lg bg-[#e8e8f7] text-[#000066]">
                                    {{ $member->member_id_no }}
                                </span>
                            </td>

                            {{-- Name --}}
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-gray-800">
                                    {{ $member->mem_last_name }}, {{ $member->mem_first_name }}
                                </p>
                                @if ($member->mem_middle_name)
                                    <p class="text-xs text-gray-400">{{ $member->mem_middle_name }}</p>
                                @endif
                            </td>

                            {{-- Chapter --}}
                            <td class="px-5 py-3.5 text-gray-500 text-xs font-semibold">
                                {{ $member->psa_chapter_code ?? '—' }}
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-3.5">
                                @php
                                    $typeMap = [
                                        'RM' => ['Regular', 'bg-blue-50 text-blue-700'],
                                        'LM' => ['Life',    'bg-purple-50 text-purple-700'],
                                        'TM' => ['Trainee', 'bg-orange-50 text-orange-700'],
                                    ];
                                    [$typeLabel, $typeCss] = $typeMap[$member->psa_mem_type] ?? [$member->psa_mem_type, 'bg-gray-100 text-gray-500'];
                                @endphp
                                <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $typeCss }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>

                         
                            {{-- Email --}}
                            <td class="px-5 py-3.5 text-xs text-gray-500 max-w-[180px] truncate">
                                {{ $member->mem_email_address ?: '—' }}
                            </td>

                            {{-- Registered On --}}
                            <td class="px-5 py-3.5 text-xs text-gray-400">
                                @if ($isReg)
                                    {{ \Carbon\Carbon::parse($member->registered_at)->format('M d, Y') }}
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>

                            {{-- Reg Status --}}
                            <td class="px-5 py-3.5">
                                @if (!$isReg)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        Not Registered
                                    </span>
                                @elseif ($member->reg_status === 'Approved')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Approved
                                    </span>
                                @elseif ($member->reg_status === 'Rejected')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-red-50 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center text-sm text-gray-400">
                                No members found matching your filters.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($membersList->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $membersList->links() }}
            </div>
        @endif

    </div>

</div>
</x-event-registration.event-registration-layout>
