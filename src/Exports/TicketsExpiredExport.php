<?php

namespace Dainsys\Support\Exports;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class TicketsExpiredExport implements FromCollection, WithCustomStartCell, WithMapping, WithEvents, ShouldAutoSize
{
    public Collection $tickets;

    public $last_row;

    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
        $this->last_row = $tickets->count() + 6;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function map($ticket): array
    {
        return [
            $ticket->reference,
            $ticket->subject->name,
            $ticket->department->name,
            $ticket->owner?->name,
            $ticket->agent?->name,
            $ticket->created_at?->diffForHumans(),
            $ticket->expected_at?->diffForHumans(),
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $event->writer->reopen(new LocalTemporaryFile(__DIR__ . '/templates/tickets-expired.xlsx'), Excel::XLSX);

                $event->writer->getSheetByIndex(0);
                $sheet = $event->getWriter()->getSheetByIndex(0);

                $sheet->setCellValue('D4', now()->format('Y-m-d H:i'));
                $sheet->getTableByName('tickets_table')->setRange("A6:G{$this->last_row}");
                $sheet->export($event->getConcernable()); // call the export on the first sheet

                return $sheet;
            }
        ];
    }
}
