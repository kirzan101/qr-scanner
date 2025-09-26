<?php

namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;

class DepartmentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            DB::beginTransaction();

            $name = isset($row['name']) ? trim($row['name']) : null;

            // Skip rows that don't have a name to prevent creating empty records.
            if (!$name) {
                return null;
            }

            // Pre-process the name to handle special characters and casing.
            $processedName = preg_replace('/[^\pL\pN\s]+/u', ' ', strtolower($name));
            // Convert to Title Case, preserving spaces between words.
            $pascalName = Str::title(trim(preg_replace('/\s+/', ' ', $processedName)));

            // Find the department by its processed name to avoid duplicates.
            $department = Department::where('name', $pascalName)->first();

            // If the department does not exist, create it with a unique code.
            if (!$department) {
                // Generate an acronym from the cleaned name.
                $words = preg_split('/\s+/', $processedName, -1, PREG_SPLIT_NO_EMPTY);
                
                // If there's only one word, use the whole word as the base acronym.
                // Otherwise, use the first letter of each word.
                if (count($words) === 1) {
                    $baseAcronym = strtoupper($words[0]);
                } else {
                    $baseAcronym = strtoupper(collect($words)->map(fn($word) => mb_substr($word, 0, 1))->join(''));
                }

                // Ensure the generated code is unique (checking soft-deleted records too).
                $finalCode = $baseAcronym;
                $counter = 2;
                while (Department::where('code', $finalCode)->withTrashed()->exists()) {
                    $finalCode = $baseAcronym . $counter;
                    $counter++;
                }

                $department = Department::create([
                    'name' => $pascalName,
                    'code' => $finalCode,
                ]);
            }

            DB::commit();

            return $department;
        } catch (Exception $e) {
            DB::rollBack();
            // It's better to log the error instead of using dd() in a production environment.
            // You can replace this with your preferred logging mechanism.
            // For example:
            // Log::error('Department import failed for row: ' . json_encode($row), ['exception' => $e]);
            return null;
        }
    }
}