<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── تعریف همه Permission ها ──────────────────────────
        $permissions = [

            // محصولات
            'view products',
            'create products',
            'edit products',
            'delete products',

            // دسته‌بندی
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // ویژگی‌های محصول
            'manage product_attributes',

            // سفارشات
            'view orders',
            'manage orders',
            'cancel orders',
            'export orders',

            // پرداخت
            'view payments',
            'verify payments',
            'refund payments',

            // کاربران
            'view users',
            'create users',
            'edit users',
            'delete users',
            'ban users',
            'manage roles',

            // محتوا
            'view pages',
            'manage pages',
            'view posts',
            'manage posts',
            'view events',
            'manage events',

            // ظاهر سایت
            'manage sliders',
            'manage menus',

            // تنظیمات
            'manage settings',
            'manage redirects',
            'manage newsletters',

            // گزارشات
            'view reports',
            'export reports',

            // تماس
            'view contact_messages',
            'reply contact_messages',
            'delete contact_messages',

            // کوپن
            'view coupons',
            'manage coupons',

            // نظرات
            'view reviews',
            'manage reviews',
            'approve reviews',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Admin — دسترسی کامل ──────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // ── Editor — مدیریت محتوا ────────────────────────────
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions([
            'view products',
            'create products',
            'edit products',
            'view categories',
            'create categories',
            'edit categories',
            'manage product_attributes',
            'view pages',
            'manage pages',
            'view posts',
            'manage posts',
            'view events',
            'manage events',
            'manage sliders',
            'manage menus',
            'view orders',
            'view reviews',
            'approve reviews',
        ]);

        // ── Sales — فروش و سفارشات ───────────────────────────
        $sales = Role::firstOrCreate(['name' => 'sales']);
        $sales->syncPermissions([
            'view products',
            'edit products',
            'view categories',
            'view orders',
            'manage orders',
            'cancel orders',
            'export orders',
            'view payments',
            'verify payments',
            'view contact_messages',
            'reply contact_messages',
            'view reports',
            'export reports',
            'view coupons',
            'manage coupons',
            'view reviews',
        ]);

        // ── Customer — مشتری ─────────────────────────────────
        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([
            'view products',
            'view categories',
            'view orders',
            'view reviews',
        ]);
    }
}
