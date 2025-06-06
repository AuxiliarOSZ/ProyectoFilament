<?php

namespace App\Imports;

use App\Models\Colaborator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Clase ColaboratorsImport
 * @package App\Imports
 * 
 * Maneja la importación de datos de colaboradores desde archivos Excel.
 * Implementa validación de datos, verificación de duplicados y preprocesamiento de datos.
 */
class ColaboratorsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * Campos que deben ser tratados como fechas durante la importación
     * @var array
     */
    private const DATE_FIELDS = ['birth_date', 'hire_date'];

    /**
     * Campos que pueden ser nulos en la importación
     * @var array
     */
    private const NULLABLE_FIELDS = ['corporate_email', 'phone', 'notes'];
    
    /**
     * Crea un nuevo modelo Colaborator a partir de la fila importada
     * Retorna null si el registro es un duplicado
     * 
     * @param array $row La fila que está siendo importada
     * @return Model|null
     */
    public function model(array $row): ?Model
    {
        return $this->checkDuplicates($row) ? null : new Colaborator($this->preprocessRow($row));
    }

    /**
     * Preprocesa los datos de la fila importada antes de crear el modelo
     * Maneja conversiones de fecha y conversión de tipos de datos
     * 
     * @param array $row Datos sin procesar del Excel
     * @return array Datos procesados listos para la creación del modelo
     */
    private function preprocessRow(array $row): array
    {
        $processed = [];

        foreach (self::DATE_FIELDS as $field) {
            $processed[$field] = $this->convertExcelDate($row[$field]);
        }

        foreach (self::NULLABLE_FIELDS as $field) {
            $processed[$field] = isset($row[$field]) ? strval($row[$field]) : null;
        }

        $requiredFields = [
            'document_type',
            'document_number',
            'first_name',
            'last_name',
            'gender',
            'personal_email',
            'mobile',
            'address',
            'residential_city',
            'education_level',
            'job_position',
            'status'
        ];

        foreach ($requiredFields as $field) {
            $processed[$field] = strval($row[$field]);
        }

        return $processed;
    }

    /**
     * Convierte el formato de fecha de Excel al formato Y-m-d
     * Maneja tanto fechas numéricas de Excel como fechas en formato string
     * 
     * @param mixed $excelDate El valor de la fecha desde Excel
     * @return string|null Cadena de fecha formateada o null
     */
    private function convertExcelDate($excelDate): ?string
    {
        if (!is_numeric($excelDate)) {
            return $excelDate;
        }

        return Carbon::create(1900, 1, 1)
            ->addDays(intval($excelDate) - 2)
            ->format('Y-m-d');
    }

    /**
     * Prepara los datos para la validación antes del procesamiento
     * 
     * @param array $data Los datos de la fila a validar
     * @param int $index El índice de la fila actual
     * @return array Datos procesados listos para validación
     */
    public function prepareForValidation($data, $index): array
    {
        return $this->preprocessRow($data);
    }

    /**
     * Define las reglas de validación para los datos importados
     * 
     * @return array Arreglo de reglas de validación para cada campo
     */
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string', 'max:15'],
            'document_number' => ['required', 'string', 'max:20'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date'],
            'personal_email' => ['required', 'email', 'max:255'],
            'corporate_email' => ['nullable', 'email', 'max:255'],
            'mobile' => ['required', 'string', 'max:15'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:150'],
            'residential_city' => ['required', 'string', 'max:100'],
            'education_level' => ['required', 'string', 'max:100'],
            'job_position' => ['required', 'string', 'max:100'],
            'hire_date' => ['required', 'date'],
            'status' => ['required', 'in:activo,inactivo'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Verifica si el registro importado ya existe en la base de datos
     * Comprueba document_number, personal_email y corporate_email (si se proporciona)
     * 
     * @param array $row La fila que se está verificando
     * @return bool True si se encuentra un duplicado, false en caso contrario
     */
    private function checkDuplicates(array $row): bool
    {
        $uniqueFields = [
            'document_number' => $row['document_number'],
            'personal_email' => $row['personal_email']
        ];

        if (!empty($row['corporate_email'])) {
            $uniqueFields['corporate_email'] = $row['corporate_email'];
        }

        foreach ($uniqueFields as $field => $value) {
            if (Colaborator::where($field, $value)->exists()) {
                return true;
            }
        }

        return false;
    }
}
