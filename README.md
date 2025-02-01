## 6amTech Technical Task (Multi Tenants Implemenation)

### Requirements

- `php:^8.2`
- `composer`

### Installation process of this task

1. `git clone https://github.com/oalid-cse/multi-tenant-test.git`
    - If you have setup ssh key in your github account then you can you ssh url to clone the repository
2. `cd multi-tenant-test`
3. `composer install`
4. `cp .env.example .env`
5. update database connection in .env file
6. `php artisan key:generate`
7. `php artisan migrate --seed`
8. `php artisan passport:keys`
9. `php artisan passport:client --personal`
10. `php artisan optimize`
11. Setup your host file as your requirement
    - If you need some specific domain for each tenant then you can setup your host file for only this
    - If you need unknown, dynamic and unlimited subdomain for each tenant then you need to setup wildcard domain in your host file


## Multi Tenants Implementation

### Make New Tenant
Command: `php artisan tenant:create`
- It will ask for tenant name and subdomain
- It will create new tenant in tenant table
- It will create new database for this tenant
- It will migrate tenant migrations which is in `database/migrations/tenant` directory

### Make Tenant Model
Command: `php artisan tenant:model {ModelName}`
- If you need to create migration also then you can use `-m` flag
- It will create new model in `app/Models` directory
- It will create new migration in `database/migrations/tenant` directory

[*Note: Or if you already created model and migration, or you want to create model/migration using laravel command or manually then you need to use a trait in your model `use App\Trait\TenantConnection`
and for your migration you need to move it to `database/migrations/tenant` directory*]
