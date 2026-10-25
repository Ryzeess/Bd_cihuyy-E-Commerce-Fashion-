<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        static $index = 0;
        $presets = [
            ['Kaos & T-Shirt', 'Koleksi t-shirt casual santai harian berbahan katun sejuk menyerap keringat.'],
            ['Kemeja Pria & Wanita', 'Kemeja formal, semi-formal, dan kasual lengan panjang maupun pendek.'],
            ['Celana Chino', 'Celana chinos stretch elastis dan fleksibel untuk berbagai kegiatan.'],
            ['Celana Denim Jeans', 'Jeans denim berkualitas tinggi, kuat, tahan lama, dan stylish.'],
            ['Jaket & Parka', 'Outerwear hangat penahan angin dengan material taslan dan parasut premium.'],
            ['Hoodie & Sweatshirt', 'Pakaian santai bertudung dengan bahan fleece tebal dan lembut.'],
            ['Dress & Gamis', 'Busana terusan wanita anggun dan elegan untuk berbagai acara.'],
            ['Rok Kasual & Formal', 'Rok plisket, span, dan A-line dengan beragam pilihan bahan.'],
            ['Blazer & Jas Modern', 'Outerwear formal berpotongan rapi untuk suasana kerja dan acara resmi.'],
            ['Cardigan & Knitwear', 'Pakaian rajut hangat dengan corak warna modern kekinian.'],
            ['Batik & Etnik', 'Kemeja dan blouse batik motif kontemporer warisan budaya.'],
            ['Activewear & Olahraga', 'Pakaian olahraga lentur, ringan, cepat kering untuk aktivitas fisik.'],
            ['Sepatu & Sneakers', 'Alas kaki modis untuk melengkapi penampilan sehari-hari.'],
            ['Tas & Backpack', 'Tas ransel, waistbag, slingbag, dan tote bag multifungsi.'],
            ['Aksesoris & Topi', 'Topi baseball, bucket hat, dompet, dan ikat pinggang stylish.'],
        ];

        if ($index < count($presets)) {
            $preset = $presets[$index++];
            return [
                'nama_kategori' => $preset[0],
                'deskripsi' => $preset[1],
            ];
        }

        return [
            'nama_kategori' => ucfirst(fake()->unique()->words(2, true)),
            'deskripsi' => fake()->sentence(8),
        ];
    }
}
