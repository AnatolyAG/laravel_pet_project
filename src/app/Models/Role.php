<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

     /**
     * Get the OpenAPI schema for the model.
     *
     * @return array
     */
    public static function openApiSchema()
    {
        return [
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'integer',
                    'format' => 'int64',
                ],
                'name' => [
                    'type' => 'string',
                ],
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],

            ],
        ];
    }
}
