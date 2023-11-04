<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Conhecendo a nova versão do Filament 3.0

    🔑 Laravel e expeb projects, such as:...

- [Site Filament laravel](https://filamentphp.com/).
- [Get started Filament](https://filamentphp.com/docs).
- [Panel Builder Installation](https://filamentphp.com/docs/3.x/panels/installation).

> Este projeto de exemplo irá abordar o exemplo que o próprio  `Filament` gera como exemplo em sua `página de panels` e vamos 
> incluir um `sistema de estoque` simples.

#### Descrição dos projetos `exemplo`
 - `Exemplo | Filament`: A construção de um sistema simples de gerenciamento de pacientes para uma clínica veterinária usando o Filament. 
 Apoiará a adição de novos `pacientes` (gatos, cães ou coelhos), atribuindo-os a um `proprietário` e registrando quais 
 `tratamentos` eles receberam. O sistema terá um painel com estatísticas sobre os tipos de pacientes e um gráfico com a 
 quantidade de tratamentos administrados no último ano.
 - `Exemplo | Novo`: 

## Configuração de banco de dados, migrate, models, etc.

    ⚜ Projeto Inventário de estoque

- **_php artisan make:model inventory -m_**
- **_php artisan make:model Category -m_**

- **_php artisan make:filament-resource Inventory --generate_** | O `generate` irá add todas propriedades da sua migrate.

> Migrations
~~~~~~
    Schema::create('inventories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description');
        $table->string('image');
        $table->integer('quantity');
        $table->foreignIdFor(\App\Models\Category::class);
        $table->timestamps();
    });

    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
~~~~~~

- OBs: Se não tem certeza com a chave, `category_id` ou qualquer outra chave, podemos usar a função `foreignIdFor` e 
passar a classe Eloquent, que automaticamente irá criar a coluna com o `nome da classe` e `_id`.

> Relacionamento Models

~~~~~~
    //Inventory
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Category
    public function products()
    {
        return $this->hasMany(Inventory::class);
    }
~~~~~~


