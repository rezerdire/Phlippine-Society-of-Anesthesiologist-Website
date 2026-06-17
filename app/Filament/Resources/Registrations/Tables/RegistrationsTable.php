<?php

namespace App\Filament\Resources\Registrations\Tables;

use App\Models\Member;
use App\Models\Registration;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                TextColumn::make('id')
                    ->label('Ref #')
                    ->formatStateUsing(fn ($state) => '#' . str_pad($state, 6, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('psa_id')
                    ->label('PSA ID')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                // ── Combined full name (uses Registration::getFullNameAttribute) ──
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(['first_name', 'last_name', 'middle_name'])
                    ->sortable(['last_name', 'first_name']),

                // ── From members table via belongsTo ──
                TextColumn::make('member.psa_chapter_code')
                    ->label('Chapter')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('membership')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'RM'    => 'Regular',
                        'LM'    => 'Life',
                        'TM'    => 'Trainee',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'RM'    => 'info',
                        'LM'    => 'success',
                        'TM'    => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('member.mem_email_address')
                    ->label('Email (PSA Record)')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('contact_number')
                    ->label('Contact')
                    ->toggleable(),

                // ── Payment proof thumbnail ──
                ImageColumn::make('proof_payment')
                    ->label('Payment')
                    ->disk('public')
                    ->height(48)
                    ->width(64)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover']),

                // ── Discount ID thumbnail ──
                ImageColumn::make('discount_id')
                    ->label('Discount ID')
                    ->disk('public')
                    ->height(48)
                    ->width(64)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Pending'  => 'warning',
                        default    => 'gray',
                    })
                    ->icon(fn ($state) => match ($state) {
                        'Approved' => 'heroicon-m-check-circle',
                        'Rejected' => 'heroicon-m-x-circle',
                        'Pending'  => 'heroicon-m-clock',
                        default    => null,
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y g:i A')
                    ->sortable()
                    ->toggleable(),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'Pending'  => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ]),

                SelectFilter::make('membership')
                    ->label('Membership Type')
                    ->options([
                        'RM' => 'Regular Member',
                        'LM' => 'Life Member',
                        'TM' => 'Trainee Member',
                    ]),

                // ── Distinct chapter codes only — no repeated options ──
                SelectFilter::make('chapter')
                    ->label('Chapter')
                    ->options(fn () => Member::query()
                        ->whereNotNull('psa_chapter_code')
                        ->distinct()
                        ->orderBy('psa_chapter_code')
                        ->pluck('psa_chapter_code', 'psa_chapter_code')
                        ->toArray()
                    )
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['value'] ?? null, fn ($q, $value) => $q
                            ->whereHas('member', fn ($q2) => $q2->where('psa_chapter_code', $value))
                        )
                    ),

                Filter::make('has_discount')
                    ->label('Has Senior Discount')
                    ->query(fn (Builder $q) => $q->whereNotNull('discount_id')),

            ])

            ->recordActions([
              ViewAction::make()
                ->modal()
                ->modalWidth('5xl')
                ->modalHeading(fn (Registration $r) => 'Registration #' . str_pad($r->id, 6, '0', STR_PAD_LEFT)),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $r) => $r->isPending())
                    ->requiresConfirmation()
                    ->action(function (Registration $r): void {
                        $r->update(['status' => Registration::STATUS_APPROVED]);
                        Notification::make()->title('Registration approved.')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Registration $r) => $r->isPending())
                    ->requiresConfirmation()
                    ->action(function (Registration $r): void {
                        $r->update(['status' => Registration::STATUS_REJECTED]);
                        Notification::make()->title('Registration rejected.')->danger()->send();
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    \Filament\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => Registration::STATUS_APPROVED])),

                    \Filament\Actions\BulkAction::make('reject_selected')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => Registration::STATUS_REJECTED])),
                ]),
            ]);
    }
}