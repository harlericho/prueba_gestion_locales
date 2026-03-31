<?php

namespace Database\Seeders;

use App\Models\Local;
use Illuminate\Database\Seeder;

class LocalesSeeder extends Seeder
{
    public function run(): void
    {
        $locales = [
            [
                'nombre'         => 'Tienda Coral',
                'direccion'      => 'Av. Quito 1',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20123456789',
            ],
            [
                'nombre'         => 'Sucursal San Isidro',
                'direccion'      => 'Calle Los Eucaliptos',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20987654321',
            ],
            [
                'nombre'         => 'Local Tia',
                'direccion'      => 'El inca, Quito',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20456789123',
            ],
            [
                'nombre'         => 'Tienda Susanita',
                'direccion'      => 'Av. Eloy alfaro',
                'estado'         => 0,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20321654987',
            ],
            [
                'nombre'         => 'Tuti',
                'direccion'      => 'Av. 6 de diciembre',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '72345678768',
            ],
            [
                'nombre'         => 'Local Independencia Quito',
                'direccion'      => 'Av. Villaflora',
                'estado'         => 0,
                'tipo_documento' => null,
                'nro_documento'  => null,
            ],
            [
                'nombre'         => 'Minimarket La Victoria',
                'direccion'      => 'Av. Jipijapa',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20654321098',
            ],
            [
                'nombre'         => 'Almacén TiendaMia',
                'direccion'      => 'Av. Miraflores',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20789012345',
            ],
            [
                'nombre'         => 'Tienda San Jose',
                'direccion'      => 'Av. Mariscal Sucre',
                'estado'         => 0,
                'tipo_documento' => 'CEDULA',
                'nro_documento'  => '45678901',
            ],
            [
                'nombre'         => 'Sucursal Harlericho',
                'direccion'      => 'Av. Quito',
                'estado'         => 1,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20112233445',
            ],
            [
                'nombre'         => 'Local Jesús María',
                'direccion'      => 'Av. Toacazo',
                'estado'         => 1,
                'tipo_documento' => null,
                'nro_documento'  => null,
            ],
            [
                'nombre'         => 'Tienda Popular',
                'direccion'      => 'Av. Shirys',
                'estado'         => 0,
                'tipo_documento' => 'RUC',
                'nro_documento'  => '20556677889',
            ],
        ];

        foreach ($locales as $local) {
            Local::create($local);
        }
    }
}
