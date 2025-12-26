<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['questions'])) {
            foreach ($data['questions'] as &$question) {
                if (!empty($question['options'])) {
                    $question['options'] = array_map(
                        'trim',
                        explode(',', $question['options'])
                    );
                }
            }
        }

        return $data;
    }
}
