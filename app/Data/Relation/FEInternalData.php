<?php

namespace App\Data\Relation;

use App\Database\Criteria;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class FEInternalData extends Data
{
    #[Computed]
    public function __construct(
        public int $idFrame,
        public string $relationType,
        public ?int $idRelationType = null,
        public ?array $idFrameElementRelated,
        public ?string $relationTypeEntry = ''
    )
    {
        $this->idRelationType = (int)substr($this->relationType, 1);
        $relationType = Criteria::byId("relationtype","idRelationType", $this->idRelationType);
        $this->relationTypeEntry = $relationType->entry;
    }

    public static function redirect(): string
    {
        $idFrame = request('idFrame');
        return "/frame/{$idFrame}/feRelations/formNew/error";
    }
    public static function rules(): array
    {
        return [
            'relationType' => ['required', 'string'],
        ];
    }
    public static function messages(): array
    {
        return [
            'relationType.required' => 'Field [Relation] is required',
        ];
    }

}
