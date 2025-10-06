<?php

namespace App\Data\Lemma;

use App\Database\Criteria;
use Spatie\LaravelData\Data;

class UpdateLemmaData extends Data
{
    public function __construct(
        public ?string $name,
//        public ?int $idPOS,
        public ?int $idUDPOS,
        public string $_token = '',
    ) {
//        if (is_null($this->idPOS) && ! is_null($this->idUDPOS)) {
//            $pos = Criteria::byId('pos_udpos', 'idUDPOS', $this->idUDPOS);
//            $this->idPOS = $pos->idPOS;
//        }
    }

    public static function rules(): array
    {
        return [
            'idUDPOS' => ['required', 'int'],
            'name' => ['required', 'string'],
        ];
    }

    public static function messages(): array
    {
        return [
            'idUDPOS.required' => 'UD-POS is required.',
            'name.required' => 'Lemma name is required.',
        ];
    }
}
