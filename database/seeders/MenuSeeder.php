<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['category' => 'main-course', 'name' => 'Ayam Bakar Madu', 'description_id' => 'Ayam bakar berbumbu dengan olesan madu dan sambal.', 'description_en' => 'Grilled chicken glazed with honey, served with chili sauce.', 'price' => 42000, 'image' => 'photo-1532550907401-a500c9a57435'],
            ['category' => 'main-course', 'name' => 'Beef Teriyaki Bowl', 'description_id' => 'Daging sapi teriyaki dengan nasi dan sayuran.', 'description_en' => 'Teriyaki beef served with rice and vegetables.', 'price' => 58000, 'image' => 'photo-1546833999-b9f581a1996d'],
            ['category' => 'main-course', 'name' => 'Crispy Fish Fillet', 'description_id' => 'Fillet ikan renyah dengan saus tartar.', 'description_en' => 'Crispy fish fillet served with tartar sauce.', 'price' => 48000, 'image' => 'photo-1519708227418-c8fd9a32b7a2'],
            ['category' => 'main-course', 'name' => 'Chicken Katsu', 'description_id' => 'Ayam katsu renyah dengan nasi hangat.', 'description_en' => 'Crispy chicken katsu served with warm rice.', 'price' => 45000, 'image' => 'photo-1532550907401-a500c9a57435'],
            ['category' => 'appetizer', 'name' => 'Vegetable Spring Rolls', 'description_id' => 'Lumpia renyah berisi sayuran segar.', 'description_en' => 'Crispy spring rolls filled with fresh vegetables.', 'price' => 22000, 'image' => 'photo-1547592180-85f173990554'],
            ['category' => 'appetizer', 'name' => 'Crispy Tofu Bites', 'description_id' => 'Tahu goreng renyah berbumbu gurih.', 'description_en' => 'Crispy tofu bites seasoned with savory spices.', 'price' => 20000, 'image' => 'photo-1541014741259-de529411b96a'],
            ['category' => 'appetizer', 'name' => 'Chicken Wings', 'description_id' => 'Sayap ayam panggang dengan saus pilihan.', 'description_en' => 'Grilled chicken wings tossed in your choice of sauce.', 'price' => 35000, 'image' => 'photo-1527477396000-e27163b481c2'],
            ['category' => 'appetizer', 'name' => 'French Fries', 'description_id' => 'Kentang goreng renyah dengan saus tomat.', 'description_en' => 'Crispy golden fries served with tomato sauce.', 'price' => 25000, 'image' => 'photo-1573080496219-bb080dd4f877'],
            ['category' => 'desserts', 'name' => 'Classic Cheesecake', 'description_id' => 'Kue keju lembut dengan saus buah.', 'description_en' => 'Creamy cheesecake served with fruit sauce.', 'price' => 38000, 'image' => 'photo-1533134242443-d4fd215305ad'],
            ['category' => 'desserts', 'name' => 'Chocolate Brownies', 'description_id' => 'Brownies cokelat dengan tekstur fudgy.', 'description_en' => 'Rich, fudgy chocolate brownies.', 'price' => 30000, 'image' => 'photo-1606313564200-e75d5e30476c'],
            ['category' => 'desserts', 'name' => 'Fruit Pancakes', 'description_id' => 'Pancake lembut dengan buah segar dan madu.', 'description_en' => 'Fluffy pancakes topped with fresh fruit and honey.', 'price' => 35000, 'image' => 'photo-1528207776546-365bb710ee93'],
            ['category' => 'desserts', 'name' => 'Caramel Pudding', 'description_id' => 'Puding lembut dengan saus karamel.', 'description_en' => 'Silky pudding topped with caramel sauce.', 'price' => 24000, 'image' => 'photo-1488477181946-6428a0291777'],
            ['category' => 'drinks', 'name' => 'Lemon Iced Tea', 'description_id' => 'Teh dingin dengan perasan lemon segar.', 'description_en' => 'Chilled tea with a squeeze of fresh lemon.', 'price' => 20000, 'image' => 'photo-1513558161293-cdaf765edfd7'],
            ['category' => 'drinks', 'name' => 'Avocado Juice', 'description_id' => 'Alpukat segar diblender hingga lembut.', 'description_en' => 'Fresh avocado blended until smooth.', 'price' => 28000, 'image' => 'photo-1600271886742-f049cd451bba'],
            ['category' => 'drinks', 'name' => 'Iced Palm Sugar Latte', 'description_id' => 'Espresso, susu, dan gula aren pilihan.', 'description_en' => 'Espresso, milk, and Indonesian palm sugar.', 'price' => 26000, 'image' => 'photo-1461023058943-07fcbe16d735'],
            ['category' => 'drinks', 'name' => 'Matcha Latte', 'description_id' => 'Teh matcha dengan susu yang lembut.', 'description_en' => 'Japanese matcha blended with creamy milk.', 'price' => 32000, 'image' => 'photo-1515823064-d6e0c04616a7'],
            ['category' => 'pasta', 'name' => 'Spaghetti Bolognese', 'description_id' => 'Spaghetti dengan saus tomat dan daging sapi.', 'description_en' => 'Spaghetti with tomato and slow-cooked beef sauce.', 'price' => 45000, 'image' => 'photo-1473093295043-cdd812d0e601'],
            ['category' => 'pasta', 'name' => 'Fettuccine Carbonara', 'description_id' => 'Pasta krim dengan keju dan smoked beef.', 'description_en' => 'Creamy pasta with cheese and smoked beef.', 'price' => 52000, 'image' => 'photo-1551183053-bf91a1d81141'],
            ['category' => 'pasta', 'name' => 'Pesto Chicken Pasta', 'description_id' => 'Pasta dengan saus pesto dan ayam panggang.', 'description_en' => 'Pasta tossed with basil pesto and grilled chicken.', 'price' => 55000, 'image' => 'photo-1473093295043-cdd812d0e601'],
            ['category' => 'pasta', 'name' => 'Beef Lasagna', 'description_id' => 'Lasagna panggang berlapis saus daging dan keju.', 'description_en' => 'Baked lasagna layered with beef sauce and cheese.', 'price' => 62000, 'image' => 'photo-1574894709920-11b28e7367e3'],
            ['category' => 'traditional', 'name' => 'Nasi Goreng Kampung', 'description_id' => 'Nasi goreng khas dengan telur dan kerupuk.', 'description_en' => 'Indonesian village-style fried rice with egg and crackers.', 'price' => 32000, 'image' => 'photo-1512058564366-18510be2db19'],
            ['category' => 'traditional', 'name' => 'Soto Ayam', 'description_id' => 'Soto ayam hangat dengan rempah Nusantara.', 'description_en' => 'Comforting chicken soup with Indonesian spices.', 'price' => 35000, 'image' => 'photo-1547592180-85f173990554'],
            ['category' => 'traditional', 'name' => 'Sate Ayam', 'description_id' => 'Sate ayam bakar dengan saus kacang.', 'description_en' => 'Grilled chicken skewers served with peanut sauce.', 'price' => 40000, 'image' => 'photo-1529563021893-cc83c992d75d'],
            ['category' => 'traditional', 'name' => 'Rendang Sapi', 'description_id' => 'Daging sapi dimasak perlahan dengan rempah.', 'description_en' => 'Slow-cooked beef simmered in rich Indonesian spices.', 'price' => 78000, 'image' => 'photo-1547592180-85f173990554'],
        ];

        foreach ($menus as $menuData) {
            $category = Category::where('slug', $menuData['category'])->firstOrFail();
            $image = 'https://images.unsplash.com/'.$menuData['image'].'?auto=format&fit=crop&w=800&q=80';
            $name = $menuData['name'];
            unset($menuData['category'], $menuData['image']);

            Menu::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    ...$menuData,
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $menuData['description_id'],
                    'image' => $image,
                    'is_available' => true,
                ]
            );
        }
    }
}
