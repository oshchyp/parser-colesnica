<?php
return array (
  'main' => 
  array (
    'doc_fields' => 
    array (
      'article' => 'CAI',
      'brand' => 'Производитель',
      'model' => 'Модель',
      'profile' => 'Профиль (мм)',
      'height' => 'Высота (%)',
      'diameter' => 'Диаметр',
      'index' => 'Индекс',
      'season' => 'Сезон',
      'tire_type' => 'Тип шины',
      'external_diameter' => 'Внешний диаметр',
      'spike' => 'Шип',
      'spike_type' => 'Тип шипов',
      'carrying' => 'Увеличенная грузоподъемность',
      'run_flat' => 'RunFlat',
      'am_des_acc' => 'Американские обозначения принадлежности',
      'app_axes' => 'Применяемость по осям',
      'retail' => 'Розница (руб)',
      'empty_field' => 'ПУСТОЕ ПОЛЕ',
      'price' => 'Цена (руб)',
      'img' => 'Изображение',
      'width' => 'Ширина',
      'q_hole' => 'Кол-во отверстий',
      'pcd1' => 'PCD1',
      'pcd2' => 'PCD2',
      'et' => 'ET',
      'dia' => 'Dia',
      'color' => 'Цвет',
      'brand_car' => 'Производитель машины, к которой подходит диск. Если диск универсальный',
      'q' => 'Кол-во (шт.)',
      'name' => 'Наименование',
      'desc' => 'Краткое описание',
      'category_name' => 'Категория (Номер/ID/Название)',
      'prepayment' => 'Ваша цена (ПРЕДОПЛАТА)',
      'protection' => 'Защита колесного диска ',
      'omolog' => 'Омологация',
      'speed_index' => 'Индекс скорости',
      'load_index' => 'Индекс нагрузки',
      'side' => 'Надпись на боковине',
      'tech' => 'Технология производства',
      'reinforced' => 'Усиленность',
      'mount' => 'Крепёж',
      'retaill' => 'Розница',
    ),
    'doc_in' => 
    array (
      'qwdqwdqwdqwd' => 'article',
    ),
    'img' => 
    array (
      'img' => 'float.jpg',
      'tires' => 
      array (
        'top' => '150',
        'left' => '1',
      ),
      'disks' => 
      array (
        'top' => '150',
        'left' => '1',
      ),
    ),
    'cat' => 
    array (
      'tires' => 'Шины',
      'disks' => 'Диски',
    ),
    'doc_in_tires' => 
    array (
      'A' => 'name',
      'B' => 'article',
      'C' => 'brand',
      'D' => 'model',
      'E' => 'profile',
      'F' => 'height',
      'G' => 'diameter',
      'H' => 'season',
      'I' => 'spike',
      'J' => 'load_index',
      'K' => 'speed_index',
      'L' => 'reinforced',
      'M' => 'run_flat',
      'N' => 'price',
      'P' => 'retail',
      'T' => 'q',
    ),
    'doc_out_tires' => 
    array (
      'A' => 'category_name',
      'B' => 'name',
      'C' => 'retail',
      'D' => 'desc',
      'E' => 'img',
      'F' => 'profile',
      'G' => 'diameter',
      'H' => 'height',
      'I' => 'season',
      'J' => 'brand',
      'R' => 'spike',
      'S' => 'article',
      'T' => 'q',
      'U' => 'run_flat',
      'Y' => 'price',
    ),
    'doc_out_disks' => 
    array (
      'A' => 'category_name',
      'AC' => 'mount',
      'B' => 'name',
      'C' => 'retail',
      'E' => 'img',
      'K' => 'width',
      'L' => 'diameter',
      'M' => 'dia',
      'N' => 'q_hole',
      'O' => 'pcd1',
      'P' => 'et',
      'Q' => 'brand',
      'S' => 'article',
      'T' => 'q',
      'V' => 'color',
      'Y' => 'price',
    ),
    'shabs' => 
    array (
      'tires' => 'Шины (Эксель)',
      'disks' => 'Диски (Эксель)',
      'tires_xml' => 'Шины (xml)',
      'disks_xml' => 'Диски (xml)',
    ),
    'doc_in_disks' => 
    array (
      'A' => 'article',
      'B' => 'name',
      'C' => 'brand',
      'D' => 'model',
      'E' => 'color',
      'F' => 'width',
      'G' => 'diameter',
      'H' => 'q_hole',
      'I' => 'pcd1',
      'J' => 'et',
      'K' => 'dia',
      'L' => 'mount',
      'M' => 'q',
      'N' => 'retail',
      'O' => 'price',
    ),
    'doc_in_test' => 
    array (
      'A' => 'article',
    ),
    'login' => 
    array (
      'login' => 'kolesnica46',
      'password' => 'kolesnica46',
    ),
    'doc_in_tires_xml' => 
    array (
      'brand' => 'brand',
      'cae' => 'article',
      'diameter' => 'diameter',
      'height' => 'profile',
      'is_studded' => 'spike',
      'name' => 'name',
      'season' => 'season',
      'width' => 'width',
    ),
    'doc_in_disks_xml' => 
    array (
      'cae' => 'article',
      'name' => 'name',
      'width' => 'width',
      'diameter' => 'diameter',
      'bolts_count' => 'q_hole',
    ),
    'convert_name' => 
    array (
      'tires' => '{name} {run_flat}',
      'disks' => '{name}',
    ),
  ),
);