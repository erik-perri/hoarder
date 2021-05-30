<?php

namespace App\Repository\Collectible;

use App\Models\Collectible;

class FieldRepository
{
    public function removeField(Collectible $collectible, string $code): void
    {
        $fieldsWithCode = $collectible->fields->where('code', '=', $code);
        if (! $fieldsWithCode->count()) {
            return;
        }

        foreach ($fieldsWithCode as $field) {
            if ($field->entity_type === 'category') {
                $table = 'collectible_categories';
            } else {
                $table = 'collectible_items';
            }

            // TODO How deleted fields are cleaned up will need to be rethought, deleting a few fields from a large set
            //      takes longer than a save action should take
            $this->removeFieldFromTable($collectible->id, $table, $field->code);

            $field->delete();
        }
    }

    private function removeFieldFromTable(
        int $collectibleId,
        string $tableName,
        string $fieldCode
    ): void {
        // TODO Figure out if there is a better way to handle escaping a JSON property name
        //      Using PDO's quote method won't work since it will put it in single quotes which does not seem to be
        //      supported in MySQL (it would support double quotes but there is not way to tell PDO to use them)
        $cleanFieldCode = preg_replace('/[^a-z0-9_]/i', '', $fieldCode);

        \DB::table($tableName)->where('collectible_id', '=', $collectibleId)
           ->update([
               'field_values' => \DB::raw('JSON_REMOVE(`field_values`, "$.'.$cleanFieldCode.'")'),
           ]);
    }
}
